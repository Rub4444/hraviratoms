<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvitationTemplate;

class InvitationTemplateController extends Controller
{
    public function index()
    {
        return InvitationTemplate::where('is_active', true)
            ->orderBy('id')
            ->get();
    }

    public function show(string $key)
    {
        $template = InvitationTemplate::where('key', $key)
            ->where('is_active', true)
            ->firstOrFail();

        return $template;
    }
}
