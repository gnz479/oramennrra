<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Token predefinido (puedes guardarlo en el archivo .env)

        $validToken = config('app.api_token');

        // Obtener el token de la solicitud (puede ser en un header o en un parámetro)
        $token = $request->header('Authorization') ?? $request->header('token'); //le damos opcion para poner el token en la url
        // $token = $request->header('Authorization') ?? $request->query('token'); //le damos opcion para poner el token en la url
        $token = str_replace('Bearer ','', $request->header('Authorization') ?? $request->header('token'));

        // Verificar si el token es válido
        if ($token !== $validToken) {
            return response()->json(['message' => 'error 401 - Invalid Token'], 401);
        }

        return $next($request);
    }
}
