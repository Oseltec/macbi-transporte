<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('servicio_tracking', function (Blueprint $table) {
        $table->id();
        $table->foreignId('servicio_id')->constrained()->cascadeOnDelete();
        $table->foreignId('chofer_id')->constrained('users');
        $table->decimal('latitud', 10, 7);
        $table->decimal('longitud', 10, 7);
        $table->decimal('precision', 8, 2)->nullable();
        $table->decimal('velocidad', 8, 2)->nullable();
        $table->decimal('direccion', 8, 2)->nullable();
        $table->timestamps();

        $table->index(['servicio_id', 'created_at']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_tracking');
    }
};
