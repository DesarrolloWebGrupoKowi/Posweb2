<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;

class CorteTienda extends Model
{
    use HasFactory;
    protected $table = 'DatCortesTienda';
    protected $fillable = ['IdCortesTienda',
                        'IdEncabezado',
                        'IdTienda',
                        'FechaVenta',
                        'ImporteTotal',
                        'IdTipoPago',
                        'IdDetalle',
                        'IdListaPrecio',
                        'IdArticulo',
                        'CantArticulo',
                        'PrecioArticulo',
                        'SubTotalArticulo',
                        'IvaArticulo',
                        'ImporteArticulo',
                        'NumNomina',
                        'StatusVenta',
                        'Bill_To',
                        'StatusVenta',
                        'FechaVenta'];
    public $timestamps = false;
    

    public function Empleado(){
        return $this->hasOne(Empleado::class, 'NumNomina', 'NumNomina');
    }
}
