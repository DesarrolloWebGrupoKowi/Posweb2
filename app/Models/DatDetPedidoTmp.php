<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatDetPedidoTmp extends Model
{
    use HasFactory;
    protected $table = 'DatDetPedidoTmp';
    protected $fillable = ['IdDetPedidoTmp', 'IdTienda', 'IdArticulo', 'CantArticulo', 'IdListaPrecio', 'ImporteArticulo', 'PrecioArticulo', 'IvaArticulo', 'ImporteArticulo', 'Status'];
    public $timestamps = false;
}
