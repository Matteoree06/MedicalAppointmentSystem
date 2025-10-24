<?php

namespace Database\Factories;

use App\Models\Especialidad;
use App\Models\Consultorio;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicoFactory extends Factory
{
    public function definition(): array
    {
        $prefijos = ['Dr.', 'Dra.'];
        $prefijo = $this->faker->randomElement($prefijos);
        
        return [
            'nombre' => $prefijo . ' ' . $this->faker->name(),
            'cedula' => $this->faker->unique()->numerify('##########'), // 10 dígitos
            'especialidad_id' => Especialidad::factory(),
            'telefono' => $this->faker->numerify('09########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'consultorio_id' => Consultorio::factory(),
        ];
    }
}
