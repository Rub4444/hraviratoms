<?php

namespace App\Http\Controllers;

use App\Mail\InvitationCreatedNotification;
use App\Repositories\InvitationRepositoryInterface;
use App\Repositories\InvitationRsvpRepositoryInterface;
use App\Repositories\InvitationTemplateRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Support\InvitationFeatures;
use App\Enums\InvitationStatus;
use Illuminate\Database\QueryException;
use App\Models\InvitationTemplate;

class PublicInvitationController extends Controller
{
    public function __construct(
        private readonly InvitationRepositoryInterface $invitations,
        private readonly InvitationRsvpRepositoryInterface $rsvps,
        private readonly InvitationTemplateRepositoryInterface $templates,
    ) {
    }

    /**
     * Публичный просмотр приглашения.
     * Только опубликованные (is_published = true).
     */
    public function show(string $slug): View
    {
        $invitation = $this->invitations->findBySlugForPublic($slug);

        return view(
            $invitation->template->view,
            [
                'invitation' => $invitation,
                'features'   => InvitationFeatures::resolve($invitation),
                'preview'    => false, // важно
            ]
        );
    }

    /**
     * Отправка RSVP с публичной страницы приглашения.
     */
    public function submitRsvp(Request $request, string $slug): RedirectResponse
    {
        $invitation = $this->invitations->findBySlugForPublic($slug);

        if ($invitation->status !== InvitationStatus::Published) {
            abort(403);
        }

        $data = $request->validate([
            'guest_name'   => ['required', 'string', 'max:255'],
            'guest_phone'  => ['nullable', 'string', 'max:50'],
            'status'       => ['required', 'in:yes,no,maybe'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:20'],
            'message'      => ['nullable', 'string', 'max:2000'],
        ]);

        $data['guest_ip'] = $request->ip();
        $cookieKey = 'rsvp_sent_'.$invitation->id;

        if (
            $request->cookie($cookieKey) ||
            $this->rsvps->existsForInvitationAndIp($invitation, $request->ip())
        ) {
            return redirect()
                ->route('invitation.public.show', $invitation->slug)
                ->with('rsvp_error', 'Դուք արդեն ուղարկել եք Ձեր պատասխանը։');
        }

        try {
            $this->rsvps->createForInvitation($invitation, $data);
        } catch (QueryException|\DomainException $e) {
            return redirect()
                ->route('invitation.public.show', $invitation->slug)
                ->with('rsvp_error', 'Դուք արդեն ուղարկել եք Ձեր պատասխանը։');
        }


        return redirect()
            ->route('invitation.public.show', $invitation->slug)
            ->with('rsvp_success', true)
            ->withCookie(cookie($cookieKey, true, 60 * 24 * 30));

    }

    /**
     * Показ формы заявки на создание приглашения.
     */
    public function showRequestForm(?string $templateKey = null)
    {
        return view('invitation.request', [
            'templates' => InvitationTemplate::all(),
            'selectedTemplate' =>
                InvitationTemplate::where('key', $templateKey)->first(),
            'demoInvitation' => session('demo.invitation'),
        ]);
    }


    /**
     * Приём заявки от обычного пользователя.
     */
    public function submitRequest(Request $request): RedirectResponse
    {
        $demo = json_decode(
            $request->input('demo_payload'),
            true
        );

        $data = $request->validate([
            'template_key'  => ['required', 'exists:invitation_templates,key'],
            'bride_name'    => ['required', 'string', 'max:255'],
            'groom_name'    => ['required', 'string', 'max:255'],
            'date'          => ['nullable', 'date'],
            'time'          => ['nullable', 'string', 'max:50'],
            'venue_name'    => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code'    => ['nullable', 'string', 'max:255'],

            'client_name'   => ['nullable', 'string', 'max:255'],
            'client_phone'  => ['required', 'string', 'max:50'],
            'client_email'  => ['nullable', 'email', 'max:255'],
            'client_notes'  => ['nullable', 'string', 'max:2000'],
        ]);

        if ($demo && isset($demo['invitation']['data'])) {
            $data['config'] = $demo['invitation']['data'];
        }

        $template = $this->templates->findActiveByKey($data['template_key']);

        $user = auth()->user(); // может быть null

        $invitation = $this->invitations->createFromPublicRequest(
            templateId: $template->id,
            data: $data,
            user: $user
        );

        $to = env('ADMIN_NOTIFICATION_EMAIL') ?: config('mail.from.address');

        if ($to) {
            Mail::to($to)->queue(new InvitationCreatedNotification($invitation, true));
        }
        session()->forget('demo.invitation');
        return redirect()
            ->route('landing')
            ->with('request_success', 'Ձեր հայտը հաջողությամբ ուղարկվել է 🥰');
    }
}
