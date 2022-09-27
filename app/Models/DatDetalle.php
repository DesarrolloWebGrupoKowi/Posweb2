<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatDetalle extends Model
{
    use HasFactory;
    protected $table = 'DatDetalle';

    protected $fillable = [
        'IdEncabezado',
        'IdArticulo', 
        'CantArticulo', 
        'PrecioArticulo', 
        'IdListaPrecio', 
        'PrecioRecorte', 
        'CapturaManual', 
        'ImporteArticulo', 
        'IvaArticulo', 
        'SubTotalArticulo', 
        'IdPedido'
    ];

    protected $primaryKey = 'IdDetalle';
    public $timestamps = false;
}
