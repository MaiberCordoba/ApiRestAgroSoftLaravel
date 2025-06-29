<?php

namespace App\Http\Controllers;

use App\Models\Semillero;
use Illuminate\Http\Request;

class SemillerosController extends Controller
{
    public function index()
    {
        try {
            $semilleros = Semillero::all();
            return response()->json($semilleros, 200);
        } catch (\Throwable $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'fk_Especies' => 'required|integer|exists:especies,id',
                'unidades' => 'required|integer',
                'fechaSiembra' => 'required|date',
                'fechaEstimada' => 'required|date',
            ]);

            Semillero::create([
                'fk_Especies' => $request->fk_Especies,
                'unidades' => $request->unidades,
                'fechaSiembra' => $request->fechaSiembra,
                'fechaEstimada' => $request->fechaEstimada,
            ]);

            return response()->json(['msg' => 'El semillero fue registrado exitosamente'], 200);
        } catch (\Throwable $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $semillero = Semillero::findOrFail($id);

            $data = $request->only(['fk_Especies', 'unidades', 'fechaSiembra', 'fechaEstimada']);

            if (empty($data)) {
                return response()->json(['msg' => 'No se proporcionaron campos para actualizar'], 400);
            }

            $semillero->update($data);

            return response()->json($semillero, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['msg' => 'No se encontró el semillero con ese ID'], 404);
        } catch (\Throwable $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $semillero = Semillero::findOrFail($id);
            $semillero->delete();

            return response()->json(['msg' => 'El semillero fue eliminado exitosamente'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['msg' => 'No se encontró el semillero con ese ID'], 404);
        } catch (\Throwable $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }
}
