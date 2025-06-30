<?php

namespace App\Http\Controllers;

use App\Models\Ventas;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class VentasController extends Controller
{
    // Obtener todas las ventas
    public function index()
    {
        try {
            $ventas = Ventas::all();
            return response()->json($ventas, 200);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Crear una nueva venta
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fk_Cosechas'=> 'required',
                'precioUnitario'=> 'required',
                'fecha'=> 'required',
            ]);

            Ventas::create($validated);

            return response()->json(['msg' => 'Se creó correctamente'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    // Actualizar una venta
    public function update(Request $request, $id)
    {
        try {
            $venta = Ventas::findOrFail($id);

            $validated = $request->validate([
               'fk_Cosechas'=> 'required',
                'precioUnitario'=> 'required',
                'fecha'=> 'required',
            ]);

            $venta->update($validated);

            return response()->json(['msg' => 'Se actualizó correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['msg' => 'No se encontró el ID'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error en el servidor'], 500);
        }
    }
}
