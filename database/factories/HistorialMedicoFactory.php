<?php

namespace Database\Factories;

use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistorialMedicoFactory extends Factory
{
    public function definition(): array
    {
        $diagnosticos = [
            'Hipertensión arterial',
            'Diabetes tipo 2',
            'Gastritis',
            'Migraña',
            'Artritis',
            'Bronquitis',
            'Dermatitis',
            'Ansiedad',
            'Lumbalgia',
            'Conjuntivitis'
        ];

        $tratamientos = [
            'Medicamento antihipertensivo',
            'Dieta hipocalórica y ejercicio',
            'Protector gástrico',
            'Analgésicos según necesidad',
            'Antiinflamatorios',
            'Broncodilatadores',
            'Cremas tópicas',
            'Terapia psicológica',
            'Fisioterapia',
            'Colirios antibióticos'
        ];

        return [
            'paciente_id' => Paciente::factory(),
            'medico_id' => Medico::factory(),
            'fecha_registro' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'diagnosticos' => $this->faker->randomElement($diagnosticos),
            'tratamientos' => $this->faker->randomElement($tratamientos),
            'observaciones' => $this->faker->optional()->paragraph(),
        ];
    }
}
