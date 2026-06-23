<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    // Lista todos los técnicos
    public function index()
    {
        $tecnicos = Tecnico::orderBy('nombre')->get();
        return view('tecnicos.index', compact('tecnicos'));
    }

    // Guarda un nuevo técnico
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'telefono' => 'nullable|string|max:15',
        ]);

        Tecnico::create($request->all());

        return back()->with('success', 'Técnico agregado correctamente.');
    }

    // Activa o desactiva un técnico
    public function toggleActivo(Tecnico $tecnico)
    {
        $tecnico->update(['activo' => !$tecnico->activo]);
        return back()->with('success', 'Estado del técnico actualizado.');
    }

    // Elimina un técnico
    public function destroy(Tecnico $tecnico)
    {
        $tecnico->delete();
        return back()->with('success', 'Técnico eliminado.');
    }

    public function create() {}
    public function show(Tecnico $tecnico) {}
    public function edit(Tecnico $tecnico) {}
    public function update(Request $request, Tecnico $tecnico) {}
}