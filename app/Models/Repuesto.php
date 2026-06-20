<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    protected $table = 'repuestos';
    protected $fillable = [
        'nombre',
        'marca_compatible',
        'modelo_compatible',
        'stock_actual',
        'stock_minimo',
        'precio_compra',
        'precio_venta',
    ];

    // Scope para filtrar repuestos con stock bajo — se usa en alertas
    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }

    // Las órdenes donde se usó este repuesto
    public function ordenes()
    {
        return $this->belongsToMany(Orden::class, 'orden_repuesto')
            ->withPivot('cantidad', 'precio_unitario');
    }

    // Items de venta donde se usó este repuesto
    public function ventaItems()
    {
        return $this->hasMany(VentaItem::class);
    }
}
