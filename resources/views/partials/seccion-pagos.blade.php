{{-- Sección de pagos incluida en la vista show de órdenes --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-gray-800 mb-4">💳 Pagos registrados</h3>

    {{-- Lista de pagos existentes --}}
    @forelse($orden->pagos as $pago)
    <div class="flex items-center justify-between py-2 border-b border-gray-100 text-sm">
        <div>
            <span class="font-medium">S/ {{ number_format($pago->monto, 2) }}</span>
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">
                {{ strtoupper($pago->metodo_pago) }}
            </span>
            @if($pago->nota)
                <span class="text-gray-400 ml-1">— {{ $pago->nota }}</span>
            @endif
        </div>
        <span class="text-gray-400 text-xs">
            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}
        </span>
    </div>
    @empty
    <p class="text-sm text-gray-400 mb-4">No hay pagos registrados aún.</p>
    @endforelse

    {{-- Formulario para registrar nuevo pago --}}
    @if($orden->saldo_pendiente > 0)
    <form method="POST" action="{{ route('pagos.store', $orden) }}" class="mt-4">
        @csrf
        <p class="text-sm font-medium text-gray-700 mb-3">Registrar nuevo pago</p>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Monto (S/)</label>
                <input type="number" name="monto" step="0.01" min="0.01"
                       placeholder="0.00"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Método de pago</label>
                <select name="metodo_pago"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
                    <option value="efectivo">💵 Efectivo</option>
                    <option value="yape">💜 Yape</option>
                    <option value="plin">💚 Plin</option>
                    <option value="transferencia">🏦 Transferencia</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="text-xs text-gray-500 mb-1 block">Nota (opcional)</label>
            <input type="text" name="nota"
                   placeholder="ej: adelanto, pago final..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
        <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg text-sm hover:bg-green-700">
            Registrar pago
        </button>
    </form>
    @else
    <div class="mt-4 bg-green-50 text-green-700 text-sm px-4 py-3 rounded-lg">
        ✅ Orden pagada completamente.
    </div>
    @endif
</div>