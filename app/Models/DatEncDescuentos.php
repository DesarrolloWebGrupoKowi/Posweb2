<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatEncDescuentos extends Model
{
    use HasFactory;
    protected $table = 'DatEncDescuentos';
    protected $fillable = [
        'NomDescuento',
        'TipoDescuento',
        'ImporteTotal',
        'FechaInicio',
        'FechaFin',
        'Status',
        'Descargar',
        'IdTienda',
        'IdPlaza',
        'FechaCreacion',
        'FechaDesactivar'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdEncDescuento';

    public function ArticulosDescuento()
    {
        return $this->hasMany(DatDetDescuentos::class, 'IdEncDescuento', 'IdEncDescuento');
    }
}
