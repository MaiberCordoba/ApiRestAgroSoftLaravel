<?php

namespace App\Http\Controllers;

use App\Models\Eras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ErasController extends Controller
{
    /**
     * List all eras.
     */
    public function index()
    {
        try {
            $eras = Eras::all();
            return response()->json($eras, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al obtener las eras'], 500);
        }
    }

    /**
     * Get an era by ID.
     */
    public function show($id)
    {
        try {
            $era = Eras::find($id);

            if (!$era) {
                return response()->json(['message' => 'Era no encontrada'], 404);
            }

            return response()->json($era, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al buscar la era'], 500);
        }
    }

    /**
     * Create a new era.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fk_Lotes' => 'required|integer',
                'tamX' => 'required|numeric',
                'tamY' => 'required|numeric',
                'posX' => 'required|numeric',
                'posY' => 'required|numeric',
                'estado' => 'required|boolean',
            ]);

            $era = Eras::create([
                'fk_Lotes' => $validated['fk_Lotes'],
                'tamX' => $validated['tamX'],
                'tamY' => $validated['tamY'],
                'posX' => $validated['posX'],
                'posY' => $validated['posY'],
                'estado' => $validated['estado'],
            ]);

            return response()->json(['message' => 'Era registrada correctamente'], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al registrar la era'], 500);
        }
    }

    /**
     * Update an era by ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $era = Eras::find($id);

            if (!$era) {
                return response()->json(['message' => 'Era no encontrada'], 404);
            }

            $validated = $request->validate([
                'fk_Lotes' => 'sometimes|integer',
                'tamX' => 'sometimes|numeric',
                'tamY' => 'sometimes|numeric',
                'posX' => 'sometimes|numeric',
                'posY' => 'sometimes|numeric',
                'estado' => 'sometimes|boolean',
            ]);

            if (empty($validated)) {
                return response()->json(['message' => 'No se proporcionaron campos para actualizar'], 400);
            }

            $era->update($validated);

            return response()->json(['message' => 'Era actualizada correctamente'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al actualizar la era'], 500);
        }
    }

    /**
     * Delete an era by ID.
     */
    public function destroy($id)
    {
        try {
            $era = Eras::find($id);

            if (!$era) {
                return response()->json(['message' => 'Era no encontrada'], 404);
            }

            $era->delete();

            return response()->json(['message' => 'Era eliminada correctamente'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al eliminar la era'], 500);
        }
    }

    /**
     * Get eras by lote ID.
     */
    public function getByLoteId($id)
    {
        try {
            $eras = Eras::where('fk_Lotes', $id)->get();

            if ($eras->isEmpty()) {
                return response()->json(['message' => 'No hay eras registradas para este lote'], 404);
            }

            return response()->json($eras, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al obtener el reporte'], 500);
        }
    }
}