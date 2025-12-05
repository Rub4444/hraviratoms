<?php

namespace App\DTO;

use App\Models\InvitationTemplate;

class InvitationTemplateDto
{
    public function __construct(
        private readonly array $data,
    ) {
    }

    public static function fromModel(InvitationTemplate $template): self
    {
        // Если захочешь – здесь можно сузить набор полей
        return new self($template->toArray());
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
