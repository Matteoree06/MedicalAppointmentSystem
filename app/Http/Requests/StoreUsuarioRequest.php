<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
     
        $userId = $this->route('id');

       
        if ($this->isMethod('post')) {
            $emailRule = 'required|email|unique:users,email';
        } else {
            $emailRule = 'required|email|unique:users,email,' . $userId;
        }

        
        if ($this->isMethod('post')) {
            $passwordRule = 'required|string|min:6';
        } else {
            $passwordRule = 'nullable|string|min:6';
        }

        return [
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'password' => $passwordRule,

            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:Masculino,Femenino,Otro',
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
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'sexo.required' => 'El sexo es obligatorio.',
            'sexo.in' => 'El sexo debe ser Masculino, Femenino u Otro.',
            'contacto_emergencia.required' => 'El contacto de emergencia es obligatorio.',
        ];
    }
}
