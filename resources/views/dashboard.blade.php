@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('header-action')
    {{-- Botón rápido para nueva orden --}}
    <a href="{{ route('ordenes.create') }}"
       class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
        + Nueva orden
    </a>
@endsection

@section('content')

{{-- TARJETAS DE ESTADÍSTICAS --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Órdenes recibidas --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-400">
        <p class="text-sm text-gray-500">Recibidos</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['recibido'] }}</p>
    </div>

    {{-- En proceso --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-400">
        <p class="text-sm text-gray-500">En proceso</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['en_proceso'] }}</p>
    </div>

    {{-- Listos para entregar --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-400">
        <p class="text-sm text-gray-500">Listos</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['listo'] }}</p>
    </div>

    {{-- Entregados --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-gray-400">
        <p class="text-sm text-gray-500">Entregados</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['entregado'] }}</p>
    </div>

</div>

{{-- SEGUNDA FILA DE STATS --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-gray-500">Órdenes ingresadas hoy</p>
        <p class="text-3xl font-bold text-blue-600">{{ $ordenesHoy }}</p>
    </div>

    {{-- Alerta de deudas pendientes --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-gray-500">Con saldo pendiente</p>
        <p class="text-3xl font-bold {{ $conDeuda > 0 ? 'text-red-500' : 'text-green-600' }}">
            {{ $conDeuda }}
        </p>
    </div>

    {{-- Alerta de stock bajo --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-gray-500">Repuestos con stock bajo</p>
        <p class="text-3xl font-bold {{ $stockBajo > 0 ? 'text-orange-500' : 'text-green-600' }}">
            {{ $stockBajo }}
        </p>
    </div>

</div>

{{-- ÚLTIMAS ÓRDENES --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Últimas órdenes</h3>
        <a href="{{ route('ordenes.index') }}" class="text-sm text-blue-600 hover:underline">
            Ver todas
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">N° Orden</th>
                <th class="px-6 py-3 text-left">Cliente</th>
                <th class="px-6 py-3 text-left">Equipo</th>
                <th class="px-6 py-3 text-left">Estado</th>
                <th class="px-6 py-3 text-left">Fecha</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($ultimasOrdenes as $orden)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">
                    <a href="{{ route('ordenes.show', $orden) }}"
                       class="font-mono text-blue-600 hover:underline">
                        {{ $orden->numero_orden }}
                    </a>
                </td>
                <td class="px-6 py-3">{{ $orden->cliente->nombre_completo }}</td>
                <td class="px-6 py-3">{{ $orden->marca }} {{ $orden->modelo }}</td>
                <td class="px-6 py-3">
                    @include('partials.badge-estado', ['estado' => $orden->estado])
                </td>
                <td class="px-6 py-3 text-gray-500">
                    {{ $orden->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                    No hay órdenes registradas aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection