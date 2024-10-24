<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GiphyController extends Controller
{
    public function storeFavorite(Request $request)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'gif_id' => 'required|numeric',
                'alias' => 'required|string|max:255',
                'user_id' => 'required|numeric|exists:users,id',
            ]);
    
            // Crear el registro del favorito
            $favorite = Favorite::create([
                'gif_id' => $request->input('gif_id'),
                'alias' => $request->input('alias'),
                'user_id' => $request->input('user_id'),
            ]);
    
            return response()->json([
                'message' => 'GIF guardado como favorito exitosamente.',
                'favorite' => $favorite,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validación fallida.',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error inesperado.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getGifById($id)
    {
        // API Key desde el archivo .env
        $apiKey = env('GIPHY_API_KEY');

        // Hacer la llamada a la API de Giphy usando el ID
        $response = Http::get("https://api.giphy.com/v1/gifs/{$id}", [
            'api_key' => $apiKey,
        ]);

        // Verificar si la respuesta es exitosa
        if ($response->successful()) {
            $data = $response->json()['data'];
            return response()->json($data);
        } else {
            return response()->json([
                'error' => 'No se pudo obtener la información del GIF.',
            ], $response->status());
        }
    }

    public function search(Request $request)
    {
        // Validación de los parámetros
        $request->validate([
            'query' => 'required|string',
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
        ]);

        // Parámetros de la solicitud
        $query = $request->input('query');
        $limit = $request->input('limit', 10); // Valor por defecto: 10
        $offset = $request->input('offset', 0); // Valor por defecto: 0

        // Tu API key de Giphy
        $apiKey = env('GIPHY_API_KEY');

        // Llamada a la API de Giphy
        $response = Http::get('https://api.giphy.com/v1/gifs/search', [
            'api_key' => $apiKey,
            'q'       => $query,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);

        // Verificación del estado de la respuesta
        if ($response->successful()) {
            $data = $response->json()['data'];
            return response()->json($data);
        } else {
            return response()->json([
                'error' => 'No se pudieron obtener los resultados de Giphy',
            ], $response->status());
        }
    }
}
