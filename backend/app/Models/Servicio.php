<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'cliente',
        'fecha_servicio',
        'origen',
        'destino',
        'estado',
        'chofer_id',
        'tarifa_id',
        'tarifa_clave_snapshot',
        'tarifa_cliente_snapshot',
        'pago_chofer_snapshot',
        'fecha_asignacion',
        'asignado_por'
    ];

    public function chofer()
    {
        return $this->belongsTo(User::class, 'chofer_id');
    }

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }

    public function asignadoPor()
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }
    public function eventos()
    {
        return $this->hasMany(ServicioEvento::class);
    }

    public function tracking()
    {
        return $this->hasMany(ServicioTracking::class);
    }
}
