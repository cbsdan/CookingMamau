<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || !$user->is_admin) { // Assuming you have an `is_admin` field in your `users` table
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
