<?php

namespace App\DTO;

use App\Models\User;

class UserDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly bool $isSuperadmin,
        public readonly ?string $deletedAt,
        public readonly int $invitationsCount,
    ) {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            id:              $user->id,
            name:            $user->name,
            email:           $user->email,
            isSuperadmin:    (bool) $user->is_superadmin,
            deletedAt:       $user->deleted_at?->toIso8601String(),
            invitationsCount:(int) ($user->invitations_count ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'is_superadmin'     => $this->isSuperadmin,
            'deleted_at'        => $this->deletedAt,
            'invitations_count' => $this->invitationsCount,
        ];
    }
}
