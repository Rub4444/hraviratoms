<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationRsvp;
use Illuminate\Http\Request;

class PublicInvitationController extends Controller
{
    public function show(string $slug)
    {
        $invitation = Invitation::with('template')->where('slug', $slug)->firstOrFail();

        return view('invitations.show', [
            'invitation' => $invitation,
        ]);
    }

    public function submitRsvp(Request $request, string $slug)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'guest_name'   => ['required', 'string', 'max:255'],
            'guest_phone'  => ['nullable', 'string', 'max:50'],
            'status'       => ['required', 'in:yes,no,maybe'],
            'guests_count' => ['nullable', 'integer', 'min:1', 'max:20'],
            'message'      => ['nullable', 'string', 'max:2000'],
        ]);

        if (empty($data['guests_count'])) {
            $data['guests_count'] = 1;
        }

        InvitationRsvp::create([
            'invitation_id' => $invitation->id,
            'guest_name'    => $data['guest_name'],
            'guest_phone'   => $data['guest_phone'] ?? null,
            'status'        => $data['status'],
            'guests_count'  => $data['guests_count'],
            'message'       => $data['message'] ?? null,
            'guest_ip'      => $request->ip(),
        ]);

        return redirect()
        ->route('invitation.public.show', $invitation->slug)
        ->with('rsvp_success', true);

    }
}
