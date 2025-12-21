<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationTemplate;
use Illuminate\Support\Carbon;
use App\Enums\InvitationStatus;

class DemoInvitationController extends Controller
{
    public function show(string $key)
    {
        // Описываем все демо-варианты
        $demos = [
            'elegant-minimal' => [
                'template_key'  => 'elegant-minimal',
                'template_name' => 'Elegant Minimal',
                'bride_name'    => 'Անահիտ',
                'groom_name'    => 'Արամ',
                'date'          => '2026-06-20',
                'time'          => '18:00',
                'venue_name'    => 'Elegant Hall · Երևան',
                'venue_address' => 'Բաղրամյան պողոտա 15',
                'dress_code'    => 'Elegant white & gold',
                'program'       => [
                    ['time' => '17:30', 'label' => 'Հյուրերի ընդունելություն, welcome drink'],
                    ['time' => '18:00', 'label' => 'Արարողություն և օղակների փոխանակում'],
                    ['time' => '19:30', 'label' => 'Ընթրիք, տորթ և առաջին պարը'],
                ],
            ],

            'nature-green' => [
                'template_key'  => 'nature-green',
                'template_name' => 'Nature Green',
                'bride_name'    => 'Մարիամ',
                'groom_name'    => 'Տիգրան',
                'date'          => '2026-07-15',
                'time'          => '17:00',
                'venue_name'    => 'Տավուշ · Բացօթյա հարսանիք',
                'venue_address' => 'Անտառային տարածք, գետի ափին',
                'dress_code'    => 'Natural / Green tones',
                'program'       => [
                    ['time' => '16:30', 'label' => 'Հյուրերի հավաք Տավուշի բնության մեջ'],
                    ['time' => '17:00', 'label' => 'Հարսանեկան արարողություն բաց երկնքի տակ'],
                    ['time' => '18:30', 'label' => 'Ֆոտոսեսիա, live music & wine'],
                ],
            ],

            'luxury-black-gold' => [
                'template_key'  => 'luxury-black-gold',
                'template_name' => 'Luxury Black & Gold',
                'bride_name'    => 'Էլիզա',
                'groom_name'    => 'Դավիթ',
                'date'          => '2026-09-10',
                'time'          => '19:30',
                'venue_name'    => 'Premium Ballroom · Երևան',
                'venue_address' => 'Հյուսիսային պողոտա 1',
                'dress_code'    => 'Black tie / Gold details',
                'program'       => [
                    ['time' => '19:00', 'label' => 'Red carpet & champagne'],
                    ['time' => '19:30', 'label' => 'Ceremony & vows'],
                    ['time' => '21:00', 'label' => 'Gala dinner & live show'],
                ],
            ],

            'romantic-pastel' => [
                'template_key'  => 'romantic-pastel',
                'template_name' => 'Romantic Pastel',
                'bride_name'    => 'Նոննա',
                'groom_name'    => 'Ռուբեն',
                'date'          => '2025-11-16',
                'time'          => '18:00',
                'venue_name'    => 'Romantic Garden · Երևան',
                'venue_address' => 'Պիոնների այգի, փարթի-տարածք',
                'dress_code'    => 'Pastel / Soft romantic',
                'program'       => [
                    ['time' => '17:30', 'label' => 'Բարի գալուստ, լուսավոր ֆոտոզոնա'],
                    ['time' => '18:00', 'label' => 'Արարողություն, խոսքեր և օղակներ'],
                    ['time' => '19:00', 'label' => 'Քեյք, պարեր և բարեհաճ brindar'],
                ],
            ],
        ];

        // Если ключ неизвестен — 404
        if (! isset($demos[$key])) {
            abort(404);
        }

        $demo = $demos[$key];

        // Создаём "виртуальное" приглашение (НЕ сохраняем в БД)
        $invitation = new Invitation();
        $invitation->slug          = 'demo-' . $key;
        $invitation->bride_name    = $demo['bride_name'];
        $invitation->groom_name    = $demo['groom_name'];
        $invitation->date          = Carbon::parse($demo['date']);
        $invitation->time          = $demo['time'];
        $invitation->venue_name    = $demo['venue_name'];
        $invitation->venue_address = $demo['venue_address'];
        $invitation->dress_code    = $demo['dress_code'];
        $invitation->data          = [
            'program' => $demo['program'],
        ];
        $invitation->status = InvitationStatus::Published;
        $invitation->meta_title    = $demo['template_name'] . ' · LoveLeaf Demo';
        $invitation->meta_description = 'LoveLeaf · ' . $demo['template_name'] . ' demo invitation';

        // Подставляем "виртуальный" шаблон (чтобы работало $invitation->template->key)
        $template = new InvitationTemplate();
        $template->key  = $demo['template_key'];
        $template->name = $demo['template_name'];

        $invitation->setRelation('template', $template);

        // Используем тот же Blade, что и для реальных приглашений
        return view('invitation.show', [
            'invitation' => $invitation,
            'isDemo' => true, // <-- добавили
        ]);
    }
}
