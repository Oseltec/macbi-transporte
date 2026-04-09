@extends('admin.layout')

@section('title', 'Tarifas')

@section('breadcrumb')
    <span class="text-gray-500">Tarifas</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">💰 Tarifas</h2>
        <p class="text-gray-600">Gestión de tarifas del servicio</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver al Dashboard
    </a>
</div>

<div class="mb-4">
    <a href="{{ route('tarifas.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ➕ Nueva Tarifa
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left py-3 px-4">Clave</th>
                <th class="text-left py-3 px-4">Descripción</th>
                <th class="text-right py-3 px-4">Tarifa Cliente</th>
                <th class="text-right py-3 px-4">Pago Chofer</th>
                <th class="text-center py-3 px-4">Activa</th>
                <th class="text-center py-3 px-4">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tarifas as $tarifa)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded font-bold">
                        {{ $tarifa->clave }}
                    </span>
                </td>
                <td class="py-3 px-4">{{ $tarifa->descripcion ?? '—' }}</td>
                <td class="py-3 px-4 text-right font-semibold">
                    ${{ number_format($tarifa->tarifa_cliente, 2) }}
                </td>
                <td class="py-3 px-4 text-right text-green-600">
                    ${{ number_format($tarifa->pago_chofer, 2) }}
                </td>
                <td class="py-3 px-4 text-center">
                    @if($tarifa->activa)
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Sí</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">No</span>
                    @endif
                </td>
                <td class="py-3 px-4 text-center">
                    <a href="{{ route('tarifas.edit', $tarifa) }}" 
                       class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600 mr-1">
                        Editar
                    </a>
                    <form action="{{ route('tarifas.destroy', $tarifa) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('¿Eliminar esta tarifa?')"
                                class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500">
                    No hay tarifas registradas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
