<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatMonederoAcumulado extends Model
{
    use HasFactory;
    protected $table = 'DatMonederoElectronico';
    protected $fillable = ['IdEncabezado', 'NumNomina', 'FechaExpiracion', 'FechaGenerado', 'Monedero', 'Subir', 'descargar', 'BatchGasto', 'MonederoGastado', 'MonederoPorGastar', 'FechaActual'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatMonedero';
}
