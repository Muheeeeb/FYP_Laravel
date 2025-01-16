<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();
        
        // Debug line to check user role
        \Log::info('User role check:', [
            'user_id' => $user->id,
            'role' => $user->role,
            'department' => $user->department_id,
            'is_active' => $user->is_active
        ]);

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}