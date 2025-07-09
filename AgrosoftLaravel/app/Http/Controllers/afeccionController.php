<?php

namespace App\Http\Controllers;

use App\Models\Afeccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AfeccionController extends Controller
{
    // Listar todas las afecciones con sus relaciones
    public function index()
    {
        try {
            $afecciones = Afeccion::with([
                'plaga.tipoPlaga',
                'plantacion.cultivos'
            ])->get();

            return response()->json($afecciones, 200);
        } catch (\Exception $e) {
            \Log::error('Error al listar afecciones: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Registrar una nueva afección
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fk_Plantaciones' => 'required|exists:plantaciones,id',
                'fk_Plagas' => 'required|exists:plagas,id',
                'fechaEncuentro' => 'required|date',
                'estado' => 'in:SinTratamiento,EnControl,Eliminado',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Todos los campos requeridos deben ser válidos'], 400);
            }

            Afeccion::create([
                'fk_Plantaciones' => $request->fk_Plantaciones,
                'fk_Plagas' => $request->fk_Plagas,
                'fechaEncuentro' => $request->fechaEncuentro,
                'estado' => $request->estado ?? 'SinTratamiento',
            ]);

            return response()->json(['message' => 'afección registrada'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al registrar afección: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Actualizar una afección por ID
    public function update(Request $request, $id)
    {
        try {
            $afeccion = Afeccion::find($id);
            if (!$afeccion) {
                return response()->json(['message' => 'afección no encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'fk_Plantaciones' => 'exists:plantaciones,id',
                'fk_Plagas' => 'exists:plagas,id',
                'fechaEncuentro' => 'date',
                'estado' => 'in:SinTratamiento,EnControl,Eliminado',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Los campos proporcionados deben ser válidos'], 400);
            }

            $afeccion->update(array_filter([
                'fk_Plantaciones' => $request->fk_Plantaciones,
                'fk_Plagas' => $request->fk_Plagas,
                'fechaEncuentro' => $request->fechaEncuentro,
                'estado' => $request->estado,
            ], fn($value) => !is_null($value)));

            return response()->json(['message' => 'afección actualizada'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar afección: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Buscar una afección por ID
    public function show($id)
    {
        try {
            $afeccion = Afeccion::with([
                'plaga.tipoPlaga',
                'plantacion.cultivos'
            ])->find($id);

            if (!$afeccion) {
                return response()->json(['message' => 'afección no encontrada'], 404);
            }

            $resultado = [
                'id' => $afeccion->id,
                'fechaEncuentro' => $afeccion->fechaEncuentro,
                'estado' => $afeccion->estado,
                'fk_Plagas' => [
                    'idPlaga' => $afeccion->plaga->id,
                    'nombre' => $afeccion->plaga->nombre,
                ],
                'fk_Plantaciones' => [
                    'id' => $afeccion->plantacion->id,
                    'fk_cultivo' => [
                        'id_cultivo' => $afeccion->plantacion->cultivos->id,
                        'nombre' => $afeccion->plantacion->cultivos->nombre,
                        'unidades' => $afeccion->plantacion->cultivos->unidades,
                    ],
                ],
            ];

            return response()->json($resultado, 200);
        } catch (\Exception $e) {
            \Log::error('Error al buscar afección: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }
}