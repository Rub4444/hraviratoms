<?php

namespace App\Repositories;

use App\Models\Invitation;
use Illuminate\Support\Collection;

interface InvitationRsvpRepositoryInterface
{
    /**
     * Все RSVP для приглашения.
     */
    public function forInvitation(Invitation $invitation): Collection;

    /**
     * Статистика по RSVP для приглашения.
     */
    public function statsForInvitation(Invitation $invitation): \App\DTO\InvitationRsvpStatsDto;
}
