<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatMonederoAcumulado extends Model
{
    use HasFactory;
    protected $table = 'DatMonederoElectronico';
    protected $fillable = ['IdEncabezado', 'NumNomina', 'FechaExpiracion', 'FechaGenerado', 'MonederoGenerado', 'MonederoGastado', 'MonederoPorGastar'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatMonedero'; 
}
