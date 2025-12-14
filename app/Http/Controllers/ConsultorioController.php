<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use Illuminate\Http\Request;

class ConsultorioController extends Controller
{
    public function index()
    {
        return response()->json(Consultorio::all(), 200);
    }

    public function show($id)
    {
        $consultorio = Consultorio::with('medicos')->find($id);

        if (!$consultorio) {
            return response()->json(['error' => 'Consultorio no encontrado'], 404);
        }

        return response()->json($consultorio, 200);
    }

    // JSON-LD ==========================================

    public function indexJsonLd()
    {
        $consultorios = Consultorio::with('medicos')->get();

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Lista de Consultorios',
            'numberOfItems' => $consultorios->count(),
            'itemListElement' => $consultorios->map(function ($c, $i) {
                return [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'item' => $this->consultorioToJsonLd($c)
                ];
            })
        ];

        return response()->json($jsonLd, 200, [], JSON_PRETTY_PRINT);
    }

    public function showJsonLd($id)
    {
        $consultorio = Consultorio::with('medicos')->find($id);

        if (!$consultorio) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Consultorio no encontrado'
            ], 404);
        }

        return response()->json($this->consultorioToJsonLd($consultorio), 200, [], JSON_PRETTY_PRINT);
    }

    private function consultorioToJsonLd($c)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Place',
            'identifier' => (string) $c->id,
            'name' => $c->nombre,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $c->ubicacion ?? 'Sin dirección'
            ],
            'medicalStaff' => $c->medicos->map(function ($m) {
                return [
                    '@type' => 'Physician',
                    'identifier' => (string) $m->id,
                    'name' => $m->nombre
                ];
            }),
            'url' => url('/api/jsonld/consultorios/' . $c->id)
        ];
    }
}
