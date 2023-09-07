<?php

namespace App\Http\Controllers;

use App\Models\CapRecepcion;
use App\Models\CatPreparado;
use App\Models\DatAsignacionPreparados;
use App\Models\DatPreparados;
use App\Models\DatRecepcion;
use App\Models\HistorialMovimientoProducto;
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
                    DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada')
                )
                ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.IdPreparado')
                ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
                ->where('IdCatStatusPreparado', '<>', 1)
                ->whereDate('CatPreparado.Fecha', $fecha)
                // ->orWhere('IdCatStatusPreparado', 3)
                ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Fecha', 'CatPreparado.Cantidad', 'CatPreparado.IdCatStatusPreparado')
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
                    DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada')
                )
                ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.IdPreparado')
                ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
                ->where('IdCatStatusPreparado', '<>', 1)
                // ->orWhere('IdCatStatusPreparado', 3)
                ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Fecha', 'CatPreparado.Cantidad', 'CatPreparado.IdCatStatusPreparado')
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
        CatPreparado::where('IdPreparado', $idPreparado)->update([
            'IdCatStatusPreparado' => 3,
        ]);

        return back();
    }

    // La asignacion de tienda, se hace de tienda a tienda
    public function AsignarTienda($idPreparado, Request $request)
    {
        // Creando asignacion de preparados
        $asignacion = new DatAsignacionPreparados();
        $asignacion->IdPreparado = $idPreparado;
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

            $nomDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('NomTienda');

            $correoDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Correo');

            $nomOrigenTienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->value('NomTienda');

            // Aqui realizamos la transaccion del producto
            DB::beginTransaction();
            // DB::connection()->beginTransaction();
            // DB::connection('server')->beginTransaction();

            // Creamos una recepcion
            $capRecepcion = new CapRecepcion();
            $capRecepcion->FechaLlegada = date('d-m-Y H:i:s');
            $capRecepcion->PackingList = $nombrePreparado;
            $capRecepcion->IdTiendaOrigen = Auth::user()->usuarioTienda->IdTienda;
            $capRecepcion->Almacen = $almacen;
            $capRecepcion->IdStatusRecepcion = 1;
            // $capRecepcion->IdUsuario = Auth::user()->IdUsuario;
            $capRecepcion->save();

            foreach ($detalleArticulos as $articulo) {
                // Creamos un detalle de recepcion por cada producto
                DatRecepcion::insert([
                    'IdCapRecepcion' => $capRecepcion->IdCapRecepcion,
                    'CodArticulo' => $articulo->CodArticulo,
                    'CantEnviada' => $cantidadEnviada * $articulo->CantidadFormula,
                    'IdStatusRecepcion' => 1,
                ]);

                // Modificamos el historial
                HistorialMovimientoProducto::insert([
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'CodArticulo' => $articulo->CodArticulo,
                    'CantArticulo' => -$cantidadEnviada * $articulo->CantidadFormula,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => $nomOrigenTienda,
                    'IdMovimiento' => 2,
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
        $asignacion = DatAsignacionPreparados::where('IdDatAsignacionPreparado', $idAsignacion)->first();

        $asignacion->delete();

        return back();
    }
}
