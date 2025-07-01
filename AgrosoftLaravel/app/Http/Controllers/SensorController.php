<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    /**
     * Listar todos los sensores
     */
    public function index()
    {
        $sensores = Sensor::all();
        return response()->json($sensores);
    }

    /**
     * Crear un nuevo sensor (sin umbrales)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_sensor' => 'required|in:Temperatura,Iluminación,Humedad Ambiental,Humedad del Terreno,Nivel de PH,Viento,Lluvia',
            'datos_sensor' => 'required|numeric',
            'era_id' => 'nullable|integer|exists:eras,id',
            'lote_id' => 'nullable|integer|exists:lotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sensor = Sensor::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $sensor
        ], 201);
    }

    /**
     * Mostrar un sensor específico
     */
    public function show($id)
    {
        $sensor = Sensor::find($id);
        
        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Sensor no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sensor
        ]);
    }

    /**
     * Actualizar un sensor
     */
    public function update(Request $request, $id)
    {
        $sensor = Sensor::find($id);
        
        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Sensor no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo_sensor' => 'sometimes|in:Temperatura,Iluminación,Humedad Ambiental,Humedad del Terreno,Nivel de PH,Viento,Lluvia',
            'datos_sensor' => 'sometimes|numeric',
            'era_id' => 'nullable|integer|exists:eras,id',
            'lote_id' => 'nullable|integer|exists:lotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $sensor->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $sensor
        ]);
    }

    /**
     * Eliminar un sensor
     */
    public function destroy($id)
    {
        $sensor = Sensor::find($id);
        
        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Sensor no encontrado'
            ], 404);
        }

        $sensor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sensor eliminado correctamente'
        ]);
    }

    /**
     * Obtener lecturas de sensores con filtros
     */
    public function filtrarLecturas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'nullable|in:Temperatura,Iluminación,Humedad Ambiental,Humedad del Terreno,Nivel de PH,Viento,Lluvia',
            'lote_id' => 'nullable|integer|exists:lotes,id',
            'era_id' => 'nullable|integer|exists:eras,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Sensor::query();

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo_sensor', $request->tipo);
        }
        
        if ($request->filled('lote_id')) {
            $query->where('lote_id', $request->lote_id);
        }
        
        if ($request->filled('era_id')) {
            $query->where('era_id', $request->era_id);
        }
        
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }

        $lecturas = $query->get();

        return response()->json([
            'success' => true,
            'data' => $lecturas
        ]);
    }

    /**
     * Obtener los umbrales de un sensor
     */
    public function obtenerUmbrales($id)
    {
        $sensor = Sensor::with('umbrales')->find($id);
        
        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Sensor no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sensor->umbrales
        ]);
    }

    /**
 * Obtener histórico de lecturas para un sensor específico
 */
public function obtenerHistoricoSensor($id, Request $request)
{
    $validator = Validator::make($request->all(), [
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $historico = Sensor::where('id', $id)
            ->whereBetween('fecha', [
                $request->fecha_inicio,
                $request->fecha_fin
            ])
            ->orderBy('fecha', 'asc')
            ->get();

        $historico->each(function ($registro) {
            $registro->setAppends(['unidad']);
        });

        return response()->json([
            'success' => true,
            'data' => $historico
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener histórico del sensor',
            'error' => $e->getMessage()
        ], 500);
    }
}
}