<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictLoginAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip this middleware for API routes
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Check if the user is authenticated with any guard
        if (Auth::guard('staff')->check()) {
            // Prevent access to staff login and other guard routes
            if ($request->routeIs('staff.login') || $request->routeIs('customer.*') || $request->routeIs('vendor.*')) {
                return redirect()->route('staff.dashboard');
            }
        } elseif (Auth::guard('customer')->check()) {
            // Prevent access to customer login and other guard routes
            if ($request->routeIs('customer.login') || $request->routeIs('staff.*') || $request->routeIs('vendor.*')) {
                return redirect()->route('customer.dashboard');
            }
        } elseif (Auth::guard('vendor')->check()) {
            // Prevent access to vendor login and other guard routes
            if ($request->routeIs('vendor.login') || $request->routeIs('staff.*') || $request->routeIs('customer.*')) {
                return redirect()->route('vendor.dashboard');
            }
        }

        return $next($request);
    }
}