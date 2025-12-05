<?php

namespace App\Repositories;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function allWithInvitationsCount(): Collection
    {
        return User::query()
            ->withCount('invitations')
            ->orderByDesc('is_superadmin')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'is_superadmin',
                'deleted_at',
            ]);
    }

    public function findOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    public function create(array $data): User
    {
        $user = new User();

        $user->name          = $data['name'];
        $user->email         = $data['email'];
        $user->is_superadmin = $data['is_superadmin'] ?? false;
        $user->password      = Hash::make($data['password']);

        $user->save();

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->name          = $data['name'];
        $user->email         = $data['email'];
        $user->is_superadmin = $data['is_superadmin'] ?? false;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return $user;
    }

    public function delete(User $user, bool $deleteInvitations): void
    {
        if ($deleteInvitations) {
            Invitation::where('user_id', $user->id)->delete();
        } else {
            Invitation::where('user_id', $user->id)->update(['user_id' => null]);
        }

        $user->delete();
    }
}
