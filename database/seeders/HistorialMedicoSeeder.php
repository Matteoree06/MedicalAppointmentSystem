<?php

namespace Database\Seeders;

use App\Models\HistorialMedico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorialMedicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistorialMedico::factory()->count(100)->create();
    }
}
