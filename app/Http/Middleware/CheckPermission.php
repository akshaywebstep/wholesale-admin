<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action)
    {
        if (!auth()->guard('web')->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->guard('web')->user();

        if ($user->user_type === 'ADMIN') {
            return $next($request);
        }

        if ($user->user_type === 'STAFF') {
            if (method_exists($user, 'hasPermission') && $user->hasPermission('ADMIN', $module, $action)) {
                return $next($request);
            }
            abort(403, 'You do not have permission to perform this action.');
        }

        auth()->guard('web')->logout();

        return redirect()->route('admin.login')->withErrors([
            'email' => 'Current session does not have admin permissions. Please login with Admin account.'
        ]);
    }
}