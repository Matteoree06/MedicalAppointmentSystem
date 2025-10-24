<?php

namespace Database\Seeders;

use App\Models\Consultorio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Consultorio::factory()->count(100)->create();
    }
}
