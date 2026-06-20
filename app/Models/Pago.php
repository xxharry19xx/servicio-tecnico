<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    // La tabla pagos no tiene updated_at
    public $timestamps = false;

    protected $fillable = [
        'orden_id',
        'monto',
        'metodo_pago',
        'nota',
        'fecha_pago',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
    ];

    // Un pago pertenece a una orden
    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }
}
