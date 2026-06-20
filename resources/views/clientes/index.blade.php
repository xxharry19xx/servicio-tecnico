@extends('layouts.app')

@section('title', 'Clientes')
@section('header', 'Clientes')

@section('content')

{{-- Buscador --}}
<form method="GET" action="{{ route('clientes.index') }}"
      class="bg-white rounded-xl shadow-sm p-4 mb-4 flex gap-3">
    <input type="text" name="buscar" value="{{ request('buscar') }}"
           placeholder="Buscar por nombre, DNI o teléfono..."
           class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1">
    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Buscar
    </button>
    @if(request('buscar'))
        <a href="{{ route('clientes.index') }}"
           class="text-sm text-gray-500 px-3 py-2 hover:text-gray-700">
            Limpiar
        </a>
    @endif
</form>

{{-- Tabla de clientes --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Cliente</th>
                <th class="px-6 py-3 text-left">DNI</th>
                <th class="px-6 py-3 text-left">Teléfono</th>
                <th class="px-6 py-3 text-left">Correo</th>
                <th class="px-6 py-3 text-left">Órdenes</th>
                <th class="px-6 py-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($clientes as $cliente)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium">
                    {{ $cliente->nombre_completo }}
                </td>
                <td class="px-6 py-3 font-mono text-gray-600">
                    {{ $cliente->dni }}
                </td>
                <td class="px-6 py-3 text-gray-600">
                    {{ $cliente->telefono }}
                </td>
                <td class="px-6 py-3 text-gray-500">
                    {{ $cliente->correo ?? '—' }}
                </td>
                <td class="px-6 py-3">
                    {{-- Cantidad de órdenes del cliente --}}
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                        {{ $cliente->ordenes_count }}
                    </span>
                </td>
                <td class="px-6 py-3">
                    <a href="{{ route('clientes.show', $cliente) }}"
                       class="text-blue-600 hover:underline text-xs">
                        Ver historial
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                    No hay clientes registrados aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($clientes->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $clientes->links() }}
        </div>
    @endif
</div>

@endsection