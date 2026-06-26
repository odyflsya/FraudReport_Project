<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (! $user || $user->status !== 'active') {
            abort(403, 'Akun Anda belum aktif. Silakan hubungi administrator.');
        }
        return $next($request);
    }
}
