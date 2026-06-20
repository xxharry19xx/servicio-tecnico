<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'numero_venta',
        'total',
        'metodo_pago',
        'atendido_por',
    ];

    // Genera el número de venta automático: V-2026-0001
    public static function generarNumeroVenta(): string
    {
        $anio = date('Y');
        $correlativo = self::whereYear('created_at', $anio)->count() + 1;
        return 'V-' . $anio . '-' . str_pad($correlativo, 4, '0', STR_PAD_LEFT);
    }

    // Una venta tiene muchos items (repuestos vendidos)
    public function items()
    {
        return $this->hasMany(VentaItem::class);
    }
}
