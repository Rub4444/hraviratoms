<?php

namespace App\Services;

use App\Models\InvitationTemplate;

class InvitationPriceCalculator
{
    public static function calculate(
        InvitationTemplate $template,
        array $featuresOverride = []
    ): int {
        $base = $template->base_price;

        $templateFeatures = $template->config['features'] ?? [];

        $features = array_replace_recursive(
            $templateFeatures,
            $featuresOverride
        );

        $total = $base;

        foreach ($features as $feature => $enabled) {
            if ($enabled) {
                $total += config("invitation_pricing.features.$feature", 0);
            }
        }

        return $total;
    }
}
