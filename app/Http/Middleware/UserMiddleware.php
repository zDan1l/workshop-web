<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     * Allow authenticated users (both admin and user roles).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user data exists in session
        $user = session('user');

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
