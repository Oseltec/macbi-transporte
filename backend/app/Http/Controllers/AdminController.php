<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total' => Servicio::count(),
            'en_proceso' => Servicio::whereIn('estado', ['asignado', 'en_origen', 'en_proceso'])->count(),
            'hoy' => Servicio::whereDate('updated_at', Carbon::today())
                ->where('estado', 'finalizado')
                ->count(),
            'choferes_activos' => User::where('rol', 'chofer')->where('activo', true)->count(),
        ];

        $estadoStats = Servicio::selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $estadoColores = [
            'creado' => 'bg-gray-400',
            'asignado' => 'bg-blue-400',
            'en_origen' => 'bg-yellow-400',
            'en_proceso' => 'bg-orange-400',
            'finalizado' => 'bg-green-400',
            'cancelado' => 'bg-red-400',
        ];

        $serviciosRecientes = Servicio::with('chofer')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($s) {
                $s->estado_clase = match($s->estado) {
                    'creado' => 'bg-gray-100 text-gray-700',
                    'asignado' => 'bg-blue-100 text-blue-700',
                    'en_origen' => 'bg-yellow-100 text-yellow-700',
                    'en_proceso' => 'bg-orange-100 text-orange-700',
                    'finalizado' => 'bg-green-100 text-green-700',
                    'cancelado' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700'
                };
                return $s;
            });

        $serviciosSinAsignar = Servicio::with('tarifa')
            ->whereNull('chofer_id')
            ->whereIn('estado', ['creado'])
            ->orderBy('fecha_servicio', 'asc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'estadoStats',
            'estadoColores',
            'serviciosRecientes',
            'serviciosSinAsignar'
        ));
    }

    public function choferes()
    {
        $choferes = User::where('rol', 'chofer')
            ->withCount(['servicios as servicios_activos' => function($q) {
                $q->whereIn('estado', ['asignado', 'en_origen', 'en_proceso']);
            }])
            ->withCount(['servicios as servicios_finalizados' => function($q) {
                $q->where('estado', 'finalizado');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.choferes', compact('choferes'));
    }

    public function mapa()
    {
        $serviciosActivos = Servicio::with(['chofer', 'tarifa'])
            ->whereIn('estado', ['asignado', 'en_origen', 'en_proceso'])
            ->get()
            ->map(function($s) {
                // Si no hay coordenadas, usar ubicación por defecto de CDMX
                $s->latitud = $s->latitud ?? 19.4326;
                $s->longitud = $s->longitud ?? -99.1332;
                $s->estado_clase = match($s->estado) {
                    'creado' => 'bg-gray-100 text-gray-700',
                    'asignado' => 'bg-blue-100 text-blue-700',
                    'en_origen' => 'bg-yellow-100 text-yellow-700',
                    'en_proceso' => 'bg-orange-100 text-orange-700',
                    'finalizado' => 'bg-green-100 text-green-700',
                    'cancelado' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700'
                };
                return $s;
            });

        return view('admin.mapa', compact('serviciosActivos'));
    }

    public function apiServiciosActivos()
    {
        $servicios = Servicio::with(['chofer', 'tarifa'])
            ->whereIn('estado', ['asignado', 'en_origen', 'en_proceso'])
            ->get()
            ->map(function($s) {
                $s->latitud = $s->latitud ?? 19.4326;
                $s->longitud = $s->longitud ?? -99.1332;
                return $s;
            });

        return response()->json($servicios);
    }
}
