<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir acceso
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'nullable|in:Masculino,Femenino,Otro',
            'numero_seguro' => 'nullable|string|max:50',
            'historial_medico' => 'nullable|string',
            'contacto_emergencia' => 'required|string|max:255',
            'tipo_sangre' => 'nullable|string|max:5',
            'alergias' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo no es válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
