<?php

namespace App\Http\Controllers;

use App\Models\PagoCita;
use Illuminate\Http\Request;

class PagoCitaController extends Controller
{
    /**
     * LISTAR TODOS LOS PAGOS
     */
    public function index()
    {
        return response()->json(
            PagoCita::with('cita')->get(),
            200
        );
    }

    /**
     * MOSTRAR UN PAGO
     */
    public function show($id)
    {
        $pago = PagoCita::with('cita')->find($id);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        return response()->json($pago, 200);
    }

    /**
     * CREAR UN NUEVO PAGO
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id'     => 'required|exists:citas,id',
            'monto'       => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:50',
            'estado'      => 'required|string|max:50',
        ]);

        $pago = PagoCita::create($validated);

        return response()->json([
            'message' => 'Pago creado correctamente',
            'data' => $pago
        ], 201);
    }

    /**
     * ACTUALIZAR UN PAGO
     */
    public function update(Request $request, $id)
    {
        $pago = PagoCita::find($id);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $validated = $request->validate([
            'cita_id'     => 'sometimes|exists:citas,id',
            'monto'       => 'sometimes|numeric|min:0',
            'metodo_pago' => 'sometimes|string|max:50',
            'estado'      => 'sometimes|string|max:50',
        ]);

        $pago->update($validated);

        return response()->json([
            'message' => 'Pago actualizado correctamente',
            'data' => $pago
        ], 200);
    }

    /**
     * ELIMINAR UN PAGO
     */
    public function destroy($id)
    {
        $pago = PagoCita::find($id);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $pago->delete();

        return response()->json(['message' => 'Pago eliminado correctamente'], 200);
    }
}
