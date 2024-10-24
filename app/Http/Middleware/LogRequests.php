<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RequestLog;
use Illuminate\Support\Facades\Auth;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Continuar con la solicitud y capturar la respuesta
        $response = $next($request);

        // Obtener el usuario autenticado (puede ser null)
        $user = Auth::user();

        // Crear el log de la solicitud
        RequestLog::create([
            'user_id' => $user ? $user->id : null,
            'service' => $request->path(),
            'request_body' => json_encode($request->all()), // Convertir a JSON
            'http_code' => $response->getStatusCode(),
            'response_body' => json_encode(json_decode($response->getContent(), true)), // Convertir a JSON
            'ip_address' => $request->ip(),
        ]);

        // Devolver la respuesta
        return $response;
    }
}
