<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    protected $primaryKey = 'IdSolicitudCancelacion';
}
