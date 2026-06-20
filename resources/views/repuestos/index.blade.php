@extends('layouts.app')

@section('title', 'Inventario')
@section('header', 'Inventario de repuestos')

@section('header-action')
    <a href="{{ route('repuestos.create') }}"
       class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
        + Agregar repuesto
    </a>
@endsection

@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Repuesto</th>
                <th class="px-6 py-3 text-left">Compatible con</th>
                <th class="px-6 py-3 text-left">Stock</th>
                <th class="px-6 py-3 text-left">Precio compra</th>
                <th class="px-6 py-3 text-left">Precio venta</th>
                <th class="px-6 py-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($repuestos as $repuesto)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium">
                    {{ $repuesto->nombre }}
                </td>
                <td class="px-6 py-3 text-gray-500">
                    {{ $repuesto->marca_compatible }} {{ $repuesto->modelo_compatible }}
                </td>
                <td class="px-6 py-3">
                    {{-- Badge rojo si el stock está bajo --}}
                    @if($repuesto->stock_actual <= $repuesto->stock_minimo)
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                            ⚠️ {{ $repuesto->stock_actual }} (mín: {{ $repuesto->stock_minimo }})
                        </span>
                    @else
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                            {{ $repuesto->stock_actual }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-3 text-gray-600">
                    S/ {{ number_format($repuesto->precio_compra, 2) }}
                </td>
                <td class="px-6 py-3 font-medium">
                    S/ {{ number_format($repuesto->precio_venta, 2) }}
                </td>
                <td class="px-6 py-3 flex gap-3">
                    <a href="{{ route('repuestos.edit', $repuesto) }}"
                       class="text-blue-600 hover:underline text-xs">
                        Editar
                    </a>
                    <form method="POST" action="{{ route('repuestos.destroy', $repuesto) }}"
                          onsubmit="return confirm('¿Eliminar este repuesto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-red-500 hover:underline text-xs">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                    No hay repuestos registrados.
                    <a href="{{ route('repuestos.create') }}" class="text-blue-600 hover:underline">
                        Agregar el primero
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($repuestos->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $repuestos->links() }}
        </div>
    @endif
</div>

@endsection