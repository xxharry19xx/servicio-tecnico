{{-- Badge de color según el estado de la orden --}}
@php
    $colores = [
        'recibido'   => 'bg-yellow-100 text-yellow-800',
        'en_proceso' => 'bg-blue-100 text-blue-800',
        'listo'      => 'bg-green-100 text-green-800',
        'entregado'  => 'bg-gray-100 text-gray-600',
    ];
    $etiquetas = [
        'recibido'   => '📥 Recibido',
        'en_proceso' => '🔧 En proceso',
        'listo'      => '✅ Listo',
        'entregado'  => '📦 Entregado',
    ];
@endphp

<span class="px-2 py-1 rounded-full text-xs font-medium {{ $colores[$estado] ?? 'bg-gray-100 text-gray-600' }}">
    {{ $etiquetas[$estado] ?? $estado }}
</span>