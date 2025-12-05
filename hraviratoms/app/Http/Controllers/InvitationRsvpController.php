<?php

namespace App\Http\Controllers;

use App\DTO\InvitationRsvpDto;
use App\Models\Invitation;
use App\Repositories\InvitationRsvpRepositoryInterface;
use Illuminate\Http\JsonResponse;

class InvitationRsvpController extends Controller
{
    public function __construct(
        private readonly InvitationRsvpRepositoryInterface $rsvps,
    ) {
    }

    protected function ensureCanAccessInvitation(Invitation $invitation): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        if ($user->is_superadmin) {
            return;
        }

        if ($invitation->user_id !== $user->id) {
            abort(403);
        }
    }

    public function index(Invitation $invitation): JsonResponse
    {
        $this->ensureCanAccessInvitation($invitation);

        // подгружаем только то, что нужно от шаблона
        $invitation->load(['template:id,name']);

        // все RSVP берём через репозиторий
        $rsvps = $this->rsvps->forInvitation($invitation);

        // считаем статистику
        $total = $rsvps->count();
        $yes   = $rsvps->where('status', 'yes')->count();
        $no    = $rsvps->where('status', 'no')->count();
        $maybe = $rsvps->where('status', 'maybe')->count();

        $guestsYesCount = (int) $rsvps->where('status', 'yes')->sum('guests_count');
        $guestsTotal    = (int) $rsvps->sum('guests_count');

        return response()->json([
            'invitation' => [
                'id'          => $invitation->id,
                'bride_name'  => $invitation->bride_name,
                'groom_name'  => $invitation->groom_name,
                'date' => optional($invitation->date)->format('Y-m-d'),
                'slug'        => $invitation->slug,
                'template'    => $invitation->template ? [
                    'id'   => $invitation->template->id,
                    'name' => $invitation->template->name,
                ] : null,
            ],
            'stats' => [
                'total_responses'  => $total,
                'yes'              => $yes,
                'no'               => $no,
                'maybe'            => $maybe,
                'guests_yes_count' => $guestsYesCount,
                'guests_total'     => $guestsTotal,
            ],
            'items' => $rsvps->map(
                fn ($rsvp) => InvitationRsvpDto::fromModel($rsvp)->toArray()
            ),
        ]);
    }
}
