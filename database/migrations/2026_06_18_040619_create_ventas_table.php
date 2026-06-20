<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Encabezado de cada venta directa de repuestos
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            // Número de comprobante: V-2026-0001
            $table->string('numero_venta', 20)->unique();
            // Total de la venta (suma de todos los items)
            $table->decimal('total', 8, 2);
            $table->enum('metodo_pago', ['efectivo', 'yape', 'plin', 'transferencia']);
            // Quién atendió la venta (nombre simple, no requiere FK)
            $table->string('atendido_por', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
