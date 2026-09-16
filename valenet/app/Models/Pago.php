<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'cliente_id',
        'cliente_plan_id',
        'periodo',
        'fecha_vencimiento',
        'monto',
        'monto_pagado',
        'fecha_pago',
        'metodo_pago',
        'referencia',
        'observacion',
        'estado',
    ];

    protected $casts = [
        'periodo' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function clientePlan(): BelongsTo
    {
        return $this->belongsTo(ClientePlan::class);
    }

    public function recordatorios(): HasMany
    {
        return $this->hasMany(RecordatorioPago::class);
    }

    public function getEstaVencidoAttribute(): bool
    {
        return $this->estado === 'pendiente'
            && $this->fecha_vencimiento
            && $this->fecha_vencimiento->isBefore(now()->startOfDay());
    }
}