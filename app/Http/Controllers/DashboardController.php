<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Repuesto;

class DashboardController extends Controller
{
    public function index()
    {
        // Contadores de órdenes por estado
        $stats = [
            'recibido'   => Orden::where('estado', 'recibido')->count(),
            'en_proceso' => Orden::where('estado', 'en_proceso')->count(),
            'listo'      => Orden::where('estado', 'listo')->count(),
            'entregado'  => Orden::where('estado', 'entregado')->count(),
        ];

        // Órdenes de hoy
        $ordenesHoy = Orden::whereDate('created_at', today())->count();

        // Órdenes con saldo pendiente
        $conDeuda = Orden::whereIn('estado', ['recibido', 'en_proceso', 'listo'])
            ->with('pagos')
            ->get()
            ->filter(fn($o) => $o->saldo_pendiente > 0)
            ->count();

        // Repuestos con stock bajo
        $stockBajo = Repuesto::stockBajo()->count();

        // Últimas 10 órdenes
        $ultimasOrdenes = Orden::with('cliente')->latest()->take(10)->get();

        return view('dashboard', compact(
            'stats',
            'ordenesHoy',
            'conDeuda',
            'stockBajo',
            'ultimasOrdenes'
        ));
    }
}
