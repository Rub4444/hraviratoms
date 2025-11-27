<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        return Invitation::with('template')
            ->orderByDesc('created_at')
            ->get();
    }

    public function show(Invitation $invitation)
    {
        $invitation->load('template', 'guests');
        return $invitation;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invitation_template_id' => ['required', 'exists:invitation_templates,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'bride_name' => ['required', 'string', 'max:255'],
            'groom_name' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'time' => ['nullable', 'string', 'max:50'],
            'venue_name' => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code' => ['nullable', 'string', 'max:255'],
            'data' => ['nullable', 'array'],
            'is_published' => ['boolean'],
        ]);

        $data['slug'] = Str::slug(
            ($data['bride_name'] ?? '') . '-' .
            ($data['groom_name'] ?? '') . '-' .
            ($data['date'] ?? now()->format('Y-m-d'))
        ) . '-' . Str::random(5);

        $invitation = Invitation::create($data);

        return response()->json($invitation->fresh('template'), 201);
    }

    public function update(Request $request, Invitation $invitation)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'bride_name' => ['required', 'string', 'max:255'],
            'groom_name' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'time' => ['nullable', 'string', 'max:50'],
            'venue_name' => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code' => ['nullable', 'string', 'max:255'],
            'data' => ['nullable', 'array'],
            'is_published' => ['boolean'],
        ]);

        $invitation->update($data);

        return $invitation->fresh('template');
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return response()->noContent();
    }
}
