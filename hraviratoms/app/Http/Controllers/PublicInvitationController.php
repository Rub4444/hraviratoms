<?php

namespace App\Http\Controllers;

use App\Models\Invitation;

class PublicInvitationController extends Controller
{
    public function show(string $slug)
    {
        $invitation = Invitation::with('template')->where('slug', $slug)->firstOrFail();

        return view('invitations.show', [
            'invitation' => $invitation,
        ]);
    }
}
