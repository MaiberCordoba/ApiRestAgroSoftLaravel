<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class VerifyToken
{
    public function handle($request, Closure $next)
    {
        try {
            // Intenta autenticar sin disparar excepciones inmediatas
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();
            
            if (!$user) {
                return $this->safeJsonResponse([
                    'status' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

        } catch (\Exception $e) {
            return $this->handleJWTException($e);
        }

        return $next($request);
    }

    protected function handleJWTException(\Exception $e)
    {
        if ($e instanceof TokenExpiredException) {
            return $this->safeJsonResponse([
                'status' => false,
                'message' => 'Token expirado',
                'error' => 'token_expired'
            ], 401);
        }

        if ($e instanceof TokenInvalidException) {
            return $this->safeJsonResponse([
                'status' => false,
                'message' => 'Token inválido',
                'error' => 'token_invalid'
            ], 401);
        }

        if ($e instanceof JWTException) {
            return $this->safeJsonResponse([
                'status' => false,
                'message' => 'Token ausente o no proporcionado',
                'error' => 'token_absent'
            ], 401);
        }

        return $this->safeJsonResponse([
            'status' => false,
            'message' => 'Error de autenticación',
            'error' => 'authentication_error'
        ], 500);
    }

    /**
     * Previne errores de cabeceras con respuestas seguras
     */
    protected function safeJsonResponse(array $data, int $status)
    {
        return response()->json($data, $status, [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0'
        ], JSON_UNESCAPED_UNICODE);
    }
}