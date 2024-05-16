<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DatRecepcion;
use App\Models\StatusRecepcion;

class CapRecepcionLocal extends Model
{
    use HasFactory;
    protected $table = 'CapRecepcion';
    protected $fillable = [
        'FechaRecepcion',
        'PackingList',
        'IdTiendaOrigen',
        'Almacen',
        'Organization_Id',
        'MotivoCancelacion',
        'FechaCancelacion',
        'IdStatusRecepcion',
        'IdTiendaDestino',
        'FechaLlegada',
        'IdUsuario',
        'IdTienda',
        'IdCaja',
        'IdCajaOrigen',
        'StatusInventario',
        'idtiporecepcion',
        'Subir',
        'IdPreparado',
        'CantidadPreparado',
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdCapRecepcion';

    public function DetalleRecepcion()
    {
        return $this->hasMany(DatRecepcion::class, 'IdCapRecepcion', 'IdCapRecepcion');
    }

    public function StatusRecepcion()
    {
        return $this->hasOne(StatusRecepcion::class, 'IdStatusRecepcion', 'IdStatusRecepcion');
    }
}
