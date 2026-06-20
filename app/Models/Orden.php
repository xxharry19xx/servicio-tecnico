<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Orden extends Model
{
    protected $table = 'ordenes';
    protected $fillable = [
        'numero_orden',
        'token_qr',
        'cliente_id',
        'tecnico_asignado',
        'marca',
        'modelo',
        'color',
        'imei',
        'contrasena_equipo',
        'accesorios',
        'falla_cliente',
        'diagnostico_tecnico',
        'precio_total',
        'mano_obra',
        'fecha_entrega_estimada',
        'estado',
        'fecha_listo',
        'fecha_entregado',
    ];

    // Campos que Laravel debe tratar como fechas
    protected $casts = [
        'fecha_entrega_estimada' => 'date',
        'fecha_listo'            => 'datetime',
        'fecha_entregado'        => 'datetime',
    ];

    // Le decimos a Laravel que el parámetro de ruta se llama "orden"
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    // Genera el número de orden automático: OT-2026-0001
    public static function generarNumeroOrden(): string
    {
        $anio = date('Y');
        // Contamos cuántas órdenes hay este año para el correlativo
        $correlativo = self::whereYear('created_at', $anio)->count() + 1;
        return 'OT-' . $anio . '-' . str_pad($correlativo, 4, '0', STR_PAD_LEFT);
    }

    // Genera un token UUID único para el portal QR público
    public static function generarTokenQr(): string
    {
        return Str::uuid()->toString();
    }

    // Calcula el total pagado sumando todos los pagos de esta orden
    public function getTotalPagadoAttribute(): float
    {
        return $this->pagos()->sum('monto');
    }

    // Calcula cuánto falta por pagar
    public function getSaldoPendienteAttribute(): float
    {
        return $this->precio_total - $this->total_pagado;
    }

    // Devuelve el estado del pago como texto para los badges
    public function getEstadoPagoAttribute(): string
    {
        if ($this->saldo_pendiente <= 0) return 'pagado';
        if ($this->total_pagado > 0) return 'parcial';
        return 'pendiente';
    }

    // Relación: una orden pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: una orden puede tener muchos pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    // Relación: una orden puede usar muchos repuestos
    public function repuestos()
    {
        return $this->belongsToMany(Repuesto::class, 'orden_repuesto')
            ->withPivot('cantidad', 'precio_unitario');
    }

    // Relación: historial de cambios de estado
    public function estadoLogs()
    {
        return $this->hasMany(EstadoLog::class);
    }

    // Calcula el costo total de los repuestos asociados a esta orden
    public function getCostoRepuestosAttribute(): float
    {
        // Cargamos los repuestos y calculamos manualmente el subtotal
        return $this->repuestos->sum(function ($repuesto) {
            return $repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario;
        });
    }

    // Recalcula y guarda el precio_total = mano_obra + repuestos
    public function recalcularTotal(): void
    {
        $this->precio_total = $this->mano_obra + $this->costo_repuestos;
        $this->save();
    }
}
