<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id();
            // Nombre completo del técnico
            $table->string('nombre', 100);
            // Teléfono opcional para contacto interno
            $table->string('telefono', 15)->nullable();
            // Para desactivar un técnico sin borrarlo
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tecnicos');
    }
};