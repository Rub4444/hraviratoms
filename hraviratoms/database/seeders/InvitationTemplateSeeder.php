<?php

namespace Database\Seeders;
use App\Models\InvitationTemplate;
use Illuminate\Database\Seeder;

class InvitationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        InvitationTemplate::updateOrCreate(
            ['key' => 'romantic-pastel'],
            [
                'name' => 'Romantic Pastel',
                'view' => 'invitation.show',
                'base_price' => 15000,
                'config' => [
                    'features' => [
                        'rsvp' => true,
                        'program' => true,
                        'gallery' => false,
                    ],
                    'design' => [
                        'theme' => 'romantic-pastel',
                        'colors' => [
                            'primary' => '#E88FB2',
                            'accent' => '#F3BFD7',
                            'background' => '#FFF7FB',
                        ],
                        'fonts' => [
                            'heading' => 'Dancing Script',
                            'body' => 'Inter',
                        ],
                    ],
                ],
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'nature-green'],
            [
                'name' => 'Nature Green',
                'view' => 'invitation.show',
                'base_price' => 16000,
                'config' => [
                    'features' => [
                        'rsvp' => true,
                        'program' => true,
                        'gallery' => true,
                    ],
                    'design' => [
                        'theme' => 'nature-green',
                        'colors' => [
                            'primary' => '#2F7D32',
                            'accent' => '#66BB6A',
                            'background' => '#F1F8F4',
                        ],
                        'fonts' => [
                            'heading' => 'Playfair Display',
                            'body' => 'Inter',
                        ],
                    ],
                ],
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'luxury-black-gold'],
            [
                'name' => 'Luxury Black & Gold',
                'view' => 'invitation.show',
                'base_price' => 20000,
                'config' => [
                    'features' => [
                        'rsvp' => true,
                        'program' => true,
                        'gallery' => true,
                    ],
                    'design' => [
                        'theme' => 'luxury-black-gold',
                        'colors' => [
                            'primary' => '#D4AF37',
                            'accent' => '#F5E6B3',
                            'background' => '#0F172A',
                        ],
                        'fonts' => [
                            'heading' => 'Cormorant Garamond',
                            'body' => 'Inter',
                        ],
                    ],
                ],
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'elegant-minimal'],
            [
                'name' => 'Elegant Minimal',
                'view' => 'invitation.show',
                'base_price' => 12000,
                'config' => [
                    'features' => [
                        'rsvp' => true,
                        'program' => false,
                        'gallery' => false,
                    ],
                    'design' => [
                        'theme' => 'elegant-minimal',
                        'colors' => [
                            'primary' => '#1E293B',
                            'accent' => '#94A3B8',
                            'background' => '#FFFFFF',
                        ],
                        'fonts' => [
                            'heading' => 'Playfair Display',
                            'body' => 'Inter',
                        ],
                    ],
                ],
            ]
        );
    }
}
