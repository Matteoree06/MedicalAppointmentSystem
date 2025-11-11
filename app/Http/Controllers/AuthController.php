<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registro
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'fecha_nacimiento' => 'required|date',
        'contacto_emergencia' => 'required|string|max:255', // ✅ Agregado
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'fecha_nacimiento' => $request->fecha_nacimiento,
        'contacto_emergencia' => $request->contacto_emergencia, // ✅ Agregado
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ], 201);
}



    // Login
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

        // opcional: eliminar tokens anteriores si quieres single-session
        // $user->tokens()->delete();

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Logout (revocar token actual)
    public function logout(Request $request)
    {
        // Borra sólo el token usado en esta petición:
        $request->user()->currentAccessToken()->delete();

        // O para cerrar todas las sesiones del usuario:
        // $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada.']);
    }

    
}
