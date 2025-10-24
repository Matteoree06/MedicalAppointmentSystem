<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EspecialidadFactory extends Factory
{

    public function definition(): array
    {
        $especialidades = [
            'Cardiología',
            'Dermatología',
            'Neurología',
            'Pediatría',
            'Psiquiatría',
            'Oncología',
            'Ginecología',
            'Ortopedia',
            'Oftalmología',
            'Endocrinología'
        ];
        $especialidad = $this->faker->randomElement($especialidades);
        return [
            'nombre' => $especialidad,
            'descripcion' => $this->faker->sentence()
        ];
    }
}
