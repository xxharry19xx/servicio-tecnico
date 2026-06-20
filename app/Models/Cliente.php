<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    // Campos que se pueden llenar masivamente desde formularios
    protected $fillable = [
        'nombre_completo',
        'dni',
        'telefono',
        'correo',
    ];

    // Un cliente puede tener muchas órdenes de trabajo
    public function ordenes()
    {
        return $this->hasMany(Orden::class);
    }
}
