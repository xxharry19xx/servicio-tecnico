@extends('layouts.app')

@section('title', $venta->numero_venta)
@section('header', 'Venta ' . $venta->numero_venta)

@section('header-action')
    <a href="{{ route('ventas.index') }}"
       class="bg-gray-100 text-gray-700 text-sm px-4 py-2 rounded-lg hover:bg-gray-200">
        ← Volver a ventas
    </a>
@endsection

@section('content')

<div class="max-w-2xl">
<div class="bg-white rounded-xl shadow-sm p-6">

    {{-- Encabezado del comprobante --}}
    <div class="text-center mb-6 pb-4 border-b">
        <p class="text-2xl font-bold text-gray-800">{{ $venta->numero_venta }}</p>
        <p class="text-sm text-gray-500">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Lista de productos vendidos --}}
    <table class="w-full text-sm mb-4">
        <thead class="text-gray-500 text-xs uppercase border-b">
            <tr>
                <th class="text-left py-2">Producto</th>
                <th class="text-center py-2">Cant.</th>
                <th class="text-right py-2">Precio</th>
                <th class="text-right py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($venta->items as $item)
            <tr>
                <td class="py-2">{{ $item->repuesto->nombre }}</td>
                <td class="py-2 text-center">{{ $item->cantidad }}</td>
                <td class="py-2 text-right">S/ {{ number_format($item->precio_unitario, 2) }}</td>
                <td class="py-2 text-right font-medium">S/ {{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Total --}}
    <div class="flex justify-between items-center pt-4 border-t">
        <span class="font-semibold text-gray-700">Total pagado</span>
        <span class="text-2xl font-bold text-green-600">
            S/ {{ number_format($venta->total, 2) }}
        </span>
    </div>

    {{-- Datos adicionales --}}
    <div class="mt-4 pt-4 border-t grid grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-gray-500 text-xs uppercase">Método de pago</p>
            <p class="font-medium">{{ strtoupper($venta->metodo_pago) }}</p>
        </div>
        @if($venta->atendido_por)
        <div>
            <p class="text-gray-500 text-xs uppercase">Atendido por</p>
            <p class="font-medium">{{ $venta->atendido_por }}</p>
        </div>
        @endif
    </div>

</div>
</div>

@endsection