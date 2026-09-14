<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureComingSoonAccess
{
    /**
     * Handle an incoming request.
     * Restrict public frontend access to Coming Soon mode unless authenticated.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If Coming Soon mode is disabled in .env, allow all traffic
        if (config('app.coming_soon', env('COMING_SOON', true)) === false) {
            return $next($request);
        }

        // 2. Only authenticated CUSTOMERS (guard: customer) can access the store
        // Admin session (guard: web) will NOT bypass Coming Soon
        if (auth('customer')->check()) {
            return $next($request);
        }

        // 3. Allowed routes for guests to login, register, reset password, etc.
        $allowedRoutes = [
            'login',
            'login.submit',
            'register',
            'register.store',
            'get.states',
            'get.cities',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'logout',
        ];

        foreach ($allowedRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // 4. Also allow explicit static assets or health check if requested
        if ($request->is('images/*', 'css/*', 'js/*', 'storage/*', 'up')) {
            return $next($request);
        }

        // 5. If root URL '/', render Coming Soon page directly
        if ($request->is('/')) {
            return response()->view('frontend.coming-soon');
        }

        // 6. Any other frontend URL (e.g. /category/..., /product/..., /cart, /search)
        // Redirect to root home page which displays the Coming Soon page
        return redirect()->route('home');
    }
}
