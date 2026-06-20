<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            // Cada pago pertenece a una orden
            $table->foreignId('orden_id')->constrained('ordenes')->onDelete('cascade');
            $table->decimal('monto', 8, 2);
            // Métodos de pago comunes en Perú
            $table->enum('metodo_pago', ['efectivo', 'yape', 'plin', 'transferencia']);
            // Nota opcional: "adelanto", "pago final", etc.
            $table->string('nota', 150)->nullable();
            // Se registra automáticamente la fecha y hora del pago
            $table->timestamp('fecha_pago')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
