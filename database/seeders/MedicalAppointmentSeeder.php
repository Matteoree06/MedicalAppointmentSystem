<?php

namespace Database\Seeders;

use App\Models\Consultorio;
use App\Models\Especialidad;
use App\Models\Paciente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalAppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            EspecialidadSeeder::class,
            ConsultorioSeeder::class,
            PacienteSeeder::class,
            MedicoSeeder::class,
            CitaSeeder::class,
            HistorialMedicoSeeder::class,
            PagoCitaSeeder::class,
        ]);
    }
}
