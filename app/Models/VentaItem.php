<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaItem extends Model
{
    protected $table = 'venta_items';

    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'repuesto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // Un item pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Un item referencia a un repuesto
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }
}
