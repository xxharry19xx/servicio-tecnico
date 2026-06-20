<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Detalle: qué repuestos se vendieron en cada venta
        Schema::create('venta_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('repuesto_id')->constrained('repuestos')->onDelete('cascade');
            $table->integer('cantidad');
            // Precio al momento de la venta (puede diferir del precio actual)
            $table->decimal('precio_unitario', 8, 2);
            // Subtotal = cantidad * precio_unitario
            $table->decimal('subtotal', 8, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_items');
    }
};
