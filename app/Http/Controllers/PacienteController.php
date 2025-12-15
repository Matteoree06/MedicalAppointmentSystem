<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Listar todos los pacientes
     */
    public function index()
    {
        $pacientes = Paciente::with(['citas', 'historialMedico'])->get();
        return response()->json($pacientes, 200);
    }

    /**
     * Mostrar un paciente específico
     */
    public function show($id)
    {
        $paciente = Paciente::with(['citas', 'historialMedico'])->find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        return response()->json($paciente, 200);
    }

    // ==========================================
    // MÉTODOS JSON-LD
    // ==========================================

    /**
     * Listar todos los pacientes en formato JSON-LD
     */
    public function indexJsonLd()
    {
        $pacientes = Paciente::with(['citas.medico.especialidad', 'historialMedico'])->get();

        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Lista de Pacientes',
            'description' => 'Listado completo de pacientes registrados en el sistema',
            'numberOfItems' => $pacientes->count(),
            'itemListElement' => $pacientes->map(function($paciente, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => $this->pacienteToJsonLd($paciente)
                ];
            })
        ];

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Mostrar un paciente específico en formato JSON-LD
     */
    public function showJsonLd($id)
    {
        $paciente = Paciente::with(['citas.medico.especialidad', 'historialMedico'])->find($id);

        if (!$paciente) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Paciente no encontrado',
                'description' => 'El paciente con ID ' . $id . ' no existe en el sistema.'
            ], 404);
        }

        $jsonLdData = $this->pacienteToJsonLd($paciente);

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Obtener citas de un paciente en formato JSON-LD
     */
    public function citasJsonLd($id)
    {
        $paciente = Paciente::with(['citas.medico.especialidad', 'citas.consultorio'])->find($id);

        if (!$paciente) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Error',
                'name' => 'Paciente no encontrado'
            ], 404);
        }

        $jsonLdData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Citas Médicas del Paciente',
            'description' => 'Lista de citas médicas programadas para el paciente',
            'numberOfItems' => $paciente->citas->count(),
            'about' => [
                '@type' => 'Patient',
                'identifier' => (string)$paciente->id,
                'name' => $paciente->nombre ?? 'Paciente #' . $paciente->id
            ],
            'itemListElement' => $paciente->citas->map(function($cita, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'MedicalAppointment',
                        'identifier' => (string)$cita->id,
                        'appointmentTime' => $cita->fecha_hora,
                        'description' => $cita->motivo ?? 'Consulta médica',
                        'physician' => [
                            '@type' => 'Physician',
                            'name' => $cita->medico->nombre ?? 'Médico asignado',
                            'medicalSpecialty' => $cita->medico->especialidad->nombre ?? null
                        ]
                    ]
                ];
            })
        ];

        return response()->json($jsonLdData, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Convertir un paciente a formato JSON-LD
     */
    private function pacienteToJsonLd($paciente)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Patient',
            'identifier' => (string)$paciente->id,
            'name' => $paciente->nombre ?? 'Paciente #' . $paciente->id,
            'email' => $paciente->email,
            'birthDate' => $paciente->fecha_nacimiento,
            'gender' => $this->mapGender($paciente->sexo),
            'telephone' => $paciente->telefono,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $paciente->direccion
            ],
            'medicalCondition' => $this->mapMedicalConditions($paciente),
            'hasAppointment' => $paciente->citas->map(function($cita) {
                return [
                    '@type' => 'MedicalAppointment',
                    'identifier' => (string)$cita->id,
                    'appointmentTime' => $cita->fecha_hora,
                    'description' => $cita->motivo ?? 'Consulta médica',
                    'physician' => [
                        '@type' => 'Physician',
                        'name' => $cita->medico->nombre ?? 'Médico asignado'
                    ]
                ];
            }),
            'medicalRecord' => $paciente->historialMedico->map(function($historial) {
                return [
                    '@type' => 'MedicalRecord',
                    'identifier' => (string)$historial->id,
                    'description' => $historial->descripcion,
                    'dateCreated' => $historial->fecha
                ];
            }),
            'memberOf' => [
                '@type' => 'MedicalOrganization',
                'name' => 'Sistema de Citas Médicas'
            ],
            'url' => url('/api/jsonld/pacientes/' . $paciente->id),
            'dateCreated' => $paciente->created_at?->toISOString(),
            'dateModified' => $paciente->updated_at?->toISOString()
        ];
    }

    /**
     * Mapear género a formato Schema.org
     */
    private function mapGender($sexo)
    {
        return match($sexo) {
            'M', 'Masculino', 'male' => 'male',
            'F', 'Femenino', 'female' => 'female',
            default => 'other'
        };
    }

    /**
     * Mapear condiciones médicas
     */
    private function mapMedicalConditions($paciente)
    {
        $conditions = [];
        
        if ($paciente->alergias) {
            $conditions[] = [
                '@type' => 'MedicalCondition',
                'name' => 'Alergias',
                'description' => $paciente->alergias
            ];
        }

        if (empty($conditions)) {
            $conditions[] = [
                '@type' => 'MedicalCondition',
                'name' => 'Sin condiciones médicas registradas'
            ];
        }

        return $conditions;
    }
}