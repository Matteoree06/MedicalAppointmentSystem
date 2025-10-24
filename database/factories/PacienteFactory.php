<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class PacienteFactory extends Factory
{
    public function definition(): array
    {
        $generos = ['Masculino', 'Femenino', 'Otro'];
        $genero = $this->faker->randomElement($generos);
        return [
            'nombre' => $genero === 'Masculino' ? $this->faker->name('male') : $this->faker->name('female'),
            'cedula' => $this->faker->unique()->numerify('##########'), // 10 dígitos
            'edad' => $this->faker->numberBetween(1, 90),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-90 years', '-1 year')->format('Y-m-d'),
            'genero' => $genero,
            'telefono' => $this->faker->numerify('09########'), // Formato móvil Ecuador
            'direccion' => $this->faker->address(),
            'correo' => $this->faker->unique()->safeEmail(),
        ];
    }
}
