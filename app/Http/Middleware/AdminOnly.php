<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminOnly Middleware
 * 
 * Ensures that only authenticated users with admin role can access protected routes.
 * Returns 401 for unauthenticated users and 403 for non-admin users.
 * 
 * Validates Requirements 1.6, 7.1
 */
class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Check if user has admin role
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'Forbidden. Admin access required.'
            ], 403);
        }

        return $next($request);
    }
}
