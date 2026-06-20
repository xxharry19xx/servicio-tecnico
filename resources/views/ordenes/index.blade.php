@extends('layouts.app')

@section('title', 'Órdenes de trabajo')
@section('header', 'Órdenes de trabajo')

@section('header-action')
    <a href="{{ route('ordenes.create') }}"
       class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
        + Nueva orden
    </a>
@endsection

@section('content')

{{-- FILTROS DE BÚSQUEDA --}}
<form method="GET" action="{{ route('ordenes.index') }}"
      class="bg-white rounded-xl shadow-sm p-4 mb-4 flex gap-3 flex-wrap">

    <input type="text" name="buscar" value="{{ request('buscar') }}"
           placeholder="Buscar por N° orden o cliente..."
           class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1 min-w-48">

    {{-- Filtro por estado --}}
    <select name="estado"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">Todos los estados</option>
        <option value="recibido"   {{ request('estado') === 'recibido'   ? 'selected' : '' }}>Recibido</option>
        <option value="en_proceso" {{ request('estado') === 'en_proceso' ? 'selected' : '' }}>En proceso</option>
        <option value="listo"      {{ request('estado') === 'listo'      ? 'selected' : '' }}>Listo</option>
        <option value="entregado"  {{ request('estado') === 'entregado'  ? 'selected' : '' }}>Entregado</option>
    </select>

    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Buscar
    </button>

    @if(request('buscar') || request('estado'))
        <a href="{{ route('ordenes.index') }}"
           class="text-sm text-gray-500 px-3 py-2 hover:text-gray-700">
            Limpiar
        </a>
    @endif

</form>

{{-- TABLA DE ÓRDENES --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">N° Orden</th>
                <th class="px-6 py-3 text-left">Cliente</th>
                <th class="px-6 py-3 text-left">Equipo</th>
                <th class="px-6 py-3 text-left">Técnico</th>
                <th class="px-6 py-3 text-left">Estado</th>
                <th class="px-6 py-3 text-left">Pago</th>
                <th class="px-6 py-3 text-left">Fecha</th>
                <th class="px-6 py-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($ordenes as $orden)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">
                    <a href="{{ route('ordenes.show', $orden) }}"
                       class="font-mono text-blue-600 hover:underline font-medium">
                        {{ $orden->numero_orden }}
                    </a>
                </td>
                <td class="px-6 py-3">
                    <p class="font-medium">{{ $orden->cliente->nombre_completo }}</p>
                    <p class="text-gray-400 text-xs">{{ $orden->cliente->telefono }}</p>
                </td>
                <td class="px-6 py-3">
                    {{ $orden->marca }} {{ $orden->modelo }}
                </td>
                <td class="px-6 py-3 text-gray-600">{{ $orden->tecnico_asignado }}</td>
                <td class="px-6 py-3">
                    @include('partials.badge-estado', ['estado' => $orden->estado])
                </td>
                <td class="px-6 py-3">
                    {{-- Badge de estado de pago --}}
                    @if($orden->estado_pago === 'pagado')
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Pagado</span>
                    @elseif($orden->estado_pago === 'parcial')
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Parcial</span>
                    @else
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Pendiente</span>
                    @endif
                </td>
                <td class="px-6 py-3 text-gray-500 text-xs">
                    {{ $orden->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-3">
                    <a href="{{ route('ordenes.show', $orden) }}"
                       class="text-blue-600 hover:underline text-xs mr-2">Ver</a>
                    <a href="{{ route('ordenes.edit', $orden->id) }}"
                       class="text-gray-600 hover:underline text-xs">Editar</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                    No hay órdenes registradas.
                    <a href="{{ route('ordenes.create') }}" class="text-blue-600 hover:underline">
                        Crear la primera
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginación --}}
    @if($ordenes->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $ordenes->links() }}
        </div>
    @endif
</div>

@endsection