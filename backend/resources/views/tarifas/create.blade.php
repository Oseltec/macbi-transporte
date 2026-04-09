@extends('admin.layout')

@section('title', 'Nueva Tarifa')

@section('breadcrumb')
    <a href="{{ route('tarifas.index') }}" class="text-blue-600 hover:text-blue-800">Tarifas</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-500">Nueva</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">➕ Nueva Tarifa</h2>
        <p class="text-gray-600">Crear una nueva tarifa de servicio</p>
    </div>
    <a href="{{ route('tarifas.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver a Tarifas
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

    <form method="POST" action="{{ route('tarifas.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Clave *</label>
                <input type="text" name="clave" value="{{ old('clave') }}" required
                       placeholder="Ej: LOC, FOR, AER"
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion') }}"
                       placeholder="Descripción del servicio"
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tarifa al Cliente *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" step="0.01" name="tarifa_cliente" 
                           value="{{ old('tarifa_cliente') }}" required
                           class="w-full border rounded pl-7 pr-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Pago al Chofer *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" step="0.01" name="pago_chofer" 
                           value="{{ old('pago_chofer') }}" required
                           class="w-full border rounded pl-7 pr-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                💾 Guardar Tarifa
            </button>
            <a href="{{ route('tarifas.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
