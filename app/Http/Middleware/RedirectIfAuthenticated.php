<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Check if the user is already logged in and redirect based on their role
                switch ($user->role) {
                    case 'hod':
                        return redirect()->route('hod.dashboard');
                    case 'dean':
                        return redirect()->route('dean.dashboard');
                    case 'hr':
                        return redirect()->route('hr.dashboard');
                    default:
                        return redirect()->route('home');
                }
            }
        }

        return $next($request);
    }
}
