<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    // Muestra el formulario o el resultado según si ya se verificó el DNI
    public function show(Request $request, string $token)
    {
        // Buscamos la orden por su token público — nunca por ID
        $orden = Orden::where('token_qr', $token)->firstOrFail();

        // Si se envió el formulario con el DNI
        if ($request->isMethod('post')) {
            $request->validate(['dni' => 'required|string|size:8']);

            // Verificamos que el DNI coincide con el cliente de esta orden
            if ($orden->cliente->dni !== $request->dni) {
                return back()->with(
                    'error',
                    'El DNI ingresado no corresponde a esta orden.'
                );
            }

            // DNI correcto — mostramos el estado del equipo
            $orden->load('pagos');
            return view('consulta.show', compact('orden', 'token'));
        }

        // GET — mostramos solo el formulario de DNI
        return view('consulta.show', compact('token'));
    }
}
