<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatCorteInvTmp extends Model
{
    use HasFactory;
    // protected $connection = 'server';
    protected $table = 'DatCorteInvTmp';
    protected $fillable = ['IdTienda', 'IdCaja', 'Codigo', 'Cantidad', 'Fecha_Creacion', 'Batch', 'StatusProcesado', 'IdMovimiento'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatCorteInvTmp';
}
