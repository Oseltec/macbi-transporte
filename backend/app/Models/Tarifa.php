<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $fillable = [
        'clave',
        'descripcion',
        'tarifa_cliente',
        'pago_chofer',
        'activa'
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
}
