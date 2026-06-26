<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        // Agrupamos por categoría para mostrarlos ordenados
        $servicios = Servicio::orderBy('categoria')->orderBy('nombre')->get();
        return view('servicios.index', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:150',
            'categoria'       => 'nullable|string|max:50',
            'precio_sugerido' => 'required|numeric|min:0',
            'repuesto_id'       => 'nullable|exists:repuestos,id',
            'cantidad_repuesto' => 'required|integer|min:1',
        ]);

        Servicio::create($request->all());
        return back()->with('success', 'Servicio agregado correctamente.');
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre'          => 'required|string|max:150',
            'categoria'       => 'nullable|string|max:50',
            'precio_sugerido' => 'required|numeric|min:0',
            'repuesto_id'       => 'nullable|exists:repuestos,id',
            'cantidad_repuesto' => 'required|integer|min:1',
        ]);

        $servicio->update($request->all());
        return back()->with('success', 'Servicio actualizado.');
    }

    public function toggleActivo(Servicio $servicio)
    {
        $servicio->update(['activo' => !$servicio->activo]);
        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return back()->with('success', 'Servicio eliminado.');
    }

    // Devuelve los servicios en JSON para el autocompletado del formulario de orden
    public function listar()
    {
        $servicios = Servicio::activos()
        ->with('repuesto')
        ->orderBy('categoria')
        ->orderBy('nombre')
        ->get();
        return response()->json($servicios);
    }

    public function create() {}
    public function show(Servicio $servicio) {}
    public function edit(Servicio $servicio) {}
}