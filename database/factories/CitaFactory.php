<?php

namespace Database\Factories;

use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Consultorio;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitaFactory extends Factory
{
    public function definition(): array
    {
        $estados = ['programada', 'completada', 'cancelada', 'reprogramada'];
        $motivos = [
            'Consulta general',
            'Control de rutina',
            'Dolor abdominal',
            'Dolor de cabeza',
            'Fiebre',
            'Consulta especializada',
            'Seguimiento de tratamiento',
            'Examen médico',
            'Revisión de exámenes',
            'Dolor muscular'
        ];
        return [
            'paciente_id' => Paciente::factory(),
            'medico_id' => Medico::factory(),
            'consultorio_id' => Consultorio::factory(),
            'fecha_hora' => $this->faker->dateTimeBetween('-3 months', '+3 months'),
            'motivo' => $this->faker->randomElement($motivos),
            'estado' => $this->faker->randomElement($estados),
            'observaciones' => $this->faker->optional()->sentence(),
        ];
    }
}
