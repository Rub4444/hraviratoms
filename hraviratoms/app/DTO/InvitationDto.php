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
        public readonly ?string $templateName,
        public readonly ?string $ownerName,
        public readonly string $createdAt,
    ) {
    }

    public static function fromModel(Invitation $invitation): self
    {
        /** @var InvitationStatus|string|null $status */
        $status = $invitation->status;

        return new self(
            id:          $invitation->id,
            slug:        $invitation->slug,
            title:       $invitation->title,
            brideName:   $invitation->bride_name,
            groomName:   $invitation->groom_name,
            date:        $invitation->date?->format('Y-m-d'),
            status:      $status instanceof InvitationStatus ? $status->value : $status,
            templateName:$invitation->template?->name,
            ownerName:   $invitation->user?->name,
            createdAt:   $invitation->created_at?->toIso8601String(),
        );
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'slug'          => $this->slug,
            'title'         => $this->title,
            'bride_name'    => $this->brideName,
            'groom_name'    => $this->groomName,
            'date'          => $this->date,
            'status'        => $this->status,
            'template_name' => $this->templateName,
            'owner_name'    => $this->ownerName,
            'created_at'    => $this->createdAt,
        ];
    }
}
