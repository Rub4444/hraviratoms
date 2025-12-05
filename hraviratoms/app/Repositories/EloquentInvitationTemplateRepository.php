<?php

namespace App\Repositories;

use App\Models\InvitationTemplate;
use Illuminate\Support\Collection;

class EloquentInvitationTemplateRepository implements InvitationTemplateRepositoryInterface
{
    public function allActive(): Collection
    {
        return InvitationTemplate::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
            // при желании можно добавить ->select([...])
    }

    public function findActiveByKey(string $key): InvitationTemplate
    {
        return InvitationTemplate::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
