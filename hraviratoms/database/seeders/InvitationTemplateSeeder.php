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
                'view' => 'invitation.templates.romantic-pastel',
                'base_price' => 15000,
                'config' => [
                    'theme' => 'romantic-pastel',

                    'features' => [
                        'rsvp'    => true,
                        'program' => true,
                        'gallery' => true,
                    ],

                    'design' => [
                        'colors' => [
                            'primary'    => '#E88FB2',
                            'accent'     => '#F3BFD7',
                            'background' => '#FFF7FB',
                        ],
                        'fonts' => [
                            'heading' => 'Dancing Script',
                            'body'    => 'Inter',
                        ],
                    ],
                ],
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'nature-green'],
            [
                'name' => 'Nature Green',
                'view' => 'invitation.templates.nature-green',
                'base_price' => 16000,
                'config' => [
                    'theme' => 'nature-green',

                    'features' => [
                        'rsvp'    => true,
                        'program' => true,
                        'gallery' => true,
                    ],

                    'design' => [
                        'colors' => [
                            'primary'    => '#2F7D32',
                            'accent'     => '#66BB6A',
                            'background' => '#F1F8F4',
                        ],
                        'fonts' => [
                            'heading' => 'Playfair Display',
                            'body'    => 'Inter',
                        ],
                    ],
                ],
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'luxury-black-gold'],
            [
                'name' => 'Luxury Black & Gold',
                'view' => 'invitation.templates.luxury-black-gold',
                'base_price' => 20000,
                'config' => [
                    'theme' => 'luxury-black-gold',

                    'features' => [
                        'rsvp'    => true,
                        'program' => true,
                        'gallery' => true,
                    ],

                    'design' => [
                        'colors' => [
                            'primary'    => '#D4AF37',
                            'accent'     => '#F5E6B3',
                            'background' => '#020617',
                        ],
                        'fonts' => [
                            'heading' => 'Cormorant Garamond',
                            'body'    => 'Inter',
                        ],
                    ],
                ],
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'elegant-minimal'],
            [
                'name' => 'Elegant Minimal',
                'view' => 'invitation.templates.elegant-minimal',
                'base_price' => 12000,
                'config' => [
                    'theme' => 'elegant-minimal',

                    'features' => [
                        'rsvp'    => true,
                        'program' => false,
                        'gallery' => false,
                    ],

                    'design' => [
                        'colors' => [
                            'primary'    => '#1E293B',
                            'accent'     => '#94A3B8',
                            'background' => '#FFFFFF',
                        ],
                        'fonts' => [
                            'heading' => 'Playfair Display',
                            'body'    => 'Inter',
                        ],
                    ],
                ],
            ]
        );
    }
}
