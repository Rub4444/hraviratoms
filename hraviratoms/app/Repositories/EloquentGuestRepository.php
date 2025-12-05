<?php

namespace App\Repositories;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Support\Collection;

class EloquentGuestRepository implements GuestRepositoryInterface
{
    public function forInvitation(Invitation $invitation): Collection
    {
        return Guest::query()
            ->where('invitation_id', $invitation->id)
            ->orderBy('created_at')
            ->get([
                'id',
                'invitation_id',
                'full_name',
                'phone',
                'rsvp_status',
                'comment',
                'created_at',
            ]);
    }

    public function createForInvitation(Invitation $invitation, array $data): Guest
    {
        $data['invitation_id'] = $invitation->id;

        return Guest::create($data);
    }

    public function update(Guest $guest, array $data): Guest
    {
        $guest->fill($data);
        $guest->save();

        return $guest;
    }

    public function delete(Guest $guest): void
    {
        $guest->delete();
    }
}
