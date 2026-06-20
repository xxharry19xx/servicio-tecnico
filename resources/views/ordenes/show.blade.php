@extends('layouts.app')

@section('title', $orden->numero_orden)
@section('header', 'Orden ' . $orden->numero_orden)

@section('header-action')
    <a href="{{ route('ordenes.edit', $orden->id) }}"
       class="bg-gray-100 text-gray-700 text-sm px-4 py-2 rounded-lg hover:bg-gray-200">
        ✏️ Editar
    </a>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- COLUMNA PRINCIPAL --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Datos del cliente y equipo --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-3">👤 Cliente</h3>
                    <p class="font-medium">{{ $orden->cliente->nombre_completo }}</p>
                    <p class="text-sm text-gray-500">DNI: {{ $orden->cliente->dni }}</p>
                    <p class="text-sm text-gray-500">{{ $orden->cliente->telefono }}</p>
                    @if($orden->cliente->correo)
                        <p class="text-sm text-gray-500">{{ $orden->cliente->correo }}</p>
                    @endif
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-3">📱 Equipo</h3>
                    <p class="font-medium">{{ $orden->marca }} {{ $orden->modelo }}</p>
                    <p class="text-sm text-gray-500">Color: {{ $orden->color }}</p>
                    @if($orden->imei)
                        <p class="text-sm text-gray-500">IMEI: {{ $orden->imei }}</p>
                    @endif
                    @if($orden->contrasena_equipo)
                        <p class="text-sm text-gray-500">Contraseña: {{ $orden->contrasena_equipo }}</p>
                    @endif
                    @if($orden->accesorios)
                        <p class="text-sm text-gray-500">Accesorios: {{ $orden->accesorios }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Falla y diagnóstico --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-3">🔧 Servicio</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Falla reportada</p>
                    <p class="text-sm">{{ $orden->falla_cliente }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Diagnóstico técnico</p>
                    <p class="text-sm">{{ $orden->diagnostico_tecnico ?? '—' }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Técnico</p>
                    <p class="text-sm">{{ $orden->tecnico_asignado }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Entrega estimada</p>
                    <p class="text-sm">
                        {{ $orden->fecha_entrega_estimada
                            ? $orden->fecha_entrega_estimada->format('d/m/Y')
                            : '—' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Sección de repuestos --}}
        @include('partials.seccion-repuestos', ['orden' => $orden])

        {{-- Sección de pagos --}}
        @include('partials.seccion-pagos', ['orden' => $orden])

        {{-- Historial de estados --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">📜 Historial de estados</h3>
            <div class="space-y-2">
                @foreach($orden->estadoLogs->sortByDesc('created_at') as $log)
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-gray-400 text-xs w-36">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                    </span>
                    @if($log->estado_anterior)
                        @include('partials.badge-estado', ['estado' => $log->estado_anterior])
                        <span class="text-gray-400">→</span>
                    @endif
                    @include('partials.badge-estado', ['estado' => $log->estado_nuevo])
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- COLUMNA LATERAL --}}
    <div class="space-y-6">

        {{-- Resumen financiero --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">💰 Resumen de pago</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Mano de obra</span>
                    <span class="font-medium">S/ {{ number_format($orden->mano_obra, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Repuestos</span>
                    <span class="font-medium">S/ {{ number_format($orden->costo_repuestos, 2) }}</span>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <span class="text-gray-700 font-medium">Total del servicio</span>
                    <span class="font-semibold">S/ {{ number_format($orden->precio_total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total pagado</span>
                    <span class="font-medium text-green-600">
                        S/ {{ number_format($orden->total_pagado, 2) }}
                    </span>
                </div>
                <div class="flex justify-between border-t pt-2 font-semibold">
                    <span>Saldo pendiente</span>
                    <span class="{{ $orden->saldo_pendiente > 0 ? 'text-red-500' : 'text-green-600' }}">
                        S/ {{ number_format($orden->saldo_pendiente, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Cambiar estado --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">⚙️ Cambiar estado</h3>
            <p class="text-sm text-gray-500 mb-3">
                Estado actual:
                @include('partials.badge-estado', ['estado' => $orden->estado])
            </p>
            <form method="POST" action="{{ route('ordenes.estado', $orden->id) }}">
                @csrf
                @method('PATCH')
                <select name="estado"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full mb-3">
                    <option value="recibido"   {{ $orden->estado === 'recibido'   ? 'selected' : '' }}>📥 Recibido</option>
                    <option value="en_proceso" {{ $orden->estado === 'en_proceso' ? 'selected' : '' }}>🔧 En proceso</option>
                    <option value="listo"      {{ $orden->estado === 'listo'      ? 'selected' : '' }}>✅ Listo</option>
                    <option value="entregado"  {{ $orden->estado === 'entregado'  ? 'selected' : '' }}>📦 Entregado</option>
                </select>
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm hover:bg-blue-700">
                    Actualizar estado
                </button>
            </form>
            @if($orden->saldo_pendiente > 0)
                <p class="text-xs text-red-500 mt-2">
                    ⚠️ No se puede entregar con saldo pendiente.
                </p>
            @endif
        </div>

        {{-- QR de la orden --}}
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            <h3 class="font-semibold text-gray-800 mb-3">📲 QR para el cliente</h3>
            <div class="flex justify-center mb-3">
                {!! QrCode::size(160)->generate(route('consulta.show', $orden->token_qr)) !!}
            </div>
            <p class="text-xs text-gray-500">
                El cliente escanea este código para consultar su equipo.
            </p>
        </div>

    </div>

</div>

@endsection