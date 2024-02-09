<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CapRecepcionLocal;
use App\Models\CatPreparado;
use App\Models\DatAsignacionPreparados;
use App\Models\DatCaja;
use App\Models\DatPreparados;
use App\Models\DatRecepcionLocal;
use App\Models\HistorialMovimientoProductoLocal;
use App\Models\InventarioTienda;
use App\Models\Tienda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsignarPreparadosController extends Controller
{
    public function Preparados(Request $request)
    {
        $fecha = $request->fecha;
        if ($request->fecha) {
            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
        }

        // return $fecha;

        $preparados = [];

        if ($fecha) {
            $preparados = CatPreparado::with('Detalle', 'Tiendas')
                ->select(
                    'CatPreparado.IdPreparado',
                    'CatPreparado.Nombre',
                    'CatPreparado.Fecha',
                    'CatPreparado.Cantidad',
                    'CatPreparado.IdCatStatusPreparado',
                    'CatPreparado.preparado',
                    DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada')
                )
                ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.preparado')
                ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
                ->where('IdCatStatusPreparado', '=', 2)
                ->whereDate('CatPreparado.Fecha', $fecha)
                // ->orWhere('IdCatStatusPreparado', 3)
                ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Fecha', 'CatPreparado.Cantidad', 'CatPreparado.IdCatStatusPreparado', 'CatPreparado.preparado')
                ->orderBy('CatPreparado.Fecha', 'DESC')
                ->paginate(10);
        } else {
            $preparados = CatPreparado::with('Detalle', 'Tiendas')
                ->select(
                    'CatPreparado.IdPreparado',
                    'CatPreparado.Nombre',
                    'CatPreparado.Fecha',
                    'CatPreparado.Cantidad',
                    'CatPreparado.IdCatStatusPreparado',
                    'CatPreparado.preparado',
                    DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada')
                )
                ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.preparado')
                ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
                ->where('IdCatStatusPreparado', '=', 2)
                // ->orWhere('IdCatStatusPreparado', 3)
                ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Fecha', 'CatPreparado.Cantidad', 'CatPreparado.IdCatStatusPreparado', 'CatPreparado.preparado')
                ->orderBy('CatPreparado.Fecha', 'DESC')
                ->paginate(10);
        }

        $tiendas = Tienda::select('IdTienda', 'NomTienda')->get();

        return view('AsignarPreparados.indexNewDesign', compact('preparados', 'tiendas', 'fecha'));
    }

    public function RegresarPreparado($idPreparado)
    {
        CatPreparado::where('IdPreparado', $idPreparado)->update([
            'IdCatStatusPreparado' => 1,
        ]);

        return back();
    }

    public function FinalizarPreparado($idPreparado)
    {
        // CatPreparado::where('IdPreparado', $idPreparado)->first();
        try {
            DB::beginTransaction();

            $detalle = DatPreparados::where('IdPreparado', $idPreparado)->get();
            foreach ($detalle as $item) {
                $stock = InventarioTienda::leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatInventario.CodArticulo')
                    ->where('CatArticulos.IdArticulo', $item->IdArticulo)
                    ->value('StockArticulo');

                // IdArticulo

                if ($stock - $item->CantidadPaquete < 0) {
                    $articulo = Articulo::where('IdArticulo', $item->IdArticulo)->first();
                    DB::rollBack();
                    return back()->with('msjdelete', 'Inventario insuficiente para el articulo: ' . $articulo->NomArticulo);
                }

                InventarioTienda::leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatInventario.CodArticulo')
                    ->where('CatArticulos.IdArticulo', $item->IdArticulo)
                    ->update([
                        'StockArticulo' => $stock - $item->CantidadPaquete,
                    ]);
            }

            CatPreparado::where('IdPreparado', $idPreparado)->update([
                'IdCatStatusPreparado' => 3,
            ]);

            DB::commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('msjdelete', 'Ha ocuarrido un error, intente de nuevo');
        }
    }

    // La asignacion de tienda, se hace de tienda a tienda
    public function AsignarTienda($idPreparado, Request $request)
    {
        // Creando asignacion de preparados
        $asignacion = new DatAsignacionPreparados();
        // Aqui pones el idPreparado generado manualmente al crear el preparado
        $asignacion->IdPreparado = $request->preparado;
        $asignacion->IdTienda = $request->idTienda;
        $asignacion->CantidadEnvio = $request->cantidad;
        $asignacion->save();

        // // Traemos el detalle de los productos del preparado
        $detalleArticulos = DatPreparados::select('DatPreparados.IdArticulo', 'CatArticulos.CodArticulo', 'DatPreparados.CantidadFormula')
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
            ->where('IdPreparado', $idPreparado)
            ->get();

        if (!count($detalleArticulos)) {
            return back()->with('msjdelete', 'El preparado esta vacio.');
        }

        // Creando transaccion de productos
        $idTiendaDestino = $request->idTienda;
        $cantidadEnviada = $request->cantidad;

        try {
            // Seleccionamos el nombre del preparado
            $nombrePreparado = CatPreparado::where('IdPreparado', $idPreparado)
                ->value('Nombre');

            // Seleccionamos la informacion de la tienda
            $almacen = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Almacen');

            $IdTD = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('IdTienda');

            $nomDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('NomTienda');

            $correoDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Correo');

            $nomOrigenTienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->value('NomTienda');

            $idCapRecepcion = DB::table('CapRecepcion')
                ->max('IdCapRecepcion') + 1;

            $numCaja = DatCaja::where('Status', 0)
                ->where('Activa', 0)
                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->value('IdCaja');

            $idRecepcion = Auth::user()->usuarioTienda->IdTienda . $numCaja . $idCapRecepcion;

            // Aqui realizamos la transaccion del producto
            DB::beginTransaction();
            // DB::connection()->beginTransaction();
            // DB::connection('server')->beginTransaction();

            // Creamos una recepcion
            $capRecepcion = new CapRecepcionLocal();
            $capRecepcion->IdRecepcionLocal = $idRecepcion;
            $capRecepcion->FechaLlegada = date('d-m-Y H:i:s');
            $capRecepcion->PackingList = $nombrePreparado;
            $capRecepcion->IdTiendaOrigen = Auth::user()->usuarioTienda->IdTienda;
            $capRecepcion->IdTiendaDestino = $IdTD;
            $capRecepcion->Almacen = $almacen;
            $capRecepcion->IdStatusRecepcion = 2;
            $capRecepcion->CantidadPreparado = $request->cantidad;
            $capRecepcion->IdPreparado = $request->preparado;

            // $capRecepcion->IdUsuario = Auth::user()->IdUsuario;
            $capRecepcion->save();

            foreach ($detalleArticulos as $articulo) {
                // Creamos un detalle de recepcion por cada producto
                DatRecepcionLocal::insert([
                    'IdCapRecepcion' => $capRecepcion->IdCapRecepcion,
                    'IdRecepcionLocal' => $idRecepcion,
                    'CodArticulo' => $articulo->CodArticulo,
                    'CantEnviada' => $cantidadEnviada * $articulo->CantidadFormula,
                    'IdStatusRecepcion' => 1,
                ]);

                // Modificamos el historial
                HistorialMovimientoProductoLocal::insert([
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'CodArticulo' => $articulo->CodArticulo,
                    'CantArticulo' => -$cantidadEnviada * $articulo->CantidadFormula,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => $nomOrigenTienda,
                    'IdMovimiento' => 15,
                    'IdUsuario' => Auth::user()->IdUsuario,
                ]);

                // Esta validacion esta pendiente por que la tienda de origen no cuenta con sistema de inventario
                // $stockArticulo = InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                //     ->where('CodArticulo', '' . $articulo->CodArticulo . '')
                //     ->value('StockArticulo');

                // if ($stockArticulo < $cantidadEnviada * $articulo->CantidadFormula) {
                //     DB::rollback();
                //     DB::connection('server')->rollback();
                //     return back()->with('msjdelete', 'No Puede Enviar Más Cantidad del Stock Disponible!');
                // }
                // InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                //     ->where('CodArticulo', '' . $articulo->CodArticulo . '')
                //     ->update([
                //         'StockArticulo' => $stockArticulo - $cantidadEnviada * $articulo->CantidadFormula,
                //     ]);
            }

            DB::commit();
            // DB::connection()->commit();
            // DB::connection('server')->commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollback();
            // DB::connection('server')->rollback();
            // DB::connection()->rollback();
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function EliminarTiendaAsignada($idAsignacion)
    {
        $asignado = DatAsignacionPreparados::where('IdDatAsignacionPreparado', $idAsignacion)
            ->first();

        //return CapRecepcionLocal::get();
        $recepcion = CapRecepcionLocal::where('IdPreparado', $asignado->IdPreparado)
            ->where('IdTiendaDestino', $asignado->IdTienda)->first();

        $detalleRecepcion = DatRecepcionLocal::where('IdCapRecepcion', $recepcion->IdCapRecepcion)->get();

        $asignacion = DatAsignacionPreparados::where('IdDatAsignacionPreparado', $idAsignacion)->first();

        DatRecepcionLocal::destroy($detalleRecepcion);
        $recepcion->delete();
        $asignacion->delete();

        return back();
    }
}
