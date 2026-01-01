<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\InvitationTemplate;
use App\Models\User;

class CalculateInvitationPriceAuthorizedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_calculate_price(): void
    {
        $user = User::factory()->create();

        $template = InvitationTemplate::factory()->create([
            'base_price' => 10000,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/invitations/calculate-price', [
                'template_id' => $template->id,
                'features' => [
                    'gallery' => true,
                ],
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'price',
            ]);
    }
}
