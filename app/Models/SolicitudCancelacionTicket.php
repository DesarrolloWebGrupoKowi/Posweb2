<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tienda;

class SolicitudCancelacionTicket extends Model
{
    use HasFactory;
    protected $table = 'SolicitudCancelacionTicket';
    protected $fillable = [
        'FechaSolicitud',
        'IdTienda',
        'IdEncabezado',
        'IdUsuarioSolicitud',
        'MotivoCancelacion',
        'SolicitudAprobada',
        'FechaAprobacion',
        'IdUsuarioAprobacion',
        'Status'
    ];
    public $timestamps = false;
    // protected $primaryKey = 'IdSolicitudCancelacion';

    public function Tienda(){
        return $this->hasOne(Tienda::class, 'IdTienda', 'IdTienda');
    }

    public function Encabezado(){
        return $this->hasOne(DatEncabezado::class, 'IdEncabezado', 'IdEncabezado');
    }

    public function Detalle(){
        return $this->hasMany(DatDetalle::class, 'IdEncabezado', 'IdEncabezado');
    }
}
