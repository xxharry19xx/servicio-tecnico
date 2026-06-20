<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta tu equipo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

<div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8">

    <div class="text-center mb-6">
        <p class="text-4xl mb-2">📱</p>
        <h1 class="text-xl font-bold text-gray-800">Consulta tu reparación</h1>
        <p class="text-sm text-gray-500 mt-1">Ingresa tu DNI para ver el estado de tu equipo</p>
    </div>

    {{-- Si aún no verificó el DNI mostramos el formulario --}}
    @if(!isset($orden))

        <form method="POST" action="{{ route('consulta.show', $token) }}">
        @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tu DNI</label>
                <input type="text" name="dni" maxlength="8"
                       placeholder="12345678"
                       autofocus
                       class="border border-gray-300 rounded-xl px-4 py-3 text-center
                              text-lg tracking-widest w-full
                              @error('dni') border-red-500 @enderror">
                @error('dni')
                    <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            @if(session('error'))
                <div class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg mb-4 text-center">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-medium hover:bg-blue-700">
                Consultar
            </button>
        </form>

    @else
    {{-- Si el DNI fue verificado mostramos el estado --}}

        {{-- Estado del equipo con ícono grande --}}
        <div class="text-center mb-6 py-4 bg-gray-50 rounded-xl">
            @php
                $iconos = [
                    'recibido'   => ['📥', 'Tu equipo fue recibido',    'text-yellow-600'],
                    'en_proceso' => ['🔧', 'Tu equipo está en revisión', 'text-blue-600'],
                    'listo'      => ['✅', '¡Tu equipo está listo!',     'text-green-600'],
                    'entregado'  => ['📦', 'Equipo entregado',           'text-gray-600'],
                ];
                [$icono, $mensaje, $color] = $iconos[$orden->estado];
            @endphp
            <p class="text-5xl mb-2">{{ $icono }}</p>
            <p class="font-semibold text-lg {{ $color }}">{{ $mensaje }}</p>
        </div>

        {{-- Detalles de la orden --}}
        <div class="space-y-3 text-sm mb-6">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">N° de orden</span>
                <span class="font-mono font-medium">{{ $orden->numero_orden }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Equipo</span>
                <span class="font-medium">{{ $orden->marca }} {{ $orden->modelo }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Técnico</span>
                <span class="font-medium">{{ $orden->tecnico_asignado }}</span>
            </div>
            @if($orden->fecha_entrega_estimada)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Entrega estimada</span>
                <span class="font-medium">
                    {{ $orden->fecha_entrega_estimada->format('d/m/Y') }}
                </span>
            </div>
            @endif
            <div class="flex justify-between py-2">
                <span class="text-gray-500">Saldo pendiente</span>
                <span class="font-semibold {{ $orden->saldo_pendiente > 0 ? 'text-red-500' : 'text-green-600' }}">
                    S/ {{ number_format($orden->saldo_pendiente, 2) }}
                </span>
            </div>
        </div>

        <a href="{{ route('consulta.show', $token) }}"
           class="block text-center text-sm text-blue-600 hover:underline">
            ← Volver
        </a>

    @endif

</div>

</body>
</html>