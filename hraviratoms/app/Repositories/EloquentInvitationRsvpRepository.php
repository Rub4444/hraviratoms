<?php

namespace App\Repositories;

use App\DTO\InvitationRsvpStatsDto;
use App\Models\Invitation;
use App\Models\InvitationRsvp;
use Illuminate\Support\Collection;

class EloquentInvitationRsvpRepository implements InvitationRsvpRepositoryInterface
{
    public function forInvitation(Invitation $invitation): Collection
    {
        return InvitationRsvp::query()
            ->where('invitation_id', $invitation->id)
            ->orderByDesc('created_at')
            ->get([
                'id',
                'invitation_id',
                'guest_name',
                'guest_phone',
                'status',
                'guests_count',
                'message',
                'created_at',
            ]);
    }

    public function statsForInvitation(Invitation $invitation): InvitationRsvpStatsDto
    {
        $rows = InvitationRsvp::query()
            ->where('invitation_id', $invitation->id)
            ->get([
                'status',
                'guests_count',
            ]);

        return InvitationRsvpStatsDto::fromCollection($rows);
    }
}
