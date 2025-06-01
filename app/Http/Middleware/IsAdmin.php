<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check for API or session user
        $user = $request->user();

        if (!$user || $user->user_type !== 'admin') {
            return response()->json(['message' => 'Forbidden. Admins only.'], 403);
        }
        return $next($request);
    }
}