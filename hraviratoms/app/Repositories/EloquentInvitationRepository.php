<?php

namespace App\Repositories;

use App\Enums\InvitationStatus;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class EloquentInvitationRepository implements InvitationRepositoryInterface
{
    public function forUser(User $user, int $perPage = 50): LengthAwarePaginator
    {
        $query = Invitation::query()
            ->with([
                'template:id,name,key',
                'user:id,name,email',
            ])
            ->orderByDesc('created_at');

        if (!$user->is_superadmin) {
            $query->where('user_id', $user->id);
        }

        // Можно выбирать только нужные колонки
        $query->select([
            'id',
            'user_id',
            'invitation_template_id',
            'slug',
            'title',
            'bride_name',
            'groom_name',
            'date',
            'status',
            'created_at',
        ]);

        return $query->paginate($perPage);
    }

    public function findAccessible(User $user, int $id): ?Invitation
    {
        $query = Invitation::query()
            ->with(['template', 'user']);

        if (!$user->is_superadmin) {
            $query->where('user_id', $user->id);
        }

        return $query->find($id);
    }

    public function createForSuperAdmin(User $user, array $data): Invitation
    {
        if (!$user->is_superadmin) {
            abort(403, 'Только супер-админ может создавать приглашения.');
        }

        // задаём владельца и начальный статус
        $data['user_id'] = $user->id;
        $data['status']  = InvitationStatus::Pending;

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug(
                ($data['bride_name'] ?? 'invite') . '-' .
                ($data['groom_name'] ?? 'invite') . '-' .
                ($data['date'] ?? now()->format('Y-m-d'))
            ) . '-' . Str::random(5);
        }

        return Invitation::create($data);
    }

    public function update(Invitation $invitation, array $data): Invitation
    {
        // Можно добавить запрет менять некоторые поля, если нужно
        $invitation->fill($data);
        $invitation->save();

        return $invitation;
    }

    public function delete(Invitation $invitation): void
    {
        $invitation->delete();
    }
}
