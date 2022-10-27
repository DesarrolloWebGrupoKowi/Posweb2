<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Caja;

class DatCaja extends Model
{
    use HasFactory;
    protected $table = 'DatCajas';
    protected $fillable = ['IdTienda', 'IdCaja', 'Activa', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatCajas';

    public function Caja(){
        return $this->hasMany(Caja::class, 'IdCaja', 'IdCaja');
    }

    public function Encabezado(){
        return $this->hasMany(DatEncabezado::class, 'IdDatCaja', 'IdDatCajas');
    }

    public function TipoPago(){
        return $this->hasMany(Tienda::class, 'IdTienda', 'IdTienda');
    }
}
