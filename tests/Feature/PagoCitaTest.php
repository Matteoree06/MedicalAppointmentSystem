<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PagoCita;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagoCitaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_pagos_de_citas_protegido_con_sanctum()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/pagos-citas');

        $response->assertStatus(200);
    }

    /** @test */
    public function jsonld_lista_pagos_citas_retorna_estructura_itemlist()
    {
        $response = $this->getJson('/api/jsonld/pagos-citas');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '@context',
                '@type',
                'name',
                'numberOfItems',
                'itemListElement',
            ])
            ->assertJson([
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
            ]);
    }

    /** @test */
    public function jsonld_show_pagos_citas_retorna_un_item_con_type()
    {
        // USO DE FACTORY (CLAVE)
        $pago = PagoCita::factory()->create();

        $response = $this->getJson("/api/jsonld/pagos-citas/{$pago->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '@type',
                '@id',
                'identifier',
                'price',
                'paymentMethod',
                'status',
                'validFrom',
            ])
            ->assertJson([
                '@type' => 'PaymentChargeSpecification',
            ]);
    }
}