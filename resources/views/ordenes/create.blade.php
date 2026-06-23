@extends('layouts.app')

@section('title', 'Nueva Orden')
@section('header', 'Nueva orden de trabajo')

@section('content')

<form method="POST" action="{{ route('ordenes.store') }}">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- COLUMNA IZQUIERDA: Datos del cliente --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b">👤 Datos del cliente</h3>

        {{-- DNI con búsqueda automática --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
            <div class="flex gap-2">
                <input type="text" name="dni" id="dni"
                       value="{{ old('dni') }}"
                       maxlength="8"
                       placeholder="12345678"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                              @error('dni') border-red-500 @enderror">
                {{-- Botón para buscar cliente existente --}}
                <button type="button" onclick="buscarCliente()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700
                               px-4 py-2 rounded-lg text-sm whitespace-nowrap">
                    Buscar
                </button>
            </div>
            @error('dni')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            {{-- Mensaje de cliente encontrado --}}
            <p id="msg-cliente" class="text-xs mt-1 hidden"></p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
            <input type="text" name="nombre_completo" id="nombre_completo"
                   value="{{ old('nombre_completo') }}"
                   placeholder="Juan Pérez García"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                          @error('nombre_completo') border-red-500 @enderror">
            @error('nombre_completo')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
            <input type="text" name="telefono" id="telefono"
                   value="{{ old('telefono') }}"
                   placeholder="987654321"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                          @error('telefono') border-red-500 @enderror">
            @error('telefono')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Correo electrónico
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <input type="email" name="correo" id="correo"
                   value="{{ old('correo') }}"
                   placeholder="cliente@email.com"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

    {{-- COLUMNA DERECHA: Datos del equipo --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b">📱 Datos del equipo</h3>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
                <select name="marca"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                               @error('marca') border-red-500 @enderror">
                    <option value="">Seleccionar...</option>
                    @foreach(['Samsung','Xiaomi','iPhone','Motorola','Huawei','LG','Otro'] as $marca)
                        <option value="{{ $marca }}" {{ old('marca') === $marca ? 'selected' : '' }}>
                            {{ $marca }}
                        </option>
                    @endforeach
                </select>
                @error('marca')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Modelo *</label>
                <input type="text" name="modelo"
                       value="{{ old('modelo') }}"
                       placeholder="Galaxy A54"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                              @error('modelo') border-red-500 @enderror">
                @error('modelo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color *</label>
                <input type="text" name="color"
                       value="{{ old('color') }}"
                       placeholder="Negro"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                              @error('color') border-red-500 @enderror">
                @error('color')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    IMEI
                    <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <input type="text" name="imei"
                       value="{{ old('imei') }}"
                       placeholder="352099001761481"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña del equipo
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <input type="text" name="contrasena_equipo"
                   value="{{ old('contrasena_equipo') }}"
                   placeholder="1234"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Accesorios recibidos
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <input type="text" name="accesorios"
                   value="{{ old('accesorios') }}"
                   placeholder="Cargador, funda, caja original"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
        </div>
    </div>

    {{-- FILA INFERIOR: Datos del servicio --}}
    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
        <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b">🔧 Datos del servicio</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Falla reportada por el cliente *
                </label>
                <textarea name="falla_cliente" rows="3"
                        placeholder="El cliente dice que..."
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                                @error('falla_cliente') border-red-500 @enderror">{{ old('falla_cliente') }}</textarea>
                @error('falla_cliente')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Diagnóstico técnico
                    <span class="text-gray-400 font-normal">(selecciona o escribe)</span>
                </label>
                {{-- Selector de servicios predefinidos --}}
                <select id="servicio-select"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full mb-2"
                        onchange="aplicarServicio(this)">
                    <option value="">Seleccionar servicio común...</option>
                    @foreach(\App\Models\Servicio::activos()->orderBy('categoria')->orderBy('nombre')->get()->groupBy('categoria') as $cat => $items)
                        <optgroup label="{{ $cat ?: 'General' }}">
                            @foreach($items as $servicio)
                                <option value="{{ $servicio->nombre }}"
                                        data-precio="{{ $servicio->precio_sugerido }}">
                                    {{ $servicio->nombre }} — S/ {{ number_format($servicio->precio_sugerido, 2) }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                {{-- Campo de texto para editar o escribir libremente --}}
                <textarea name="diagnostico_tecnico" id="diagnostico_tecnico" rows="2"
                        placeholder="Al revisar el equipo se encontró..."
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">{{ old('diagnostico_tecnico') }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mano de obra (S/) *</label>
                <input type="number" name="mano_obra" step="0.01" min="0"
                    value="{{ old('mano_obra') }}"
                    placeholder="0.00"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full
                            @error('mano_obra') border-red-500 @enderror">
                @error('mano_obra')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Los repuestos se agregan después, desde el detalle de la orden.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha estimada de entrega
                    <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <input type="date" name="fecha_entrega_estimada"
                       value="{{ old('fecha_entrega_estimada') }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full">
            </div>
        </div>
    </div>

</div>

{{-- BOTONES --}}
<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('ordenes.index') }}"
       class="px-6 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
        Guardar orden
    </button>
</div>

<script>
// Cuando se selecciona un servicio, autocompleta el diagnóstico y el precio
function aplicarServicio(select) {
    const opcion = select.options[select.selectedIndex];
    if (!opcion.value) return;

    // Ponemos el nombre del servicio en el diagnóstico
    document.getElementById('diagnostico_tecnico').value = opcion.value;

    // Autocompletamos la mano de obra con el precio sugerido
    const precio = opcion.dataset.precio;
    if (precio && precio > 0) {
        document.querySelector('[name="mano_obra"]').value = precio;
    }
}
</script>

</form>

{{-- Script para autocompletar datos del cliente por DNI --}}
<script>
async function buscarCliente() {
    const dni = document.getElementById('dni').value.trim();
    const msg = document.getElementById('msg-cliente');

    if (dni.length !== 8) {
        msg.textContent = 'Ingresa 8 dígitos.';
        msg.className = 'text-xs mt-1 text-red-500';
        msg.classList.remove('hidden');
        return;
    }

    // Llamamos al endpoint que busca el cliente por DNI
    const res = await fetch(`/clientes/buscar?dni=${dni}`);
    const data = await res.json();

    if (data.encontrado) {
        // Autocompletamos los campos con los datos del cliente
        document.getElementById('nombre_completo').value = data.cliente.nombre_completo;
        document.getElementById('telefono').value        = data.cliente.telefono;
        document.getElementById('correo').value          = data.cliente.correo ?? '';
        msg.textContent = '✅ Cliente encontrado y datos cargados.';
        msg.className = 'text-xs mt-1 text-green-600';
    } else {
        msg.textContent = 'Cliente nuevo — completa los datos manualmente.';
        msg.className = 'text-xs mt-1 text-gray-500';
    }

    msg.classList.remove('hidden');
}
</script>

@endsection