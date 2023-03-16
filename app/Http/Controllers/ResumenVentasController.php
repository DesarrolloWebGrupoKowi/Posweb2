<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\ListaPrecio;
use App\Models\ListaPrecioTienda;
use App\Models\DatDetalle;
use App\Models\DatEncabezado;
use App\Models\Tienda;

use Illuminate\Http\Request;

class ResumenVentasController extends Controller
{
    public function ResumenVentas(Request $request){
        $tiendas = Tienda::where('Status', 0)
            ->get();

        $idTienda = $request->idTienda;

        $primerDiaMesActual = '01' . '-' . date('m') . '-' . date('Y'); // sacar el primer dia del mes actual

        $idListasPrecio = ListaPrecioTienda::where('IdTienda', $idTienda)
            ->whereIn('IdListaPrecio', function ($query) use ($primerDiaMesActual, $idTienda){
                $query->selectRaw('distinct(IdListaPrecio)')
                    ->from('DatDetalle')
                    ->whereIn('IdEncabezado', function ($encabezado) use ($primerDiaMesActual, $idTienda){
                        $encabezado->select('IdEncabezado')
                            ->where('StatusVenta', 0)
                            ->where('IdTienda', $idTienda)
                            ->whereRaw("cast(FechaVenta as date) >= '". $primerDiaMesActual ."' ")
                            ->from('DatEncabezado');
                    });
            })
            ->pluck('IdListaPrecio');

        $nomListasPrecio = ListaPrecio::whereIn('IdListaPrecio', $idListasPrecio)
            ->orderBy('IdListaPrecio')
            ->pluck('NomListaPrecio');

        $totalIngresos = DatDetalle::whereIn('IdListaPrecio', $idListasPrecio)
            ->whereIn('IdEncabezado', function ($encabezado) use ($primerDiaMesActual, $idTienda){
                $encabezado->select('IdEncabezado')
                    ->where('StatusVenta', 0)
                    ->where('IdTienda', $idTienda)
                    ->whereRaw("cast(FechaVenta as date) >= '". $primerDiaMesActual ."' ")
                    ->from('DatEncabezado');
                })
            ->selectRaw('SUM(DatDetalle.ImporteArticulo) as ImporteTotal')
            ->groupBy('IdListaPrecio')
            ->orderBy('IdListaPrecio')
            ->pluck('ImporteTotal');

        return view('ResumenVentas.ResumenVentas', compact('nomListasPrecio', 'totalIngresos', 'primerDiaMesActual', 'tiendas', 'idTienda'));
    }
}
