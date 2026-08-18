<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('ADMIN', $module, $action)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}