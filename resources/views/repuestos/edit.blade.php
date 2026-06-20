@extends('layouts.app')

@section('title', 'Editar repuesto')
@section('header', 'Editar repuesto')

@section('content')

<div class="max-w-2xl">
<form method="POST" action="{{ route('repuestos.update', $repuesto) }}">
@csrf
@method('PUT')

<div class="bg-white rounded-xl shadow-sm p-6 space-y-4">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $repuesto->nombre) }}"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca compatible</label>
            <input type="text" name="marca_compatible"
                   value="{{ old('marca_compatible', $repuesto->marca_compatible) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo compatible</label>
            <input type="text" name="modelo_compatible"
                   value="{{ old('modelo_compatible', $repuesto->modelo_compatible) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock actual</label>
            <input type="number" name="stock_actual" min="0"
                   value="{{ old('stock_actual', $repuesto->stock_actual) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo</label>
            <input type="number" name="stock_minimo" min="0"
                   value="{{ old('stock_minimo', $repuesto->stock_minimo) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de compra (S/)</label>
            <input type="number" name="precio_compra" step="0.01" min="0"
                   value="{{ old('precio_compra', $repuesto->precio_compra) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de venta (S/)</label>
            <input type="number" name="precio_venta" step="0.01" min="0"
                   value="{{ old('precio_venta', $repuesto->precio_venta) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

</div>

<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('repuestos.index') }}"
       class="px-6 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        Guardar cambios
    </button>
</div>

</form>
</div>

@endsection