<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoLog extends Model
{
    protected $table = 'estado_logs';
    // Solo tiene created_at, no updated_at
    public $timestamps = false;

    protected $fillable = [
        'orden_id',
        'estado_anterior',
        'estado_nuevo',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Un log pertenece a una orden
    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }
}
