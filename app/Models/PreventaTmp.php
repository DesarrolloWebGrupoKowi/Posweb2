<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreventaTmp extends Model
{
    use HasFactory;
    protected $table = 'DatVentaTmp';
    protected $fillable = [
        'IdDatVentaTmp', 
        'IdTienda', 
        'IdArticulo', 
        'CantArticulo', 
        'PrecioLista', 
        'PrecioVenta',
        'IdListaPrecio', 
        'IvaArticulo', 
        'SubTotalArticulo', 
        'ImporteArticulo',
        'MultiPago', 
        'IdPaquete',
        'Status',
        'IdDatPrecios'];
    public $timestamps = false;
}
