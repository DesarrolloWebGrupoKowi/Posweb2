<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BajoStockMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tienda;
use App\Models\InventarioTienda;
use App\Models\Articulo;
use App\Models\CorreoTienda;

class StockTiendaController extends Controller
{
    public function ReporteStock(Request $request){
        $codArticulo = $request->codArticulo;
        $radioBuscar = $request->radioBuscar;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $stocks = DB::table('DatInventario as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.IdTienda', $idTienda)
            ->where('a.CodArticulo', 'like', '%'.$codArticulo.'%')
            ->orderBy('a.CodArticulo')
            ->get();

        $totalStock = InventarioTienda::where('IdTienda', $idTienda)
            ->where('CodArticulo', 'like', '%'.$codArticulo.'%')
            ->sum('StockArticulo');

        $articulosBajoInventario = DB::table('DatInventario as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->select('b.CodArticulo', 'b.NomArticulo', 'a.StockArticulo')
            ->where('IdTienda', $idTienda)
            ->where('a.StockArticulo', '<=', 5)
            ->get();

        /*//Enviar Correo alerta bajo stock no usada por el momento 
        if($articulosBajoInventario->count() > 0){
            try {
                $correoTienda = CorreoTienda::where('IdTienda', $idTienda)
                    ->where('Status', 0)
                    ->first();

                $correos = [
                    'ebosse@kowi.com.mx',
                    'soporte@kowi.com.mx'
                ];

                array_push($correos, $correoTienda->GerenteCorreo, $correoTienda->Supervisor, $correoTienda->AlmacenistaCorreo);

                $correos = array_filter($correos);

                $nomTienda = Tienda::where('IdTienda', $idTienda)
                    ->value('NomTienda');

                $subject = 'ALERTA BAJO INVENTARIO EN : ' . $nomTienda;

                Mail::to($correos)
                    ->send(new BajoStockMail($subject, $articulosBajoInventario));
                    
            } catch (\Throwable $th) {
            }
        }*/
            
        //return $articulosBajoInventario;

        return view('Stock.ReporteStock', compact('tienda', 'stocks', 'codArticulo', 'totalStock'));
    }
}
