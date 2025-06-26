<?php

namespace App\Http\Controllers;

use App\Models\Desechos;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class DesechosController extends Controller
{
    // Obtener todos los desechos
    public function index()
    {
        try {
            $desechos = Desechos::all();
            return response()->json($desechos, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear un nuevo desecho
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'fecha' => 'required|date',
                'fk_TiposDesecho' => 'required|integer|exists:tiposdesecho,id',
                'estado' => 'nullable|string|in:Activo,Inactivo', // según tus enums
            ]);

            Desechos::create($validated);

            return response()->json(['msg' => 'Se creó correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar un desecho
    public function update(Request $request, $id)
    {
        try {
            $desecho = Desechos::findOrFail($id);

            $validated = $request->validate([
                'nombre' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'fecha' => 'sometimes|date',
                'fk_TiposDesecho' => 'sometimes|integer|exists:tiposdesecho,id',
                'estado' => 'sometimes|string|in:Activo,Inactivo',
            ]);

            $desecho->update($validated);

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
