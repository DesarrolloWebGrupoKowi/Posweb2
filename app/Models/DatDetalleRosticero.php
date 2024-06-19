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
        'subir'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdDatDetalleRosticero';
}
