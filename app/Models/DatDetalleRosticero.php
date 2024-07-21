<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatDetalleRosticero extends Model
{
    use HasFactory;
    protected $table = 'DatDetalleRosticero';
    protected $fillable = [
        'IdRosticero',
        'CodigoArticulo',
        'Cantidad',
        'FechaCreacion',
        'CodigoEtiqueta',
        'subir',
        'STATUS',
        'Linea',
        'Vendida',
        'CodigoEtiquetaRef',
        'CantMermaRecalentado '
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdDatDetalleRosticero';

    public function Fechas()
    {
        return $this->hasMany(DatDetalleRosticero::class, 'CodigoEtiqueta', 'CodigoEtiqueta')
            ->leftJoin('DatRosticero', 'DatRosticero.IdRosticero', 'DatDetalleRosticero.IdRosticero')
            ->select(
                'CodigoEtiqueta',
                'IdDatDetalleRosticero',
                'DatRosticero.IdRosticero',
                'DatRosticero.Fecha'
            )
            ->where('DatDetalleRosticero.Status', 0)
            ->where('DatDetalleRosticero.Vendida', 1);
    }
}
