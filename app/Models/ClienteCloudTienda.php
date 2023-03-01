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
    protected $fillable = ['IdDatClientesCloudTienda',
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
                DB::raw("sum(DatCortesTienda.CantArticulo) as CantArticulo"), 
                DB::raw("DatCortesTienda.PrecioArticulo as PrecioArticulo"),
                DB::raw("sum(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo"),
                DB::raw("sum(DatCortesTienda.IvaArticulo) as IvaArticulo"),
                DB::raw("sum(DatCortesTienda.ImporteArticulo) as ImporteArticulo")
            ])
            ->groupBy(
                'DatCortesTienda.Bill_To', 'DatCortesTienda.IdTipoPago', 'DatCortesTienda.IdListaPrecio', 'DatCortesTienda.IdArticulo',  
                'DatCortesTienda.PrecioArticulo', 'CatArticulos.NomArticulo',
                'CatArticulos.CodArticulo'
            );
    }
}
