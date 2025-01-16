<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckDepartment
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = Auth::user();
            
            \Log::info('User Check', [
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'role' => $user->role
            ]);

            // If user is not authenticated, let auth middleware handle it
            if (!$user) {
                return redirect()->route('login');
            }

            // Check if user has HOD role and no department
            if ($user->hasRole('hod') && !$user->department_id) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Your HOD account requires a department assignment. Please contact the administrator.');
            }

            return $next($request);

        } catch (\Exception $e) {
            \Log::error('CheckDepartment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}