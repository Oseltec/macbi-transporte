<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('tarifas', TarifaController::class);
    Route::resource('servicios', ServicioController::class);
});

Route::middleware(['auth', 'chofer'])->group(function () {
    Route::get('/mis-servicios', [ServicioController::class, 'misServicios'])
        ->name('servicios.mis');
    Route::patch('/servicios/{servicio}/finalizar',
    [ServicioController::class, 'finalizar'])
    ->name('servicios.finalizar');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/choferes', [AdminController::class, 'choferes'])->name('choferes');
    Route::get('/mapa', [AdminController::class, 'mapa'])->name('mapa');
});

Route::get('/mapa-test', function () {
    return file_get_contents(public_path('mapa-test.html'));
});

Route::get('/mapa-vivo', function () {
    return file_get_contents(public_path('mapa-vivo.html'));
});

require __DIR__.'/auth.php';
