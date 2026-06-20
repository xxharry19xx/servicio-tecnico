<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Repuesto;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    // Lista todas las ventas con totales del día
    public function index()
    {
        $ventas = Venta::with('items.repuesto')->latest()->paginate(20);

        // Total vendido hoy — útil para el resumen de caja
        $totalHoy = Venta::whereDate('created_at', today())->sum('total');

        return view('ventas.index', compact('ventas', 'totalHoy'));
    }

    // Formulario para registrar una nueva venta
    public function create()
    {
        // Solo mostramos repuestos con stock disponible
        $repuestos = Repuesto::where('stock_actual', '>', 0)->orderBy('nombre')->get();

        return view('ventas.create', compact('repuestos'));
    }

    // Guarda la venta y descuenta el stock
    public function store(Request $request)
    {
        $request->validate([
            'metodo_pago'         => 'required|in:efectivo,yape,plin,transferencia',
            'atendido_por'        => 'nullable|string|max:100',
            'items'               => 'required|array|min:1',
            'items.*.repuesto_id' => 'required|exists:repuestos,id',
            'items.*.cantidad'    => 'required|integer|min:1',
        ]);

        $total = 0;
        $itemsValidados = [];

        // Verificamos stock disponible antes de crear nada
        foreach ($request->items as $item) {
            $repuesto = Repuesto::find($item['repuesto_id']);

            if ($repuesto->stock_actual < $item['cantidad']) {
                return back()->with(
                    'error',
                    "Stock insuficiente de {$repuesto->nombre}. Disponible: {$repuesto->stock_actual}"
                );
            }

            $subtotal = $repuesto->precio_venta * $item['cantidad'];
            $total += $subtotal;

            $itemsValidados[] = [
                'repuesto'        => $repuesto,
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $repuesto->precio_venta,
                'subtotal'        => $subtotal,
            ];
        }

        // Creamos la venta con su número automático
        $venta = Venta::create([
            'numero_venta'  => Venta::generarNumeroVenta(),
            'total'         => $total,
            'metodo_pago'   => $request->metodo_pago,
            'atendido_por'  => $request->atendido_por,
        ]);

        // Creamos cada item y descontamos el stock
        foreach ($itemsValidados as $item) {
            VentaItem::create([
                'venta_id'        => $venta->id,
                'repuesto_id'     => $item['repuesto']->id,
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal'        => $item['subtotal'],
            ]);

            // Descontamos del inventario
            $item['repuesto']->decrement('stock_actual', $item['cantidad']);
        }

        return redirect()->route('ventas.show', $venta)
            ->with('success', "Venta {$venta->numero_venta} registrada correctamente.");
    }

    // Muestra el detalle/comprobante de una venta
    public function show(Venta $venta)
    {
        $venta->load('items.repuesto');
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta) {}
    public function update(Request $request, Venta $venta) {}

    // Las ventas no se eliminan para mantener el historial de caja
    public function destroy(Venta $venta) {}
}
