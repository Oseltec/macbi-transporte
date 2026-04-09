<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Macbi Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .leaflet-container { font-family: inherit; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold text-blue-400">🚗 Macbi Admin</h1>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('servicios.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('servicios.*') ? 'bg-blue-600' : '' }}">
                    📋 Servicios
                </a>
                <a href="{{ route('admin.choferes') }}" class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.choferes') ? 'bg-blue-600' : '' }}">
                    👨‍✈️ Choferes
                </a>
                <a href="{{ route('tarifas.index') }}" class="block px-4 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('tarifas.*') ? 'bg-blue-600' : '' }}">
                    💰 Tarifas
                </a>
                <a href="/mapa-vivo" class="block px-4 py-2 rounded hover:bg-gray-800">
                    🚗 Mapa en Vivo
                </a>
                <div class="border-t border-gray-700 pt-4 mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-red-800 text-red-400">
                            🚪 Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Breadcrumb -->
            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <span class="mr-2">🏠</span>
                    <span>Dashboard</span>
                </a>
                @hasSection('breadcrumb')
                    <span class="mx-2 text-gray-400">/</span>
                    @yield('breadcrumb')
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
