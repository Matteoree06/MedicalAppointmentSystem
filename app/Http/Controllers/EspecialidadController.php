<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * Listar todas las especialidades
     */
    public function index()
    {
        return response()->json(Especialidad::all(), 200);
    }

    /**
     * Mostrar una especialidad
     */
    public function show($id)
    {
        $especialidad = Especialidad::with('medicos')->find($id);

        if (!$especialidad) {
            return response()->json(['message' => 'Especialidad no encontrada'], 404);
        }

        return response()->json($especialidad, 200);
    }

    // ==========================================
    // JSON-LD
    // ==========================================

    /**
     * Listar todas las especialidades en JSON-LD
     */
    public function indexJsonLd()
    {
        $especialidades = Especialidad::with('medicos')->get();

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Lista de Especialidades Médicas',
            'numberOfItems' => $especialidades->count(),
            'itemListElement' => $especialidades->map(function ($esp, $i) {
                return [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'item' => $this->especialidadToJsonLd($esp)
                ];
            })
        ];

        return response()->json($jsonLd, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Mostrar una especialidad en JSON-LD
     */
    public function showJsonLd($id)
    {
        $especialidad = Especialidad::with('medicos')->find($id);

        if (!$especialidad) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Especialidad no encontrada',
            ], 404);
        }

        return response()->json(
            $this->especialidadToJsonLd($especialidad),
            200,
            [],
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Convertir especialidad en JSON-LD
     */
    private function especialidadToJsonLd($especialidad)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalSpecialty',
            'identifier' => (string) $especialidad->id,
            'name' => $especialidad->nombre,
            'description' => $especialidad->descripcion ?? 'Especialidad médica',
            'hasPhysician' => $especialidad->medicos->map(function ($m) {
                return [
                    '@type' => 'Physician',
                    'name' => $m->nombre,
                    'identifier' => (string) $m->id
                ];
            }),
            'url' => url('/api/jsonld/especialidades/' . $especialidad->id),
        ];
    }
}
