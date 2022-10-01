<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Articulo;
use App\Models\Tienda;
use App\Models\TransaccionTienda;

class TransaccionProductoController extends Controller
{
    public function TransaccionProducto(Request $request){
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $nomTienda = Tienda::where('IdTienda', $idTienda)
            ->value('NomTienda');

        $destinosTienda = TransaccionTienda::where('IdTienda', $idTienda)
            ->pluck('IdTiendaDestino');

        $tiendas = Tienda::where('Status', 0)
            ->whereIn('IdTienda', $destinosTienda)
            ->get();

        return view('TransaccionProducto.TransaccionProducto', compact('nomTienda', 'tiendas'));
    }

    public function BuscarArticuloTransaccion(Request $request){
        $codArticulo = $request->codArticulo;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $nomArticulo = DB::table('CatArticulos as a')
            ->leftJoin('DatInventario as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('b.IdTienda', $idTienda)
            ->where('a.CodArticulo', $codArticulo)
            ->where('a.Status', 0)
            ->value('a.NomArticulo');

        $stockArticulo = DB::table('CatArticulos as a')
            ->leftJoin('DatInventario as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('b.IdTienda', $idTienda)
            ->where('a.CodArticulo', $codArticulo)
            ->where('a.Status', 0)
            ->value('b.StockArticulo');

        return $nomArticulo . ' - ' . $stockArticulo;
    }

    public function GuardarTransaccion(Request $request){
        $idTiendaDestino = $request->idTiendaDestino;
        $codsArticulo = $request->CodArticulo;
        $cantsArticulo = $request->CantArticulo;

        return $request->all();

    }
}
