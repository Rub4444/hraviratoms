<?php

namespace App\Support;

use App\Models\Invitation;

class InvitationFeatures
{
    public static function resolve(Invitation $invitation): array
    {
        return array_merge(
            [
                'rsvp'    => false,
                'gallery' => false,
                'program' => false,
            ],
            $invitation->template->config['features'] ?? [],
            is_array($invitation->data['features'] ?? null)
                ? $invitation->data['features']
                : []
        );
    }
}
