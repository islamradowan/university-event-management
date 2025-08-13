<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
            // For API requests, just return null so Laravel sends 401 JSON
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
        }

        // For web routes, redirect to login page if it exists
        return route('login'); // make sure this route exists for web auth
    }
}
