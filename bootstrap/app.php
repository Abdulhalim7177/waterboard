<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\RestrictLoginAccess;
use App\Http\Middleware\VendorAuth;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'restrict.login' => RestrictLoginAccess::class,
            'vendor.auth' => VendorAuth::class,
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'admin' => AdminMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('mngr-secure-9374/*')) {
                return route('staff.login');
            }
            if ($request->is('customer/*')) {
                return route('customer.login');
            }
            if ($request->is('vendor/*')) {
                return route('vendor.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();