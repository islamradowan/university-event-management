<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Accept both: role:admin,organizer  or role:admin organizer
        if (count($roles) === 1 && strpos($roles[0], ',') !== false) {
            $roles = array_map('trim', explode(',', $roles[0]));
        }

        $user = $request->user();
            if (!$user || !in_array(strtolower($user->role), array_map('strtolower', $roles))) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}