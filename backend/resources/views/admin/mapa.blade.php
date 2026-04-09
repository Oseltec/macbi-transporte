@extends('admin.layout')

@section('title', 'Mapa en Vivo')

@section('breadcrumb')
    <span class="text-gray-500">Mapa en Vivo</span>
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold">🗺️ Mapa en Vivo</h2>
        <p class="text-gray-600">Seguimiento de servicios activos en tiempo real</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ← Volver al Dashboard
    </a>
</div>

@if($serviciosActivos->isEmpty())
<div class="bg-white rounded-lg shadow p-8 text-center">
    <div class="text-6xl mb-4">🚗</div>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay servicios activos</h3>
    <p class="text-gray-500">Los servicios activos aparecerán aquí en el mapa</p>
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Lista de Servicios Activos -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">📋 Servicios Activos</h3>
            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-sm font-semibold">
                {{ $serviciosActivos->count() }}
            </span>
        </div>
        <div id="servicios-lista" class="space-y-2 max-h-96 overflow-y-auto">
            @forelse($serviciosActivos as $servicio)
            <div class="border rounded p-3 hover:bg-gray-50 cursor-pointer servicio-item transition-all hover:shadow-md" 
                 data-id="{{ $servicio->id }}"
                 data-lat="{{ $servicio->latitud }}"
                 data-lng="{{ $servicio->longitud }}"
                 data-cliente="{{ $servicio->cliente }}"
                 data-origen="{{ $servicio->origen }}"
                 data-destino="{{ $servicio->destino }}"
                 data-estado="{{ $servicio->estado }}"
                 data-chofer="{{ $servicio->chofer?->name ?? 'Sin asignar' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $servicio->cliente }}</p>
                        <p class="text-xs text-gray-500">👤 {{ $servicio->chofer?->name ?? 'Sin asignar' }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs {{ $servicio->estado_clase }}">
                        {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-2">
                    📍 {{ Str::limit($servicio->origen, 35) }}
                </p>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No hay servicios activos</p>
            @endforelse
        </div>
        <button id="btn-actualizar" class="mt-4 w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 flex items-center justify-center gap-2">
            <span>🔄</span> Actualizar
        </button>
    </div>

    <!-- Mapa -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-4">🗺️ Mapa</h3>
        <div id="mapa" class="h-[500px] rounded-lg border" style="background: #f0f0f0;"></div>
    </div>
</div>

<!-- Detalles del Servicio -->
<div id="detalles-servicio" class="hidden bg-white rounded-lg shadow p-4 mt-6">
    <h3 class="text-lg font-semibold mb-4">📋 Detalles del Servicio</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-xs text-gray-500">Cliente</p>
            <p id="det-cliente" class="font-semibold">-</p>
        </div>
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-xs text-gray-500">Chofer</p>
            <p id="det-chofer" class="font-semibold">-</p>
        </div>
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-xs text-gray-500">Estado</p>
            <p id="det-estado" class="font-semibold">-</p>
        </div>
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-xs text-gray-500">Origen</p>
            <p id="det-origen" class="font-semibold text-sm">-</p>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
var map = null;
var markers = {};

function initMap() {
    if (document.getElementById('mapa')) {
        map = L.map('mapa').setView([19.4326, -99.1332], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Agregar marcadores iniciales
        var items = document.querySelectorAll('.servicio-item');
        if (items.length > 0) {
            var bounds = [];
            items.forEach(function(item) {
                var lat = parseFloat(item.dataset.lat) || 19.4326;
                var lng = parseFloat(item.dataset.lng) || -99.1332;
                bounds.push([lat, lng]);
                agregarMarcador({
                    id: item.dataset.id,
                    latitud: lat,
                    longitud: lng,
                    cliente: item.dataset.cliente,
                    origen: item.dataset.origen,
                    destino: item.dataset.destino,
                    estado: item.dataset.estado,
                    chofer: { name: item.dataset.chofer }
                });
            });
            
            if (bounds.length > 0) {
                map.fitBounds(bounds, {padding: [50, 50]});
            }
        }

        // Click en servicios
        items.forEach(function(item) {
            item.addEventListener('click', function() {
                var lat = parseFloat(this.dataset.lat) || 19.4326;
                var lng = parseFloat(this.dataset.lng) || -99.1332;
                map.setView([lat, lng], 15);
                
                if (markers[this.dataset.id]) {
                    markers[this.dataset.id].openPopup();
                }

                document.getElementById('detalles-servicio').classList.remove('hidden');
                document.getElementById('det-cliente').textContent = this.dataset.cliente;
                document.getElementById('det-chofer').textContent = this.dataset.chofer;
                document.getElementById('det-estado').textContent = this.dataset.estado.replace('_', ' ');
                document.getElementById('det-origen').textContent = this.dataset.origen;
            });
        });
    }
}

function getColorEstado(estado) {
    const colores = {
        'asignado': '#3b82f6',
        'en_origen': '#eab308',
        'en_proceso': '#f97316',
        'finalizado': '#22c55e',
        'creado': '#6b7280'
    };
    return colores[estado] || '#6b7280';
}

function agregarMarcador(servicio) {
    var color = getColorEstado(servicio.estado);
    var estadoIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background:' + color + ';width:32px;height:32px;border-radius:50%;border:3px solid white;display:flex;align-items:center;justify-content:center;font-size:16px;box-shadow: 0 2px 4px rgba(0,0,0,0.3);">🚗</div>',
        iconSize: [32, 32],
        iconAnchor: [16, 16]
    });

    var marker = L.marker([servicio.latitud, servicio.longitud], {icon: estadoIcon})
        .addTo(map)
        .bindPopup('<strong>' + servicio.cliente + '</strong><br>Chofer: ' + (servicio.chofer ? servicio.chofer.name : 'Sin asignar') + '<br>Estado: ' + servicio.estado.replace('_', ' '));
    
    markers[servicio.id] = marker;
}

function actualizarMapa() {
    if (!map) return;
    
    fetch('/api/admin/servicios-activos')
        .then(r => r.json())
        .then(servicios => {
            Object.values(markers).forEach(function(m) { map.removeLayer(m); });
            markers = {};
            
            if (servicios.length > 0) {
                var bounds = [];
                servicios.forEach(function(s) {
                    bounds.push([s.latitud || 19.4326, s.longitud || -99.1332]);
                    agregarMarcador(s);
                });
                map.fitBounds(bounds, {padding: [50, 50]});
            }
        });
}

// Inicializar cuando cargue el DOM
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Botón actualizar
    document.getElementById('btn-actualizar').addEventListener('click', actualizarMapa);
    
    // Auto-actualizar cada 30 segundos
    setInterval(actualizarMapa, 30000);
});
</script>
@endpush