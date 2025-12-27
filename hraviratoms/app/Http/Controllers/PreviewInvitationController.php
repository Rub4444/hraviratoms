<?php

namespace App\Http\Controllers;

use App\Models\InvitationTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Invitation;
use App\Support\InvitationFeatures;

class PreviewInvitationController extends Controller
{
    public function show(Request $request): View
    {
        $templateKey = $request->input('template_key');

        // 🔥 invitation приходит как JSON-строка
        $invitationData = json_decode(
            $request->input('invitation'),
            true
        );

        if (!$templateKey || !$invitationData) {
            abort(400, 'Preview data missing');
        }

        $template = InvitationTemplate::where('key', $templateKey)->firstOrFail();

        // ✅ ФЕЙК-МОДЕЛЬ (НЕ save)
        $invitation = new Invitation([
            'bride_name'    => $invitationData['bride_name'] ?? '',
            'groom_name'    => $invitationData['groom_name'] ?? '',
            'date'          => $invitationData['date'] ?? null,
            'time'          => $invitationData['time'] ?? null,
            'venue_name'    => $invitationData['venue_name'] ?? null,
            'venue_address' => $invitationData['venue_address'] ?? null,
            'dress_code'    => $invitationData['dress_code'] ?? null,
            'data'          => $invitationData['data'] ?? [], // 🔥 ВАЖНО
        ]);

        // 🔥 подставляем template вручную
        $invitation->setRelation('template', $template);

        return view(
            $template->view,
            [
                'invitation' => $invitation,
                'features'   => InvitationFeatures::resolve($invitation),
                'preview'    => true,
            ]
        );
    }

}
