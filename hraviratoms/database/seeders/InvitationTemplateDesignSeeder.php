<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvitationTemplate;

class InvitationTemplateDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvitationTemplate::updateOrCreate(
            ['key' => 'elegant-minimal'], // ← по этому полю ищем
            [
                'name' => 'Elegant Minimal',
                'description' => 'Մաքուր սպիտակ ֆոն, նուրբ տեքստ և ոսկեգույն շեշտեր։',
                'is_active' => true,
                'card_class' => 'rounded-2xl bg-white/90 p-4 border border-gold-light/70 hover:border-gold-dark hover:shadow-md transition',
                'title_class' => 'text-xs font-semibold text-gold-dark',
                'desc_class' => 'mt-1 text-slate-700',
                'link_class' => 'mt-2 text-[11px] text-gold-dark underline',
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'nature-green'],
            [
                'name' => 'Nature Green',
                'description' => 'Բնության մոտիվներ, կանաչ նուրբ գույներ, Տավուշյան տրամադրություն։',
                'is_active' => true,
                'card_class' => 'rounded-2xl bg-white/90 p-4 border border-leaf-soft/80 hover:border-leaf-deep hover:shadow-md transition',
                'title_class' => 'text-xs font-semibold text-leaf-deep',
                'desc_class' => 'mt-1 text-slate-700',
                'link_class' => 'mt-2 text-[11px] text-leaf-deep underline',
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'luxury-black-gold'],
            [
                'name' => 'Luxury Black & Gold',
                'description' => 'Մուգ ֆոն, ոսկեգույն դետալներ, պրեմիում զգացողություն։',
                'is_active' => true,
                'card_class' => 'rounded-2xl bg-slate-950 p-4 border border-gold-dark hover:shadow-md transition',
                'title_class' => 'text-xs font-semibold text-gold-light',
                'desc_class' => 'mt-1 text-slate-200',
                'link_class' => 'mt-2 text-[11px] text-gold-light underline',
            ]
        );

        InvitationTemplate::updateOrCreate(
            ['key' => 'romantic-pastel'],
            [
                'name' => 'Romantic Pastel',
                'description' => 'Պաստելային վարդագույն, նուրբ և ռոմանտիկ մթնոլորտ։',
                'is_active' => true,
                'card_class' => 'rounded-2xl bg-love-blush/80 p-4 border border-pink-200/70 hover:border-pink-400 hover:shadow-md transition',
                'title_class' => 'text-xs font-semibold text-pink-600',
                'desc_class' => 'mt-1 text-slate-700',
                'link_class' => 'mt-2 text-[11px] text-pink-600 underline',
            ]
        );
    }

}
