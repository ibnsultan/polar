<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRegistrationEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if registration is enabled
        if (!config('auth.registration.enabled', true)) {
            // If it's an AJAX request, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Registration is currently disabled.',
                    'success' => false
                ], 403);
            }

            // For regular requests, redirect to login with error message
            return redirect()->route('login')->with('error', 'Registration is currently disabled.');
        }

        return $next($request);
    }
}
