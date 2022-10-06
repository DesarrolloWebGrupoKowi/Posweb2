<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DatDetalle;
use App\Models\Articulo;
use App\Models\DatTipoPago;
use App\Models\SolicitudFactura;
use App\Models\Tienda;

class DatEncabezado extends Model
{
    use HasFactory;
    protected $table = 'DatEncabezado';

    protected $fillable = [
        'IdEncabezado',
        'IdTienda',
        'IdDatCaja', 
        'IdTicket', 
        'FechaVenta', 
        'IdUsuario', 
        'SubTotal', 
        'Iva', 
        'MonederoDescuento', 
        'Promocion', 
        'ImporteVenta', 
        'MonederoGenerado', 
        'StatusVenta', 
        'MotivoCancel', 
        'FechaCancelacion', 
        'StatusRed', 
        'FechaCreacion', 
        'SolicitudFE', 
        'IdMetodoPago', 
        'IdUsoCFDI', 
        'IdFormaPago', 
        'FolioCupon',
        'NumNomina',
        'IdTipoCliente'
    ];

    protected $primaryKey = 'IdEncabezado';
    public $timestamps = false;

    public function detalle(){
        return $this->hasMany(DatDetalle::class, 'IdEncabezado', 'IdEncabezado');
    }

    public function TipoPago(){
        return $this->belongsToMany(TipoPago::class, DatTipoPago::class, 'IdEncabezado', 'IdTipoPago')
                    ->withPivot('Pago', 'Restante')
                    ->as('PivotPago');
    }

    public function SolicitudFactura(){
        return $this->hasMany(SolicitudFactura::class, 'IdEncabezado', 'IdEncabezado');
    }

    public function Tienda(){
        return $this->hasOne(Tienda::class, 'IdTienda', 'IdTienda');
    }
}
