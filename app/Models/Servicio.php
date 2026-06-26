<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'categoria',
        'precio_sugerido',
        'activo',
        'repuesto_id',
        'cantidad_repuesto',
    ];

    protected $casts = [
        'activo'          => 'boolean',
        'precio_sugerido' => 'float',
    ];

    // Solo servicios activos
    public function scopeActivos($query)
    {
        return $query->whereRaw('"activo" = true');
    }

    // Un servicio puede estar vinculado a un repuesto del inventario
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }
}