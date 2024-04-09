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
use App\Models\Caja;
use App\Models\CorreoTienda;
use App\Models\DatCaja;
use App\Models\DatCorteInvTmp;
use App\Models\DatInventario;
use App\Models\HistorialMovimientoProducto;

class StockTiendaController extends Controller
{
    public function ReporteStock(Request $request)
    {
        $codArticulo = $request->codArticulo;
        $radioBuscar = $request->radioBuscar;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $stocksPos = DB::table('DatInventario as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.IdTienda', $idTienda)
            ->where(function ($query) use (
                $codArticulo
            ) {
                $query->where('a.CodArticulo', 'like', '%' . $codArticulo . '%');
                $query->orWhere('b.NomArticulo', 'like', '%' . $codArticulo . '%');
            })
            ->where('a.StockArticulo', '>', 0)
            ->orderBy('b.NomArticulo')
            ->get();

        $stocksLess = DB::table('DatInventario as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.IdTienda', $idTienda)
            ->where(function ($query) use (
                $codArticulo
            ) {
                $query->where('a.CodArticulo', 'like', '%' . $codArticulo . '%');
                $query->orWhere('b.NomArticulo', 'like', '%' . $codArticulo . '%');
            })
            ->where('a.StockArticulo', '<=', 0)
            ->orderBy('b.NomArticulo')
            ->get();

        $stocks = [];
        foreach ($stocksPos as $item) {
            array_push($stocks, $item);
        }
        foreach ($stocksLess as $item) {
            array_push($stocks, $item);
        }

        $totalStock = InventarioTienda::where('IdTienda', $idTienda)
            ->where('CodArticulo', 'like', '%' . $codArticulo . '%')
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

    public function ReporteStockAdmin(Request $request)
    {
        $usuarioTienda = Auth::user()->usuarioTienda;
        $idTienda = $request->idTienda;

        if (!$usuarioTienda) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        if ($usuarioTienda->Todas == 0) {
            $tiendas = Tienda::where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas = Tienda::where('Status', 0)
                ->where('IdTienda', $usuarioTienda->IdTienda)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)
                ->where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }

        $stocksPos  = DatInventario::leftJoin('CatArticulos as b', 'b.CodArticulo', 'DatInventario.CodArticulo')
            ->where('IdTienda', $idTienda)
            ->where('DatInventario.StockArticulo', '>', 0)
            ->orderBy('b.NomArticulo')
            ->get();

        $stocksLess = DatInventario::leftJoin('CatArticulos as b', 'b.CodArticulo', 'DatInventario.CodArticulo')
            ->where('IdTienda', $idTienda)
            ->where('DatInventario.StockArticulo', '<=', 0)
            ->orderBy('b.NomArticulo')
            ->get();

        $stocks = [];
        foreach ($stocksPos as $item) {
            array_push($stocks, $item);
        }
        foreach ($stocksLess as $item) {
            array_push($stocks, $item);
        }

        return view('Stock.ReporteStockAdmin', compact('tiendas', 'idTienda', 'stocks'));
    }

    public function UpdateStockViewAdmin(Request $request)
    {
        $usuarioTienda = Auth::user()->usuarioTienda;
        $idTienda = $request->idTienda;

        if (!$usuarioTienda) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        if ($usuarioTienda->Todas == 0) {
            $tiendas = Tienda::where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas = Tienda::where('Status', 0)
                ->where('IdTienda', $usuarioTienda->IdTienda)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)
                ->where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }

        $stocks = DatInventario::leftJoin('CatArticulos as b', 'b.CodArticulo', 'DatInventario.CodArticulo')
            ->where('IdTienda', $idTienda)
            ->get();

        return view('Stock.UpdateStockAdmin', compact('tiendas', 'idTienda', 'stocks'));
    }

    public function UpdateStockAdmin($id, Request $request)
    {
        // return $id;
        $stocks = $request->stock;

        $stocksActual = DatInventario::leftJoin('CatArticulos as b', 'b.CodArticulo', 'DatInventario.CodArticulo')
            ->where('IdTienda', $id)
            ->get();

        try {
            $contReg = 0;

            $batch = DatCorteInvTmp::select(DB::raw('Max(CAST(Batch AS int)) as batch'))
                ->where('IdTienda', $id)
                ->value('batch');

            // Aqui se hace el ajuste de inventario
            foreach ($stocks as $codigo => $ajusteStock) {
                foreach ($stocksActual as $key => $stockActual) {
                    if ($codigo == $stockActual->CodArticulo && $ajusteStock != $stockActual->StockArticulo) {

                        $ajuste = $ajusteStock - $stockActual->StockArticulo;

                        DatCorteInvTmp::insert([
                            'IdTienda' => $id,
                            'IdCaja' => 1,
                            'Codigo' => $codigo,
                            'Cantidad' => $ajuste,
                            'Fecha_Creacion' => date('d-m-Y H:i:s'),
                            'Batch' => $batch + 1,
                            'StatusProcesado' => 0,
                            'IdMovimiento' => 16,
                        ]);

                        HistorialMovimientoProducto::insert([
                            'IdTienda' => $id,
                            'CodArticulo' => $codigo,
                            'CantArticulo' => $ajuste,
                            'FechaMovimiento' => date('d-m-Y H:i:s'),
                            'Referencia' => 'Ajuste de inventario',
                            'IdMovimiento' => 16,
                            'IdUsuario' => Auth::user()->IdUsuario,
                        ]);

                        $contReg = $contReg + 1;
                    }
                }
            }

            if ($contReg == 0) {
                return back()->with('msjdelete', 'No ha realizado ajustes de inventario');
            } else {
                $sp = "Execute Sp_Actualizar_Stock " . $id . ",1";
                DB::statement($sp);
            }
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
        return back()->with('msjAdd', 'Ajuste de inventario realizado con éxito');
    }
}
