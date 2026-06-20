@extends('layouts.app')

@section('title', 'Nuevo repuesto')
@section('header', 'Agregar repuesto')

@section('content')

<div class="max-w-2xl">
<form method="POST" action="{{ route('repuestos.store') }}">
@csrf

<div class="bg-white rounded-xl shadow-sm p-6 space-y-4">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Nombre del repuesto *
        </label>
        <input type="text" name="nombre"
               value="{{ old('nombre') }}"
               placeholder="Pantalla Samsung A54"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                      @error('nombre') border-red-500 @enderror">
        @error('nombre')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca compatible *</label>
            <input type="text" name="marca_compatible"
                   value="{{ old('marca_compatible') }}"
                   placeholder="Samsung"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo compatible *</label>
            <input type="text" name="modelo_compatible"
                   value="{{ old('modelo_compatible') }}"
                   placeholder="Galaxy A54"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock actual *</label>
            <input type="number" name="stock_actual" min="0"
                   value="{{ old('stock_actual', 0) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo *</label>
            <input type="number" name="stock_minimo" min="0"
                   value="{{ old('stock_minimo', 2) }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            <p class="text-xs text-gray-400 mt-1">Alerta cuando baje de este número</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de compra (S/) *</label>
            <input type="number" name="precio_compra" step="0.01" min="0"
                   value="{{ old('precio_compra') }}"
                   placeholder="0.00"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de venta (S/) *</label>
            <input type="number" name="precio_venta" step="0.01" min="0"
                   value="{{ old('precio_venta') }}"
                   placeholder="0.00"
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
        Guardar repuesto
    </button>
</div>

</form>
</div>

@endsection