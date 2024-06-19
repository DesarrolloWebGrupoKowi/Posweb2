<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
    // protected $primaryKey = 'IdSolicitudFactura';

    public function FacturaLocal()
    {
        return $this->belongsToMany(Articulo::class, CorteTienda::class, 'IdSolicitudFactura', 'IdArticulo', 'IdSolicitudFactura')
            ->select('CatArticulos.CodArticulo', 'CatArticulos.NomArticulo')
            ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'DatCortesTienda.IdEncabezado')
            ->withPivot('CantArticulo', 'PrecioArticulo', 'ImporteArticulo', 'IvaArticulo')
            ->as('PivotDetalle');
    }

    public function Factura()
    {
        return $this->belongsToMany(Articulo::class, CorteTienda::class, 'IdSolicitudFactura', 'IdArticulo', 'IdSolicitudFactura')
            ->select(
                [
                    DB::raw("CatArticulos.CodArticulo"),
                    DB::raw("CatArticulos.NomArticulo"),
                    DB::raw("DatCortesTienda.IdListaPrecio"),
                    DB::raw("DatCortesTienda.IdTipoPago"),
                    DB::raw("DatCortesTienda.Source_Transaction_Identifier"),
                    // DB::raw("XXH2.STATUS as STATUS"),
                    // DB::raw("XXH2.MENSAJE_ERROR as MENSAJE_ERROR"),
                    // DB::raw("XXH2.Batch_Name as Batch_Name"),
                ]
            )
            ->where('DatCortesTienda.StatusVenta', 0)
            ->withPivot('CantArticulo', 'PrecioArticulo', 'ImporteArticulo', 'IvaArticulo')
            ->as('PivotDetalle');
    }

    public function ConstanciaSituacionFiscal()
    {
        return $this->hasOne(ConstanciaSituacionFiscal::class, 'IdSolicitudFactura', 'IdSolicitudFactura');
    }

    public function CorteTiendaOracle()
    {
        return $this->belongsToMany(Articulo::class, CorteTienda::class, 'Bill_To', 'IdArticulo', 'Bill_To')
            ->select([
                DB::raw("CatArticulos.CodArticulo"),
                DB::raw("CatArticulos.NomArticulo"),
                DB::raw("DatCortesTienda.IdListaPrecio"),
                DB::raw("DatCortesTienda.IdTipoPago"),
                DB::raw("DatCortesTienda.Source_Transaction_Identifier"),
                DB::raw("sum(DatCortesTienda.CantArticulo) as CantArticulo"),
                DB::raw("DatCortesTienda.PrecioArticulo as PrecioArticulo"),
                DB::raw("sum(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo"),
                DB::raw("sum(DatCortesTienda.IvaArticulo) as IvaArticulo"),
                DB::raw("sum(DatCortesTienda.ImporteArticulo) as ImporteArticulo"),
                DB::raw("CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.STATUS as STATUS"),
                DB::raw("CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.MENSAJE_ERROR as MENSAJE_ERROR"),
                DB::raw("CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.Batch_Name as Batch_Name"),
            ])
            ->groupBy(
                'DatCortesTienda.Bill_To',
                'DatCortesTienda.IdTipoPago',
                'DatCortesTienda.IdListaPrecio',
                'DatCortesTienda.IdArticulo',
                'DatCortesTienda.PrecioArticulo',
                'CatArticulos.NomArticulo',
                'CatArticulos.CodArticulo',
                'DatCortesTienda.Source_Transaction_Identifier',
                'CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.STATUS',
                'CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.MENSAJE_ERROR',
                'CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.Batch_Name'
            );
    }

    public function PedidoOracle()
    {
        return $this->hasMany(CorteTienda::class, 'IdSolicitudFactura', 'IdSolicitudFactura');
    }
}
