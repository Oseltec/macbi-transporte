@extends('admin.layout')

@section('title', 'Nuevo Servicio')

@section('breadcrumb')
    <a href="{{ route('servicios.index') }}" class="text-blue-600 hover:text-blue-800">Servicios</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-500">Nuevo</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">➕ Nuevo Servicio</h2>
        <p class="text-gray-600">Crear un nuevo servicio de transporte</p>
    </div>
    <a href="{{ route('servicios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver a Servicios
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('servicios.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Cliente *</label>
                <input type="text" name="cliente" value="{{ old('cliente') }}" required
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Fecha del Servicio *</label>
                <input type="date" name="fecha_servicio" value="{{ old('fecha_servicio') ?? now()->format('Y-m-d') }}" required
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Origen *</label>
                <input type="text" name="origen" value="{{ old('origen') }}" required
                       placeholder="Dirección de recogida"
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Destino *</label>
                <input type="text" name="destino" value="{{ old('destino') }}" required
                       placeholder="Dirección de destino"
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Chofer *</label>
                <select name="chofer_id" required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccionar chofer...</option>
                    @foreach($choferes as $chofer)
                        <option value="{{ $chofer->id }}" {{ old('chofer_id') == $chofer->id ? 'selected' : '' }}>
                            {{ $chofer->name }}
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
                        <option value="{{ $tarifa->id }}" {{ old('tarifa_id') == $tarifa->id ? 'selected' : '' }}>
                            {{ $tarifa->clave }} - ${{ $tarifa->tarifa_cliente }} (Pago: ${{ $tarifa->pago_chofer }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                💾 Guardar Servicio
            </button>
            <a href="{{ route('servicios.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
