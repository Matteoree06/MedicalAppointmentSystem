<?php

namespace Database\Seeders;

use App\Models\PagoCita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PagoCitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PagoCita::factory()->count(100)->create();
    }
}
