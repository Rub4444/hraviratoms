<?php

namespace App\Repositories;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Support\Collection;

interface GuestRepositoryInterface
{
    /**
     * Все гости для приглашения.
     */
    public function forInvitation(Invitation $invitation): Collection;

    /**
     * Создать гостя для приглашения.
     */
    public function createForInvitation(Invitation $invitation, array $data): Guest;

    /**
     * Обновить гостя.
     */
    public function update(Guest $guest, array $data): Guest;

    /**
     * Удалить гостя.
     */
    public function delete(Guest $guest): void;
}
