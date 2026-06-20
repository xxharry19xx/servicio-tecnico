
@extends('layouts.app')

@section('title', 'Ventas')
@section('header', 'Ventas de repuestos')

@section('header-action')
    <a href="{{ route('ventas.create') }}"
       class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
        + Nueva venta
    </a>
@endsection

@section('content')

{{-- Resumen del día --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-4">
    <p class="text-sm text-gray-500">Total vendido hoy</p>
    <p class="text-3xl font-bold text-green-600">S/ {{ number_format($totalHoy, 2) }}</p>
</div>

{{-- Tabla de ventas --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">N° Venta</th>
                <th class="px-6 py-3 text-left">Productos</th>
                <th class="px-6 py-3 text-left">Método de pago</th>
                <th class="px-6 py-3 text-left">Total</th>
                <th class="px-6 py-3 text-left">Atendido por</th>
                <th class="px-6 py-3 text-left">Fecha</th>
                <th class="px-6 py-3 text-left"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($ventas as $venta)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">
                    <span class="font-mono font-medium text-blue-600">
                        {{ $venta->numero_venta }}
                    </span>
                </td>
                <td class="px-6 py-3 text-gray-600">
                    {{-- Mostramos los nombres de los repuestos vendidos --}}
                    {{ $venta->items->pluck('repuesto.nombre')->implode(', ') }}
                </td>
                <td class="px-6 py-3">
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                        {{ strtoupper($venta->metodo_pago) }}
                    </span>
                </td>
                <td class="px-6 py-3 font-medium">
                    S/ {{ number_format($venta->total, 2) }}
                </td>
                <td class="px-6 py-3 text-gray-500">
                    {{ $venta->atendido_por ?? '—' }}
                </td>
                <td class="px-6 py-3 text-gray-500 text-xs">
                    {{ $venta->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-3">
                    <a href="{{ route('ventas.show', $venta->id) }}"
                       class="text-blue-600 hover:underline text-xs">
                        Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                    No hay ventas registradas.
                    <a href="{{ route('ventas.create') }}" class="text-blue-600 hover:underline">
                        Registrar la primera
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($ventas->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $ventas->links() }}
        </div>
    @endif
</div>

@endsection