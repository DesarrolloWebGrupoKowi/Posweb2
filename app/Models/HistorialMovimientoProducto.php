<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialMovimientoProducto extends Model
{
    use HasFactory;
    protected $connection = 'server';
    protected $table = 'DatHistorialMovimientos';
    protected $fillable = ['IdTienda', 'CodArticulo', 'CantArticulo', 'FechaMovimiento', 'Referencia', 'IdMovimiento', 'IdUsuario'];
    public $timestamps = false;
    protected $primaryKey = 'IdDatHistorialMovimientos'; 
}
