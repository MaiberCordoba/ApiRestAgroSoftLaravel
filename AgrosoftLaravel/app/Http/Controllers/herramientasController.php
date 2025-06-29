<?php

namespace App\Http\Controllers;

use App\Models\Herramientas;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class HerramientasController extends Controller
{
    // Obtener todas las herramientas
    public function index()
    {
        try {
            $herramientas = Herramientas::all();
            return response()->json($herramientas, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear una herramienta
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'estado' => 'nullable|string|in:Disponible,EnUso,Dañada', // ajusta según tus valores válidos
                'cantidad' => 'required|integer|min:0',
            ]);

            Herramientas::create($validated);

            return response()->json(['msg' => 'Se creó correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar una herramienta
    public function update(Request $request, $id)
    {
        try {
            $herramienta = Herramientas::findOrFail($id);

            $validated = $request->validate([
                'nombre' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'estado' => 'sometimes|string|in:Disponible,EnUso,Dañada',
                'cantidad' => 'sometimes|integer|min:0',
            ]);

            $herramienta->update($validated);

            return response()->json(['msg' => 'Se actualizó correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['msg' => 'No se encontró el ID'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error en el servidor'], 500);
        }
    }
}
