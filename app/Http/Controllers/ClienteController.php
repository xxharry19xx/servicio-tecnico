<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Lista todos los clientes con búsqueda opcional
    public function index(Request $request)
    {
        $query = Cliente::withCount('ordenes')->latest();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('nombre_completo', 'like', "%{$buscar}%")
                ->orWhere('dni', 'like', "%{$buscar}%")
                ->orWhere('telefono', 'like', "%{$buscar}%");
        }

        $clientes = $query->paginate(20);

        return view('clientes.index', compact('clientes'));
    }

    // Muestra el historial completo de un cliente
    public function show(Cliente $cliente)
    {
        // Cargamos todas sus órdenes con datos de pagos
        $cliente->load(['ordenes' => function ($q) {
            $q->with('pagos')->latest();
        }]);

        return view('clientes.show', compact('cliente'));
    }

    // Busca un cliente por DNI — usado para autocompletar en nueva orden
    public function buscar(Request $request)
    {
        $cliente = Cliente::where('dni', $request->dni)->first();

        if ($cliente) {
            return response()->json([
                'encontrado' => true,
                'cliente'    => $cliente,
            ]);
        }

        return response()->json(['encontrado' => false]);
    }

    // Los demás métodos del resource no los necesitamos
    // ya que clientes se crean desde las órdenes
    public function create() {}
    public function store(Request $request) {}
    public function edit(Cliente $cliente) {}
    public function update(Request $request, Cliente $cliente) {}
    public function destroy(Cliente $cliente) {}
}
