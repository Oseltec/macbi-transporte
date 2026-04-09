@extends('admin.layout')

@section('title', 'Mapa en Vivo')

@section('breadcrumb')
    <span class="text-gray-500">Mapa en Vivo</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">🗺️ Mapa en Vivo</h2>
        <p class="text-gray-600">Seguimiento de servicios activos</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver al Dashboard
    </a>
</div>

<!-- Mapa de prueba -->
<div class="bg-white rounded-lg shadow p-4">
    <h3 class="text-lg font-semibold mb-4">🗺️ Mapa de Ciudad de México</h3>
    <div id="map-test" class="h-[500px] rounded-lg border" style="width: 100%; min-height: 500px;"></div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando mapa...');
    
    var map = L.map('map-test').setView([19.4326, -99.1332], 12);
    
    console.log('Mapa creado, cargando tiles...');
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19
    }).addTo(map);
    
    console.log('Tiles agregados');
    
    // Marcador de prueba
    var marker = L.marker([19.4326, -99.1332]).addTo(map);
    marker.bindPopup('<b>Ciudad de México</b><br>Punto de prueba').openPopup();
    
    console.log('Mapa inicializado correctamente');
});
</script>
@endpush