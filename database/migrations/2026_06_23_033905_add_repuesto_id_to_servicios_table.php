<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Vinculamos el servicio con un repuesto del inventario (opcional)
            $table->foreignId('repuesto_id')
                  ->nullable()
                  ->constrained('repuestos')
                  ->nullOnDelete()
                  ->after('precio_sugerido');
            // Cantidad de repuestos que se usan en este servicio
            $table->integer('cantidad_repuesto')->default(1)->after('repuesto_id');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropForeign(['repuesto_id']);
            $table->dropColumn(['repuesto_id', 'cantidad_repuesto']);
        });
    }
};