<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioEvento extends Model
{
   protected $fillable = [
    'servicio_id',
    'chofer_id',
    'tipo_evento',
    'latitud',
    'longitud',
    'fecha_evento'
];
}
