<?php

namespace App\Http\Controllers;

use App\Models\TiposPlaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TiposPlagaController extends Controller
{
    // Listar todos los tipos de plaga
    public function index()
    {
        try {
            $tiposPlaga = TiposPlaga::all();
            return response()->json($tiposPlaga, 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Registrar un nuevo tipo de plaga
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'img' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Todos los campos requeridos deben ser válidos'], 400);
            }

            TiposPlaga::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'img' => $request->img,
            ]);

            return response()->json(['message' => 'tipo de plaga registrada'], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Actualizar un tipo de plaga por ID
    public function update(Request $request, $id)
    {
        try {
            $tipoPlaga = TiposPlaga::find($id);
            if (!$tipoPlaga) {
                return response()->json(['message' => 'tipo de plaga no encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'string|max:255',
                'descripcion' => 'string',
                'img' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Los campos proporcionados deben ser válidos'], 400);
            }

            $tipoPlaga->update(array_filter([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'img' => $request->img,
            ], fn($value) => !is_null($value)));

            return response()->json(['message' => 'tipo de plaga actualizada'], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }

    // Buscar un tipo de plaga por ID
    public function show($id)
    {
        try {
            $tipoPlaga = TiposPlaga::find($id);
            if ($tipoPlaga) {
                return response()->json($tipoPlaga, 200);
            }
            return response()->json(['message' => 'tipo de plaga no encontrada'], 404);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['message' => 'error en el sistema'], 500);
        }
    }
}