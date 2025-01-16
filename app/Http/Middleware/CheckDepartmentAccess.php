<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDepartmentAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if ($user->role === 'hod') {
            $departmentId = $request->route('department_id') ?? 
                           $request->input('department_id');
                           
            if ($departmentId && $departmentId != $user->department_id) {
                return redirect()->back()
                    ->with('error', 'You can only access your own department.');
            }
        }

        return $next($request);
    }
}