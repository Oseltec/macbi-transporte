<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Http\Controllers\ServicioController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function () {
    return response()->json([
        'status' => 'API funcionando'
    ]);
});

// Ruta pública para el mapa del admin
Route::get('/admin/servicios-activos', [AdminController::class, 'apiServiciosActivos']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/whoami', function (Request $request) {
        return response()->json($request->user());
    });

    Route::get('/mis-servicios', [ServicioController::class, 'misServicios']);

    Route::patch('/servicios/{servicio}/iniciar', [ServicioController::class, 'iniciar']);

    Route::post('/servicios/{servicio}/evento', [ServicioController::class, 'registrarEvento']);

    Route::patch('/servicios/{servicio}/en-origen', [ServicioController::class, 'marcarEnOrigen']);

    Route::patch('/servicios/{servicio}/finalizar', function (Request $request, Servicio $servicio) {

        if ($servicio->chofer_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $servicio->update([
            'estado' => 'finalizado'
        ]);

        return response()->json([
            'message' => 'Servicio finalizado correctamente',
            'servicio' => $servicio
        ]);
    });

});