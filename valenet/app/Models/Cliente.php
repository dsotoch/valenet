<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombres',
        'apellidos',
        'documento',
        'telefono',
        'email',
        'direccion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
    public function planes()
    {
        return $this->hasMany(ClientePlan::class);
    }

    public function planActual()
    {
        return $this->hasOne(ClientePlan::class)
            ->where('estado', true)
            ->latestOfMany();
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
