<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla intermedia que registra qué repuestos se usaron en cada orden
        Schema::create('orden_repuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes')->onDelete('cascade');
            $table->foreignId('repuesto_id')->constrained('repuestos')->onDelete('cascade');
            // Cantidad usada de ese repuesto
            $table->integer('cantidad');
            // Precio al momento de usar (puede diferir del precio actual)
            $table->decimal('precio_unitario', 8, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_repuesto');
    }
};
