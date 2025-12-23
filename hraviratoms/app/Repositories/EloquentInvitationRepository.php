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
            abort(403);
        }

        $invitation = new Invitation();

        $invitation->fill($data);

        // 🔥 КРИТИЧНО — ЯВНО
        $invitation->data = $data['data'] ?? [];

        $invitation->user_id = $user->id;
        $invitation->status  = InvitationStatus::Pending;

        if (empty($invitation->slug)) {
            $invitation->slug = Str::slug(
                $invitation->bride_name . '-' .
                $invitation->groom_name . '-' .
                ($invitation->date ?? now()->format('Y-m-d'))
            ) . '-' . Str::random(5);
        }

        $invitation->save();

        return $invitation;
    }


    public function update(Invitation $invitation, array $data): Invitation
    {
        // 🔥 ЯВНО обновляем JSON поле
        if (array_key_exists('data', $data)) {
            $invitation->data = $data['data'];
            unset($data['data']);
        }

        // остальные поля
        $invitation->fill($data);
        $invitation->save();

        return $invitation;
    }


    public function delete(Invitation $invitation): void
    {
        $invitation->delete();
    }

    /**
     * Публичный поиск по slug — только опубликованные.
     */
    public function findBySlugForPublic(string $slug): Invitation
    {
        return Invitation::query()
            ->with('template')
            ->where('slug', $slug)
            ->where('status', InvitationStatus::Published)
            ->firstOrFail();
    }

    /**
     * Создание приглашения по публичной заявке.
     */
    public function createFromPublicRequest(
        int $templateId,
        array $data,
        ?User $user = null
    ): Invitation {
        $slug = Str::slug(
            ($data['bride_name'] ?? '') . '-' .
            ($data['groom_name'] ?? '') . '-' .
            uniqid()
        );

        return Invitation::create([
            'invitation_template_id' => $templateId,
            'user_id'                => $user?->id,

            'bride_name'    => $data['bride_name'],
            'groom_name'    => $data['groom_name'],
            'date'          => $data['date'] ?? null,
            'time'          => $data['time'] ?? null,
            'venue_name'    => $data['venue_name'] ?? null,
            'venue_address' => $data['venue_address'] ?? null,
            'dress_code'    => $data['dress_code'] ?? null,

            'status'       => InvitationStatus::Pending,

            'slug' => $slug,

            'data' => [
                'client_name'  => $data['client_name'] ?? null,
                'client_phone' => $data['client_phone'],
                'client_email' => $data['client_email'] ?? null,
                'client_notes' => $data['client_notes'] ?? null,
                'source'       => 'public_request_form',
            ],
        ]);
    }
}
