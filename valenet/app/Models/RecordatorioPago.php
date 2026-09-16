<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordatorioPago extends Model
{
    protected $table = 'recordatorios_pagos';

    protected $fillable = [
        'pago_id',
        'canal',
        'tipo',
        'fecha_envio',
        'mensaje',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }
}