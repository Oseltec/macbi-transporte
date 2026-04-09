<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();

            $table->string('cliente');
            $table->date('fecha_servicio');
            $table->string('origen');
            $table->string('destino');

            $table->enum('estado', [
                'creado',
                'asignado',
                'en_proceso',
                'finalizado',
                'cancelado'
            ])->default('creado');

            // Relaciones
            $table->foreignId('chofer_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('tarifa_id')
                  ->nullable()
                  ->constrained('tarifas')
                  ->nullOnDelete();

            // Snapshot financiero
            $table->string('tarifa_clave_snapshot')->nullable();
            $table->decimal('tarifa_cliente_snapshot', 10, 2)->nullable();
            $table->decimal('pago_chofer_snapshot', 10, 2)->nullable();

            $table->timestamp('fecha_asignacion')->nullable();
            $table->foreignId('asignado_por')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
