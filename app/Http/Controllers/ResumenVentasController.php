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

        $idEncabezados = DatEncabezado::where('IdTienda', $idTienda)
            ->where('StatusVenta', 0)
            ->pluck('IdEncabezado');

        $idListasPrecio = ListaPrecioTienda::where('IdTienda', $idTienda)
            ->whereIn('IdListaPrecio', function ($query) use ($idEncabezados){
                $query->selectRaw('distinct(IdListaPrecio)')
                    ->from('DatDetalle')
                    ->whereIn('IdEncabezado', $idEncabezados);
            })
            ->pluck('IdListaPrecio');

        $nomListasPrecio = ListaPrecio::whereIn('IdListaPrecio', $idListasPrecio)
            ->orderBy('IdListaPrecio')
            ->pluck('NomListaPrecio');

        $totalIngresos = DatDetalle::whereIn('IdListaPrecio', $idListasPrecio)
            ->whereIn('IdEncabezado', $idEncabezados)
            ->selectRaw('SUM(DatDetalle.ImporteArticulo) as ImporteTotal')
            ->groupBy('IdListaPrecio')
            ->orderBy('IdListaPrecio')
            ->pluck('ImporteTotal');

        return view('ResumenVentas.ResumenVentas', compact('nomListasPrecio', 'totalIngresos', 'primerDiaMesActual', 'tiendas', 'idTienda'));
    }
}
