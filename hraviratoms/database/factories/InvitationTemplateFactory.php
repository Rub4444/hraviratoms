<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InvitationTemplate;

class InvitationTemplateFactory extends Factory
{
    protected $model = InvitationTemplate::class;

    public function definition(): array
    {
        return [
            'name' => 'Test Template',
            'key' => 'test-template',
            'view' => 'templates.elegant-minimal',
            'base_price' => 10000,
            'is_active' => true,
        ];
    }
}
