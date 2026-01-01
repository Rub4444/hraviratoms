<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\InvitationTemplate;

class CalculateInvitationPriceUnauthorizedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_calculate_price(): void
    {
        // 📦 шаблон
        $template = InvitationTemplate::factory()->create([
            'base_price' => 10000,
        ]);

        // ❌ без авторизации
        $response = $this->postJson('/api/invitations/calculate-price', [
            'template_id' => $template->id,
            'features' => [
                'gallery' => true,
            ],
        ]);

        $response->assertStatus(401);
    }
}
