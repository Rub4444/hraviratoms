<?php

namespace App\Http\Controllers;

use App\Models\InvitationTemplate;

class HomeController extends Controller
{
    public function index()
    {
        $templates = InvitationTemplate::where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('landing', [
            'templates' => $templates,
        ]);
    }
}
