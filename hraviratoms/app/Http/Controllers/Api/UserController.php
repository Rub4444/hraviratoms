<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->is_superadmin) {
            abort(403);
        }

        // Можно отфильтровать, например, только не-суперадминов,
        // но можно и всех показать.
        return User::orderBy('name')
            ->get(['id', 'name', 'email', 'is_superadmin']);
    }
}
