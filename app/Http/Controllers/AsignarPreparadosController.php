<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CapRecepcionLocal;
use App\Models\CatPaquete;
use App\Models\CatPreparado;
use App\Models\DatAsignacionPreparados;
use App\Models\DatCaja;
use App\Models\DatPaquete;
use App\Models\DatPreparados;
use App\Models\DatRecepcionLocal;
use App\Models\HistorialMovimientoProductoLocal;
use App\Models\InventarioTienda;
use App\Models\ListaPrecio;
use App\Models\Tienda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsignarPreparadosController extends Controller
{
    public function Preparados(Request $request)
    {
        // $fecha = $request->fecha;
        // if ($request->fecha) {
        $fecha = date('Y-m-d', strtotime('-5 day'));
        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        // $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
        // }

        // return $fecha;

        // $preparados = [];

        // if ($fecha) {
        //     $preparados = CatPreparado::with('Detalle', 'Tiendas')
        //         ->select(
        //             'CatPreparado.IdPreparado',
        //             'CatPreparado.Nombre',
        //             'CatPreparado.Fecha',
        //             'CatPreparado.Cantidad',
        //             'CatPreparado.IdCatStatusPreparado',
        //             'CatPreparado.preparado',
        //             DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada')
        //         )
        //         ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.preparado')
        //         ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
        //         ->where('IdCatStatusPreparado', '=', 2)
        //         ->whereDate('CatPreparado.Fecha', $fecha)
        //         // ->orWhere('IdCatStatusPreparado', 3)
        //         ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Fecha', 'CatPreparado.Cantidad', 'CatPreparado.IdCatStatusPreparado', 'CatPreparado.preparado')
        //         ->orderBy('CatPreparado.Fecha', 'DESC')
        //         ->paginate(10);
        // } else {
        // $preparados = CatPreparado::with('Detalle', 'Tiendas')
        //     ->select(
        //         'CatPreparado.IdPreparado',
        //         'CatPreparado.Nombre',
        //         'CatPreparado.Fecha',
        //         'CatPreparado.Cantidad',
        //         'CatPreparado.IdCatStatusPreparado',
        //         'CatPreparado.preparado',
        //         'DatPrecios.PrecioArticulo',
        //         'DatPreparados.CantidadFormula',
        //         DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada'),
        //         // DB::raw('DatPrecios.PrecioArticulo * DatPreparados.CantidadFormula AS Total')
        //     )
        //     ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.preparado')
        //     ->leftJoin('DatPreparados', 'CatPreparado.IdPreparado', 'DatPreparados.IdPreparado')
        //     ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
        //     ->leftJoin('DatPrecios', [['CatArticulos.CodArticulo', 'DatPrecios.CodArticulo'], ['DatPreparados.IDLISTAPRECIO', 'DatPrecios.IdListaPrecio']])
        //     ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
        //     ->where('IdCatStatusPreparado', '=', 2)
        //     // ->orWhere('IdCatStatusPreparado', 3)
        //     ->groupBy(
        //         'CatPreparado.IdPreparado',
        //         'CatPreparado.Nombre',
        //         'CatPreparado.Fecha',
        //         'CatPreparado.Cantidad',
        //         'CatPreparado.IdCatStatusPreparado',
        //         'CatPreparado.preparado',
        //         'DatPrecios.PrecioArticulo',
        //         'DatPreparados.CantidadFormula'
        //     )
        //     ->orderBy('CatPreparado.Fecha', 'DESC')
        //     ->paginate(10);

        $preparados = CatPreparado::with('Detalle', 'Tiendas')
            ->select(
                'CatPreparado.IdPreparado',
                'CatPreparado.Preparado',
                'CatPreparado.Nombre',
                'CatPreparado.Fecha',
                'CatPreparado.Cantidad',
                'CatPreparado.IdCatStatusPreparado',
                'CatPreparado.preparado',
                // DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada'),
                DB::raw(
                    'SUM(
                        IIF(' . $idTienda . ' = DatAsignacionPreparados.IdTienda,
                            ISNULL(DatAsignacionPreparados.CantidadVendida,0),
                            DatAsignacionPreparados.CantidadEnvio)) as CantidadAsignada'
                ),
                // DB::raw('SUM(DatPrecios.PrecioArticulo * DatPreparados.CantidadFormula) AS Total')
            )
            ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.preparado')
            // ->leftJoin('DatPreparados', 'CatPreparado.IdPreparado', 'DatPreparados.IdPreparado')
            // ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
            // ->leftJoin('DatPrecios', [['CatArticulos.CodArticulo', 'DatPrecios.CodArticulo'], ['DatPreparados.IDLISTAPRECIO', 'DatPrecios.IdListaPrecio']])
            ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
            // ->where('IdCatStatusPreparado', '=', 2)
            ->whereDate('CatPreparado.Fecha', '>=', $fecha)
            // ->orWhere('IdCatStatusPreparado', 3)
            ->groupBy(
                'CatPreparado.IdPreparado',
                'CatPreparado.Preparado',
                'CatPreparado.Nombre',
                'CatPreparado.Fecha',
                'CatPreparado.Cantidad',
                'CatPreparado.IdCatStatusPreparado',
                'CatPreparado.preparado'
            )
            ->orderBy('CatPreparado.Fecha', 'DESC')
            ->having(DB::raw(
                'CatPreparado.Cantidad - ISNULL(SUM(
                    IIF(' . $idTienda . ' = DatAsignacionPreparados.IdTienda,
                        ISNULL(DatAsignacionPreparados.CantidadVendida,0),
                        DatAsignacionPreparados.CantidadEnvio)),0)'
            ), '>', 0)
            ->get();
        // }

        $tiendas = Tienda::select('IdTienda', 'NomTienda')->get();
        $articulos = Articulo::get();
        $listaPrecios = ListaPrecio::get();

        return view('AsignarPreparados.indexNewDesign', compact('preparados', 'tiendas', 'articulos', 'listaPrecios'));
    }

    public function VerPreparado($id, Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        $tiendas = Tienda::select('IdTienda', 'NomTienda')->where('IdTienda', '<>', $idTienda)->get();
        $articulos = Articulo::get();
        $listaPrecios = ListaPrecio::get();

        $preparado = CatPreparado::with(['Detalle', 'Tiendas' => function ($query) use ($idTienda) {
            $query->select('DatAsignacionPreparados.*', 'CatTiendas.*');
            $query->where('DatAsignacionPreparados.IdTienda', '<>', $idTienda);
        }])
            ->select(
                'CatPreparado.IdPreparado',
                'CatPreparado.Nombre',
                'CatPreparado.Fecha',
                'CatPreparado.Cantidad',
                'CatPreparado.Subir',
                'CatPreparado.IdCatStatusPreparado',
                'CatPreparado.preparado',
                // 'DatPrecios.PrecioArticulo',
                // 'DatPreparados.CantidadFormula',
                // DB::raw('SUM(IIF(' . $idTienda . ' = DatAsignacionPreparados.IdTienda, DatAsignacionPreparados.CantidadVendida,DatAsignacionPreparados.CantidadEnvio)) as CantidadAsignada'),
                DB::raw(
                    'SUM(
                        IIF(' . $idTienda . ' = DatAsignacionPreparados.IdTienda,
                            DatAsignacionPreparados.CantidadVendida,
                            DatAsignacionPreparados.CantidadEnvio)) as CantidadAsignada'
                ),
                // DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada'),
                // DB::raw('SUM(DatPrecios.PrecioArticulo * DatPreparados.CantidadFormula) AS Total')
            )
            ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.preparado')
            // ->leftJoin('DatPreparados', 'CatPreparado.IdPreparado', 'DatPreparados.IdPreparado')
            // ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
            // ->leftJoin('DatPrecios', [['CatArticulos.CodArticulo', 'DatPrecios.CodArticulo'], ['DatPreparados.IDLISTAPRECIO', 'DatPrecios.IdListaPrecio']])
            ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
            ->where('CatPreparado.IdPreparado', $id)
            ->groupBy(
                'CatPreparado.IdPreparado',
                'CatPreparado.Nombre',
                'CatPreparado.Fecha',
                'CatPreparado.Cantidad',
                'CatPreparado.Subir',
                'CatPreparado.IdCatStatusPreparado',
                'CatPreparado.preparado',
                // 'DatPrecios.PrecioArticulo',
                // 'DatPreparados.CantidadFormula',
            )
            ->first();

        return view('AsignarPreparados.preparado', compact('idTienda', 'preparado', 'listaPrecios', 'tiendas', 'articulos'));
    }

    public function RegresarPreparado($idPreparado)
    {
        CatPreparado::where('IdPreparado', $idPreparado)->update([
            'IdCatStatusPreparado' => 1,
        ]);

        return back();
    }

    public function FinalizarPreparado($preparado)
    {
        try {
            // DB::beginTransaction();
            // En caso de que no se ayan asignado todos los preparados a tiendas, estos quedaran asignados por defecto a la tienda local
            $Preparado = CatPreparado::where('IdPreparado', $preparado)->first();
            $idPreparado = $Preparado->Preparado;
            $cantidadPreparado = $Preparado->Cantidad;
            $cantidadEnvio = DatAsignacionPreparados::where('IdPreparado', $idPreparado)->sum('CantidadEnvio');

            if ($cantidadEnvio < $cantidadPreparado) {
                $resto = $cantidadPreparado - $cantidadEnvio;
                $idTienda = Auth::user()->usuarioTienda->IdTienda;

                $asignacion = new DatAsignacionPreparados();
                $asignacion->IdPreparado = $idPreparado;
                $asignacion->IdTienda = $idTienda;
                $asignacion->CantidadEnvio = $resto;
                $asignacion->save();
            }

            // Restamos el inventario que consumira
            $detalle = DatPreparados::where('IdPreparado', $preparado)->get();
            foreach ($detalle as $item) {
                $stock = InventarioTienda::leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatInventario.CodArticulo')
                    ->where('CatArticulos.IdArticulo', $item->IdArticulo)
                    ->value('StockArticulo');

                if ($stock - $item->CantidadPaquete < 0) {
                    $articulo = Articulo::where('IdArticulo', $item->IdArticulo)->first();
                    DB::rollBack();
                    return back()->with('msjdelete', 'Inventario insuficiente para el articulo: ' . $articulo->NomArticulo);
                }

                // Se quito el consumo de inventario, ya que no siempre se asigna toda la cantidad
                // InventarioTienda::leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatInventario.CodArticulo')
                //     ->where('CatArticulos.IdArticulo', $item->IdArticulo)
                //     ->update([
                //         'StockArticulo' => $stock - $item->CantidadPaquete,
                //     ]);
            }

            // Actualizamos el preparado para que se mande al servidor
            CatPreparado::where('IdPreparado', $preparado)->update([
                'IdCatStatusPreparado' => 3,
            ]);
            // DB::commit();
            return back()->with('msjAdd', 'Datos guardados correctamente');
        } catch (\Throwable $th) {
            // DB::rollBack();
            return $th;
            return back()->with('msjdelete', 'Ha ocuarrido un error, intente de nuevo');
        }
    }

    // La asignacion de tienda, se hace de tienda a tienda
    public function AsignarTienda($idPreparado, Request $request)
    {
        try {
            DB::beginTransaction();
            $idTienda = Auth::user()->usuarioTienda->IdTienda;
            $cantidadAsignar = $request->cantidad;

            // Validar en caso de que el preparado ya este en status 3, para actualizar el asignado local
            //$statusPreparado = CatPreparado::where('IdPreparado', $idPreparado)->value('IdCatStatusPreparado');
            $asignado = DatAsignacionPreparados::where('IdPreparado', $request->preparado)
                ->where('IdTienda', $idTienda)
                ->first();

            if ($asignado) {
                $cantidadEnvio = $asignado->CantidadEnvio;
                $cantidadVendido = $asignado->CantidadVendida;
                $restoEnvios = $cantidadEnvio - $cantidadVendido;

                DatAsignacionPreparados::where('IdPreparado', $request->preparado)
                    ->where('IdTienda', $idTienda)
                    ->update([
                        'CantidadEnvio' => $cantidadEnvio - $cantidadAsignar
                    ]);

                if ($restoEnvios - $request->cantidad == 0) {
                    CatPaquete::where('IdPreparado', $request->preparado)->update([
                        'Status' => 1
                    ]);
                }
            }

            // If el preparado esta en status 3, se activa para que se vuelva a subir
            $IdCatStatusPreparado = CatPreparado::where('IdPreparado', $idPreparado)->value('IdCatStatusPreparado');
            if ($IdCatStatusPreparado == 3) {
                CatPreparado::where('IdPreparado', $idPreparado)
                    ->update([
                        'Subir' => 0
                    ]);
            }

            // Creando asignacion de preparados
            $asignacion = new DatAsignacionPreparados();
            // Aqui pones el idPreparado generado manualmente al crear el preparado
            $asignacion->IdPreparado = $request->preparado;
            $asignacion->IdTienda = $request->idTienda;
            $asignacion->CantidadEnvio = $cantidadAsignar;
            $asignacion->save();

            // Traemos el detalle de los productos del preparado
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

            // Seleccionamos el nombre del preparado
            $nombrePreparado = CatPreparado::where('IdPreparado', $idPreparado)
                ->value('Nombre');

            // Seleccionamos la informacion de la tienda
            $almacen = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Almacen');

            $IdTD = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('IdTienda');

            $nomOrigenTienda = Tienda::where('IdTienda', $idTienda)
                ->value('NomTienda');

            // Creamos una recepcion
            $idCaja = DatCaja::where('Status', 0)
                ->where('Activa', 0)
                ->where('IdTienda', $idTienda)
                ->value('IdCaja');

            $recepcion = CapRecepcionLocal::create([
                // 'IdRecepcionLocal' => $idRecepcion,
                'FechaLlegada' => date('d-m-Y H:i:s'),
                'PackingList' => $nombrePreparado,
                'IdTiendaOrigen' => $idTienda,
                'IdTiendaDestino' => $IdTD,
                'Almacen' => $almacen,
                'IdStatusRecepcion' => 2,
                'CantidadPreparado' => $request->cantidad,
                'IdPreparado' => $request->preparado,
                'Subir' => 0,
                'idtiporecepcion' => 15,
                'IdCajaOrigen' => $idCaja,
            ]);

            //Id Insertado
            $IdCapRecepcionLocal = CapRecepcionLocal::where('IdCapRecepcion', $recepcion->IdCapRecepcion)->value('IdRecepcionLocal');

            foreach ($detalleArticulos as $articulo) {
                // Creamos un detalle de recepcion por cada producto
                DatRecepcionLocal::insert([
                    'IdCapRecepcion' => $recepcion->IdCapRecepcion,
                    'IdRecepcionLocal' => $IdCapRecepcionLocal,
                    'CodArticulo' => $articulo->CodArticulo,
                    'CantEnviada' => $cantidadEnviada * $articulo->CantidadFormula,
                    'IdStatusRecepcion' => 1,
                ]);

                // Modificamos el historial
                HistorialMovimientoProductoLocal::insert([
                    'IdTienda' => $idTienda,
                    'CodArticulo' => $articulo->CodArticulo,
                    'CantArticulo' => -$cantidadEnviada * $articulo->CantidadFormula,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => $nomOrigenTienda,
                    'IdMovimiento' => 15,
                    'IdUsuario' => Auth::user()->IdUsuario,
                ]);

                // Esta validacion esta pendiente por que la tienda de origen no cuenta con sistema de inventario
                // $stockArticulo = InventarioTienda::where('IdTienda', $idTienda)
                //     ->where('CodArticulo', '' . $articulo->CodArticulo . '')
                //     ->value('StockArticulo');

                // if ($stockArticulo < $cantidadEnviada * $articulo->CantidadFormula) {
                //     DB::rollback();
                //     DB::connection('server')->rollback();
                //     return back()->with('msjdelete', 'No Puede Enviar Más Cantidad del Stock Disponible!');
                // }
                // InventarioTienda::where('IdTienda', $idTienda)
                //     ->where('CodArticulo', '' . $articulo->CodArticulo . '')
                //     ->update([
                //         'StockArticulo' => $stockArticulo - $cantidadEnviada * $articulo->CantidadFormula,
                //     ]);
            }

            DB::commit();
            return back()->with(['msjAdd' => 'La sentencia se ejecuto correctamente', 'id' => $request->preparado]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function EliminarTiendaAsignada($idAsignacion)
    {
        try {
            DB::beginTransaction();

            $asignado = DatAsignacionPreparados::where('IdDatAsignacionPreparado', $idAsignacion)
                ->first();

            $idTienda = Auth::user()->usuarioTienda->IdTienda;
            $cantidadAsignar = $asignado->CantidadEnvio;
            $IdPreparado = $asignado->IdPreparado;

            // Validar en caso de que el preparado ya este en status 3, para actualizar el asignado local
            $asignadoLocal = DatAsignacionPreparados::where('IdPreparado', $IdPreparado)
                ->where('IdTienda', $idTienda)
                ->first();

            if ($asignadoLocal) {
                $cantidadEnvio = $asignadoLocal->CantidadEnvio;

                DatAsignacionPreparados::where('IdPreparado', $IdPreparado)
                    ->where('IdTienda', $idTienda)
                    ->update([
                        'CantidadEnvio' => $cantidadEnvio + $cantidadAsignar
                    ]);

                CatPaquete::where('IdPreparado', $IdPreparado)->update([
                    'Status' => 0
                ]);
            }

            // Eliminado de lineas de recepcion y asignado
            $recepcion = CapRecepcionLocal::where('IdPreparado', $asignado->IdPreparado)
                ->where('IdTiendaDestino', $asignado->IdTienda)->first();


            if ($recepcion) {
                $detalleRecepcion = DatRecepcionLocal::where('IdCapRecepcion', $recepcion->IdCapRecepcion)->get();
                DatRecepcionLocal::destroy($detalleRecepcion);
                $recepcion->delete();
            }

            $asignacion = DatAsignacionPreparados::where('IdDatAsignacionPreparado', $idAsignacion)->first();
            $asignacion->delete();

            DB::commit();
            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('msjdelete', 'Ha ocuarrido un error, intente de nuevo');
        }
    }
}
