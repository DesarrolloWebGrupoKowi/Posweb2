<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatPaquete extends Model
{
    use HasFactory;
    protected $table = 'DatPaquetes';
    protected $fillable = ['IdPaquete', 'IdListaPrecio', 'PrecioArticulo', 'CodArticulo', 'CantArticulo', 'ImporteArticulo'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatPaquetes';
}
