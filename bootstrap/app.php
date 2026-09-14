<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Unauthenticated Users Redirect
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            return route('login');
        });

        // Prevent 419 Page Expired on login & logout endpoints across multiple tabs/guards
        $middleware->validateCsrfTokens(except: [
            'login',
            'admin/login',
            'logout',
            'admin/logout',
        ]);

        // Custom Middleware Aliases
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'coming_soon' => \App\Http\Middleware\EnsureComingSoonAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login')
                    ->with('error', 'Session refreshed. Please sign in again.')
                    ->withInput($request->except('password', '_token'));
            }

            return redirect()->route('home')
                ->with('error', 'Your session was refreshed. Please try again.')
                ->withInput($request->except('password', '_token'));
        });
    })->create();