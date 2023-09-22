<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatPreparado extends Model
{
    use HasFactory;
    // protected $connection = 'server';
    protected $table = 'CatPreparado';
    public $timestamps = false;
    protected $primaryKey = 'IdPreparado';

    public function Detalle()
    {
        return $this->hasMany(DatPreparados::class, 'IdPreparado', 'IdPreparado')
            ->leftjoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo');
    }

    public function Tiendas()
    {
        return $this->hasMany(DatAsignacionPreparados::class, 'IdPreparado', 'preparado')
            ->leftjoin('CatTiendas', 'CatTiendas.IdTienda', 'DatAsignacionPreparados.IdTienda');
    }
}
