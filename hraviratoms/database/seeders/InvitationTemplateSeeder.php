<?php

namespace Database\Seeders; // ПРОВЕРЬ: может быть namespace App\Database\Seeders в твоей версии

use App\Models\InvitationTemplate;
use Illuminate\Database\Seeder;

class InvitationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key' => 'elegant-minimal',
                'name' => 'Elegant Minimal',
                'description' => 'Светлый минимализм с золотыми акцентами.',
                'preview_image' => null,
            ],
            [
                'key' => 'nature-green',
                'name' => 'Nature Green',
                'description' => 'Нежные зелёные оттенки и природный стиль.',
                'preview_image' => null,
            ],
            [
                'key' => 'luxury-black-gold',
                'name' => 'Luxury Black & Gold',
                'description' => 'Тёмный фон, золото и премиум атмосфера.',
                'preview_image' => null,
            ],
            [
                'key' => 'romantic-pastel',
                'name' => 'Romantic Pastel',
                'description' => 'Пастельные тона и романтичный дизайн.',
                'preview_image' => null,
            ],
        ];

        foreach ($templates as $data) {
            InvitationTemplate::firstOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
