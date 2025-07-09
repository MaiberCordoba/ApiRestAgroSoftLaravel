<?php

namespace App\Http\Controllers;

use App\Models\Plagas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlagasController extends Controller
{
    // Listar todas las plagas con su tipo de plaga
    public function index()
    {
        try {
            $plagas = Plagas::with('tipoPlaga')->get()->map(function ($plaga) {
                return [
                    'id' => $plaga->id,
                    'nombre' => $plaga->nombre,
                    'descripcion' => $plaga->descripcion,
                    'img' => $plaga->img,
                    'tiposPlaga' => [
                        'id' => $plaga->tipoPlaga->id,
                        'nombre' => $plaga->tipoPlaga->nombre,
                        'descripcion' => $plaga->tipoPlaga->descripcion,
                        'img' => $plaga->tipoPlaga->img,
                    ],
                ];
            });

            return response()->json($plagas, 200);
        } catch (\Exception $e) {
            \Log::error('Error al listar plagas: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Registrar una nueva plaga
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fk_Tipo' => 'required|exists:tiposplaga,id',
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'img' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Todos los campos requeridos deben ser válidos'], 400);
            }

            Plagas::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'img' => $request->img,
                'fk_TiposPlaga' => $request->fk_Tipo,
            ]);

            return response()->json(['message' => 'Plaga registrada'], 201);
        } catch (\Exception $e) {
            \Log::error('Error al registrar plaga: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Actualizar una plaga por ID
    public function update(Request $request, $id)
    {
        try {
            $plaga = Plagas::find($id);
            if (!$plaga) {
                return response()->json(['message' => 'Plaga no encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'fk_Tipo' => 'exists:tiposplaga,id',
                'nombre' => 'string|max:255',
                'descripcion' => 'string',
                'img' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Los campos proporcionados deben ser válidos'], 400);
            }

            $plaga->update(array_filter([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'img' => $request->img,
                'fk_TiposPlaga' => $request->fk_Tipo,
            ], fn($value) => !is_null($value)));

            return response()->json(['message' => 'Plaga actualizada'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar plaga: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }


    // Buscar una plaga por ID
    public function show($id)
    {
        try {
            $plaga = Plagas::with('tipoPlaga')->find($id);
            if (!$plaga) {
                return response()->json(['message' => 'Plaga no encontrada'], 404);
            }

            $resultado = [
                'id' => $plaga->id,
                'nombre' => $plaga->nombre,
                'descripcion' => $plaga->descripcion,
                'img' => $plaga->img,
                'tipoPlaga' => [
                    'id' => $plaga->tipoPlaga->id,
                    'nombre' => $plaga->tipoPlaga->nombre,
                    'descripcion' => $plaga->tipoPlaga->descripcion,
                    'img' => $plaga->tipoPlaga->img,
                ],
            ];

            return response()->json($resultado, 200);
        } catch (\Exception $e) {
            \Log::error('Error al buscar plaga: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }
}