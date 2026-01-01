<?php

namespace App\DTO;

use Illuminate\Support\Collection;

class InvitationRsvpCollectionDto
{
    public function __construct(
        public readonly Collection $items,
        public readonly InvitationRsvpStatsDto $stats,
    ) {
    }
}
