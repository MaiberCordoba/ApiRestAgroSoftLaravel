<?php

namespace App\Http\Controllers;

use App\Models\UsosProductos;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use Log;

class UsosProductosController extends Controller
{
    // Obtener todos los usos de insumos
    public function index()
    {
        try {
            $usos = UsosProductos::all();
            return response()->json($usos, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear un uso de insumo
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fk_Insumos' => 'required',
                'fk_Actividades' => 'required',
                'cantidadProducto' => 'required|numeric|min:0',
            ]);

            UsosProductos::create($validated);

            return response()->json(['msg' => 'Se creó correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar un uso de insumo
    public function update(Request $request, $id)
    {
        try {
            $uso = UsosProductos::findOrFail($id);

            $validated = $request->validate([
                'fk_Insumo' => 'sometimes|integer|exists:insumos,id',
                'fk_Actividad' => 'sometimes|integer|exists:actividades,id',
                'cantidadProducto' => 'sometimes|numeric|min:0',
            ]);

            $uso->update($validated);

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
