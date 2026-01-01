<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationTemplate;
use Illuminate\Support\Carbon;
use App\Enums\InvitationStatus;

class DemoInvitationController extends Controller
{
    public function show(?string $key = null)
    {
        return view('demo.show', [
            'templateKey' => $key,
        ]);
    }
}