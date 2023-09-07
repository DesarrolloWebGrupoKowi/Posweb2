<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatAsignacionPreparados extends Model
{
    use HasFactory;
    // protected $connection = 'server';
    protected $table = 'DatAsignacionPreparados';
    public $timestamps = false;
    protected $primaryKey = 'IdDatAsignacionPreparado';

    public function Detalle()
    {
        return $this->hasMany(CatPreparado::class, 'IdPreparado', 'IdPreparado')
            ->select(
                'CatPreparado.IdPreparado',
                'CatArticulos.IdArticulo',
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                'DatPreparados.CantidadPaquete',
                'DatPreparados.CantidadFormula'
            )
            ->leftjoin('DatPreparados', 'DatPreparados.IdPreparado', 'CatPreparado.IdPreparado')
            ->leftjoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo');
    }
}
