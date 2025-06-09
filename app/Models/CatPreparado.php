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
            ->leftjoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
            ->leftJoin('DatPrecios', [['CatArticulos.CodArticulo', 'DatPrecios.CodArticulo'], ['DatPreparados.IDLISTAPRECIO', 'DatPrecios.IdListaPrecio']]);
    }

    public function Tiendas()
    {
        return $this->hasMany(DatAsignacionPreparados::class, 'IdPreparado', 'preparado')
            ->leftjoin('CatTiendas', 'CatTiendas.IdTienda', 'DatAsignacionPreparados.IdTienda');
            // ->leftjoin(
            //     'CapRecepcion',
            //     function ($join) {
            //         $join->on('DatAsignacionPreparados.IdPreparado', '=', 'CapRecepcion.IdPreparado')
            //             ->on('DatAsignacionPreparados.IdTienda', '=', 'CapRecepcion.IdTiendaDestino');
            //     }
            // );
    }
}
