<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveRoleIsSet
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() &&
        !Session::has('active_role_id') &&
        !$request->routeIs('filament.admin.pages.switch-role') &&
        !$request->routeIs('filament.auth.login')) {
            return redirect()->route('filament.admin.pages.switch-role');
        }

        return $next($request);
    }
}