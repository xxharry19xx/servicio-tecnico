<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    protected $table = 'tecnicos';

    protected $fillable = [
        'nombre',
        'telefono',
        'activo',
    ];

    protected $casts = [
        // Le decimos a Laravel que activo es boolean
        'activo' => 'boolean',
    ];

    // Scope para obtener solo técnicos activos
    public function scopeActivos($query)
    {
        // Usamos whereRaw para forzar el cast en PostgreSQL
        return $query->whereRaw('"activo" = true');
    }

    // Un técnico tiene muchas órdenes
    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'tecnico_asignado', 'nombre');
    }


}