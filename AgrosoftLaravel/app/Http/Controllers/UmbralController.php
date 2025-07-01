<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Umbral;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UmbralController extends Controller
{


    public function index()
    {
        $umbrales = Umbral::all();
        return response()->json($umbrales);
    }
    /**
     * Crear un umbral para un sensor
     */
    public function store(Request $request, $sensorId)
    {
        $sensor = Sensor::find($sensorId);
        
        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Sensor no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'valor_minimo' => 'required|numeric',
            'valor_maximo' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $umbral = $sensor->umbrales()->create([
            'valor_minimo' => $request->valor_minimo,
            'valor_maximo' => $request->valor_maximo
        ]);

        return response()->json([
            'success' => true,
            'data' => $umbral
        ], 201);
    }

    /**
     * Actualizar un umbral
     */
    public function update(Request $request, $id)
    {
        $umbral = Umbral::find($id);

        if (!$umbral) {
            return response()->json([
                'success' => false,
                'message' => 'Umbral no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'valor_minimo' => 'required|numeric',
            'valor_maximo' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $umbral->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $umbral
        ]);
    }

    /**
     * Eliminar un umbral
     */
    public function destroy($id)
    {
        $umbral = Umbral::find($id);
        
        if (!$umbral) {
            return response()->json([
                'success' => false,
                'message' => 'Umbral no encontrado'
            ], 404);
        }

        $umbral->delete();

        return response()->json([
            'success' => true,
            'message' => 'Umbral eliminado correctamente'
        ]);
    }

    /**
     * Verificar alertas para un sensor
     */
    public function verificarAlertas($sensorId)
    {
        $sensor = Sensor::with('umbrales')->find($sensorId);
        
        if (!$sensor) {
            return response()->json([
                'success' => false,
                'message' => 'Sensor no encontrado'
            ], 404);
        }

        $alertas = [];
        
        foreach ($sensor->umbrales as $umbral) {
            if ($sensor->datos_sensor < $umbral->valor_minimo) {
                $alertas[] = [
                    'tipo' => 'minimo',
                    'umbral' => $umbral->valor_minimo,
                    'valor_actual' => $sensor->datos_sensor,
                    'mensaje' => "Valor por debajo del mínimo ({$umbral->valor_minimo})"
                ];
            }
            
            if ($sensor->datos_sensor > $umbral->valor_maximo) {
                $alertas[] = [
                    'tipo' => 'maximo',
                    'umbral' => $umbral->valor_maximo,
                    'valor_actual' => $sensor->datos_sensor,
                    'mensaje' => "Valor por encima del máximo ({$umbral->valor_maximo})"
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'sensor_id' => $sensor->id,
            'tipo_sensor' => $sensor->tipo_sensor,
            'valor_actual' => $sensor->datos_sensor,
            'alertas' => $alertas
        ]);
    }
}