<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tiposSangre = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $alergias = [
            'Ninguna alergia conocida',
            'Penicilina',
            'Maní',
            'Mariscos',
            'Aspirina',
            'Polvo',
            'Polen'
        ];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'fecha_nacimiento' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'sexo' => fake()->randomElement(['Masculino', 'Femenino', 'Otro']),
            'numero_seguro' => fake()->optional(0.7)->numerify('###-##-####'),
            'historial_medico' => fake()->optional(0.5)->realText(200),
            'contacto_emergencia' => fake()->phoneNumber(),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake()->address(),
            'tipo_sangre' => fake()->optional(0.8)->randomElement($tiposSangre),
            'alergias' => fake()->optional(0.6)->randomElement($alergias),
            'activo' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
