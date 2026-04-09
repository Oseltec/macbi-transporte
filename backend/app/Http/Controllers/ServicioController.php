<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;


class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with(['chofer', 'tarifa'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $choferes = User::where('rol', 'chofer')->where('activo', true)->get();
        $tarifas = Tarifa::where('activa', true)->get();

        return view('servicios.create', compact('choferes', 'tarifas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente' => ['required', 'string', 'max:255'],

            'fecha_servicio' => [
                'required',
                'date',
                 'after_or_equal:' . now()->format('Y-m-d'),
            ],

            'origen' => ['required', 'string', 'max:255'],
            'destino' => ['required', 'string', 'max:255'],

            'chofer_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where('rol', 'chofer')
                    ->where('activo', true),
            ],

            'tarifa_id' => [
                'required',
                Rule::exists('tarifas', 'id')
                    ->where('activa', true),
            ],
        ]);

        $tarifa = Tarifa::findOrFail($request->tarifa_id);

        Servicio::create([
            'cliente' => $request->cliente,
            'fecha_servicio' => $request->fecha_servicio,
            'origen' => $request->origen,
            'destino' => $request->destino,
            'estado' => 'asignado',
            'chofer_id' => $request->chofer_id,
            'tarifa_id' => $tarifa->id,
            'tarifa_clave_snapshot' => $tarifa->clave,
            'tarifa_cliente_snapshot' => $tarifa->tarifa_cliente,
            'pago_chofer_snapshot' => $tarifa->pago_chofer,
            'fecha_asignacion' => now(),
            'asignado_por' => auth()->id(),
        ]);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio creado y asignado correctamente');
    }


    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        $choferes = User::where('rol', 'chofer')->where('activo', true)->get();
        $tarifas = Tarifa::where('activa', true)->get();

        return view('servicios.edit', compact('servicio', 'choferes', 'tarifas'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $servicio->update($request->all());

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio actualizado');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado');
    }

    public function misServicios(Request $request)
    {
        // 👇 Obtener usuario autenticado vía Sanctum
        
        $usuario = $request->user();

        if (!$usuario) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $usuarioId = $usuario->id;

        $activos = Servicio::where('chofer_id', $usuarioId)
            ->whereIn('estado', ['asignado', 'en_origen', 'en_proceso'])
            ->orderBy('fecha_servicio', 'desc')
            ->get();

        $semanas = collect();

        for ($i = 0; $i < 4; $i++) {
            $inicio = Carbon::now()->subWeeks($i)->startOfWeek();
            $fin = Carbon::now()->subWeeks($i)->endOfWeek();

            $semanas->push([
                'label' => "Semana del {$inicio->format('d M')} al {$fin->format('d M')}",
                'inicio' => $inicio->toDateString(),
                'fin' => $fin->toDateString(),
            ]);
        }

        $queryFinalizados = Servicio::where('chofer_id', $usuarioId)
            ->where('estado', 'finalizado');

        if ($request->filled('inicio') && $request->filled('fin')) {
            $queryFinalizados->whereBetween('fecha_servicio', [
                $request->inicio,
                $request->fin
            ]);
        }

        $finalizados = $queryFinalizados
            ->orderBy('fecha_servicio', 'desc')
            ->get();

        $totalSemana = $finalizados->sum('pago_chofer_snapshot');

        // 👇 Si es petición API, devolver JSON
        if ($request->expectsJson()) {
            return response()->json([
                'activos' => $activos,
                'finalizados' => $finalizados,
                'totalSemana' => $totalSemana,
                'semanas' => $semanas,
            ]);
        }

        // 👇 Si es web, devolver vista
        return view('servicios.mis', compact(
            'activos',
            'finalizados',
            'totalSemana',
            'semanas'
        ));
    }

    public function finalizar(Servicio $servicio)
    {
        if ($servicio->chofer_id !== auth()->id()) {
            abort(403);
        }

        $servicio->update([
            'estado' => 'finalizado'
        ]);

        return back()->with('success', 'Servicio finalizado correctamente');
    }

    public function iniciar(Request $request, Servicio $servicio)
{
    if ($servicio->chofer_id !== $request->user()->id) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    $servicio->estado = 'en_proceso';
    $servicio->save();

    return response()->json([
        'message' => 'Servicio iniciado'
    ]);
}

    public function registrarEvento(Request $request, Servicio $servicio)
    {
        if ($servicio->chofer_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'tipo_evento' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $servicio->eventos()->create([
            'chofer_id' => $request->user()->id,
            'tipo_evento' => $request->tipo_evento,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'fecha_evento' => now(),
        ]);

        return response()->json(['message' => 'Evento registrado']);
    }
    public function marcarEnOrigen(Request $request, Servicio $servicio)
    {
        if ($servicio->chofer_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $servicio->estado = 'en_origen';
        $servicio->save();

        return response()->json(['message' => 'Chofer en origen']);
    }
}
