@extends('layouts.app')

@section('title', $cliente->nombre_completo)
@section('header', 'Historial de ' . $cliente->nombre_completo)

@section('content')

{{-- Datos del cliente --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-gray-500 text-xs uppercase font-medium mb-1">DNI</p>
            <p class="font-mono font-medium">{{ $cliente->dni }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-xs uppercase font-medium mb-1">Teléfono</p>
            <p>{{ $cliente->telefono }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-xs uppercase font-medium mb-1">Correo</p>
            <p>{{ $cliente->correo ?? '—' }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-xs uppercase font-medium mb-1">Cliente desde</p>
            <p>{{ $cliente->created_at->format('d/m/Y') }}</p>
        </div>
    </div>
</div>

{{-- Historial de órdenes --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">
            Órdenes de trabajo
            <span class="text-gray-400 font-normal text-sm ml-1">
                ({{ $cliente->ordenes->count() }})
            </span>
        </h3>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">N° Orden</th>
                <th class="px-6 py-3 text-left">Equipo</th>
                <th class="px-6 py-3 text-left">Falla</th>
                <th class="px-6 py-3 text-left">Estado</th>
                <th class="px-6 py-3 text-left">Total</th>
                <th class="px-6 py-3 text-left">Fecha</th>
                <th class="px-6 py-3 text-left"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($cliente->ordenes as $orden)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">
                    <span class="font-mono font-medium text-blue-600">
                        {{ $orden->numero_orden }}
                    </span>
                </td>
                <td class="px-6 py-3">
                    {{ $orden->marca }} {{ $orden->modelo }}
                </td>
                <td class="px-6 py-3 text-gray-500 max-w-xs truncate">
                    {{ $orden->falla_cliente }}
                </td>
                <td class="px-6 py-3">
                    @include('partials.badge-estado', ['estado' => $orden->estado])
                </td>
                <td class="px-6 py-3">
                    <p>S/ {{ number_format($orden->precio_total, 2) }}</p>
                    @if($orden->saldo_pendiente > 0)
                        <p class="text-red-500 text-xs">
                            Debe: S/ {{ number_format($orden->saldo_pendiente, 2) }}
                        </p>
                    @endif
                </td>
                <td class="px-6 py-3 text-gray-500 text-xs">
                    {{ $orden->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-3">
                    <a href="{{ route('ordenes.show', $orden) }}"
                       class="text-blue-600 hover:underline text-xs">
                        Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                    Este cliente no tiene órdenes registradas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection