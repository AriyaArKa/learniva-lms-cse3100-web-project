<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $userRole = $request->user()->role;

        // If user tries to access a route they don't have permission for
        if ($userRole !== $role) {
            // Redirect to their appropriate dashboard
            switch ($userRole) {
                case 'admin':
                    return redirect('/admin/dashboard')->with('error', 'You do not have permission to access that resource.');
                case 'instructor':
                    return redirect('/instructor/dashboard')->with('error', 'You do not have permission to access that resource.');
                case 'user':
                default:
                    return redirect('/dashboard')->with('error', 'You do not have permission to access that resource.');
            }
        }
        
        return $next($request);
    }
}
