<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonederoElectronico extends Model
{
    use HasFactory;
    protected $table = 'CatMonederoElectronico';
    protected $fillable = ['VigenciaMonedero', 'MaximoAcumulado', 'MonederoMultiplo', 'PesosPorMultiplo', 'IdGrupo', 'FechaCreacion', 'FechaEliminacion', 'IdUsuario', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdCatMonederoElectronico'; 
}
