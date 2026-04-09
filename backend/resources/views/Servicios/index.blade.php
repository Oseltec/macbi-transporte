@extends('admin.layout')

@section('title', 'Servicios')

@section('breadcrumb')
    <span class="text-gray-500">Servicios</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">📋 Servicios</h2>
        <p class="text-gray-600">Listado de todos los servicios</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver al Dashboard
    </a>
</div>

<div class="mb-4">
    <a href="{{ route('servicios.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-flex items-center gap-2">
        <span>➕</span> Nuevo Servicio
    </a>
</div>

<!-- Stats rápidos -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-xs text-gray-500">Creados</p>
        <p class="text-2xl font-bold text-gray-600">{{ $servicios->where('estado', 'creado')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-xs text-gray-500">Asignados</p>
        <p class="text-2xl font-bold text-blue-600">{{ $servicios->where('estado', 'asignado')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-xs text-gray-500">En Proceso</p>
        <p class="text-2xl font-bold text-orange-500">{{ $servicios->whereIn('estado', ['en_origen', 'en_proceso'])->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-xs text-gray-500">Finalizados</p>
        <p class="text-2xl font-bold text-green-600">{{ $servicios->where('estado', 'finalizado')->count() }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-3 px-4 font-semibold">Cliente</th>
                    <th class="text-left py-3 px-4 font-semibold">Fecha</th>
                    <th class="text-left py-3 px-4 font-semibold">Origen</th>
                    <th class="text-left py-3 px-4 font-semibold">Destino</th>
                    <th class="text-left py-3 px-4 font-semibold">Chofer</th>
                    <th class="text-center py-3 px-4 font-semibold">Tarifa</th>
                    <th class="text-right py-3 px-4 font-semibold">Pago</th>
                    <th class="text-center py-3 px-4 font-semibold">Estado</th>
                    <th class="text-center py-3 px-4 font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicios as $servicio)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <div class="font-medium">{{ $servicio->cliente }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="text-gray-600">{{ \Carbon\Carbon::parse($servicio->fecha_servicio)->format('d M Y') }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="text-gray-600 max-w-[150px] truncate" title="{{ $servicio->origen }}">
                            📍 {{ Str::limit($servicio->origen, 20) }}
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="text-gray-600 max-w-[150px] truncate" title="{{ $servicio->destino }}">
                            📍 {{ Str::limit($servicio->destino, 20) }}
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        @if($servicio->chofer)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                    {{ substr($servicio->chofer->name, 0, 1) }}
                                </div>
                                <span class="text-gray-700">{{ $servicio->chofer->name }}</span>
                            </div>
                        @else
                            <span class="text-gray-400 italic">Sin asignar</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($servicio->tarifa_clave_snapshot)
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">
                                {{ $servicio->tarifa_clave_snapshot }}
                            </span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right font-semibold text-green-600">
                        ${{ number_format($servicio->pago_chofer_snapshot ?? 0, 2) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        @php
                            $estadoClase = match($servicio->estado) {
                                'creado' => 'bg-gray-100 text-gray-700',
                                'asignado' => 'bg-blue-100 text-blue-700',
                                'en_origen' => 'bg-yellow-100 text-yellow-700',
                                'en_proceso' => 'bg-orange-100 text-orange-700',
                                'finalizado' => 'bg-green-100 text-green-700',
                                'cancelado' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $estadoClase }}">
                            {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex gap-1 justify-center">
                            <a href="{{ route('servicios.edit', $servicio) }}" 
                               class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">
                                ✏️ Editar
                            </a>
                            <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Eliminar este servicio?')"
                                        class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-12 text-center text-gray-500">
                        <div class="text-4xl mb-2">📋</div>
                        No hay servicios registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
