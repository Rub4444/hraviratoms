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

        $invitation->load(['template:id,name']);

        $result = $this->rsvps->listWithStats($invitation);

        return response()->json([
            'invitation' => [
                'id'         => $invitation->id,
                'bride_name' => $invitation->bride_name,
                'groom_name' => $invitation->groom_name,
                'date'       => optional($invitation->date)->format('Y-m-d'),
                'slug'       => $invitation->slug,
                'template'   => $invitation->template ? [
                    'id'   => $invitation->template->id,
                    'name' => $invitation->template->name,
                ] : null,
            ],

            'stats' => [
                'total_responses'  => $result->stats->total,
                'yes'              => $result->stats->byStatus['yes']['count'] ?? 0,
                'no'               => $result->stats->byStatus['no']['count'] ?? 0,
                'maybe'            => $result->stats->byStatus['maybe']['count'] ?? 0,
                'guests_yes_count' => $result->stats->byStatus['yes']['guests'] ?? 0,
                'guests_total'     => $result->stats->guestsTotal,
            ],

            'items' => $result->items->map(
                fn ($rsvp) => InvitationRsvpDto::fromModel($rsvp)->toArray()
            ),
        ]);
    }

}
