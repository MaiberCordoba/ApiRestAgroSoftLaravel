<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class ActividadesController extends Controller
{
    // Crear una nueva actividad
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fk_Cultivos' => 'required|integer|exists:cultivos,id',
                'fk_Usuarios' => 'required|integer',
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'fecha' => 'required|date',
                'estado' => 'nullable|string|in:Asignada,Cancelada,Completada', 
            ]);

            Actividades::create($validated);

            return response()->json(['msg' => 'Actividad creada correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Obtener todas las actividades
    public function index()
    {
        try {
            $actividades = Actividades::all();
            return response()->json($actividades, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Obtener una sola actividad
    public function show($id)
    {
        try {
            $actividad = Actividades::findOrFail($id);
            return response()->json($actividad, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['msg' => 'No se encontró el ID'], 404);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar una actividad
    public function update(Request $request, $id)
    {
        try {
            $actividad = Actividades::findOrFail($id);

            $validated = $request->validate([
                'fk_Cultivos' => 'sometimes|integer|exists:cultivos,id',
                'fk_Usuarios' => 'sometimes|integer',
                'titulo' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'fecha' => 'sometimes|date',
                'estado' => 'sometimes|string|in:Asignada,Cancelada,Completada',
            ]);

            $actividad->update($validated);

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
