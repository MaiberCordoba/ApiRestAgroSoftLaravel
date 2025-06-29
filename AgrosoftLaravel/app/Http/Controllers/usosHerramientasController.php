<?php

namespace App\Http\Controllers;

use App\Models\UsosHerramientas;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class UsosHerramientasController extends Controller
{
    // Obtener todos los registros de uso de herramientas
    public function index()
    {
        try {
            $usos = UsosHerramientas::all();
            return response()->json($usos, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear un nuevo uso de herramienta
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fk_Herramienta' => 'required|integer|exists:herramientas,id',
                'fk_Actividad' => 'required|integer|exists:actividades,id',
                'cantidadUsada' => 'required|numeric|min:0',
                // agrega más validaciones si tienes más campos
            ]);

            UsosHerramientas::create($validated);

            return response()->json(['msg' => 'Se creó correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar un uso de herramienta
    public function update(Request $request, $id)
    {
        try {
            $uso = UsosHerramientas::findOrFail($id);

            $validated = $request->validate([
                'fk_Herramienta' => 'sometimes|integer|exists:herramientas,id',
                'fk_Actividad' => 'sometimes|integer|exists:actividades,id',
                'cantidadUsada' => 'sometimes|numeric|min:0',
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
