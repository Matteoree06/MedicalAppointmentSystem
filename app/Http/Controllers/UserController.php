<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUsuarioRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Listar todos los usuarios
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

    // Actualizar información de un usuario
    public function update(StoreUsuarioRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $validated = $request->validated();

       
        if (!empty($validated['password'] ?? null)) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
           
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'user' => $user
        ], 200);
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente'
        ], 200);
    }

    // ==========================================
    // MÉTODOS JSON-LD
    // ==========================================

    /**
     * Listar todos los usuarios en formato JSON-LD
     */
    public function indexJsonLd()
    {
        $users = User::with(['citas', 'historialMedicoRegistros'])->get();

        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Lista de Usuarios/Pacientes',
            'numberOfItems' => $users->count(),
            'itemListElement' => $users->map(function($user, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => $this->userToJsonLd($user)
                ];
            })
        ];

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Mostrar un usuario específico en formato JSON-LD
     */
    public function showJsonLd($id)
    {
        $user = User::with(['citas', 'historialMedicoRegistros'])->find($id);

        if (!$user) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Usuario no encontrado',
                'description' => 'El usuario con ID ' . $id . ' no existe.'
            ], 404);
        }

        $jsonLdData = $this->userToJsonLd($user);

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Convertir un usuario a formato JSON-LD
     */
    private function userToJsonLd($user)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Patient',
            'identifier' => (string)$user->id,
            'name' => $user->name,
            'email' => $user->email,
            'birthDate' => $user->fecha_nacimiento?->format('Y-m-d'),
            'gender' => $user->sexo === 'M' ? 'male' : ($user->sexo === 'F' ? 'female' : 'other'),
            'telephone' => $user->telefono,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $user->direccion
            ],
            'medicalCondition' => [
                '@type' => 'MedicalCondition',
                'name' => $user->alergias ? 'Alergias: ' . $user->alergias : 'Sin alergias conocidas'
            ],
            'bloodType' => $user->tipo_sangre,
            'emergencyContact' => $user->contacto_emergencia,
            'medicalRecord' => $user->historial_medico,
            'insuranceNumber' => $user->numero_seguro,
            'isActive' => $user->activo,
            'memberOf' => [
                '@type' => 'MedicalOrganization',
                'name' => 'Sistema de Citas Médicas'
            ],
            'hasAppointment' => $user->citas->map(function($cita) {
                return [
                    '@type' => 'MedicalAppointment',
                    'identifier' => (string)$cita->id,
                    'appointmentTime' => $cita->fecha_hora,
                    'description' => $cita->motivo ?? 'Consulta médica'
                ];
            }),
            'url' => url('/api/jsonld/users/' . $user->id),
            'dateCreated' => $user->created_at?->toISOString(),
            'dateModified' => $user->updated_at?->toISOString()
        ];
    }
}
