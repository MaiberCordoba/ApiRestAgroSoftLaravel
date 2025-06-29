<?php

namespace App\Http\Controllers;

use App\Models\Plantacion;
use App\Models\Plantaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlantacionesController extends Controller
{
    public function index()
    {
        try {
            $plantaciones = Plantaciones::with('cultivos')->get();
            return response()->json($plantaciones);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error al listar las plantaciones'], 500);
        }
    }

    public function show($id)
    {
        try {
            $plantacion = Plantaciones::find($id);
            if (!$plantacion) {
                return response()->json(['message' => 'Plantación no encontrada'], 404);
            }
            return response()->json($plantacion);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error al buscar la plantación'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fk_Cultivos' => 'required|integer',
            'fk_Eras' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Todos los campos son obligatorios'], 400);
        }

        try {
            $plantacion = Plantaciones::create([
                'fk_Cultivos' => $request->fk_Cultivos,
                'fk_Eras' => $request->fk_Eras,
            ]);

            return response()->json(['message' => 'Plantación registrada correctamente'], 201);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error al registrar la plantación'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $plantacion = Plantaciones::find($id);
            if (!$plantacion) {
                return response()->json(['message' => 'Plantación no encontrada'], 404);
            }

            $plantacion->update($request->only(['fk_Cultivos', 'fk_Eras']));

            return response()->json(['message' => 'Plantación actualizada correctamente']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error al actualizar la plantación'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $plantacion = Plantaciones::find($id);
            if (!$plantacion) {
                return response()->json(['message' => 'Plantación no encontrada'], 404);
            }

            $plantacion->delete();
            return response()->json(['message' => 'Plantación eliminada correctamente']);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error al eliminar la plantación'], 500);
        }
    }

    public function byEra($fk_Eras)
    {
        $plantaciones = Plantaciones::where('fk_Eras', $fk_Eras)->get();

        if ($plantaciones->isEmpty()) {
            return response()->json(['message' => 'No hay plantaciones registradas en esta era'], 404);
        }

        return response()->json([
            'message' => '🌱 Plantaciones encontradas',
            'total' => $plantaciones->count(),
            'datos' => $plantaciones,
        ]);
    }

    public function byCrop($fk_Cultivos)
    {
        $plantaciones = Plantaciones::where('fk_Cultivos', $fk_Cultivos)->get();
        return response()->json($plantaciones);
    }

    public function byCropAndEra($fk_Eras, $fk_Cultivos)
    {
        $plantaciones = Plantaciones::where('fk_Eras', $fk_Eras)
            ->where('fk_Cultivos', $fk_Cultivos)
            ->get();

        return response()->json($plantaciones);
    }
}
