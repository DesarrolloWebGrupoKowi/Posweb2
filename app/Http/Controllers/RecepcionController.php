<?php

namespace App\Http\Controllers;

use App\Mail\RecepcionProductoMail;
use App\Models\Articulo;
use App\Models\CapRecepcion;
use App\Models\CapturaManualTmp;
use App\Models\CatPaquete;
use App\Models\CorreoTienda;
use App\Models\DatAsignacionPreparados;
use App\Models\DatAsignacionPreparadosLocal;
use App\Models\DatCaja;
use App\Models\DatRecepcion;
use App\Models\HistorialMovimientoProducto;
use App\Models\InventarioTienda;
use App\Models\RecepcionSinInternet;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class RecepcionController extends Controller
{
    public function RecepcionProducto(Request $request)
    {
        // exec("ping -n 1 posweb2admin.kowi.com.mx", $salida, $codigo);

        // if ($codigo === 1) {
        //     return redirect('RecepcionLocalSinInternet');
        // }

        $tienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->first();

        $recepcion = CapRecepcion::with('StatusRecepcion')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'CapRecepcion.IdTiendaOrigen')
            ->where('CapRecepcion.Almacen', $tienda->Almacen)
            ->where('CapRecepcion.IdStatusRecepcion', 1)
            ->where(function ($query) {
                $query->whereNull('CapRecepcion.IdTiendaDestino');
                $query->orWhere('CapRecepcion.IdTiendaDestino', Auth::user()->usuarioTienda->IdTienda);
            })
            ->get();

        $idRecepcion = $request->idRecepcion;
        $cantidadPreparado = CapRecepcion::where('IdCapRecepcion', $idRecepcion)->value('CantidadPreparado');
        // return  DatAsignacionPreparados::where('IdPreparado', $idRecepcionLocal)->get();
        // return  CapRecepcion::where('IdCapRecepcion', $idRecepcion)->get();

        empty($idRecepcion) ? $idRecepcion = 0 : $idRecepcion = $idRecepcion;

        $detalleRecepcion = DB::select(
            "select c.PackingList, d.NomTienda as TiendaOrigen, c.Almacen, a.*, b.NomArticulo" .
                " from DatRecepcion as a" .
                " left join CatArticulos as b on b.CodArticulo=a.CodArticulo" .
                " left join CapRecepcion as c on c.IdCapRecepcion=a.IdCapRecepcion" .
                ' left join CatTiendas as d on d.IdTienda = c.IdTiendaOrigen' .
                " where a.IdCapRecepcion = " . $idRecepcion . "" .
                " and a.IdStatusRecepcion = 1" .
                " union all" .
                " select Referencia, '', '" . $tienda->Almacen . "', 0, 0, 0, a.CodArticulo, a.CantArticulo, 0, 1, 0, b.NomArticulo" .
                " from CapRecepcionManualTmp as a" .
                " left join CatArticulos as b on b.CodArticulo=a.CodArticulo" .
                " where a.IdTienda = '" . $tienda->IdTienda . "' "
        );

        $totalRecepcion = DatRecepcion::where('IdCapRecepcion', $idRecepcion)
            ->where('IdStatusRecepcion', 1)
            ->sum('CantEnviada');

        $totalManual = CapturaManualTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->sum('CantArticulo');

        $totalCantidad = $totalRecepcion + $totalManual;

        return view('Recepcion.RecepcionProducto', compact('tienda', 'recepcion', 'detalleRecepcion', 'totalCantidad', 'idRecepcion', 'cantidadPreparado'));
    }

    public function AgregarProductoManual(Request $request)
    {
        $radioBuscar = $request->radioBuscar;
        $filtroArticulo = $request->filtroArticulo;

        $tienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->first();

        $articulos = Articulo::where('CodArticulo', '0')
            ->get();

        $articuloPendiente = 1;

        if ($radioBuscar == 'codigo') {
            $dRecepcion = DB::table('CapRecepcion as a')
                ->leftJoin('DatRecepcion as b', 'b.IdCapRecepcion', 'a.IdCapRecepcion')
                ->where('a.Almacen', $tienda->Almacen)
                ->whereNull('a.FechaRecepcion')
                ->whereNull('a.IdPreparado')
                ->where('a.IdStatusRecepcion', 1)
                ->where('b.CodArticulo', $filtroArticulo)
                ->get();

            if ($dRecepcion->count() == 0) {
                $articulos = Articulo::where('CodArticulo', $filtroArticulo)
                    ->get();
            } else {
                $articuloPendiente = 0;
            }
        } else {
            $articulos = Articulo::where('NomArticulo', 'like', '%' . $filtroArticulo . '%')
                ->whereRaw("CodArticulo not in" .
                    " (select c.CodArticulo" .
                    " from CapRecepcion as a" .
                    " left join DatRecepcion as b on b.IdCapRecepcion=a.IdCapRecepcion " .
                    " left join CatArticulos as c on c.CodArticulo=b.CodArticulo" .
                    " where a.Almacen = 'ALP-114'" .
                    " and a.IdStatusRecepcion = 1" .
                    " and c.NomArticulo like '%" . $filtroArticulo . "%')")
                ->get();
        }

        return view('Recepcion.AgregarProductoManual', compact('articulos', 'articuloPendiente'));
    }

    public function CapturaManualTmp(Request $request)
    {
        $codArticulo = $request->codArticulo;
        $cantArticulo = $request->cantArticulo;
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $capturaManual = CapturaManualTmp::where('CodArticulo', $codArticulo)
            ->where('IdTienda', $idTienda)
            ->first();

        if (!empty($codArticulo) && !empty($cantArticulo)) {
            if (empty($capturaManual)) {
                CapturaManualTmp::insert([
                    'IdTienda' => $idTienda,
                    'CodArticulo' => $codArticulo,
                    'CantArticulo' => $cantArticulo,
                    'Referencia' => 'MANUAL',
                    'IdMovimiento' => 3,
                ]);
            } else {
                CapturaManualTmp::where('IdTienda', $idTienda)
                    ->where('CodArticulo', $codArticulo)
                    ->update([
                        'CantArticulo' => $capturaManual->CantArticulo + $cantArticulo,
                    ]);
            }
        }

        $articulosManual = DB::table('CapRecepcionManualTmp as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.IdTienda', $idTienda)
            ->get();

        return view('Recepcion.CapturaManualTmp', compact('articulosManual'));
    }

    public function EliminarProductoManual($idCapRecepcionManual)
    {
        CapturaManualTmp::where('IdCapRecepcionManual', $idCapRecepcionManual)
            ->delete();

        return redirect('CapturaManualTmp');
    }

    public function RecepcionarProducto($idRecepcion, Request $request)
    {

        $chkArticulo = $request->chkArticulo;
        $cantRecepcionada = $request->cantRecepcionada;

        if (empty($chkArticulo)) {
            return redirect('RecepcionProducto')->with('msjdelete', 'Debe Seleccionar Productos a Recepcionar!');
        }

        try {
            // DB::connection('server')->beginTransaction();
            DB::beginTransaction();

            if ($request->cantidad) {
                // return $idRecepcionLocal = CapRecepcion::where('IdCapRecepcion', $idRecepcion)->get();
                $idRecepcionLocal = CapRecepcion::where('IdCapRecepcion', $idRecepcion)->value('IdPreparado');


                if (count(DatAsignacionPreparadosLocal::where('IdPreparado', $idRecepcionLocal)->get()) == 0) {
                    return redirect('RecepcionProducto')->with('msjdelete', 'No se encuentra los datos de asignacion para recepcionar.');
                }

                if (count(CatPaquete::where('IdPreparado', $idRecepcionLocal)->get()) == 0) {
                    return redirect('RecepcionProducto')->with('msjdelete', 'No se encuentra el paquete que se quiere recepcionar.');
                }

                CatPaquete::where('IdPreparado', $idRecepcionLocal)->update(['Status' => 0]);
                DatAsignacionPreparadosLocal::where('IdPreparado', $idRecepcionLocal)->update(['CantidadEnvio' => $request->cantidad]);
            }

            // return '';

            $Linea = 0;
            foreach ($chkArticulo as $key => $referencia) {
                foreach ($cantRecepcionada as $codArticulo => $cRecepcionada) {
                    if ($key == $codArticulo) {
                        DatRecepcion::where('IdCapRecepcion', $idRecepcion)
                            ->where('CodArticulo', '' . $key . '')
                            ->update([
                                'CantRecepcionada' => $cRecepcionada,
                                'IdStatusRecepcion' => 2,
                            ]);
                        //A un packingLst se le agrega un articulo manual
                        $Linea = $Linea + 1;
                        if ($idRecepcion > 0 && $referencia == 'MANUAL') {
                            DatRecepcion::insert([
                                'IdCapRecepcion' => $idRecepcion,
                                'CodArticulo' => $codArticulo,
                                'CantEnviada' => $cRecepcionada,
                                'CantRecepcionada' => $cRecepcionada,
                                'Linea' => $Linea,
                                'IdStatusRecepcion' => 2,
                            ]);
                        }

                        //Si hay inventario de ese articulo para la tienda
                        $inventario = InventarioTienda::where('CodArticulo', '' . $key . '')
                            ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                            ->first();

                        if (empty($inventario)) {
                            InventarioTienda::insert([
                                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                                'CodArticulo' => $key,
                                'StockArticulo' => $cRecepcionada,
                            ]);

                            // DB::table('DatInventario')->insert([
                            //     'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                            //     'CodArticulo' => $key,
                            //     'StockArticulo' => $cRecepcionada,
                            // ]);
                        } else {
                            InventarioTienda::where('CodArticulo', '' . $key . '')
                                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                                ->update([
                                    'StockArticulo' => $inventario->StockArticulo + $cRecepcionada,
                                ]);

                            // DB::table('DatInventario')->where('CodArticulo', '' . $key . '')
                            //     ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                            //     ->update([
                            //         'StockArticulo' => $inventario->StockArticulo + $cRecepcionada,
                            //     ]);
                        }

                        HistorialMovimientoProducto::insert([
                            'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                            'CodArticulo' => $key,
                            'CantArticulo' => $cRecepcionada,
                            'FechaMovimiento' => date('d-m-Y H:i:s'),
                            'Referencia' => $referencia,
                            'IdMovimiento' => $referencia == 'MANUAL' ? 11 : 1,
                            'IdUsuario' => Auth::user()->IdUsuario,
                        ]);
                    }
                }
            }

            //Solo inserta los articulos manuales
            if ($idRecepcion == 0) {
                //Obetener tienda
                $almacen = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)->value('Almacen');

                // $idCapRecepcion = DB::table('CapRecepcion')
                //     ->max('IdCapRecepcion') + 1;

                //Obtener caja
                $idCaja = DatCaja::where('Status', 0)
                    ->where('Activa', 0)
                    ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->value('IdCaja');

                // $idRecepcion = Auth::user()->usuarioTienda->IdTienda . $idCaja . $idCapRecepcion;

                //Insert a CapRecepcion y DatRecepcion cuando el idRecepcion==0
                $recepcion = CapRecepcion::create([
                    // 'IdRecepcionLocal' => $idRecepcion,
                    'FechaRecepcion' => date('d-m-Y H:i:s'),
                    'FechaLlegada' => date('d-m-Y H:i:s'),
                    'PackingList' => 'RECEPCION MANUAL',
                    'Almacen' => $almacen,
                    'IdStatusRecepcion' => 2,
                    'IdUsuario' => Auth::user()->IdUsuario,
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'IdTiendaOrigen' => Auth::user()->usuarioTienda->IdTienda,
                    'IdCaja' => $idCaja,
                    'IdCajaOrigen' => $idCaja,
                    'StatusInventario' => 1,
                    'idtiporecepcion' => 11,
                ]);

                //Id Insertado
                $IdCapRecepcionLocal = CapRecepcion::where('IdCapRecepcion', $recepcion->IdCapRecepcion)->value('IdRecepcionLocal');

                $Linea = 0;

                //Articulos checkeados
                foreach ($chkArticulo as $codArticulo => $refArticulo) {
                    foreach ($cantRecepcionada as $codArticuloRec => $cantRecepcion) {
                        if ($codArticulo == $codArticuloRec) {
                            $Linea = $Linea + 1;
                            DatRecepcion::insert([
                                'IdCapRecepcion' => $recepcion->IdCapRecepcion,
                                'IdRecepcionLocal' => $IdCapRecepcionLocal,
                                'CodArticulo' => $codArticulo,
                                'CantEnviada' => $cantRecepcion,
                                'CantRecepcionada' => $cantRecepcion,
                                'Linea' => $Linea,
                                'IdStatusRecepcion' => 2,
                            ]);
                        }
                    }
                }
            }

            CapturaManualTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->delete();

            $faltantesPorRecepcionar = DatRecepcion::where('IdCapRecepcion', $idRecepcion)
                ->where('IdStatusRecepcion', 1)
                ->count();

            if ($faltantesPorRecepcionar == 0) {
                //Obtener caja
                $idCaja = DatCaja::where('Status', 0)
                    ->where('Activa', 0)
                    ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->value('IdCaja');

                CapRecepcion::where('IdCapRecepcion', $idRecepcion)
                    ->update([
                        'IdStatusRecepcion' => 2,
                        'FechaRecepcion' => date('d-m-Y H:i:s'),
                        'IdUsuario' => Auth::user()->IdUsuario,
                        'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                        'IdCaja' => $idCaja,
                        'Subir' => 0,
                    ]);
            }

            // enviar correo de recepcion realizada
            if ($idRecepcion > 0) {
                try {
                    $correoTienda = CorreoTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('Status', 0)
                        ->first();

                    $correos = [
                        'soporte@kowi.com.mx',
                        'cponce@kowi.com.mx',
                        'sistemas@kowi.com.mx',
                    ];

                    // array_push($correos, $correoTienda->GerenteCorreo, $correoTienda->Supervisor, $correoTienda->AlmacenistaCorreo);

                    $correos = array_filter($correos);

                    $recepcion = CapRecepcion::with(['DetalleRecepcion' => function ($query) {
                        $query->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatRecepcion.CodArticulo')
                            ->where('DatRecepcion.IdStatusRecepcion', 2);
                    }])
                        ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('IdCapRecepcion', $idRecepcion)
                        ->first();

                    $nomTienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->value('NomTienda');

                    // Mail::to($correos)
                    //     ->send(new RecepcionProductoMail($recepcion, $nomTienda));
                } catch (\Throwable $th) {
                    // DB::connection('server')->rollback();
                    DB::rollBack();
                    return back()->with('msjdelete', 'Error:' . $th->getMessage());
                }
            }

            // DB::connection('server')->commit();
            DB::commit();

            return redirect('RecepcionProducto')->with('msjAdd', 'Productos Recepcionados Correctamente!');
        } catch (\Throwable $th) {
            // DB::connection('server')->rollback();
            DB::rollBack();
            return redirect('RecepcionProducto')->with('msjdelete', 'Error' . $th->getMessage());
        }
    }

    public function CancelarRecepcion($idRecepcion, Request $request)
    {
        $motivoCancelacion = $request->motivoCancelacion;
        $idCaja = DatCaja::where('Status', 0)
            ->where('Activa', 0)
            ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->value('IdCaja');

        try {
            DB::beginTransaction();

            CapRecepcion::where('IdCapRecepcion', $idRecepcion)
                ->update([
                    'IdStatusRecepcion' => 3,
                    'FechaCancelacion' => date('d-m-Y H:i:s'),
                    'MotivoCancelacion' => $motivoCancelacion,
                    'IdUsuario' => Auth::user()->IdUsuario,
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'IdCaja' => $idCaja,
                ]);

            DatRecepcion::where('IdCapRecepcion', $idRecepcion)
                ->update([
                    'IdStatusRecepcion' => 3,
                ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        return redirect('RecepcionProducto')->with('msjdelete', 'Recepción Cancelada Correctamente!');
    }

    public function ReporteRecepciones(Request $request)
    {
        $fecha1 = $request->input('fecha1', date('Y-m-d'));
        $fecha2 = $request->input('fecha2', date('Y-m-d'));
        $chkReferencia = $request->chkReferencia;
        $referencia = $request->referencia;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        if (!empty($chkReferencia)) {
            $recepciones = CapRecepcion::with(['DetalleRecepcion' => function ($query) {
                $query->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatRecepcion.CodArticulo')
                    ->leftJoin('CatStatusRecepcion', 'CatStatusRecepcion.IdStatusRecepcion', 'DatRecepcion.IdStatusRecepcion');
            }, 'StatusRecepcion'])
                ->where('Almacen', $tienda->Almacen)
                ->whereRaw("cast(FechaLlegada as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->where('PackingList', $referencia)
                ->get();
        } else {
            $recepciones = CapRecepcion::with(['DetalleRecepcion' => function ($query) {
                $query->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatRecepcion.CodArticulo')
                    ->leftJoin('CatStatusRecepcion', 'CatStatusRecepcion.IdStatusRecepcion', 'DatRecepcion.IdStatusRecepcion');
            }, 'StatusRecepcion'])
                ->where('Almacen', $tienda->Almacen)
                ->whereRaw("cast(FechaLlegada as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->get();
        }

        //return $recepciones;

        return view('Recepcion.ReporteRecepciones', compact('recepciones', 'fecha1', 'fecha2', 'referencia', 'chkReferencia'));
    }

    public function RecepcionLocalSinInternet(Request $request)
    {
        // exec("ping -n 1 google.com", $salida, $codigo);

        // if ($codigo === 0) {
        //     return redirect('RecepcionProducto');
        // }

        $articulos = Articulo::where('Status', 0)
            ->get();

        $capturasSinInternet = DB::table('CapRecepcionManualTmp as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->get();

        return view('Recepcion.RecepcionLocalSinInternet', compact('articulos', 'capturasSinInternet'));
    }

    public function AgregarProductoLocalSinInternet(Request $request)
    {
        try {
            DB::beginTransaction();

            RecepcionSinInternet::insert([
                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                'CodArticulo' => $request->codArticulo,
                'CantArticulo' => $request->cantArticulo,
                'IdMovimiento' => 13,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }

        DB::commit();
        return back();
    }

    public function EliminarArticuloSinInternet($idCapRecepcionManual)
    {
        try {
            DB::beginTransaction();

            RecepcionSinInternet::where('IdCapRecepcionManual', $idCapRecepcionManual)
                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjDelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back();
    }

    public function RecepcionarProductoSinInternet(Request $request)
    {
        $origen = $request->origen;

        try {
            DB::beginTransaction();

            $productos = RecepcionSinInternet::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->get();

            $idCapRecepcion = DB::table('CapRecepcion')
                ->max('IdCapRecepcion') + 1;

            $numCaja = DatCaja::where('Status', 0)
                ->where('Activa', 0)
                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->value('IdCaja');

            $idRecepcion = Auth::user()->usuarioTienda->IdTienda . $numCaja . $idCapRecepcion;

            DB::table('CapRecepcion')->insert([
                'IdRecepcionLocal' => $idRecepcion,
                'FechaRecepcion' => date('d-m-Y H:i:s'),
                'FechaLlegada' => date('d-m-Y H:i:s'),
                'PackingList' => 'RECEPCION SIN INTERNET MANUAL',
                'IdTiendaOrigen' => null,
                'Almacen' => null,
                'IdStatusRecepcion' => 2,
                'IdUsuario' => Auth::user()->IdUsuario,
                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                'IdCaja' => $numCaja,
                'StatusInventario' => 0,
            ]);

            //Sacar el IdCapRecepcion que acabo insertar
            $idCapRecepcion = DB::table('CapRecepcion')->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->max('IdCapRecepcion');

            //Insertar inventario y detalle de la recepcion
            foreach ($productos as $key => $producto) {
                $stock = InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('CodArticulo', $producto->CodArticulo)
                    ->sum('StockArticulo');

                if (empty($stock)) {

                    InventarioTienda::insert([
                        'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                        'CodArticulo' => $producto->CodArticulo,
                        'StockArticulo' => $producto->CantArticulo,
                    ]);
                } else {
                    InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('CodArticulo', $producto->CodArticulo)
                        ->update([
                            'StockArticulo' => $stock + $producto->CantArticulo,
                        ]);
                }

                DB::table('DatRecepcion')->insert([
                    'IdCapRecepcion' => $idCapRecepcion,
                    'IdRecepcionLocal' => $idRecepcion,
                    'CodArticulo' => $producto->CodArticulo,
                    'CantEnviada' => $producto->CantArticulo,
                    'CantRecepcionada' => $producto->CantArticulo,
                    'Linea' => $key + 1,
                    'IdStatusRecepcion' => 2,
                ]);

                DB::table('DatHistorialMovimientos')->insert([
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'CodArticulo' => $producto->CodArticulo,
                    'CantArticulo' => $producto->CantArticulo,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => $idRecepcion,
                    'IdMovimiento' => 13,
                    'IdUsuario' => Auth::user()->IdUsuario,
                ]);
            }

            RecepcionSinInternet::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se recepcionó el producto correctamente');
    }
}
