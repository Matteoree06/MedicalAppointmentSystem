<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\HistorialMedico;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistorialMedicoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_el_historial_medico()
    {
        // Usuario autenticado
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Datos relacionados
        $paciente = Paciente::factory()->create();
        $medico   = Medico::factory()->create();

        // Crear historial médico
        HistorialMedico::factory()->create([
            'paciente_id' => $paciente->id,
            'medico_id'   => $medico->id,
        ]);

        // Llamada al endpoint
        $response = $this->getJson('/api/historial-medico');

        // Validación
        $response->assertStatus(200);
    }
}
