<?php

namespace App\DTO;

use App\Enums\InvitationStatus;
use App\Models\Invitation;

class InvitationDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly ?string $title,
        public readonly string $brideName,
        public readonly string $groomName,
        public readonly ?string $date,
        public readonly ?string $status,
        public readonly array $template,
        public readonly array $config,
        public readonly int $price,
        public readonly ?string $ownerName,
        public readonly string $createdAt,
        public readonly ?string $time,
        public readonly ?string $venueName,
        public readonly ?string $venueAddress,
        public readonly ?string $dressCode,
        public readonly ?int $userId,
    ) {
    }

    public static function fromModel(Invitation $invitation): self
    {
        /** @var InvitationStatus|string|null $status */
        $status = $invitation->status;

        return new self(
            id:        $invitation->id,
            slug:      $invitation->slug,
            title:     $invitation->title,
            brideName: $invitation->bride_name,
            groomName: $invitation->groom_name,
            date:      $invitation->date?->format('Y-m-d'),
            status:    $status instanceof InvitationStatus ? $status->value : $status,

            template: [
                'id'   => $invitation->template?->id,
                'key'  => $invitation->template?->key,
                'name' => $invitation->template?->name,
            ],

            config: array_replace_recursive(
                $invitation->template?->config ?? [],
                $invitation->data ?? []
            ),

            price: $invitation->price ?? 0,

            ownerName: $invitation->user?->name,
            createdAt: $invitation->created_at?->toIso8601String(),

            time:         $invitation->time,
            venueName:    $invitation->venue_name,
            venueAddress: $invitation->venue_address,
            dressCode:    $invitation->dress_code,
            userId:       $invitation->user_id,
        );


    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,

            'bride_name' => $this->brideName,
            'groom_name' => $this->groomName,
            'date'       => $this->date,
            'time'       => $this->time,

            'venue_name'    => $this->venueName,
            'venue_address' => $this->venueAddress,
            'dress_code'    => $this->dressCode,

            'status' => $this->status,
            'user_id'=> $this->userId,

            'template' => $this->template,
            'config'   => $this->config,
            'price'    => $this->price,

            'owner_name' => $this->ownerName,
            'created_at' => $this->createdAt,
        ];

    }
}
