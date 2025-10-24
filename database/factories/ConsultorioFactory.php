<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultorioFactory extends Factory
{
    public function definition(): array
    {
        $ubicaciones = [
            'Ala Norte - Primer Piso',
            'Ala Sur - Primer Piso', 
            'Ala Este - Segundo Piso',
            'Ala Oeste - Segundo Piso',
            'Torre Central - Tercer Piso',
            'Edificio Principal - Cuarto Piso',
            'Consulta Externa - Planta Baja',
            'Urgencias - Planta Baja',
            'Especialidades - Quinto Piso'
        ];
        return [
            'numero' => $this->faker->unique()->numberBetween(100, 999),
            'ubicacion' => $this->faker->randomElement($ubicaciones)
        ];
    }
}
