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
            // Nombre del servicio: "Cambio de pantalla", "Puerto USB-C", etc.
            $table->string('nombre', 150);
            // Categoría para agrupar: "Pantallas", "Puertos", "Software", etc.
            $table->string('categoria', 50)->nullable();
            // Precio sugerido — el técnico puede modificarlo
            $table->decimal('precio_sugerido', 8, 2)->default(0);
            // Para desactivar sin borrar
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};