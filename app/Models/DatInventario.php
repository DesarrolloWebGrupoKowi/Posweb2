<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;

class DatInventario extends Model
{
    use HasFactory;
    protected $connection = 'server';
    protected $table = 'DatInventario';
    protected $fillable = ['IdTienda', 'CodArticulo', 'StockArticulo'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatInventario';

    public function Articulo()
    {
        return $this->hasOne(Articulo::class, 'CodArticulo', 'CodArticulo');
    }
}
