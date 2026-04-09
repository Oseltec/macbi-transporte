@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('breadcrumb')
    <span class="text-gray-500">Dashboard</span>
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold">📊 Dashboard</h2>
    <p class="text-gray-600">Resumen de la operación</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Servicios Totales</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
            </div>
            <div class="text-4xl">📋</div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">En Proceso</p>
                <p class="text-3xl font-bold text-yellow-500">{{ $stats['en_proceso'] }}</p>
            </div>
            <div class="text-4xl">⏳</div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Finalizados Hoy</p>
                <p class="text-3xl font-bold text-green-500">{{ $stats['hoy'] }}</p>
            </div>
            <div class="text-4xl">✅</div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Choferes Activos</p>
                <p class="text-3xl font-bold text-purple-500">{{ $stats['choferes_activos'] }}</p>
            </div>
            <div class="text-4xl">👨‍✈️</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Servicios Recientes -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">📋 Servicios Recientes</h3>
            <a href="{{ route('servicios.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                Ver todos →
            </a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left py-2 px-2">Cliente</th>
                    <th class="text-left py-2 px-2">Origen</th>
                    <th class="text-left py-2 px-2">Estado</th>
                    <th class="text-left py-2 px-2">Chofer</th>
                </tr>
            </thead>
            <tbody>
                @forelse($serviciosRecientes as $servicio)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-2 font-medium">{{ $servicio->cliente }}</td>
                    <td class="py-2 px-2 text-gray-600 text-xs">{{ Str::limit($servicio->origen, 20) }}</td>
                    <td class="py-2 px-2">
                        <span class="px-2 py-1 rounded text-xs {{ $servicio->estado_clase }}">
                            {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                        </span>
                    </td>
                    <td class="py-2 px-2">
                        @if($servicio->chofer)
                            <span class="text-blue-600">{{ $servicio->chofer->name }}</span>
                        @else
                            <span class="text-gray-400 italic text-xs">Sin asignar</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500">
                        <div class="text-2xl mb-1">📋</div>
                        No hay servicios
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Estado de Servicios -->
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-4">📊 Estado de Servicios</h3>
        <div class="space-y-3">
            @forelse($estadoStats as $estado => $cantidad)
            <div class="flex items-center justify-between">
                <span class="text-sm capitalize">{{ str_replace('_', ' ', $estado) }}</span>
                <div class="flex items-center gap-2">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $estadoColores[$estado] ?? 'bg-gray-400' }}" 
                             style="width: {{ $stats['total'] > 0 ? ($cantidad / $stats['total']) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-bold bg-gray-100 px-2 py-0.5 rounded">{{ $cantidad }}</span>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">No hay datos</p>
            @endforelse
        </div>
        
        @if(empty($estadoStats))
        <div class="mt-4 text-center text-gray-500 text-sm">
            Sin servicios registrados
        </div>
        @endif
    </div>
</div>

<!-- Servicios sin asignar -->
<div class="bg-white rounded-lg shadow p-4 mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">⚠️ Servicios Pendientes de Asignar</h3>
        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
            {{ $serviciosSinAsignar->count() }}
        </span>
    </div>
    @if($serviciosSinAsignar->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-yellow-50">
                    <th class="text-left py-3 px-3">Cliente</th>
                    <th class="text-left py-3 px-3">Origen</th>
                    <th class="text-left py-3 px-3">Destino</th>
                    <th class="text-left py-3 px-3">Fecha</th>
                    <th class="text-center py-3 px-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviciosSinAsignar as $servicio)
                <tr class="border-b hover:bg-yellow-50">
                    <td class="py-3 px-3 font-medium">{{ $servicio->cliente }}</td>
                    <td class="py-3 px-3 text-gray-600 text-xs">📍 {{ Str::limit($servicio->origen, 25) }}</td>
                    <td class="py-3 px-3 text-gray-600 text-xs">📍 {{ Str::limit($servicio->destino, 25) }}</td>
                    <td class="py-3 px-3">
                        <span class="bg-gray-100 px-2 py-1 rounded text-xs">
                            {{ \Carbon\Carbon::parse($servicio->fecha_servicio)->format('d M') }}
                        </span>
                    </td>
                    <td class="py-3 px-3 text-center">
                        <a href="{{ route('servicios.edit', $servicio) }}" 
                           class="bg-blue-500 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-600 inline-flex items-center gap-1">
                            <span>👤</span> Asignar
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-4xl mb-2">✅</div>
        <p class="text-green-600 font-medium">Todos los servicios están asignados</p>
    </div>
    @endif
</div>
@endsection
