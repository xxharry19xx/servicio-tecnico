<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Historial de todos los cambios de estado de cada orden
        Schema::create('estado_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes')->onDelete('cascade');
            // Guardamos de dónde venía y a dónde fue
            $table->string('estado_anterior', 20)->nullable();
            $table->string('estado_nuevo', 20);
            // Fecha y hora exacta del cambio
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_logs');
    }
};
