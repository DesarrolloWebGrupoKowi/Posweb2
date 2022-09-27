<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;

class DatDetPedido extends Model
{
    use HasFactory;
    protected $table = 'DatDetPedido';
    protected $fillable = ['IdPedido', 'IdArticulo', 'CantArticulo', 'SubTotalArticulo', 'IvaArticulo', 'ImporteArticulo', 'PrecioArticulo'];
    public $timestamps = false; 
    protected $primaryKey = 'IdDetPedido';

    public function Articulo(){
        return $this->hasOne(Articulo::class, 'IdArticulo', 'IdArticulo');
    }
}
