<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUsuarioRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 🔹 Mostrar todos los usuarios
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // 🔹 Crear un nuevo usuario usando StoreUsuarioRequest
    public function store(StoreUsuarioRequest $request)
    {
        // ✅ Laravel ya valida automáticamente con StoreUsuarioRequest
        $validated = $request->validated();

        // Encriptar contraseña
        $validated['password'] = bcrypt($validated['password']);

        // Crear usuario
        $user = User::create($validated);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user
        ], 201);
    }

    // 🔹 Mostrar un usuario específico
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user, 200);
    }

    // 🔹 Actualizar un usuario existente
    public function update(StoreUsuarioRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'user' => $user
        ], 200);
    }

    // 🔹 Eliminar un usuario
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado exitosamente'], 200);
    }
}
