<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetTimezone::class,
        ]);
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'timezone' => \App\Http\Middleware\SetTimezone::class,
        ]);
        
        // Rate limiting for API routes
        $middleware->throttleApi();
        
        // CSRF protection is enabled by default for web routes
        // Ensure API routes have proper CORS headers
        $middleware->validateCsrfTokens(except: [
            'api/*', // Exclude all API routes from CSRF protection
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Log all exceptions
        $exceptions->report(function (\Throwable $e) {
            if (!($e instanceof \Illuminate\Validation\ValidationException)) {
                Log::error('Exception occurred', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        });
        
        // Handle ModelNotFoundException (404)
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found.'
                ], 404);
            }
            return response()->view('errors.404', [], 404);
        });
        
        // Handle AuthenticationException (401)
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            Log::warning('Unauthenticated access attempt', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
            return redirect()->route('login');
        });
        
        // Handle ValidationException (422) - Laravel handles this by default
        // but we ensure consistent JSON format
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        });
        
        // Handle AuthorizationException (403)
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            Log::warning('Unauthorized access attempt', [
                'url' => $request->fullUrl(),
                'user_id' => $request->user()?->id,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This action is unauthorized.'
                ], 403);
            }
            return response()->view('errors.403', [], 403);
        });
    })->create();
