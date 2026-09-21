<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'velocidad',
        'precio',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'estado' => 'boolean',
    ];
    public function clientes()
    {
        return $this->hasMany(ClientePlan::class);
    }
    public function clientePlanes()
    {
        return $this->hasMany(ClientePlan::class);
    }
}
