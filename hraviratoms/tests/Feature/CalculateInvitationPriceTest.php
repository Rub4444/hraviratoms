<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\InvitationTemplate;
use App\Models\User;

class CalculateInvitationPriceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_price_based_on_template_and_features(): void
    {
        $user = User::factory()->create();

        // 1️⃣ Создаём шаблон приглашения
        $template = InvitationTemplate::factory()->create([
            'base_price' => 10000,
        ]);

        // 2️⃣ Отправляем запрос на расчёт цены
        $response = $this
        ->actingAs($user, 'sanctum')
        ->postJson('/api/invitations/calculate-price', [
            'template_id' => $template->id,
            'features' => [
                'gallery' => true,
                'rsvp' => true,
                'program' => true,
            ],
        ]);
        // 3️⃣ Проверяем ответ
        $response
        ->assertStatus(200)
        ->assertJson([
            'price' => 20000,
        ]);
    }
}
