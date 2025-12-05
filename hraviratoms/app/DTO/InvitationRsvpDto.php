<?php

namespace App\DTO;

use App\Models\InvitationRsvp;

class InvitationRsvpDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $guestName,
        public readonly ?string $guestPhone,
        public readonly ?string $status,
        public readonly int $guestsCount,
        public readonly ?string $message,
        public readonly string $createdAt,
    ) {
    }

    public static function fromModel(InvitationRsvp $rsvp): self
    {
        return new self(
            id:          $rsvp->id,
            guestName:   $rsvp->guest_name,
            guestPhone:  $rsvp->guest_phone,
            status:      $rsvp->status,
            guestsCount: (int) ($rsvp->guests_count ?? 0),
            message:     $rsvp->message,
            // форматируем как ISO-строку; если хочешь оставить как есть — можно использовать ->toDateTimeString()
            createdAt:   $rsvp->created_at?->toIso8601String() ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'guest_name'   => $this->guestName,
            'guest_phone'  => $this->guestPhone,
            'status'       => $this->status,
            'guests_count' => $this->guestsCount,
            'message'      => $this->message,
            'created_at'   => $this->createdAt,
        ];
    }
}
