<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use Illuminate\Http\Request;

class HistorialMedicoController extends Controller
{
    /**
     * LISTAR TODO EL HISTORIAL
     */
    public function index()
    {
        return response()->json(
            HistorialMedico::with(['paciente', 'medico'])->get(),
            200
        );
    }

    /**
     * MOSTRAR UN REGISTRO DE HISTORIAL
     */
    public function show($id)
    {
        $historial = HistorialMedico::with(['paciente', 'medico'])->find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial no encontrado'], 404);
        }

        return response()->json($historial, 200);
    }

    /**
     * CREAR UN NUEVO REGISTRO DE HISTORIAL
     */
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

    /**
     * ACTUALIZAR UN REGISTRO DE HISTORIAL
     */
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

    /**
     * ELIMINAR UN REGISTRO DE HISTORIAL
     */
    public function destroy($id)
    {
        $historial = HistorialMedico::find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial no encontrado'], 404);
        }

        $historial->delete();

        return response()->json(['message' => 'Historial eliminado correctamente'], 200);
    }
}
