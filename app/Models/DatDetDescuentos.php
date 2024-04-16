<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatDetDescuentos extends Model
{
    use HasFactory;
    protected $table = 'DatDetDescuentos';
    protected $fillable = [
        'IdEncDescuento',
        'IdArticulo',
        'CantArticulo',
        'PrecioDescuento',
        'IdListaPrecio'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdDetDescuento';
}
