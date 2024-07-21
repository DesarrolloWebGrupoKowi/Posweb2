<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatTransferencia extends Model
{
    use HasFactory;

    protected $table = 'DatTransferencia';
    public $timestamps = false;
    protected $primaryKey = 'IdDatTransferencia';
    protected $fillable = [
        'IdTransferencia',
        'IdCaja',
        'IdTiendaOrigen',
        'IdTiendaDestino',
        'FechaTransferencia',
        'IdUsuario',
        'Subir',
        'Anual',
        'Batch',
    ];

    public function Detalle()
    {
        return $this->hasMany(DatTransferenciaDetalle::class, 'IdTransferencia', 'IdTransferencia')
            ->leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatTransferenciaDetalle.CodArticulo');
    }
}
