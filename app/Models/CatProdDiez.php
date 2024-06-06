<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatProdDiez extends Model
{
    use HasFactory;
    protected $table = 'CatProdDiez';
    protected $fillable = [
        'CodArticulo',
        'Cantidad_Ini',
        'Cantidad_Fin',
        'IdListaPrecio',
        'IdUsuario',
        'Creacion',
        'Status'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdEncDescuento';
}
