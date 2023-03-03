<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ClienteCloud;
use App\Models\Articulo;
use App\Models\CorteTienda;

class ClienteCloudTienda extends Model
{
    use HasFactory;
    protected $table = 'DatClientesCloudTienda';
    protected $fillable = [
        'IdDatClientesCloudTienda',
        'IdTienda',
        'IdClienteCloud',
        'TipoCliente',
        'Ship_To',
        'Bill_To',
        'Codigo_Envio',
        'Direccion',
        'Locacion',
        'Pais',
        'Ciudad',
        'Codigo_Postal',
        'IdListaPrecio',
        'IdTipoPago',
        'IdTipoNomina'
    ];
                        
    public $timestamps = false;

    public function Customer(){
        return $this->hasMany(ClienteCloud::class, 'IdClienteCloud', 'IdClienteCloud');
    }

    public function CorteTienda(){        
        return $this->belongsToMany(Articulo::class, CorteTienda::class, 'Bill_To', 'IdArticulo', 'Bill_To')
            ->select([
                DB::raw("CatArticulos.CodArticulo"),
                DB::raw("CatArticulos.NomArticulo"), 
                DB::raw("DatCortesTienda.IdListaPrecio"),
                DB::raw("DatCortesTienda.IdTipoPago"),
                DB::raw("DatCortesTienda.IdCortesTienda"),
                DB::raw("sum(DatCortesTienda.CantArticulo) as CantArticulo"), 
                DB::raw("DatCortesTienda.PrecioArticulo as PrecioArticulo"),
                DB::raw("sum(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo"),
                DB::raw("sum(DatCortesTienda.IvaArticulo) as IvaArticulo"),
                DB::raw("sum(DatCortesTienda.ImporteArticulo) as ImporteArticulo")
            ])
            ->groupBy(
                'DatCortesTienda.Bill_To', 'DatCortesTienda.IdTipoPago', 'DatCortesTienda.IdCortesTienda', 
                'DatCortesTienda.IdListaPrecio', 'DatCortesTienda.IdArticulo',  
                'DatCortesTienda.PrecioArticulo', 'CatArticulos.NomArticulo',
                'CatArticulos.CodArticulo'
            );
    }

    public function CorteTiendaOracle(){        
        return $this->belongsToMany(Articulo::class, CorteTienda::class, 'Bill_To', 'IdArticulo', 'Bill_To')
            ->select([
                DB::raw("CatArticulos.CodArticulo"),
                DB::raw("CatArticulos.NomArticulo"), 
                DB::raw("DatCortesTienda.IdListaPrecio"),
                DB::raw("DatCortesTienda.IdTipoPago"),
                DB::raw("DatCortesTienda.Source_Transaction_Identifier"),
                DB::raw("DatCortesTienda.IdCortesTienda"),
                DB::raw("sum(DatCortesTienda.CantArticulo) as CantArticulo"), 
                DB::raw("DatCortesTienda.PrecioArticulo as PrecioArticulo"),
                DB::raw("sum(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo"),
                DB::raw("sum(DatCortesTienda.IvaArticulo) as IvaArticulo"),
                DB::raw("sum(DatCortesTienda.ImporteArticulo) as ImporteArticulo"),
                DB::raw("CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.STATUS as STATUS"), 
                DB::raw("CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.MENSAJE_ERROR as MENSAJE_ERROR"), 
                DB::raw("CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.Batch_Name as Batch_Name")
            ])
            ->groupBy(
                'DatCortesTienda.Bill_To', 'DatCortesTienda.IdTipoPago', 'DatCortesTienda.IdCortesTienda', 
                'DatCortesTienda.IdListaPrecio', 'DatCortesTienda.IdArticulo',  
                'DatCortesTienda.PrecioArticulo', 'CatArticulos.NomArticulo',
                'CatArticulos.CodArticulo', 'DatCortesTienda.Source_Transaction_Identifier', 'CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.STATUS',
                'CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.MENSAJE_ERROR', 'CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS.Batch_Name'
            );
    }

    public function PedidoOracle(){
        return $this->hasMany(CorteTienda::class, 'Bill_To', 'Bill_To');
    }
}
