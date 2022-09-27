<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DatDetalle;
use App\Models\DatEncabezado;

class MovimientoMonederoElectronico extends Model
{
    use HasFactory;
    protected $table = 'DatMovimientosMonederoElectronico';
    protected $fillable = ['NumNomina', 'IdEncabezado', 'FechaMovimiento', 'Monedero'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatMovMonedero'; 

    public function Encabezado(){
        return $this->hasMany(DatEncabezado::class, 'IdEncabezado', 'IdEncabezado');
    }

    public function Detalle(){
        return $this->hasMany(DatDetalle::class, 'IdEncabezado', 'IdEncabezado');
    }
}
