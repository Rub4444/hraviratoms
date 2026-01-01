<?php

namespace App\Repositories;

use App\DTO\InvitationRsvpStatsDto;
use App\Models\Invitation;
use App\Models\InvitationRsvp;
use Illuminate\Support\Collection;
use DomainException;
use App\DTO\InvitationRsvpCollectionDto;

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

    public function createForInvitation(
        Invitation $invitation,
        array $data
    ): InvitationRsvp {
        try {
            return InvitationRsvp::create([
                'invitation_id' => $invitation->id,
                'guest_name'    => $data['guest_name'],
                'guest_phone'   => $data['guest_phone'] ?? null,
                'status'        => $data['status'],
                'guests_count'  => $data['guests_count'] ?? 1,
                'message'       => $data['message'] ?? null,
                'guest_ip'      => $data['guest_ip'] ?? null,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if (
                $e->getCode() === '23000' &&
                str_contains($e->getMessage(), 'invitation_rsvps_invitation_id_guest_ip_unique')
            ) {
                throw new DomainException('RSVP already exists');
            }

            throw $e;
        }
    }


    public function existsForInvitationAndIp(
        Invitation $invitation,
        string $ip
    ): bool {
        return InvitationRsvp::where('invitation_id', $invitation->id)
            ->where('guest_ip', $ip)
            ->exists();
    }

    public function listWithStats(
        Invitation $invitation
    ): InvitationRsvpCollectionDto {

        $rows = InvitationRsvp::query()
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

        return new InvitationRsvpCollectionDto(
            items: $rows,
            stats: InvitationRsvpStatsDto::fromCollection($rows),
        );
    }
}
