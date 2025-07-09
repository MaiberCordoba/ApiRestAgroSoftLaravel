<?php

namespace App\Http\Controllers;

use App\Models\TipoControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoControlController extends Controller
{
    // Listar todos los tipos de control
    public function index()
    {
        try {
            $tiposControl = TipoControl::all();
            return response()->json($tiposControl, 200);
        } catch (\Exception $e) {
            \Log::error('Error al listar tipos de control: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el sistema'], 500);
        }
    }

    // Registrar un nuevo tipo de control
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Todos los campos requeridos deben ser válidos'], 400);
            }

            TipoControl::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            return response()->json(['message' => 'Tipo de control registrado'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al registrar tipo de control: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el sistema'], 500);
        }
    }

    // Actualizar un tipo de control por ID
    public function update(Request $request, $id)
    {
        try {
            $tipoControl = TipoControl::find($id);
            if (!$tipoControl) {
                return response()->json(['message' => 'Tipo de control no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'string|max:255',
                'descripcion' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Los campos proporcionados deben ser válidos'], 400);
            }

            $tipoControl->update(array_filter([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ], fn($value) => !is_null($value)));

            return response()->json(['message' => 'Tipo de control actualizado'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar tipo de control: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el sistema'], 500);
        }
    }

    // Eliminar un tipo de control por ID
    public function destroy($id)
    {
        try {
            $tipoControl = TipoControl::find($id);
            if (!$tipoControl) {
                return response()->json(['message' => 'Tipo de control no encontrado'], 404);
            }

            $tipoControl->delete();
            return response()->json(['message' => 'Tipo de control eliminado'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar tipo de control: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el sistema'], 500);
        }
    }

    // Buscar un tipo de control por ID
    public function show($id)
    {
        try {
            $tipoControl = TipoControl::find($id);
            if (!$tipoControl) {
                return response()->json(['message' => 'Tipo de control no encontrado'], 404);
            }

            return response()->json($tipoControl, 200);
        } catch (\Exception $e) {
            \Log::error('Error al buscar tipo de control: ' . $e->getMessage());
            return response()->json(['message' => 'Error en el sistema'], 500);
        }
    }
}