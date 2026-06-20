<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Pago;
use App\Http\Requests\StorePagoRequest;

class PagoController extends Controller
{
    // Registra un nuevo pago para una orden específica
    public function store(StorePagoRequest $request, Orden $orden)
    {
        // Verificamos que no se pague más de lo que se debe
        if ($request->monto > $orden->saldo_pendiente) {
            return back()->with(
                'error',
                "El monto no puede ser mayor al saldo pendiente (S/ {$orden->saldo_pendiente})"
            );
        }

        Pago::create([
            'orden_id'    => $orden->id,
            'monto'       => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'nota'        => $request->nota,
            'fecha_pago'  => now(),
        ]);

        return back()->with('success', 'Pago registrado correctamente.');
    }
}
