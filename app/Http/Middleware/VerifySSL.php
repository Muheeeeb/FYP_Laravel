<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySSL
{
    public function handle(Request $request, Closure $next)
    {
        // Only enforce HTTPS in production
        if (app()->environment('production')) {
            // Check if we're not on HTTPS
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
} 