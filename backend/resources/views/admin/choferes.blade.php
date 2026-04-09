@extends('admin.layout')

@section('title', 'Choferes')

@section('breadcrumb')
    <span class="text-gray-500">Choferes</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">👨‍✈️ Choferes</h2>
        <p class="text-gray-600">Gestión de choferes activos</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver al Dashboard
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left py-3 px-4">Nombre</th>
                <th class="text-left py-3 px-4">Email</th>
                <th class="text-left py-3 px-4">Teléfono</th>
                <th class="text-center py-3 px-4">Activo</th>
                <th class="text-center py-3 px-4">Servicios Activos</th>
                <th class="text-center py-3 px-4">Servicios Finalizados</th>
                <th class="text-center py-3 px-4">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($choferes as $chofer)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($chofer->name, 0, 1) }}
                        </div>
                        {{ $chofer->name }}
                    </div>
                </td>
                <td class="py-3 px-4">{{ $chofer->email }}</td>
                <td class="py-3 px-4">{{ $chofer->telefono ?? 'N/A' }}</td>
                <td class="py-3 px-4 text-center">
                    @if($chofer->activo)
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Activo</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Inactivo</span>
                    @endif
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                        {{ $chofer->servicios_activos ?? 0 }}
                    </span>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                        {{ $chofer->servicios_finalizados ?? 0 }}
                    </span>
                </td>
                <td class="py-3 px-4 text-center">
                    <a href="{{ route('admin.mapa') }}?chofer={{ $chofer->id }}" 
                       class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                        Ver en Mapa
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center text-gray-500">
                    No hay choferes registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
