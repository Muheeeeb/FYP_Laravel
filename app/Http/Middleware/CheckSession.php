<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if session has expired
        if (!$request->session()->has('last_activity')) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Session has expired.');
        }

        // Update last activity timestamp
        $request->session()->put('last_activity', time());

        return $next($request);
    }
}