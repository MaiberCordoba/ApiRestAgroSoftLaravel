<?php

namespace App\Http\Controllers;

use App\Models\Lotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;


class LotesController extends Controller
{
    // Obtener todos los lotes
    public function index()
    {
        try {
            $lotes = Lotes::all();
            return response()->json($lotes, 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear un nuevo lote
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                'tamX' => 'required|numeric',
                'tamY' => 'required|numeric',
                'estado' => 'required|boolean',
                'posX' => 'required|numeric',
                'posY' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Todos los campos son obligatorios'], 400);
            }

            Lotes::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'tamX' => (float) $request->tamX,
                'tamY' => (float) $request->tamY,
                'estado' => $request->estado,
                'posX' => (float) $request->posX,
                'posY' => (float) $request->posY,
            ]);

            return response()->json(['msg' => 'Lote registrado correctamente'], 201);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') { // Integrity constraint violation (e.g., unique constraint)
                return response()->json(['msg' => 'El nombre del lote ya existe. Debe ser único.'], 409);
            }
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar un lote por ID
    public function update(Request $request, $id)
    {
        try {
            $lote = Lotes::find($id);
            if (!$lote) {
                return response()->json(['msg' => 'Lote no encontrado'], 404);
            }

            $data = array_filter($request->only([
                'nombre', 'descripcion', 'tamX', 'tamY', 'estado', 'posX', 'posY'
            ]), fn($value) => !is_null($value));

            // Apply defaults for non-provided fields
            $lote->nombre = $data['nombre'] ?? $lote->nombre;
            $lote->descripcion = $data['descripcion'] ?? $lote->descripcion;
            $lote->tamX = isset($data['tamX']) ? (float) $data['tamX'] : $lote->tamX;
            $lote->tamY = isset($data['tamY']) ? (float) $data['tamY'] : $lote->tamY;
            $lote->estado = isset($data['estado']) && is_bool($data['estado']) ? $data['estado'] : $lote->estado;
            $lote->posX = isset($data['posX']) ? (float) $data['posX'] : $lote->posX;
            $lote->posY = isset($data['posY']) ? (float) $data['posY'] : $lote->posY;

            $lote->save();

            return response()->json(['msg' => 'Lote actualizado correctamente'], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['msg' => 'El nombre del lote ya existe. Debe ser único.'], 409);
            }
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Eliminar un lote por ID
    public function destroy($id)
    {
        try {
            $lote = Lotes::find($id);
            if (!$lote) {
                return response()->json(['msg' => 'Lote no encontrado'], 404);
            }

            $lote->delete();
            return response()->json(['msg' => 'Lote eliminado correctamente'], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Obtener un lote por ID
    public function show($id)
    {
        try {
            $lote = Lotes::find($id);
            if ($lote) {
                return response()->json($lote, 200);
            }
            return response()->json(['msg' => 'Lote no encontrado'], 404);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Buscar por ubicación
    public function getByUbicacion($posX, $posY)
    {
        try {
            if (!is_numeric($posX) || !is_numeric($posY)) {
                return response()->json(['msg' => 'Posiciones inválidas'], 400);
            }

            $lotes = Lotes::where('posX', (float) $posX)->where('posY', (float) $posY)->get();
            if ($lotes->isEmpty()) {
                return response()->json(['msg' => 'No hay lotes en esa ubicación'], 404);
            }
            return response()->json($lotes, 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Buscar por estado
    public function getByEstado($estado)
    {
        try {
            if (!in_array($estado, ['0', '1'])) {
                return response()->json(['msg' => 'El estado debe ser 0 o 1'], 400);
            }

            $lotes = Lotes::where('estado', (bool) $estado)->get();
            if ($lotes->isEmpty()) {
                return response()->json(['msg' => 'No hay lotes en ese estado'], 404);
            }
            return response()->json($lotes, 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Reporte por dimensiones
    public function getByDimensiones($tamX, $tamY)
    {
        try {
            if (!is_numeric($tamX) || !is_numeric($tamY)) {
                return response()->json(['msg' => 'Dimensiones inválidas'], 400);
            }

            $lotes = Lotes::where('tamX', (float) $tamX)->where('tamY', (float) $tamY)->get();
            if ($lotes->isEmpty()) {
                return response()->json(['msg' => 'No hay lotes con esas dimensiones'], 404);
            }
            return response()->json($lotes, 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }
}