@extends('layouts.app')

@section('title', 'Catálogo de servicios')
@section('header', 'Catálogo de servicios')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Formulario para agregar servicio --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Agregar servicio</h3>

        <form method="POST" action="{{ route('servicios.store') }}">
        @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Categoría <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <input type="text" name="categoria"
                       value="{{ old('categoria') }}"
                       placeholder="Pantallas, Puertos, Software..."
                       list="categorias-list"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
                <datalist id="categorias-list">
                    @foreach($servicios->pluck('categoria')->unique()->filter() as $cat)
                        <option value="{{ $cat }}">
                    @endforeach
                </datalist>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del servicio *</label>
                <input type="text" name="nombre"
                       value="{{ old('nombre') }}"
                       placeholder="Cambio de pantalla, Puerto USB-C..."
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Precio total (S/) *</label>
                <input type="number" name="precio_sugerido" step="0.01" min="0"
                       value="{{ old('precio_sugerido', 0) }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
                <p class="text-xs text-gray-400 mt-1">Incluye mano de obra + repuesto</p>
            </div>

            {{-- Vinculación con repuesto del inventario --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Repuesto del inventario
                    <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <select name="repuesto_id"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full mb-2">
                    <option value="">Sin repuesto vinculado</option>
                    @foreach(\App\Models\Repuesto::orderBy('nombre')->get() as $repuesto)
                        <option value="{{ $repuesto->id }}"
                                {{ old('repuesto_id') == $repuesto->id ? 'selected' : '' }}>
                            {{ $repuesto->nombre }} (Stock: {{ $repuesto->stock_actual }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400">
                    Al usar este servicio se descontará automáticamente del inventario
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Cantidad de repuesto a usar
                </label>
                <input type="number" name="cantidad_repuesto" min="1"
                       value="{{ old('cantidad_repuesto', 1) }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-32">
            </div>

            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">
                Agregar
            </button>
        </form>
    </div>

    {{-- Lista de servicios --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Servicios registrados</h3>

        @php $categorias = $servicios->groupBy('categoria'); @endphp

        @forelse($categorias as $categoria => $items)
        <div class="mb-4">
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">
                {{ $categoria ?: 'Sin categoría' }}
            </p>
            @foreach($items as $servicio)
            <div class="py-2 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium {{ $servicio->activo ? '' : 'text-gray-400 line-through' }}">
                            {{ $servicio->nombre }}
                        </p>
                        <p class="text-xs text-gray-400">
                            S/ {{ number_format($servicio->precio_sugerido, 2) }}
                            @if($servicio->repuesto)
                                — 🔩 {{ $servicio->repuesto->nombre }}
                                (x{{ $servicio->cantidad_repuesto }})
                            @endif
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('servicios.toggle', $servicio->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="text-xs px-2 py-1 rounded
                                           {{ $servicio->activo
                                               ? 'bg-green-100 text-green-700'
                                               : 'bg-gray-100 text-gray-500' }}">
                                {{ $servicio->activo ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('servicios.destroy', $servicio->id) }}"
                              onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @empty
        <p class="text-sm text-gray-400">No hay servicios registrados aún.</p>
        @endforelse
    </div>

</div>

@endsection