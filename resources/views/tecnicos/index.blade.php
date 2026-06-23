@extends('layouts.app')

@section('title', 'Técnicos')
@section('header', 'Técnicos')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Formulario para agregar técnico --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Agregar técnico</h3>

        <form method="POST" action="{{ route('tecnicos.store') }}">
        @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="nombre"
                       value="{{ old('nombre') }}"
                       placeholder="Nombre del técnico"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Teléfono <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <input type="text" name="telefono"
                       value="{{ old('telefono') }}"
                       placeholder="987654321"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            </div>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">
                Agregar
            </button>
        </form>
    </div>

    {{-- Lista de técnicos --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Técnicos registrados</h3>

        <div class="space-y-2">
            @forelse($tecnicos as $tecnico)
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <div>
                    <p class="font-medium text-sm {{ $tecnico->activo ? '' : 'text-gray-400 line-through' }}">
                        {{ $tecnico->nombre }}
                    </p>
                    @if($tecnico->telefono)
                        <p class="text-xs text-gray-400">{{ $tecnico->telefono }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    {{-- Activar/desactivar --}}
                    <form method="POST" action="{{ route('tecnicos.toggle', $tecnico->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="text-xs px-2 py-1 rounded
                                       {{ $tecnico->activo
                                           ? 'bg-green-100 text-green-700'
                                           : 'bg-gray-100 text-gray-500' }}">
                            {{ $tecnico->activo ? 'Activo' : 'Inactivo' }}
                        </button>
                    </form>
                    {{-- Eliminar --}}
                    <form method="POST" action="{{ route('tecnicos.destroy', $tecnico->id) }}"
                          onsubmit="return confirm('¿Eliminar este técnico?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400">No hay técnicos registrados aún.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection