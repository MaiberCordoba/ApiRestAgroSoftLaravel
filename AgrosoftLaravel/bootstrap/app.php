<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Inicia el buffer de salida para prevenir errores de cabeceras
ob_start();

try {
    $app = Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__.'/../routes/web.php',
            api: __DIR__.'/../routes/api.php',
            commands: __DIR__.'/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware): void {
            // Registra tu middleware JWT
            $middleware->alias([
                'jwt.verify' => \App\Http\Middleware\VerifyToken::class,
            ]);
        })
        ->withExceptions(function (Exceptions $exceptions): void {
            // Configuración personalizada para excepciones
            $exceptions->render(function (Throwable $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    'error' => class_basename($e)
                ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
            });
        })->create();
} catch (Throwable $e) {
    // Manejo seguro de errores durante el bootstrap
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Error inicializando la aplicación',
        'error' => class_basename($e)
    ]);
    exit;
}

// Limpia el buffer sin enviar cabeceras
ob_end_clean();

return $app;