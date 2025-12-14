<?php

namespace Database\Factories;

use App\Models\PagoCita;
use App\Models\Cita;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagoCitaFactory extends Factory
{
    protected $model = PagoCita::class;

    public function definition(): array
    {
        return [
            'cita_id' => Cita::factory(),
            'monto' => $this->faker->randomFloat(2, 10, 200),
            'fecha_pago' => $this->faker->date(),
            'metodo_pago' => $this->faker->randomElement(['Efectivo', 'Tarjeta', 'Transferencia']),
            'estado' => $this->faker->randomElement(['Pagado', 'Pendiente']),
        ];
    }
}
