<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Articulo;
use App\Models\Tienda;
use App\Models\TransaccionTienda;
use App\Models\InventarioTienda;
use App\Models\CapRecepcion;
use App\Models\DatRecepcion;
use App\Models\MovimientoProducto;
use App\Models\HistorialMovimientoProducto;

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
        
        $tienda = Tienda::where('IdTienda', $idTiendaDestino)
        ->select('NomTienda', 'Almacen')
        ->first();
        
        try {
            DB::beginTransaction();

            $capRecepcion = new CapRecepcion();
            $capRecepcion->FechaLlegada = date('d-m-Y H:i:s');
            $capRecepcion->PackingList = $tienda->NomTienda;
            $capRecepcion->Almacen = $tienda->Almacen;
            $capRecepcion->IdStatusRecepcion = 1;
            $capRecepcion->IdUsuario = Auth::user()->IdUsuario;
            $capRecepcion->save();

            foreach ($codsArticulo as $keyCodArticulo => $cantArticulo) {
                DatRecepcion::insert([
                    'IdCapRecepcion' => $capRecepcion->IdCapRecepcion,
                    'CodArticulo' => $keyCodArticulo,
                    'CantEnviada' => $cantArticulo,
                    'IdStatusRecepcion' => 1
                ]);

                HistorialMovimientoProducto::insert([
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'CodArticulo' => $keyCodArticulo,
                    'CantArticulo' => -$cantArticulo,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => $tienda->NomTienda,
                    'IdMovimiento' => 2,
                    'IdUsuario' => Auth::user()->IdUsuario
                ]);

                $stockArticulo = InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('CodArticulo', ''.$keyCodArticulo.'')
                    ->value('StockArticulo');

                InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('CodArticulo', ''.$keyCodArticulo.'')
                    ->update([
                        'StockArticulo' => $stockArticulo - $cantArticulo
                    ]);

                }
                
                DB::commit();
                return back()->with('msjAdd', 'Transferencia Exitosa!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

    }
}
