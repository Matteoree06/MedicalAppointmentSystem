<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * LISTAR TODAS LAS CITAS
     */
    public function index()
    {
        return response()->json(
            Cita::with(['paciente', 'medico', 'consultorio'])->get(),
            200
        );
    }

    /**
     * MOSTRAR UNA CITA
     */
    public function show($id)
    {
        $cita = Cita::with(['paciente', 'medico', 'consultorio'])->find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        return response()->json($cita, 200);
    }

    /**
     * CREAR UNA NUEVA CITA
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'    => 'required|exists:pacientes,id',
            'medico_id'      => 'required|exists:medicos,id',
            'consultorio_id' => 'required|exists:consultorios,id',
            'fecha_hora'     => 'required|date',
            'motivo'         => 'nullable|string',
        ]);

        $cita = Cita::create($validated);

        return response()->json([
            'message' => 'Cita creada correctamente',
            'data' => $cita
        ], 201);
    }

    /**
     * ACTUALIZAR UNA CITA
     */
    public function update(Request $request, $id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $validated = $request->validate([
            'paciente_id'    => 'exists:pacientes,id',
            'medico_id'      => 'exists:medicos,id',
            'consultorio_id' => 'exists:consultorios,id',
            'fecha_hora'     => 'date',
            'motivo'         => 'nullable|string',
        ]);

        $cita->update($validated);

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'data' => $cita
        ], 200);
    }

    /**
     * ELIMINAR UNA CITA
     */
    public function destroy($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $cita->delete();

        return response()->json(['message' => 'Cita eliminada correctamente'], 200);
    }

    // ============================================================
    // ======================== JSON-LD ===========================
    // ============================================================

    /**
     * LISTAR CITAS EN JSON-LD
     */
    public function indexJsonLd()
    {
        $citas = Cita::with(['paciente', 'medico', 'consultorio'])->get();

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Agenda de Citas Médicas',
            'numberOfItems' => $citas->count(),
            'itemListElement' => $citas->map(function ($c, $i) {
                return [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'item' => $this->citaToJsonLd($c)
                ];
            }),
        ];

        return response()->json($jsonLd, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * MOSTRAR UNA CITA EN JSON-LD
     */
    public function showJsonLd($id)
    {
        $cita = Cita::with(['paciente', 'medico', 'consultorio'])->find($id);

        if (!$cita) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Cita no encontrada'
            ], 404);
        }

        return response()->json(
            $this->citaToJsonLd($cita),
            200,
            [],
            JSON_PRETTY_PRINT
        );
    }

    /**
     * FORMATEO JSON-LD
     */
    private function citaToJsonLd($c)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalAppointment',
            'identifier' => (string) $c->id,
            'appointmentTime' => $c->fecha_hora,
            'description' => $c->motivo ?? 'Consulta médica',

            'patient' => [
                '@type' => 'Patient',
                'identifier' => (string) $c->paciente_id,
                'name' => $c->paciente->nombre ?? 'Paciente'
            ],

            'doctor' => [
                '@type' => 'Physician',
                'identifier' => (string) $c->medico_id,
                'name' => $c->medico->nombre ?? 'Médico'
            ],

            'location' => [
                '@type' => 'Place',
                'identifier' => (string) $c->consultorio_id,
                'name' => $c->consultorio->nombre ?? 'Consultorio'
            ],

            'url' => url('/api/jsonld/citas/' . $c->id)
        ];
    }
}
