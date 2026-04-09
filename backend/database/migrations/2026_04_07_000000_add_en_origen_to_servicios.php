<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite no soporta modificar enum, usamos raw SQL
        DB::statement("ALTER TABLE servicios ADD COLUMN estado_temp VARCHAR(20)");
        
        // Copiar valores existentes
        DB::statement("UPDATE servicios SET estado_temp = estado");
        
        // Eliminar columna vieja y crear nueva con todos los estados
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
        
        Schema::table('servicios', function (Blueprint $table) {
            $table->enum('estado', [
                'creado',
                'asignado',
                'en_origen',
                'en_proceso',
                'finalizado',
                'cancelado'
            ])->default('creado')->after('destino');
        });
        
        // Restaurar valores
        DB::statement("UPDATE servicios SET estado = estado_temp WHERE estado_temp IS NOT NULL");
        
        // Eliminar columna temporal
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('estado_temp');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
        
        Schema::table('servicios', function (Blueprint $table) {
            $table->enum('estado', [
                'creado',
                'asignado',
                'en_proceso',
                'finalizado',
                'cancelado'
            ])->default('creado');
        });
    }
};
