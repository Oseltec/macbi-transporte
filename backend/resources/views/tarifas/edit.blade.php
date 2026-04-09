@extends('admin.layout')

@section('title', 'Editar Tarifa')

@section('breadcrumb')
    <a href="{{ route('tarifas.index') }}" class="text-blue-600 hover:text-blue-800">Tarifas</a>
    <span class="mx-2 text-gray-400">/</span>
    <span class="text-gray-500">Editar</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">✏️ Editar Tarifa</h2>
        <p class="text-gray-600">Modificar datos de la tarifa</p>
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

    <form method="POST" action="{{ route('tarifas.update', $tarifa) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Clave *</label>
                <input type="text" name="clave" value="{{ $tarifa->clave }}" required
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción</label>
                <input type="text" name="descripcion" value="{{ $tarifa->descripcion }}"
                       class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tarifa al Cliente *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" step="0.01" name="tarifa_cliente" 
                           value="{{ $tarifa->tarifa_cliente }}" required
                           class="w-full border rounded pl-7 pr-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Pago al Chofer *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" step="0.01" name="pago_chofer" 
                           value="{{ $tarifa->pago_chofer }}" required
                           class="w-full border rounded pl-7 pr-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Activa</label>
                <select name="activa" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ $tarifa->activa ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$tarifa->activa ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                💾 Actualizar Tarifa
            </button>
            <a href="{{ route('tarifas.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
