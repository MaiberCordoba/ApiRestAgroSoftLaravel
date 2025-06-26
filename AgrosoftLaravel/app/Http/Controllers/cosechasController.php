<?php

namespace App\Http\Controllers;

use App\Models\Cosechas;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class CosechasController extends Controller
{
    // Obtener todas las cosechas
    public function index()
    {
        try {
            $cosechas = Cosechas::all();
            return response()->json($cosechas, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear una nueva cosecha
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fk_Cultivo' => 'required|integer|exists:cultivos,id',
                'fecha' => 'required|date',
                'cantidad' => 'required|numeric',
                'unidad' => 'required|string|max:50',
                'estado' => 'nullable|string|in:Disponible,Agotada', // según tus enums reales
            ]);

            Cosechas::create($validated);

            return response()->json(['msg' => 'Se creó correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar una cosecha
    public function update(Request $request, $id)
    {
        try {
            $cosecha = Cosechas::findOrFail($id);

            $validated = $request->validate([
                'fk_Cultivo' => 'sometimes|integer|exists:cultivos,id',
                'fecha' => 'sometimes|date',
                'cantidad' => 'sometimes|numeric',
                'unidad' => 'sometimes|string|max:50',
                'estado' => 'sometimes|string|in:Disponible,Agotada',
            ]);

            $cosecha->update($validated);

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
