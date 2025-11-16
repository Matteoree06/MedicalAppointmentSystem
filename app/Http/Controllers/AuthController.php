<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // -----------------------------------------
    // Registro de Usuario
    // -----------------------------------------
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'fecha_nacimiento' => 'required|date',
            'contacto_emergencia' => 'required|string|max:255',
            'perfil' => 'required|string|in:admin,editor,usuario', //  Requisito del proyecto
        ]);

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
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

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
}
