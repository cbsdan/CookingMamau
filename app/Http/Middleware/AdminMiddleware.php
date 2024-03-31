<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // Redirect the unauthenticated user to the login page
            return redirect()->route('login')->with('error', "Login first!");
        }

        // Check if the authenticated user is an admin
        if (!auth()->user()->is_admin) {
            // Redirect the non-admin user to a different route or display an error message
            return redirect()->route('home')->with('error', "You are not authorized to access the page!");
        }

        // Proceed to the next middleware or the requested route
        return $next($request);
    }
    
}
