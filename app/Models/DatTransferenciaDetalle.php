<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatTransferenciaDetalle extends Model
{
    use HasFactory;

    protected $table = 'DatTransferenciaDetalle';
    public $timestamps = false;
    protected $primaryKey = 'IdDatTransferenciaDetalle';
    protected $fillable = [
        'IdTransferencia',
        'CodArticulo',
        'CantidadTrasferencia',
    ];
}
