<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTimezone
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set timezone for date/time functions
        date_default_timezone_set('Asia/Vladivostok');
        
        // Also set it for Carbon
        if (class_exists('Carbon\Carbon')) {
            \Carbon\Carbon::setLocale('ru_RU');
        }
        
        return $next($request);
    }
}