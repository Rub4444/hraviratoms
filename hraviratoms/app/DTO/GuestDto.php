<?php

namespace App\DTO;

use App\Models\Guest;

class GuestDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $invitationId,
        public readonly string $fullName,
        public readonly ?string $phone,
        public readonly ?string $rsvpStatus,
        public readonly ?string $comment,
        public readonly string $createdAt,
    ) {
    }

    public static function fromModel(Guest $guest): self
    {
        return new self(
            id:           $guest->id,
            invitationId: $guest->invitation_id,
            fullName:     $guest->full_name,
            phone:        $guest->phone,
            rsvpStatus:   $guest->rsvp_status,
            comment:      $guest->comment,
            createdAt:    $guest->created_at?->toIso8601String() ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'invitation_id' => $this->invitationId,
            'full_name'     => $this->fullName,
            'phone'         => $this->phone,
            'rsvp_status'   => $this->rsvpStatus,
            'comment'       => $this->comment,
            'created_at'    => $this->createdAt,
        ];
    }
}
