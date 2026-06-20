@extends('layouts.app')

@section('title', 'Nueva venta')
@section('header', 'Registrar venta de repuestos')

@section('content')

<div class="max-w-3xl">

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg mb-4">
        ❌ {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('ventas.store') }}" id="form-venta">
@csrf

<div class="bg-white rounded-xl shadow-sm p-6 mb-4">
    <h3 class="font-semibold text-gray-800 mb-4">📦 Productos a vender</h3>

    {{-- Contenedor donde se agregan las filas de productos --}}
    <div id="items-container" class="space-y-3">
        {{-- Primera fila --}}
        <div class="item-row flex gap-3 items-start">
            <select name="items[0][repuesto_id]" required
                    class="repuesto-select border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1"
                    onchange="actualizarPrecio(this)">
                <option value="">Seleccionar repuesto...</option>
                @foreach($repuestos as $repuesto)
                    <option value="{{ $repuesto->id }}"
                            data-precio="{{ $repuesto->precio_venta }}"
                            data-stock="{{ $repuesto->stock_actual }}">
                        {{ $repuesto->nombre }} — S/ {{ number_format($repuesto->precio_venta, 2) }}
                        (Stock: {{ $repuesto->stock_actual }})
                    </option>
                @endforeach
            </select>
            <input type="number" name="items[0][cantidad]" min="1" value="1" required
                   class="cantidad-input border border-gray-300 rounded-lg px-3 py-2 text-sm w-24"
                   onchange="calcularTotal()">
            <span class="subtotal-display text-sm font-medium w-24 text-right pt-2">S/ 0.00</span>
        </div>
    </div>

    {{-- Botón para agregar más productos --}}
    <button type="button" onclick="agregarItem()"
            class="mt-3 text-sm text-blue-600 hover:underline">
        + Agregar otro producto
    </button>

    {{-- Total general --}}
    <div class="mt-4 pt-4 border-t flex justify-between items-center">
        <span class="font-semibold text-gray-700">Total a pagar</span>
        <span id="total-general" class="text-2xl font-bold text-green-600">S/ 0.00</span>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-4">
    <h3 class="font-semibold text-gray-800 mb-4">💳 Datos de la venta</h3>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Método de pago *</label>
            <select name="metodo_pago" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
                <option value="efectivo">💵 Efectivo</option>
                <option value="yape">💜 Yape</option>
                <option value="plin">💚 Plin</option>
                <option value="transferencia">🏦 Transferencia</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Atendido por <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <input type="text" name="atendido_por"
                   placeholder="Nombre del técnico"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>
</div>

<div class="flex justify-end gap-3">
    <a href="{{ route('ventas.index') }}"
       class="px-6 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
            class="px-6 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
        Registrar venta
    </button>
</div>

</form>
</div>

{{-- Script para manejar múltiples productos dinámicamente --}}
<script>
let itemIndex = 1;

// Actualiza el precio mostrado cuando se selecciona un repuesto
function actualizarPrecio(select) {
    calcularTotal();
}

// Agrega una nueva fila de producto al formulario
function agregarItem() {
    const container = document.getElementById('items-container');
    const opciones = container.querySelector('select').innerHTML;

    const nuevaFila = document.createElement('div');
    nuevaFila.className = 'item-row flex gap-3 items-start';
    nuevaFila.innerHTML = `
        <select name="items[${itemIndex}][repuesto_id]" required
                class="repuesto-select border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1"
                onchange="actualizarPrecio(this)">
            ${opciones}
        </select>
        <input type="number" name="items[${itemIndex}][cantidad]" min="1" value="1" required
               class="cantidad-input border border-gray-300 rounded-lg px-3 py-2 text-sm w-24"
               onchange="calcularTotal()">
        <span class="subtotal-display text-sm font-medium w-24 text-right pt-2">S/ 0.00</span>
        <button type="button" onclick="this.parentElement.remove(); calcularTotal();"
                class="text-red-500 hover:text-red-700 pt-2">✕</button>
    `;
    container.appendChild(nuevaFila);
    itemIndex++;
}

// Recalcula el total general sumando todos los subtotales
function calcularTotal() {
    let total = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.repuesto-select');
        const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
        const opcionSeleccionada = select.options[select.selectedIndex];
        const precio = parseFloat(opcionSeleccionada?.dataset.precio) || 0;

        const subtotal = precio * cantidad;
        row.querySelector('.subtotal-display').textContent = 'S/ ' + subtotal.toFixed(2);

        total += subtotal;
    });

    document.getElementById('total-general').textContent = 'S/ ' + total.toFixed(2);
}
</script>

@endsection