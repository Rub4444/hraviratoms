<?php

namespace App\Repositories;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface InvitationRepositoryInterface
{
    /**
     * Список приглашений для текущего пользователя
     * (если супер-админ — то для всех).
     */
    public function forUser(User $user, int $perPage = 50): LengthAwarePaginator;

    /**
     * Найти приглашение, к которому пользователь имеет доступ.
     */
    public function findAccessible(User $user, int $id): ?Invitation;

    /**
     * Создать приглашение (используем только для супер-админа).
     */
    public function createForSuperAdmin(User $user, array $data): Invitation;

    /**
     * Обновить приглашение (без проверки прав).
     */
    public function update(Invitation $invitation, array $data): Invitation;

    /**
     * Удалить приглашение.
     */
    public function delete(Invitation $invitation): void;
}
