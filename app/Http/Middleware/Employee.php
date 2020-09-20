<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Employee
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isEmployee()) {
            return $next($request);
        }

        return response("Only employees are allowed to access this endpoint.", 403);
    }
}
