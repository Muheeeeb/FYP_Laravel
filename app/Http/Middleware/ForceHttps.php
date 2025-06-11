<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        if (App::environment('production')) {
            // Force HTTPS in production
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
} 