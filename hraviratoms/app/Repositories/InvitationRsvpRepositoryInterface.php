<?php

namespace App\Repositories;

use App\Models\Invitation;
use Illuminate\Support\Collection;
use App\DTO\InvitationRsvpStatsDto;
use App\Models\InvitationRsvp;

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

    /**
     * Создать RSVP для приглашения (используем и публичной формой, и при необходимости в админке).
     */
    public function createForInvitation(Invitation $invitation, array $data): InvitationRsvp;

}
