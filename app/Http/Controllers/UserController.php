<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUsuarioRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mostrar todos los usuarios
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Crear un nuevo usuario
    public function store(StoreUsuarioRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user
        ], 201);
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user, 200);
    }
}
