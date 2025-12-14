<?php

namespace App\Http\Controllers;

use App\Models\PagoCita;
use Illuminate\Http\Request;

class PagoCitaController extends Controller
{
    // =========================
    // CRUD NORMAL
    // =========================

    public function index()
    {
        return response()->json(
            PagoCita::with('cita')->get(),
            200
        );
    }

    public function show($id)
    {
        $pago = PagoCita::with('cita')->find($id);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        return response()->json($pago, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id'     => 'required|exists:citas,id',
            'monto'       => 'required|numeric|min:0',
            'fecha_pago'  => 'required|date',
            'metodo_pago' => 'nullable|string|max:50',
            'estado'      => 'required|string|max:50',
        ]);

        $pago = PagoCita::create($validated);

        return response()->json([
            'message' => 'Pago creado correctamente',
            'data' => $pago
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pago = PagoCita::find($id);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $validated = $request->validate([
            'cita_id'     => 'sometimes|exists:citas,id',
            'monto'       => 'sometimes|numeric|min:0',
            'fecha_pago'  => 'sometimes|date',
            'metodo_pago' => 'sometimes|nullable|string|max:50',
            'estado'      => 'sometimes|string|max:50',
        ]);

        $pago->update($validated);

        return response()->json([
            'message' => 'Pago actualizado correctamente',
            'data' => $pago
        ], 200);
    }

    public function destroy($id)
    {
        $pago = PagoCita::find($id);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $pago->delete();

        return response()->json(['message' => 'Pago eliminado correctamente'], 200);
    }

    // =========================
    // JSON-LD (WEB SEMÁNTICA)
    // =========================

    public function indexJsonLd()
    {
        $pagos = PagoCita::with('cita')->get();

        $jsonldData = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Lista de Pagos de Citas',
            'description' => 'Listado de pagos registrados en el sistema',
            'numberOfItems' => $pagos->count(),
            'itemListElement' => $pagos->values()->map(function ($pago, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => $this->pagoToJsonLd($pago),
                ];
            })->toArray(),
        ];

        return response()->json($jsonldData, 200, [], JSON_PRETTY_PRINT);
    }

    public function showJsonLd($id)
    {
        $pago = PagoCita::with('cita')->find($id);

        if (!$pago) {
            return response()->json([
                '@context' => 'https://schema.org',
                '@type' => 'Thing',
                'name' => 'Pago no encontrado',
            ], 404, [], JSON_PRETTY_PRINT);
        }

        return response()->json(
            $this->pagoToJsonLd($pago),
            200,
            [],
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Conversión de PagoCita a JSON-LD
     */
    private function pagoToJsonLd(PagoCita $pago): array
    {
        return [
            '@type' => 'PaymentChargeSpecification',
            '@id' => url("/api/pagos-citas/{$pago->id}"),
            'identifier' => (string) $pago->id,
            'price' => (float) $pago->monto,
            'priceCurrency' => 'USD',
            'paymentMethod' => $pago->metodo_pago,
            'status' => $pago->estado,

            //  CLAVE: siempre presente y en formato string
            'validFrom' => optional($pago->fecha_pago)->toDateString(),

            'subjectOf' => $pago->cita ? [
                '@type' => 'Event',
                '@id' => url("/api/citas/{$pago->cita->id}"),
                'name' => 'Cita médica',
            ] : null,
        ];
    }
}
