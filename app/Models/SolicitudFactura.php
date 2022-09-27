<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DatEncabezado;
use App\Models\DatDetalle;
use App\Models\Articulo;
use App\Models\CorteTienda;
use App\Models\ConstanciaSituacionFiscal;

class SolicitudFactura extends Model
{
    use HasFactory;
    protected $table = 'SolicitudFactura';
    protected $fillable = [
        'IdSolicitudFactura',
        'FechaSolicitud', 
        'IdEncabezado', 
        'IdTienda', 
        'IdClienteCloud', 
        'TipoPersona', 
        'RFC',
        'NomCliente', 
        'Calle', 
        'NumExt', 
        'NumInt',
        'Colonia', 
        'Ciudad',
        'Municipio',
        'Estado',
        'Pais',
        'CodigoPostal',
        'Email',
        'Telefono',
        'IdUsuarioSolicitud',
        'IdUsuarioCliente',
        'Fecha_Cliente',
        'Bill_To',
        'UsoCFDI'
    ];
    public $timestamps = false;
    protected $primaryKey = 'IdSolicitudFactura';

    public function Factura(){
        return $this->belongsToMany(Articulo::class, CorteTienda::class, 'IdSolicitudFactura', 'IdArticulo', 'IdSolicitudFactura')
                    ->select('CatArticulos.CodArticulo','CatArticulos.NomArticulo')
                    ->withPivot('CantArticulo', 'PrecioArticulo', 'ImporteArticulo', 'IvaArticulo')
                    ->as('PivotDetalle');
    }

    public function ConstanciaSituacionFiscal(){
        return $this->hasOne(ConstanciaSituacionFiscal::class, 'IdSolicitudFactura', 'IdSolicitudFactura');
    }
}
