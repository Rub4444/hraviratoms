<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * Список всех (не удалённых) пользователей с подсчётом приглашений.
     */
    public function allWithInvitationsCount(): Collection;

    /**
     * Найти пользователя по id или упасть.
     */
    public function findOrFail(int $id): User;

    /**
     * Создать пользователя.
     */
    public function create(array $data): User;

    /**
     * Обновить пользователя.
     */
    public function update(User $user, array $data): User;

    /**
     * Мягко удалить пользователя и, при необходимости, его приглашения/связи.
     */
    public function delete(User $user, bool $deleteInvitations): void;
}
