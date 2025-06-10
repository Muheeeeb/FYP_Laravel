<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Verify2FA
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and not verified
        if (Auth::check() && !session('verified')) {
            // Store intended URL if not a verification route
            if (!$request->is('verify*')) {
                session(['url.intended' => $request->url()]);
            }
            return redirect()->route('verify.form');
        }

        return $next($request);
    }
}