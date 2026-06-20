<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            // Nombre descriptivo: "Pantalla Samsung A54"
            $table->string('nombre', 150);
            $table->string('marca_compatible', 50);
            $table->string('modelo_compatible', 100);
            // Stock actual y mínimo — alerta cuando actual <= mínimo
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(2);
            // Precio de compra y venta para calcular margen
            $table->decimal('precio_compra', 8, 2);
            $table->decimal('precio_venta', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repuestos');
    }
};
