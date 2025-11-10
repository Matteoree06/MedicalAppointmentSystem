<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario administrador
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@medicalapp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'fecha_nacimiento' => '1980-05-15',
            'sexo' => 'Masculino',
            'numero_seguro' => '123-45-6789',
            'historial_medico' => 'Sin antecedentes médicos relevantes',
            'contacto_emergencia' => '+1-555-0123',
            'telefono' => '+1-555-0100',
            'direccion' => '123 Main St, Ciudad, Estado 12345',
            'tipo_sangre' => 'O+',
            'alergias' => 'Ninguna alergia conocida',
            'activo' => true,
        ]);

        // Crear un usuario médico
        User::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'medico@medicalapp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'fecha_nacimiento' => '1975-03-22',
            'sexo' => 'Masculino',
            'numero_seguro' => '987-65-4321',
            'historial_medico' => 'Médico especialista en medicina general',
            'contacto_emergencia' => '+1-555-0124',
            'telefono' => '+1-555-0101',
            'direccion' => '456 Medical Ave, Ciudad, Estado 12345',
            'tipo_sangre' => 'A+',
            'alergias' => 'Ninguna alergia conocida',
            'activo' => true,
        ]);

        // Crear un paciente de ejemplo
        User::create([
            'name' => 'María García',
            'email' => 'paciente@medicalapp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'fecha_nacimiento' => '1990-07-10',
            'sexo' => 'Femenino',
            'numero_seguro' => '456-78-9012',
            'historial_medico' => 'Hipertensión controlada con medicamentos',
            'contacto_emergencia' => '+1-555-0125',
            'telefono' => '+1-555-0102',
            'direccion' => '789 Patient Rd, Ciudad, Estado 12345',
            'tipo_sangre' => 'B+',
            'alergias' => 'Penicilina',
            'activo' => true,
        ]);

        // Generar 10 usuarios adicionales con datos aleatorios
        User::factory(10)->create();
    }
}
