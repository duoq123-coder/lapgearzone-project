<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
return Application::configure(basePath: dirname(__DIR__))
->withRouting(
web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
commands: __DIR__.'/../routes/console.php',
health: '/up',
)
->withMiddleware(function (Middleware $middleware): void {
// Đăng ký alias 'admin' cho AdminMiddleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'delivery' => \App\Http\Middleware\DeliveryMiddleware::class,
            'force_change_password' => \App\Http\Middleware\ForceChangePassword::class,
        ]);

        // Miễn trừ kiểm tra CSRF cho Webhook của PayOS
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'payos/*',
            'payos-webhook',
        ]);
    })
->withExceptions(function (Exceptions $exceptions): void {
//
})->create();