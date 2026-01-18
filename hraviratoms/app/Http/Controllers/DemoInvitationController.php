<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationTemplate;
use Illuminate\Http\Request;
use App\Support\InvitationFeatures;

class DemoInvitationController extends Controller
{
    public function preview(string $template, Request $request)
    {
        $templateModel = InvitationTemplate::where('key', $template)->firstOrFail();

        $data = json_decode($request->query('invitation'), true) ?? [];

        $invitation = new Invitation([
            'bride_name' => $data['bride_name'] ?? '',
            'groom_name' => $data['groom_name'] ?? '',
            'date' => $data['date'] ?? null,
            'time' => $data['time'] ?? null,
            'venue_name' => $data['venue_name'] ?? null,
            'venue_address' => $data['venue_address'] ?? null,
            'dress_code' => $data['dress_code'] ?? null,
            'data' => $data['data'] ?? [],
        ]);

        $invitation->setRelation('template', $templateModel);

        return view($templateModel->view, [
            'invitation' => $invitation,
            'features' => InvitationFeatures::resolve($invitation),
            'preview' => true,
        ]);
    }

    public function show(?string $key = null)
    {
        return view('demo.show', [
            'templateKey' => $key,
        ]);
    }
}