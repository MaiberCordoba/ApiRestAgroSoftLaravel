<?php

namespace App\Http\Controllers;

use App\Models\TiposEspecie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TiposEspecieController extends Controller
{
    // Obtener todos los tipos de especie
    public function index()
    {
        try {
            $tiposEspecie = TiposEspecie::all();
            return response()->json($tiposEspecie, 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear un nuevo tipo de especie
    public function store(Request $request)
    {
        try {
            $data = $request->only(['nombre', 'descripcion', 'img']);

            $nuevoTipoEspecie = TiposEspecie::create($data);

            if ($nuevoTipoEspecie) {
                return response()->json(['msg' => 'El tipo de especie fue registrado exitosamente'], 200);
            } else {
                return response()->json(['msg' => 'Error al registrar el tipo de especie'], 400);
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar un tipo de especie
    public function update(Request $request, $id)
    {
        try {
            $tipoEspecie = TiposEspecie::find($id);

            if (!$tipoEspecie) {
                return response()->json(['msg' => 'No se encontró el tipo de especie con ese ID'], 404);
            }

            $tipoEspecie->update($request->only(['nombre', 'descripcion', 'img']));

            return response()->json(['msg' => 'El tipo de especie fue actualizado exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Eliminar un tipo de especie
    public function destroy($id)
    {
        try {
            $tipoEspecie = TiposEspecie::find($id);

            if (!$tipoEspecie) {
                return response()->json(['msg' => 'No se encontró el tipo de especie con ese ID'], 404);
            }

            $tipoEspecie->delete();

            return response()->json(['msg' => 'El tipo de especie fue eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }
}
