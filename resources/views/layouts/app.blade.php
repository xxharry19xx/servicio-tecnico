<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — @yield('title', 'Panel')</title>
    {{-- Tailwind y assets compilados por Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">

        {{-- Logo del sistema --}}
        <div class="px-6 py-5 border-b border-gray-700">
            <h1 class="text-lg font-bold text-white">📱 Servicio Técnico</h1>
            <p class="text-xs text-gray-400 mt-1">Panel de gestión</p>
        </div>

        {{-- Menú de navegación --}}
        <nav class="flex-1 px-4 py-6 space-y-1">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                      {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                🏠 <span>Dashboard</span>
            </a>

            <a href="{{ route('ordenes.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                      {{ request()->routeIs('ordenes.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                📋 <span>Órdenes de trabajo</span>
            </a>

            <a href="{{ route('clientes.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                      {{ request()->routeIs('clientes.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                👥 <span>Clientes</span>
            </a>

            <a href="{{ route('tecnicos.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                    {{ request()->routeIs('tecnicos.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                👨‍🔧 <span>Técnicos</span>
            </a>

            <a href="{{ route('ventas.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                    {{ request()->routeIs('ventas.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                💰 <span>Ventas</span>
            </a>

            <a href="{{ route('repuestos.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                      {{ request()->routeIs('repuestos.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                📦 <span>Inventario</span>
            </a>

        </nav>

        {{-- Usuario logueado en la parte inferior --}}
        <div class="px-4 py-4 border-t border-gray-700">
            <p class="text-xs text-gray-400">Conectado como:</p>
            <p class="text-sm text-white font-medium">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                        class="text-xs text-red-400 hover:text-red-300">
                    Cerrar sesión
                </button>
            </form>
        </div>

    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="flex-1 flex flex-col overflow-auto">

        {{-- Barra superior --}}
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">
                @yield('header', 'Dashboard')
            </h2>
            {{-- Botón de acción rápida si la vista lo define --}}
            @yield('header-action')
        </header>

        {{-- Mensajes de éxito y error --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800
                            px-4 py-3 rounded-lg mb-4">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800
                            px-4 py-3 rounded-lg mb-4">
                    ❌ {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Contenido de cada página --}}
        <main class="flex-1 px-6 py-4">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>