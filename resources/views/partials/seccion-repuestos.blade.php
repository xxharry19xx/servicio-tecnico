{{-- Sección de repuestos usados, incluida en la vista show de órdenes --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-gray-800 mb-4">🔩 Repuestos usados</h3>

    {{-- Lista de repuestos ya asociados --}}
    @forelse($orden->repuestos as $repuesto)
    <div class="flex items-center justify-between py-2 border-b border-gray-100 text-sm">
        <div>
            <span class="font-medium">{{ $repuesto->nombre }}</span>
            <span class="text-gray-400 ml-1">
                x{{ $repuesto->pivot->cantidad }} — S/ {{ number_format($repuesto->pivot->precio_unitario, 2) }} c/u
            </span>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-medium">
                S/ {{ number_format($repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario, 2) }}
            </span>
            {{-- Botón para quitar el repuesto --}}
            <form method="POST" action="{{ route('ordenes.repuestos.remove', [$orden->id, $repuesto->id]) }}"
                  onsubmit="return confirm('¿Quitar este repuesto? Se devolverá al stock.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:underline text-xs">
                    Quitar
                </button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-sm text-gray-400 mb-4">No se han usado repuestos en esta orden.</p>
    @endforelse

    {{-- Formulario para agregar un repuesto del inventario --}}
    <form method="POST" action="{{ route('ordenes.repuestos.add', $orden->id) }}" class="mt-4">
        @csrf
        <p class="text-sm font-medium text-gray-700 mb-3">Agregar repuesto</p>
        <div class="flex gap-3">
            <select name="repuesto_id" required
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1">
                <option value="">Seleccionar del inventario...</option>
                @foreach(\App\Models\Repuesto::where('stock_actual', '>', 0)->orderBy('nombre')->get() as $rep)
                    <option value="{{ $rep->id }}">
                        {{ $rep->nombre }} — S/ {{ number_format($rep->precio_venta, 2) }}
                        (Stock: {{ $rep->stock_actual }})
                    </option>
                @endforeach
            </select>
            <input type="number" name="cantidad" min="1" value="1" required
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-20">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 whitespace-nowrap">
                Agregar
            </button>
        </div>
    </form>
</div>