<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationRsvp;
use Illuminate\Http\Request;
use App\Models\InvitationTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationCreatedNotification;

class PublicInvitationController extends Controller
{
    public function show(string $slug)
    {
        $invitation = Invitation::with('template')->where('slug', $slug)->firstOrFail();

        return view('invitations.show', [
            'invitation' => $invitation,
        ]);
    }

    public function submitRsvp(Request $request, string $slug)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'guest_name'   => ['required', 'string', 'max:255'],
            'guest_phone'  => ['nullable', 'string', 'max:50'],
            'status'       => ['required', 'in:yes,no,maybe'],
            'guests_count' => ['nullable', 'integer', 'min:1', 'max:20'],
            'message'      => ['nullable', 'string', 'max:2000'],
        ]);

        if (empty($data['guests_count'])) {
            $data['guests_count'] = 1;
        }

        InvitationRsvp::create([
            'invitation_id' => $invitation->id,
            'guest_name'    => $data['guest_name'],
            'guest_phone'   => $data['guest_phone'] ?? null,
            'status'        => $data['status'],
            'guests_count'  => $data['guests_count'],
            'message'       => $data['message'] ?? null,
            'guest_ip'      => $request->ip(),
        ]);

        return redirect()
        ->route('invitation.public.show', $invitation->slug)
        ->with('rsvp_success', true);

    }

    /**
     * Показ формы заявки на приглашение
     */
    public function showRequestForm(?string $templateKey = null)
    {
        $templates = InvitationTemplate::where('is_active', true)
            ->orderBy('id')
            ->get();

        $selectedTemplate = null;

        if ($templateKey) {
            $selectedTemplate = $templates->firstWhere('key', $templateKey);
        }

        return view('invitations.request', [
            'templates'        => $templates,
            'selectedTemplate' => $selectedTemplate,
        ]);
    }

    /**
     * Приём заявки от обычного пользователя
     */
    public function submitRequest(Request $request)
    {
        $data = $request->validate([
            'template_key'  => ['required', 'exists:invitation_templates,key'],
            'bride_name'    => ['required', 'string', 'max:255'],
            'groom_name'    => ['required', 'string', 'max:255'],
            'date'          => ['nullable', 'date'],
            'time'          => ['nullable', 'string', 'max:50'],
            'venue_name'    => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code'    => ['nullable', 'string', 'max:255'],

            // Контакты клиента
            'client_name'   => ['nullable', 'string', 'max:255'],
            'client_phone'  => ['required', 'string', 'max:50'],
            'client_email'  => ['nullable', 'email', 'max:255'],
            'client_notes'  => ['nullable', 'string', 'max:2000'],
        ]);

        $template = InvitationTemplate::where('key', $data['template_key'])->firstOrFail();

        // 👇 если пользователь не авторизован — просто не заполняем user_id
        $userId = auth()->check() ? auth()->id() : null;

        $slug = Str::slug(
            ($data['bride_name'] ?? '') . '-' .
            ($data['groom_name'] ?? '') . '-' .
            uniqid()
        );

        $invitation = Invitation::create([
            'invitation_template_id' => $template->id,
            'user_id'                => $userId,

            'bride_name'    => $data['bride_name'],
            'groom_name'    => $data['groom_name'],
            'date'          => $data['date'] ?? null,
            'time'          => $data['time'] ?? null,
            'venue_name'    => $data['venue_name'] ?? null,
            'venue_address' => $data['venue_address'] ?? null,
            'dress_code'    => $data['dress_code'] ?? null,

            // Главное отличие от админа:
            'status'       => Invitation::STATUS_PENDING,
            'is_published' => false,

            'slug' => $slug,

            // Доп. инфа клиента складываем в JSON "data"
            'data' => [
                'client_name'  => $data['client_name'] ?? null,
                'client_phone' => $data['client_phone'],
                'client_email' => $data['client_email'] ?? null,
                'client_notes' => $data['client_notes'] ?? null,
                'source'       => 'public_request_form',
            ],
        ]);



        $to = env('ADMIN_NOTIFICATION_EMAIL') ?: config('mail.from.address');

        if ($to) {
            Mail::to($to)->send(new InvitationCreatedNotification($invitation, true));
        }


        return redirect()
            ->route('landing') // это '/'
            ->with('request_success', 'Ձեր հայտը հաջողությամբ ուղարկվել է 🥰');

    }
}
