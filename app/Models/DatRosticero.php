<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatRosticero extends Model
{
    use HasFactory;
    protected $table = 'DatRosticero';
    protected $fillable = [
        'IdRosticero',
        'CodigoMatPrima',
        'CantidadMatPrima',
        'CodigoVenta',
        'CantidadVenta',
        'IdTienda',
        'IdCaja',
        'Fecha',
        'MermaStnd',
        'MermaReal',
        'Disponible',
        'subir',
        'Anual',
        'IdUsuario',
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdDatRosticero';

    public function Detalle()
    {
        return $this->hasMany(DatDetalleRosticero::class, 'IdRosticero', 'IdRosticero')
            ->leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatDetalleRosticero.CodigoArticulo');
    }

    public function Lotes()
    {
        return $this->hasMany(ItemCloudTable::class, 'ITEM_NUMBER', 'CodigoMatPrima');
    }
}
