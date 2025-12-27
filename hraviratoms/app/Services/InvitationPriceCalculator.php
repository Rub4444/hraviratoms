<?php

namespace App\Services;

use App\Models\InvitationTemplate;

class InvitationPriceCalculator
{
    public static function calculate(
        InvitationTemplate $template,
        array $featuresOverride = []
    ): int {
        $total = (int) $template->base_price;

        $templateFeatures = $template->config['features'] ?? [];

        $features = array_replace_recursive(
            $templateFeatures,
            $featuresOverride
        );

        $pricing = config('invitation_pricing.features');

        foreach ($features as $feature => $enabled) {
            if ($enabled && isset($pricing[$feature])) {
                $total += (int) $pricing[$feature]['price'];
            }
        }

        return $total;
    }
}
