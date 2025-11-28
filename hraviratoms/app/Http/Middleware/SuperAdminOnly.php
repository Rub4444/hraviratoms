<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdminOnly
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->is_superadmin) {
            abort(403, 'Доступ запрещён.');
        }

        return $next($request);
    }
}
