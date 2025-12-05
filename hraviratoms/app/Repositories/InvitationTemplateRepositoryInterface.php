<?php

namespace App\Repositories;

use App\Models\InvitationTemplate;
use Illuminate\Support\Collection;

interface InvitationTemplateRepositoryInterface
{
    /**
     * Все активные шаблоны.
     */
    public function allActive(): Collection;

    /**
     * Найти активный шаблон по key или кинуть 404.
     */
    public function findActiveByKey(string $key): InvitationTemplate;
}
