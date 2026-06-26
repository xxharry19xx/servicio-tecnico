<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            // Monto de descuento aplicado a la orden
            $table->decimal('descuento', 8, 2)->default(0)->after('mano_obra');
            // Motivo del descuento opcional
            $table->string('motivo_descuento', 150)->nullable()->after('descuento');
        });
    }

    public function down(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropColumn(['descuento', 'motivo_descuento']);
        });
    }
};