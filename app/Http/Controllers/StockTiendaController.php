<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tienda;
use App\Models\InventarioTienda;
use App\Models\Articulo;

class StockTiendaController extends Controller
{
    public function ReporteStock(Request $request){
        $codArticulo = $request->codArticulo;
        $radioBuscar = $request->radioBuscar;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $stocks = InventarioTienda::with('Articulo')
            ->where('IdTienda', $idTienda)
            ->where('CodArticulo', 'like', '%'.$codArticulo.'%')
            ->orderBy('CodArticulo')
            ->get();

        $totalStock = InventarioTienda::where('IdTienda', $idTienda)
            ->where('CodArticulo', 'like', '%'.$codArticulo.'%')
            ->sum('StockArticulo');

        $articulosBajoInventario = DB::table('DatInventario as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('IdTienda', $idTienda)
            ->where('a.StockArticulo', '<=', 5)
            ->pluck('a.StockArticulo', 'b.NomArticulo');

        //return $articulosBajoInventario;

        $asunto = '**TEST POSWEB2** BAJO INVENTARIO EN TIENDA: '. $tienda->NomTienda;
        $mensaje = 'Los Articulos '. $articulosBajoInventario.' Tienen Bajo Inventario ';
    
        try {
            DB::beginTransaction();

            DB::statement("Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx', '".$asunto."', '".$mensaje."'");

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return view('Stock.ReporteStock', compact('tienda', 'stocks', 'codArticulo', 'totalStock'));
        }

        //return $articulosBajoInventario;

        return view('Stock.ReporteStock', compact('tienda', 'stocks', 'codArticulo', 'totalStock'));
    }
}
