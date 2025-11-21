<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    // -----------------------------------------
    // Registro de Usuario
    // -----------------------------------------
    public function register(RegisterRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'contacto_emergencia' => $request->contacto_emergencia,
            'perfil' => $request->perfil, //  Requisito del sistema
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user,
            'token' => $token,
        ], 201);
    }



    // -----------------------------------------
    // Login
    // -----------------------------------------
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }

        $user = Auth::user();

        // Opcional: borrar tokens anteriores (single session)
        // $user->tokens()->delete();

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user,
            'token' => $token,
        ]);
    }



    // -----------------------------------------
    // Logout (revoca el token actual)
    // -----------------------------------------
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }



    // -----------------------------------------
    // Información del usuario autenticado
    // -----------------------------------------
    public function userData(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'perfil' => $request->user()->perfil, // muestra el rol/perfil
        ]);
    }



    // -----------------------------------------
    // Validar token - Verificar si el token es válido
    // -----------------------------------------
    public function validateToken(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'valid' => true,
            'message' => 'Token válido',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'perfil' => $user->perfil,
                'fecha_nacimiento' => $user->fecha_nacimiento,
                'contacto_emergencia' => $user->contacto_emergencia,
            ]
        ], 200);
    }
}
