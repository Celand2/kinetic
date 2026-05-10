<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 419 Page Expired : session expirée ou token CSRF invalide
        $exceptions->render(function (
            \Illuminate\Session\TokenMismatchException $e,
            \Illuminate\Http\Request $request
        ) {
            // Si c'est une requête AJAX/JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expirée. Rechargez la page.'], 419);
            }

            // Pour les forms auth (login/register), on redirige avec message
            if ($request->is('login') || $request->is('register')) {
                return redirect()->route('login')
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->with('error', 'Session expirée. Veuillez réessayer.');
            }

            // Pour tout autre form (logout, etc.), on redirige à la page précédente
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Session expirée. Veuillez soumettre à nouveau.');
        });
    })->create();
