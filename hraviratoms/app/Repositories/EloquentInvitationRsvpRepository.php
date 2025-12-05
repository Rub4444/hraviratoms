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

    public function createForInvitation(Invitation $invitation, array $data): InvitationRsvp
    {
        $payload = [
            'invitation_id' => $invitation->id,
            'guest_name'    => $data['guest_name'],
            'guest_phone'   => $data['guest_phone'] ?? null,
            'status'        => $data['status'],
            'guests_count'  => $data['guests_count'] ?? 1,
            'message'       => $data['message'] ?? null,
            'guest_ip'      => $data['guest_ip'] ?? null,
        ];

        if (empty($payload['guests_count'])) {
            $payload['guests_count'] = 1;
        }

        return InvitationRsvp::create($payload);
    }
}
