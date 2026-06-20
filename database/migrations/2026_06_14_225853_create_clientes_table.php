<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            // ID autoincremental
            $table->id();
            // Nombre completo del cliente
            $table->string('nombre_completo', 150);
            // DNI único — usamos para buscar y autocompletar
            $table->string('dni', 8)->unique();
            $table->string('telefono', 15);
            // Correo opcional
            $table->string('correo', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
