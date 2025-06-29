<?php

namespace App\Http\Controllers;

use App\Models\Insumos;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class InsumosController extends Controller
{
    // Obtener todos los insumos
    public function index()
    {
        try {
            $insumos = Insumos::all();
            return response()->json($insumos, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear un nuevo insumo
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'unidades' => 'required|integer|min:0',
            ]);

            Insumos::create($validated);

            return response()->json(['msg' => 'Se creó correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar un insumo
    public function update(Request $request, $id)
    {
        try {
            $insumo = Insumos::findOrFail($id);

            $validated = $request->validate([
                'nombre' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'precio' => 'sometimes|numeric|min:0',
                'unidades' => 'sometimes|integer|min:0',
            ]);

            $insumo->update($validated);

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
