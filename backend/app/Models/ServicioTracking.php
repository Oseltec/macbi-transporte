<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioTracking extends Model
{
    protected $fillable = [
    'servicio_id',
    'chofer_id',
    'latitud',
    'longitud',
    'precision',
    'velocidad',
    'direccion'
];
}
