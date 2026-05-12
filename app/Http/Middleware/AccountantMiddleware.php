<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountantMiddleware
{
    /**
     * Handle an incoming request.
     * Allows users with 'admin' OR 'accountant' role.
     * Accountants are restricted to: clients, charges, bills, bill items.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Access denied. Authentication required.');
        }

        // Admins can access everything — let them pass
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Accountants may only access the allowed modules
        if ($user->isAccountant()) {
            return $next($request);
        }

        abort(403, 'Access denied. Insufficient permissions.');
    }
}
