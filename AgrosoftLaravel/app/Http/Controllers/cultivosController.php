<?php

namespace App\Http\Controllers;

use App\Models\Cultivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CultivosController extends Controller
{
    public function index()
    {
        return response()->json(Cultivos::all(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fk_Especies' => 'required|integer',
            'nombre' => 'required|string',
            'unidades' => 'required|integer',
            'activo' => 'required|boolean',
            'fechaSiembra' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Todos los campos son obligatorios'], 400);
        }

        Cultivos::create([
            'fk_Especies' => $request->fk_Especies,
            'nombre' => $request->nombre,
            'unidades' => $request->unidades,
            'activo' => $request->activo,
            'fechaSiembra' => $request->fechaSiembra,
        ]);

        return response()->json(['message' => '✅ Cultivo registrado correctamente'], 201);
    }

    public function show($id)
    {
        try {
            $cultivo = Cultivos::findOrFail($id);
            return response()->json($cultivo, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Cultivo no encontrado'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cultivo = Cultivos::findOrFail($id);

            $data = $request->only(['fk_Especies', 'nombre', 'unidades', 'activo', 'fechaSiembra']);
            if (empty($data)) {
                return response()->json(['message' => 'No se proporcionaron campos para actualizar'], 400);
            }

            $cultivo->update($data);

            return response()->json($cultivo, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Cultivo no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el cultivo'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cultivo = Cultivos::findOrFail($id);
            $cultivo->delete();

            return response()->json(['message' => 'Cultivo eliminado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Cultivo no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el cultivo'], 500);
        }
    }

    public function porEspecie($fk_Especies)
    {
        return response()->json(Cultivos::where('fk_Especies', $fk_Especies)->get(), 200);
    }

    public function porSiembra($fechaSiembra)
    {
        if (!$fechaSiembra) {
            return response()->json(['message' => 'Debe proporcionar una fecha de siembra'], 400);
        }

        $cultivos = Cultivos::whereDate('fechaSiembra', $fechaSiembra)->get();

        if ($cultivos->isEmpty()) {
            return response()->json(['message' => 'No hay cultivos registrados en esa fecha'], 404);
        }

        return response()->json([
            'titulo' => '📅 Reporte de Cultivos por Fecha de Siembra',
            'fechaGeneracion' => now()->toDateTimeString(),
            'fechaConsultada' => $fechaSiembra,
            'totalCultivos' => $cultivos->count(),
            'cultivos' => $cultivos
        ], 200);
    }

    public function reporteActivos()
    {
        $reporte = Cultivos::select('nombre')
            ->where('activo', true)
            ->groupBy('nombre')
            ->selectRaw('count(*) as cantidad')
            ->get();

        return response()->json($reporte, 200);
    }
}
