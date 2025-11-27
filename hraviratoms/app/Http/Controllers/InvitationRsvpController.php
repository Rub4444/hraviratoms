<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\JsonResponse;

class InvitationRsvpController extends Controller
{
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

        $invitation->load('rsvps');

        $rsvps = $invitation->rsvps()
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $rsvps->count();
        $yes   = $rsvps->where('status', 'yes')->count();
        $no    = $rsvps->where('status', 'no')->count();
        $maybe = $rsvps->where('status', 'maybe')->count();

        $guestsYesCount = $rsvps->where('status', 'yes')->sum('guests_count');
        $guestsTotal    = $rsvps->sum('guests_count');

        return response()->json([
            'invitation' => [
                'id'          => $invitation->id,
                'bride_name'  => $invitation->bride_name,
                'groom_name'  => $invitation->groom_name,
                'date'        => $invitation->date,
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
            'items' => $rsvps->map(function ($rsvp) {
                return [
                    'id'           => $rsvp->id,
                    'guest_name'   => $rsvp->guest_name,
                    'guest_phone'  => $rsvp->guest_phone,
                    'status'       => $rsvp->status,
                    'guests_count' => $rsvp->guests_count,
                    'message'      => $rsvp->message,
                    'created_at'   => $rsvp->created_at,
                ];
            }),
        ]);
    }
}
