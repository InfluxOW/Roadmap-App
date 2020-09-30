<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Manager
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isManager()) {
            return $next($request);
        }

        return response("Only managers are allowed to access this endpoint.", 403);
    }
}
