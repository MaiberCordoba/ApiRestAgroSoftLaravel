<?php

namespace App\Http\Controllers;

use App\Models\Especies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspeciesController extends Controller
{
    // Obtener todas las especies
    public function index()
    {
        $especies = Especies::all();
        return response()->json($especies, 200);
    }

    // Crear nueva especie
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'img' => 'nullable|string',
            'tiempoCrecimiento' => 'nullable|integer',
            'fk_TiposEspecie' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => 'Datos inválidos', 'errors' => $validator->errors()], 400);
        }

        $especie = Especies::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'img' => $request->img,
            'tiempoCrecimiento' => $request->tiempoCrecimiento,
            'fk_TiposEspecie' => $request->fk_TiposEspecie,
        ]);

        return response()->json(['msg' => 'La especie fue registrada exitosamente'], 200);
    }

    // Actualizar parcialmente una especie
    public function update(Request $request, $id)
    {
        $especie = Especies::find($id);

        if (!$especie) {
            return response()->json(['msg' => 'No se encontró la especie a actualizar'], 404);
        }

        $especie->update($request->only([
            'nombre', 'descripcion', 'img', 'tiempoCrecimiento', 'fk_TiposEspecie'
        ]));

        return response()->json(['msg' => 'La especie fue actualizada exitosamente'], 200);
    }

    // Eliminar una especie
    public function destroy($id)
    {
        $especie = Especies::find($id);

        if (!$especie) {
            return response()->json(['msg' => 'No se encontró la especie a eliminar'], 404);
        }

        $especie->delete();
        return response()->json(['msg' => 'La especie fue eliminada exitosamente'], 200);
    }
}
