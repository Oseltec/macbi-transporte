@extends('admin.layout')

@section('title', 'Editar Servicio')

@section('breadcrumb')
    <a href="{{ route('servicios.index') }}" class="text-blue-600 hover:text-blue-800">Servicios</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-500">Editar</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">✏️ Editar Servicio</h2>
        <p class="text-gray-600">Asignar chofer y tarifa al servicio</p>
    </div>
    <a href="{{ route('servicios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver a Servicios
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-4 p-4 bg-blue-50 rounded">
        <p><strong>Cliente:</strong> {{ $servicio->cliente }}</p>
        <p><strong>Origen:</strong> {{ $servicio->origen }}</p>
        <p><strong>Destino:</strong> {{ $servicio->destino }}</p>
        <p><strong>Fecha:</strong> {{ $servicio->fecha_servicio }}</p>
        <p><strong>Estado actual:</strong> 
            <span class="px-2 py-1 rounded text-xs {{
                $servicio->estado === 'finalizado' ? 'bg-green-100 text-green-700' :
                ($servicio->estado === 'cancelado' ? 'bg-red-100 text-red-700' :
                'bg-blue-100 text-blue-700')
            }}">
                {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
            </span>
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('servicios.update', $servicio) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Chofer *</label>
                <select name="chofer_id" required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccionar chofer...</option>
                    @foreach($choferes as $chofer)
                        <option value="{{ $chofer->id }}" {{ old('chofer_id', $servicio->chofer_id) == $chofer->id ? 'selected' : '' }}>
                            {{ $chofer->name }} {{ !$chofer->activo ? '(Inactivo)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tarifa *</label>
                <select name="tarifa_id" required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccionar tarifa...</option>
                    @foreach($tarifas as $tarifa)
                        <option value="{{ $tarifa->id }}" 
                                {{ old('tarifa_id', $servicio->tarifa_id) == $tarifa->id ? 'selected' : '' }}
                                {{ !$tarifa->activa ? 'disabled' : '' }}>
                            {{ $tarifa->clave }} - ${{ $tarifa->tarifa_cliente }} (Pago: ${{ $tarifa->pago_chofer }})
                            {{ !$tarifa->activa ? '(Inactiva)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Estado</label>
                <select name="estado" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="creado" {{ old('estado', $servicio->estado) == 'creado' ? 'selected' : '' }}>Creado</option>
                    <option value="asignado" {{ old('estado', $servicio->estado) == 'asignado' ? 'selected' : '' }}>Asignado</option>
                    <option value="en_origen" {{ old('estado', $servicio->estado) == 'en_origen' ? 'selected' : '' }}>En Origen</option>
                    <option value="en_proceso" {{ old('estado', $servicio->estado) == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="finalizado" {{ old('estado', $servicio->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="cancelado" {{ old('estado', $servicio->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                💾 Guardar Cambios
            </button>
            <a href="{{ route('servicios.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
