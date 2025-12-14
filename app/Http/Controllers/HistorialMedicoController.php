<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use Illuminate\Http\Request;

class HistorialMedicoController extends Controller
{
    // =========================
    // CRUD NORMAL (JSON normal)
    // =========================

    public function index()
    {
        return response()->json(
            HistorialMedico::with(['paciente', 'medico'])->get(),
            200
        );
    }

    public function show($id)
    {
        $historial = HistorialMedico::with(['paciente', 'medico'])->find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial no encontrado'], 404);
        }

        return response()->json($historial, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'   => 'required|exists:pacientes,id',
            'medico_id'     => 'required|exists:medicos,id',
            'diagnostico'   => 'required|string',
            'tratamiento'   => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $historial = HistorialMedico::create($validated);

        return response()->json([
            'message' => 'Historial creado correctamente',
            'data' => $historial
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $historial = HistorialMedico::find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial no encontrado'], 404);
        }

        $validated = $request->validate([
            'paciente_id'   => 'sometimes|exists:pacientes,id',
            'medico_id'     => 'sometimes|exists:medicos,id',
            'diagnostico'   => 'sometimes|string',
            'tratamiento'   => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $historial->update($validated);

        return response()->json([
            'message' => 'Historial actualizado correctamente',
            'data' => $historial
        ], 200);
    }

    public function destroy($id)
    {
        $historial = HistorialMedico::find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial no encontrado'], 404);
        }

        $historial->delete();

        return response()->json(['message' => 'Historial eliminado correctamente'], 200);
    }

    // =========================
    // ✅ JSON-LD (WEB SEMÁNTICA)
    // =========================

    public function indexJsonLd()
    {
        $items = HistorialMedico::with(['paciente', 'medico'])->get();

        $jsonldData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Historial Médico',
            'description' => 'Listado de registros clínicos del sistema',
            'numberOfItems' => $items->count(),
            'itemListElement' => $items->values()->map(function ($h, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => $this->historialToJsonLd($h),
                ];
            })->toArray(),
        ];

        return response()->json($jsonldData, 200, [], JSON_PRETTY_PRINT);
    }

    public function showJsonLd($id)
    {
        $h = HistorialMedico::with(['paciente', 'medico'])->find($id);

        if (!$h) {
            $jsonldData = [
                '@context' => 'https://schema.org',
                '@type' => 'Thing',
                'name' => 'Historial no encontrado',
            ];
            return response()->json($jsonldData, 404, [], JSON_PRETTY_PRINT);
        }

        return response()->json($this->historialToJsonLd($h), 200, [], JSON_PRETTY_PRINT);
    }

    private function historialToJsonLd(HistorialMedico $h): array
    {
        return [
            '@type' => 'MedicalRecord',
            '@id' => url("/api/historial-medico/{$h->id}"),
            'identifier' => (string) $h->id,
            'description' => $h->diagnostico,
            'treatment' => $h->tratamiento,
            'note' => $h->observaciones,

            'patient' => $h->paciente ? [
                '@type' => 'Person',
                '@id' => url("/api/pacientes/{$h->paciente->id}"),
                'name' => $h->paciente->nombre ?? null,
            ] : null,

            'provider' => $h->medico ? [
                '@type' => 'Physician',
                '@id' => url("/api/medicos/{$h->medico->id}"),
                'name' => $h->medico->nombre ?? null,
            ] : null,
        ];
    }
}
