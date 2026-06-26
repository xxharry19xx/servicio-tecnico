@extends('layouts.app')

@section('title', 'Editar ' . $orden->numero_orden)
@section('header', 'Editar orden ' . $orden->numero_orden)

@section('content')

<form method="POST" action="{{ route('ordenes.update', $orden) }}">
@csrf
@method('PUT')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Datos del equipo (solo lectura, no se editan) --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b">📱 Equipo</h3>
        <div class="space-y-2 text-sm text-gray-600">
            <p><span class="font-medium">Cliente:</span> {{ $orden->cliente->nombre_completo }}</p>
            <p><span class="font-medium">Equipo:</span> {{ $orden->marca }} {{ $orden->modelo }}</p>
            <p><span class="font-medium">Color:</span> {{ $orden->color }}</p>
            @if($orden->imei)
                <p><span class="font-medium">IMEI:</span> {{ $orden->imei }}</p>
            @endif
        </div>

        {{-- Accesorios y contraseña sí se pueden editar --}}
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña del equipo
            </label>
            <input type="text" name="contrasena_equipo"
                   value="{{ old('contrasena_equipo', $orden->contrasena_equipo) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Accesorios
            </label>
            <input type="text" name="accesorios"
                   value="{{ old('accesorios', $orden->accesorios) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

    {{-- Datos del servicio --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b">🔧 Servicio</h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Técnico asignado *</label>
            <select name="tecnico_asignado"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                        @error('tecnico_asignado') border-red-500 @enderror">
                <option value="">Seleccionar técnico...</option>
                @foreach(\App\Models\Tecnico::activos()->orderBy('nombre')->get() as $tecnico)
                    <option value="{{ $tecnico->nombre }}"
                            {{ old('tecnico_asignado') === $tecnico->nombre ? 'selected' : '' }}>
                        {{ $tecnico->nombre }}
                    </option>
                @endforeach
            </select>
            @error('tecnico_asignado')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Diagnóstico técnico
            </label>
            <textarea name="diagnostico_tecnico" rows="4"
                      class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">{{ old('diagnostico_tecnico', $orden->diagnostico_tecnico) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Mano de obra (S/) *
            </label>
            <input type="number" name="mano_obra" step="0.01" min="0"
                value="{{ old('mano_obra', $orden->mano_obra) }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                        @error('mano_obra') border-red-500 @enderror">
            @error('mano_obra')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descuento --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Descuento (S/)
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <input type="number" name="descuento" step="0.01" min="0"
                value="{{ old('descuento', $orden->descuento) }}"
                placeholder="0.00"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Fecha estimada de entrega
            </label>
            <input type="date" name="fecha_entrega_estimada"
                   value="{{ old('fecha_entrega_estimada', $orden->fecha_entrega_estimada?->format('Y-m-d')) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

</div>

{{-- Botones --}}
<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('ordenes.show', $orden) }}"
       class="px-6 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        Guardar cambios
    </button>
</div>

</form>

@endsection