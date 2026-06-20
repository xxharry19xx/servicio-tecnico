<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use Illuminate\Http\Request;

class RepuestoController extends Controller
{
    // Lista todos los repuestos — primero los de stock bajo
    public function index()
    {
        $repuestos = Repuesto::orderByRaw('stock_actual <= stock_minimo DESC')
            ->orderBy('nombre')
            ->paginate(20);

        return view('repuestos.index', compact('repuestos'));
    }

    public function create()
    {
        return view('repuestos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:150',
            'marca_compatible' => 'required|string|max:50',
            'modelo_compatible' => 'required|string|max:100',
            'stock_actual'     => 'required|integer|min:0',
            'stock_minimo'     => 'required|integer|min:0',
            'precio_compra'    => 'required|numeric|min:0',
            'precio_venta'     => 'required|numeric|min:0',
        ]);

        Repuesto::create($request->all());

        return redirect()->route('repuestos.index')
            ->with('success', 'Repuesto agregado correctamente.');
    }

    public function edit(Repuesto $repuesto)
    {
        return view('repuestos.edit', compact('repuesto'));
    }

    public function update(Request $request, Repuesto $repuesto)
    {
        $request->validate([
            'nombre'           => 'required|string|max:150',
            'marca_compatible' => 'required|string|max:50',
            'modelo_compatible' => 'required|string|max:100',
            'stock_actual'     => 'required|integer|min:0',
            'stock_minimo'     => 'required|integer|min:0',
            'precio_compra'    => 'required|numeric|min:0',
            'precio_venta'     => 'required|numeric|min:0',
        ]);

        $repuesto->update($request->all());

        return redirect()->route('repuestos.index')
            ->with('success', 'Repuesto actualizado correctamente.');
    }

    public function destroy(Repuesto $repuesto)
    {
        $repuesto->delete();

        return redirect()->route('repuestos.index')
            ->with('success', 'Repuesto eliminado.');
    }

    // show no se usa en este módulo
    public function show(Repuesto $repuesto) {}
}
