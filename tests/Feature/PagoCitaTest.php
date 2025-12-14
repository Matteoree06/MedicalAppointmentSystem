<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagoCitaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_pagos_de_citas()
    {
        // Usuario autenticado
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Llamada al endpoint
        $response = $this->getJson('/api/pagos-citas');

        // Validación
        $response->assertStatus(200);
    }
}
