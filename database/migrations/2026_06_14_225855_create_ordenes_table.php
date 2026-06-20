<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            // Número legible de la orden: OT-2026-0001
            $table->string('numero_orden', 20)->unique();
            // Token UUID para el portal QR público (no expone el ID)
            $table->string('token_qr', 64)->unique();
            // Relación con el cliente
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('tecnico_asignado', 100);
            // Datos del equipo
            $table->string('marca', 50);
            $table->string('modelo', 100);
            $table->string('color', 50);
            $table->string('imei', 20)->nullable();
            $table->string('contrasena_equipo', 50)->nullable();
            $table->text('accesorios')->nullable();
            // Datos del servicio
            $table->text('falla_cliente');
            $table->text('diagnostico_tecnico')->nullable();
            $table->decimal('precio_total', 8, 2)->default(0);
            $table->date('fecha_entrega_estimada')->nullable();
            // Estado actual de la orden
            $table->enum('estado', ['recibido', 'en_proceso', 'listo', 'entregado'])
                ->default('recibido');
            // Fechas especiales de cambio de estado
            $table->timestamp('fecha_listo')->nullable();
            $table->timestamp('fecha_entregado')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
