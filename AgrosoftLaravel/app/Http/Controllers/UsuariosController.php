<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth; // Añadir esto
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    private $validRoles = ['admin', 'instructor', 'pasante', 'aprendiz', 'visitante'];

    public function getAll()
    {
        try {
            $usuarios = Usuarios::all();
            
            if ($usuarios->isEmpty()) {
                return response()->json([
                    'msg' => 'No se encontraron usuarios',
                    'status' => 200
                ], 200);
            }

            return response()->json($usuarios, 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Internal server error'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|numeric|unique:usuarios',
                'nombre' => 'required|string',
                'apellidos' => 'required|string',
                'fechaNacimiento' => 'required|date',
                'telefono' => 'required|string',
                'correoElectronico' => 'required|email|unique:usuarios',
                'passwordHash' => 'required|string|min:6',
                'rol' => 'string|in:' . implode(',', $this->validRoles),
                'estado' => 'string|in:activo,inactivo'
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->first()], 400);
            }

            $data = $request->only([
                'identificacion',
                'nombre',
                'apellidos',
                'fechaNacimiento',
                'telefono',
                'correoElectronico',
                'rol',
                'estado'
            ]);

            $data['passwordHash'] = Hash::make($request->passwordHash);
            $data['rol'] = $request->rol ?? 'visitante';
            $data['estado'] = $request->estado ?? 'activo';

            $usuario = Usuarios::create($data);

            return response()->json([
                'msg' => 'Usuario creado',
                'data' => $usuario
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error interno al crear usuario',
                'error' => env('APP_DEBUG', false) ? $e->getMessage() : null
            ], 500);
        }
    }

public function login(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'correoElectronico' => 'required|email',
            'passwordHash' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()->first()], 400);
        }
    
        // Credenciales CORREGIDAS - usa 'password' como clave
        $credentials = [
            'correoElectronico' => $request->correoElectronico,
            'password' => $request->passwordHash  // ¡Clave cambiada a 'password'!
        ];
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['msg' => 'Credenciales inválidas'], 401);
        }
    
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Error en login: ' . $e->getMessage());
        return response()->json([
            'msg' => 'Error al iniciar sesión',
            'error' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}

    public function update(Request $request, $identificacion)
    {
        try {
            $usuario = Usuarios::where('identificacion', $identificacion)->first();

            if (!$usuario) {
                return response()->json(['msg' => 'Usuario no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'string',
                'apellidos' => 'string',
                'fechaNacimiento' => 'date',
                'telefono' => 'string',
                'correoElectronico' => 'email|unique:usuarios,correoElectronico,' . $usuario->id,
                'passwordHash' => 'string|min:6',
                'rol' => 'string|in:' . implode(',', $this->validRoles),
                'estado' => 'string|in:activo,inactivo'
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->errors()->first()], 400);
            }

            $data = $request->except(['identificacion']);

            if ($request->has('passwordHash')) {
                $data['passwordHash'] = Hash::make($request->passwordHash);
            }

            $usuario->update($data);

            return response()->json([
                'msg' => 'Usuario actualizado',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Error al actualizar usuario'], 500);
        }
    }

    public function getCurrentUser(Request $request)
    {
        try {
           $user = auth()->user();

            if (!$user) {
                return response()->json(['msg' => 'Token inválido'], 401);
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->identificacion,
                    'nombre' => $user->nombre . ' ' . $user->apellidos,
                    'email' => $user->correoElectronico,
                    'rol' => $user->rol
                ]
            ], 200);
          } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Error interno'], 500);
        }
    }

    public function getTotalUsers()
    {
        try {
            $total = Usuarios::count();
            $activos = Usuarios::where('estado', 'activo')->count();
            $inactivos = Usuarios::where('estado', 'inactivo')->count();

            return response()->json([
                'total_usuarios' => $total,
                'usuarios_activos' => $activos,
                'usuarios_inactivos' => $inactivos
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Error al contar usuarios'], 500);
        }
    }

     public function refreshToken()
    {
        try {
            $newToken = auth()->refresh();
            return response()->json([
                'token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        } catch (JWTException $e) {
            return response()->json(['msg' => 'No se pudo refrescar el token'], 401);
        }
    }
}