<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ParentAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Only apply to parent users
        if ($user && $user->hasRole('parent')) {
            $parentUser = $user->parentUser;
            
            if ($parentUser) {
                // Share parent's students with all views
                view()->share('parentStudents', $parentUser->students);
                
                // Get student IDs for filtering
                $studentIds = $parentUser->students->pluck('id')->toArray();
                
                // Share student IDs for use in controllers
                view()->share('parentStudentIds', $studentIds);
            }
        }
        
        return $next($request);
    }
}
