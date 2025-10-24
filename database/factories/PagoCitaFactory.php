<?php

namespace Database\Factories;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagoCitaFactory extends Factory
{
    public function definition(): array
    {
        $metodosPago = [
            'Efectivo',
            'Tarjeta de crédito',
            'Tarjeta de débito',
            'Transferencia bancaria',
            'Seguro médico',
            'Pago en línea'
        ];

        return [
            'cita_id' => Cita::factory(),
            'monto' => $this->faker->randomFloat(2, 25.00, 150.00), // Entre $25 y $150
            'fecha_pago' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'metodo_pago' => $this->faker->randomElement($metodosPago),
        ];
    }
}
