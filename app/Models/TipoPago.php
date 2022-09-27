<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CorteTienda;
use App\Models\Articulo;
use App\Models\TipoPagoTienda;

class TipoPago extends Model
{
    use HasFactory;
    protected $table = 'CatTipoPago';
    protected $fillable = [
                            'NomTipoPago',
                            'ClaveSat',
                            'Status'
                        ];
    public $timestamps = false;
    protected $primaryKey = 'IdTipoPago';

    public function CorteTienda(){
        return $this->belongsToMany(Articulo::class, CorteTienda::class, 'IdTipoPago', 'IdArticulo')
                    ->select([
                            DB::raw("CatArticulos.CodArticulo"),
                            DB::raw("CatArticulos.NomArticulo"), 
                            DB::raw("sum(DatCortesTienda.CantArticulo) as CantArticulo"), 
                            DB::raw("DatCortesTienda.PrecioArticulo as PrecioArticulo"),
                            DB::raw("sum(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo"),
                            DB::raw("sum(DatCortesTienda.IvaArticulo) as IvaArticulo"),
                            DB::raw("sum(DatCortesTienda.ImporteArticulo) as ImporteArticulo")
                            ])
                    ->whereDate('FechaVenta', date('Y-m-d'))
                    ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('StatusVenta', 0)
                    ->groupBy(
                                'DatCortesTienda.IdTipoPago', 'DatCortesTienda.IdArticulo', 
                                'DatCortesTienda.PrecioArticulo', 'CatArticulos.NomArticulo',
                                'CatArticulos.CodArticulo'
                            );
    }

    public function CortePago(){
        return $this->hasMany(CorteTienda::class, 'IdTipoPago', 'IdTipoPago')
                    ->select('IdCortesTienda', 'IdTipoPago', 'ImporteArticulo', 'IdSolicitudFactura');
    }
}
