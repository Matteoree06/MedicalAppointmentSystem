<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    /**
     * Listar todos los médicos
     */
    public function index()
    {
        $medicos = Medico::with(['especialidad', 'consultorio', 'citas'])->get();
        return response()->json($medicos, 200);
    }

    /**
     * Mostrar un médico específico
     */
    public function show($id)
    {
        $medico = Medico::with(['especialidad', 'consultorio', 'citas'])->find($id);

        if (!$medico) {
            return response()->json(['message' => 'Médico no encontrado'], 404);
        }

        return response()->json($medico, 200);
    }

    // ==========================================
    // MÉTODOS JSON-LD
    // ==========================================

    /**
     * Listar todos los médicos en formato JSON-LD
     */
    public function indexJsonLd()
    {
        $medicos = Medico::with(['especialidad', 'consultorio', 'citas.paciente'])->get();

        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Lista de Médicos',
            'description' => 'Directorio completo de médicos disponibles en el sistema',
            'numberOfItems' => $medicos->count(),
            'itemListElement' => $medicos->map(function($medico, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => $this->medicoToJsonLd($medico)
                ];
            })
        ];

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Mostrar un médico específico en formato JSON-LD
     */
    public function showJsonLd($id)
    {
        $medico = Medico::with(['especialidad', 'consultorio', 'citas.paciente', 'historialMedico'])->find($id);

        if (!$medico) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Médico no encontrado',
                'description' => 'El médico con ID ' . $id . ' no existe en el sistema.'
            ], 404);
        }

        $jsonLdData = $this->medicoToJsonLd($medico);

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Obtener citas de un médico en formato JSON-LD
     */
    public function citasJsonLd($id)
    {
        $medico = Medico::with(['citas.paciente', 'especialidad'])->find($id);

        if (!$medico) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Médico no encontrado'
            ], 404);
        }

        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Agenda Médica',
            'description' => 'Lista de citas programadas para el médico',
            'numberOfItems' => $medico->citas->count(),
            'about' => [
                '@type' => 'Physician',
                'identifier' => (string)$medico->id,
                'name' => $medico->nombre,
                'medicalSpecialty' => $medico->especialidad->nombre ?? null
            ],
            'itemListElement' => $medico->citas->map(function($cita, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'MedicalAppointment',
                        'identifier' => (string)$cita->id,
                        'appointmentTime' => $cita->fecha_hora,
                        'description' => $cita->motivo ?? 'Consulta médica',
                        'patient' => [
                            '@type' => 'Patient',
                            'name' => $cita->paciente->nombre ?? 'Paciente #' . $cita->paciente_id
                        ]
                    ]
                ];
            })
        ];

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Obtener médicos por especialidad en formato JSON-LD
     */
    public function porEspecialidadJsonLd($especialidadId)
    {
        $medicos = Medico::with(['especialidad', 'consultorio'])
                         ->where('especialidad_id', $especialidadId)
                         ->get();

        $especialidad = $medicos->first()?->especialidad;

        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Médicos por Especialidad',
            'description' => 'Lista de médicos especializados en: ' . ($especialidad?->nombre ?? 'Especialidad desconocida'),
            'numberOfItems' => $medicos->count(),
            'about' => [
                '@type' => 'MedicalSpecialty',
                'identifier' => (string)$especialidadId,
                'name' => $especialidad?->nombre ?? 'Especialidad #' . $especialidadId
            ],
            'itemListElement' => $medicos->map(function($medico, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => $this->medicoToJsonLd($medico)
                ];
            })
        ];

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Convertir un médico a formato JSON-LD
     */
    private function medicoToJsonLd($medico)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Physician',
            'identifier' => (string)$medico->id,
            'name' => $medico->nombre,
            'email' => $medico->email,
            'telephone' => $medico->telefono,
            'medicalSpecialty' => [
                '@type' => 'MedicalSpecialty',
                'name' => $medico->especialidad->nombre ?? 'Medicina General',
                'identifier' => (string)($medico->especialidad_id ?? '')
            ],
            'worksFor' => [
                '@type' => 'MedicalOrganization',
                'name' => 'Sistema de Citas Médicas',
                'department' => $medico->especialidad->nombre ?? 'Medicina General'
            ],
            'workLocation' => [
                '@type' => 'Place',
                'name' => $medico->consultorio->nombre ?? 'Consultorio General',
                'identifier' => (string)($medico->consultorio_id ?? ''),
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $medico->consultorio->ubicacion ?? 'Ubicación por definir'
                ]
            ],
            'availableService' => [
                '@type' => 'MedicalProcedure',
                'name' => 'Consulta Médica',
                'category' => $medico->especialidad->nombre ?? 'Medicina General'
            ],
            'hasCredential' => [
                '@type' => 'EducationalOccupationalCredential',
                'name' => 'Licencia Médica',
                'credentialCategory' => 'Medicina',
                'recognizedBy' => [
                    '@type' => 'Organization',
                    'name' => 'Colegio Médico'
                ]
            ],
            'schedule' => $this->generateSchedule($medico),
            'hasAppointment' => $medico->citas->map(function($cita) {
                return [
                    '@type' => 'MedicalAppointment',
                    'identifier' => (string)$cita->id,
                    'appointmentTime' => $cita->fecha_hora,
                    'description' => $cita->motivo ?? 'Consulta médica'
                ];
            }),
            'yearsOfExperience' => $medico->anos_experiencia ?? null,
            'isAcceptingNewPatients' => $medico->acepta_nuevos_pacientes ?? true,
            'url' => url('/api/jsonld/medicos/' . $medico->id),
            'dateCreated' => $medico->created_at?->toISOString(),
            'dateModified' => $medico->updated_at?->toISOString()
        ];
    }

    /**
     * Generar horario del médico
     */
    private function generateSchedule($medico)
    {
        return [
            '@type' => 'Schedule',
            'name' => 'Horario de Atención',
            'description' => 'Horario disponible para consultas médicas',
            'scheduleTimezone' => 'America/Guayaquil',
            'byDay' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => $medico->horario_inicio ?? '08:00',
            'closes' => $medico->horario_fin ?? '17:00'
        ];
    }
}