<?php

namespace App\Http\Controllers;

use App\Models\Controles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ControlesController extends Controller
{
    // Listar todos los controles con sus relaciones
    public function index()
    {
        try {
            $controles = Controles::with([
                'tipoControl',
                'afeccion.plaga.tipoPlaga',
                'afeccion.plantacion.cultivos'
            ])->get();

            return response()->json($controles, 200);
        } catch (\Exception $e) {
            \Log::error('Error al listar controles: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema', 'error' => $e->getMessage()], 500);
        }
    }

    // Registrar un nuevo control
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fk_Afeccion' => 'required|exists:afecciones,id',
                'fk_TipoControl' => 'required|exists:tiposcontrol,id',
                'descripcion' => 'required|string',
                'fechaControl' => 'required|date',
            ]);

            if ($validator->fails()) {
                \Log::error('Validación fallida: ' . json_encode($validator->errors()));
                return response()->json(['message' => 'Todos los campos requeridos deben ser válidos', 'errors' => $validator->errors()], 400);
            }

            \Log::info('Datos recibidos para crear control: ' . json_encode($request->all()));

            $control = Controles::create([
                'fk_Afecciones' => $request->fk_Afeccion,
                'fk_TiposControl' => $request->fk_TipoControl,
                'descripcion' => $request->descripcion,
                'fechaControl' => $request->fechaControl,
            ]);

            \Log::info('Control creado: ' . json_encode($control));

            return response()->json(['message' => 'Control registrado'], 201);
        } catch (\Exception $e) {
            \Log::error('Error al registrar control: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema', 'error' => $e->getMessage()], 500);
        }
    }

    // Actualizar un control por ID
    public function update(Request $request, $id)
    {
        try {
            $control = Controles::find($id);
            if (!$control) {
                return response()->json(['message' => 'Control no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'fk_Afeccion' => 'exists:afecciones,id',
                'fk_TipoControl' => 'exists:tiposcontrol,id',
                'descripcion' => 'string',
                'fechaControl' => 'date',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Los campos proporcionados deben ser válidos', 'errors' => $validator->errors()], 400);
            }

            $control->update(array_filter([
                'fk_Afecciones' => $request->fk_Afeccion,
                'fk_TiposControl' => $request->fk_TipoControl,
                'descripcion' => $request->descripcion,
                'fechaControl' => $request->fechaControl,
            ], fn($value) => !is_null($value)));

            return response()->json(['message' => 'Control actualizado'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar control: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema', 'error' => $e->getMessage()], 500);
        }
    }

    // Buscar un control por ID
    public function show($id)
    {
        try {
            $control = Controles::with([
                'tipoControl',
                'afeccion.plaga.tipoPlaga',
                'afeccion.plantacion.cultivos'
            ])->find($id);

            if (!$control) {
                return response()->json(['message' => 'Control no encontrado'], 404);
            }

            $resultado = [
                'id' => $control->id,
                'fk_Afecciones' => [
                    'id' => $control->afeccion->id,
                    'fechaEncuentro' => $control->afeccion->fechaEncuentro,
                    'estado' => $control->afeccion->estado,
                    'fk_Plagas' => [
                        'idPlaga' => $control->afeccion->plaga->id,
                        'nombre' => $control->afeccion->plaga->nombre,
                    ],
                    'fk_Plantaciones' => [
                        'id' => $control->afeccion->plantacion->id,
                        'fk_cultivo' => [
                            'id_cultivo' => $control->afeccion->plantacion->cultivos->id,
                            'nombre' => $control->afeccion->plantacion->cultivos->nombre,
                            'unidades' => $control->afeccion->plantacion->cultivos->unidades,
                        ],
                    ],
                ],
                'fk_TiposControl' => [
                    'id' => $control->tipoControl->id,
                    'nombre' => $control->tipoControl->nombre,
                    'descripcion' => $control->tipoControl->descripcion,
                ],
                'descripcion' => $control->descripcion,
                'fechaControl' => $control->fechaControl,
            ];

            return response()->json($resultado, 200);
        } catch (\Exception $e) {
            \Log::error('Error al buscar control: ' . $e->getMessage());
            return response()->json(['message' => 'error en el sistema', 'error' => $e->getMessage()], 500);
        }
    }
}