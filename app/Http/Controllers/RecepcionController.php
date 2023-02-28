<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CapRecepcion;
use App\Models\DatRecepcion;
use App\Models\Tienda;
use App\Models\Articulo;
use App\Models\CapturaManualTmp;
use App\Models\InventarioTienda;
use App\Models\HistorialMovimientoProducto;
use App\Models\RecepcionSinInternet;
use App\Models\DatCaja;
use App\Mail\RecepcionProductoMail;
use App\Models\CorreoTienda;

class RecepcionController extends Controller
{
    public function RecepcionProducto(Request $request){
        exec("ping -n 1 google.com", $salida, $codigo);

        if($codigo === 1){
            return redirect('RecepcionLocalSinInternet');
        }

        $tienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->first();

        $recepcion = CapRecepcion::with('StatusRecepcion')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'CapRecepcion.IdTiendaOrigen')
            ->where('CapRecepcion.Almacen', $tienda->Almacen)
            ->where('CapRecepcion.IdStatusRecepcion', 1)
            ->get();

        $idRecepcion = $request->idRecepcion;
        empty($idRecepcion) ? $idRecepcion = 0 : $idRecepcion = $idRecepcion;

        $detalleRecepcion = DB::connection('server')->select("select c.PackingList, d.NomTienda as TiendaOrigen, c.Almacen, a.*, b.NomArticulo".
                                        " from DatRecepcion as a".
                                        " left join CatArticulos as b on b.CodArticulo=a.CodArticulo".
                                        " left join CapRecepcion as c on c.IdCapRecepcion=a.IdCapRecepcion".
                                        ' left join CatTiendas as d on d.IdTienda = c.IdTiendaOrigen'.
                                        " where a.IdCapRecepcion = ".$idRecepcion."".
                                        " and a.IdStatusRecepcion = 1".
                                        " union all".
                                        " select Referencia, '', '".$tienda->Almacen."', 0, 0, 0, a.CodArticulo, a.CantArticulo, 0, 1, 0, b.NomArticulo".
                                        " from CapRecepcionManualTmp as a".
                                        " left join CatArticulos as b on b.CodArticulo=a.CodArticulo".
                                        " where a.IdTienda = '".$tienda->IdTienda."' ");

        $totalRecepcion = DatRecepcion::where('IdCapRecepcion', $idRecepcion)
            ->where('IdStatusRecepcion', 1)
            ->sum('CantEnviada');

        $totalManual = CapturaManualTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->sum('CantArticulo');
        $totalCantidad = $totalRecepcion + $totalManual;

        return view('Recepcion.RecepcionProducto', compact('tienda', 'recepcion', 'detalleRecepcion', 'totalCantidad', 'idRecepcion'));
    }

    public function AgregarProductoManual(Request $request){
        $radioBuscar = $request->radioBuscar;
        $filtroArticulo = $request->filtroArticulo;

        $tienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->first();

        $articulos = Articulo::where('CodArticulo', '0')
            ->get();

        $articuloPendiente = 1;
        
        if($radioBuscar == 'codigo'){
            $dRecepcion = DB::connection('server')->table('CapRecepcion as a')
                ->leftJoin('DatRecepcion as b', 'b.IdCapRecepcion', 'a.IdCapRecepcion')
                ->where('a.Almacen', $tienda->Almacen)
                ->whereNull('a.FechaRecepcion')
                ->where('a.IdStatusRecepcion', 1)
                ->where('b.CodArticulo', $filtroArticulo)
                ->get();

            if($dRecepcion->count() == 0){
                $articulos = Articulo::where('CodArticulo', $filtroArticulo)
                    ->get();
            }
            else{
                $articuloPendiente = 0;
            }
        }
        else{
            $articulos = Articulo::where('NomArticulo', 'like', '%'.$filtroArticulo.'%')
                ->whereRaw("CodArticulo not in". 
                        " (select c.CodArticulo". 
                        " from CapRecepcion as a". 
                        " left join DatRecepcion as b on b.IdCapRecepcion=a.IdCapRecepcion ".
                        " left join CatArticulos as c on c.CodArticulo=b.CodArticulo". 
                        " where a.Almacen = 'ALP-114'". 
                        " and a.IdStatusRecepcion = 1". 
                        " and c.NomArticulo like '%".$filtroArticulo."%')")
                ->get();
        }

        return view('Recepcion.AgregarProductoManual', compact('articulos', 'articuloPendiente'));
    }

    public function CapturaManualTmp(Request $request){
        $codArticulo = $request->codArticulo;
        $cantArticulo = $request->cantArticulo;
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $capturaManual = CapturaManualTmp::where('CodArticulo', $codArticulo)
            ->where('IdTienda', $idTienda)
            ->first();

        if(!empty($codArticulo) && !empty($cantArticulo)){
            if(empty($capturaManual)){
                CapturaManualTmp::insert([
                    'IdTienda' => $idTienda,
                    'CodArticulo' => $codArticulo,
                    'CantArticulo' => $cantArticulo,
                    'Referencia' => 'MANUAL',
                    'IdMovimiento' => 3
                ]);
            }
            else{
                CapturaManualTmp::where('IdTienda', $idTienda)
                    ->where('CodArticulo', $codArticulo)
                    ->update([
                        'CantArticulo' => $capturaManual->CantArticulo + $cantArticulo
                    ]);
            }
        }

        $articulosManual = DB::connection('server')->table('CapRecepcionManualTmp as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.IdTienda', $idTienda)
            ->get();

        return view('Recepcion.CapturaManualTmp', compact('articulosManual'));
    }

    public function EliminarProductoManual($idCapRecepcionManual){
        CapturaManualTmp::where('IdCapRecepcionManual', $idCapRecepcionManual)
            ->delete();

        return redirect('CapturaManualTmp');
    }

    public function RecepcionarProducto($idRecepcion, Request $request){
        $chkArticulo = $request->chkArticulo;
        $cantRecepcionada = $request->cantRecepcionada;

        if(empty($chkArticulo)){
            return redirect('RecepcionProducto')->with('msjdelete', 'Debe Seleccionar Productos a Recepcionar!');
        }

        try {
            DB::connection('server')->beginTransaction();
            DB::beginTransaction();

            foreach ($chkArticulo as $key => $referencia) {
                foreach ($cantRecepcionada as $codArticulo => $cRecepcionada) {
                    if($key == $codArticulo){
                        DatRecepcion::where('IdCapRecepcion', $idRecepcion)
                            ->where('CodArticulo', ''.$key.'')
                            ->update([
                                'CantRecepcionada' => $cRecepcionada,
                                'IdStatusRecepcion' => 2
                        ]);
    
                        //Si hay inventario de ese articulo para la tienda
                        $inventario = InventarioTienda::where('CodArticulo', ''.$key.'')
                            ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                            ->first();
    
                        if(empty($inventario)){
                            InventarioTienda::insert([
                                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                                'CodArticulo' => $key,
                                'StockArticulo' => $cRecepcionada
                            ]);

                            DB::connection('server')->table('DatInventario')->insert([
                                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                                'CodArticulo' => $key,
                                'StockArticulo' => $cRecepcionada
                            ]);
                        }
                        else{
                            InventarioTienda::where('CodArticulo', ''.$key.'')
                                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                                ->update([
                                    'StockArticulo' => $inventario->StockArticulo + $cRecepcionada
                                ]);

                            DB::connection('server')->table('DatInventario')->where('CodArticulo', ''.$key.'')
                                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                                ->update([
                                    'StockArticulo' => $inventario->StockArticulo + $cRecepcionada
                                ]);
                        }

                        HistorialMovimientoProducto::insert([
                            'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                            'CodArticulo' => $key,
                            'CantArticulo' => $cRecepcionada,
                            'FechaMovimiento' => date('d-m-Y H:i:s'),
                            'Referencia' => $referencia,
                            'IdMovimiento' => $referencia == 'MANUAL' ? 11 : 1,
                            'IdUsuario' => Auth::user()->IdUsuario
                        ]);
                    }
                } 
            }
    
            CapturaManualTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->delete();
    
            $faltantesPorRecepcionar = DatRecepcion::where('IdCapRecepcion', $idRecepcion)
                ->where('IdStatusRecepcion', 1)
                ->count();
    
            if($faltantesPorRecepcionar == 0){
                CapRecepcion::where('IdCapRecepcion', $idRecepcion)
                    ->update([
                        'IdStatusRecepcion' => 2,
                        'FechaRecepcion' => date('d-m-Y H:i:s'),
                        'IdUsuario' => Auth::user()->IdUsuario,
                        'IdTienda' => Auth::user()->usuarioTienda->IdTienda
                    ]);
            }
            
            DB::connection('server')->commit();
            DB::commit();

            // enviar correo de recepcion realizada
            try {

                $correoTienda = CorreoTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('Status', 0)
                    ->first();
                
                $correos = [
                    'soporte@kowi.com.mx',
                    'cponce@kowi.com.mx',
                    'sistemas@kowi.com.mx'
                ];

                array_push($correos, $correoTienda->GerenteCorreo, $correoTienda->Supervisor, $correoTienda->AlmacenistaCorreo);

                $correos = array_filter($correos);

                $maxIdCapRecepcion = CapRecepcion::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->max('IdCapRecepcion');

                $recepcion = CapRecepcion::with(['DetalleRecepcion', function($query){
                    $query->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatRecepcion.CodArticulo');
                }])
                    ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('IdCapRecepcion', $maxIdCapRecepcion)
                    ->first();

                $nomTienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->value('NomTienda');

                Mail::to($correos)
                    ->send(new RecepcionProductoMail($recepcion, $nomTienda));

            } catch (\Throwable $th) {
                return $th->getMessage();
            }
    
            return redirect('RecepcionProducto')->with('msjAdd', 'Productos Recepcionados Correctamente!');

        } catch (\Throwable $th) {
            DB::connection('server')->rollback();
            DB::rollBack();
            return redirect('RecepcionProducto')->with('msjdelete', 'Error'. $th->getMessage());
        }
    }

    public function CancelarRecepcion($idRecepcion, Request $request){
        $motivoCancelacion = $request->motivoCancelacion;

        try {
            DB::connection('server')->beginTransaction();
            
            CapRecepcion::where('IdCapRecepcion', $idRecepcion)
                ->update([
                    'IdStatusRecepcion' => 3,
                    'FechaCancelacion' => date('d-m-Y H:i:s'),
                    'MotivoCancelacion' => $motivoCancelacion,
                    'IdUsuario' => Auth::user()->IdUsuario
                ]);

            DatRecepcion::where('IdCapRecepcion', $idRecepcion)
                ->update([
                    'IdStatusRecepcion' => 3
                ]);

            DB::connection('server')->commit();
        } catch (\Throwable $th) {
            DB::connection('server')->rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        return redirect('RecepcionProducto')->with('msjdelete', 'Recepción Cancelada Correctamente!');
    }

    public function ReporteRecepciones(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $chkReferencia = $request->chkReferencia;
        $referencia = $request->referencia;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        if(!empty($chkReferencia)){
            $recepciones = CapRecepcion::with(['DetalleRecepcion' => function ($query){
                $query->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatRecepcion.CodArticulo')
                      ->leftJoin('CatStatusRecepcion', 'CatStatusRecepcion.IdStatusRecepcion', 'DatRecepcion.IdStatusRecepcion');
            }, 'StatusRecepcion'])
                ->where('Almacen', $tienda->Almacen)
                ->whereRaw("cast(FechaLlegada as date) between '".$fecha1."' and '".$fecha2."'")
                ->where('PackingList', $referencia)
                ->get();
        }
        else{
            $recepciones = CapRecepcion::with(['DetalleRecepcion' => function ($query){
                $query->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatRecepcion.CodArticulo')
                      ->leftJoin('CatStatusRecepcion', 'CatStatusRecepcion.IdStatusRecepcion', 'DatRecepcion.IdStatusRecepcion');
            }, 'StatusRecepcion'])
                ->where('Almacen', $tienda->Almacen)
                ->whereRaw("cast(FechaLlegada as date) between '".$fecha1."' and '".$fecha2."'")
                ->get();
        }

        //return $recepciones;

        return view('Recepcion.ReporteRecepciones', compact('recepciones', 'fecha1', 'fecha2', 'referencia', 'chkReferencia'));
    }

    public function RecepcionLocalSinInternet(Request $request){
        exec("ping -n 1 google.com", $salida, $codigo);

        if($codigo === 0){
            return redirect('RecepcionProducto');
        }

        $articulos = Articulo::where('Status', 0)
                ->get();

        $capturasSinInternet = DB::table('CapRecepcionManualTmp as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->get(); 

        return view('Recepcion.RecepcionLocalSinInternet', compact('articulos', 'capturasSinInternet'));
    }

    public function AgregarProductoLocalSinInternet(Request $request){
        try {
            DB::beginTransaction();

            RecepcionSinInternet::insert([
                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                'CodArticulo' => $request->codArticulo,
                'CantArticulo' => $request->cantArticulo,
                'IdMovimiento' => 13
            ]);
            
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }

        DB::commit();
        return back();
    }

    public function EliminarArticuloSinInternet($idCapRecepcionManual){
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

    public function RecepcionarProductoSinInternet(Request $request){
        $origen = $request->origen;

        try {
            DB::beginTransaction();

            $productos = RecepcionSinInternet::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->get();

            $idCapRecepcion = DB::table('CapRecepcion')
                    ->max('IdCapRecepcion')+1;

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
                'StatusInventario' => 0
            ]);

            //Sacar el IdCapRecepcion que acabo insertar
            $idCapRecepcion = DB::table('CapRecepcion')->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->max('IdCapRecepcion');

            //Insertar inventario y detalle de la recepcion
            foreach ($productos as $key => $producto) {
                $stock = InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('CodArticulo', $producto->CodArticulo)
                    ->sum('StockArticulo');

                if(empty($stock)){

                    InventarioTienda::insert([
                        'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                        'CodArticulo' => $producto->CodArticulo,
                        'StockArticulo' => $producto->CantArticulo
                    ]);

                }else{
                    InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->where('CodArticulo', $producto->CodArticulo)
                        ->update([
                            'StockArticulo' => $stock + $producto->CantArticulo
                        ]);
                }

                DB::table('DatRecepcion')->insert([
                    'IdCapRecepcion' => $idCapRecepcion,
                    'IdRecepcionLocal' => $idRecepcion,
                    'CodArticulo' => $producto->CodArticulo,
                    'CantEnviada' => $producto->CantArticulo,
                    'CantRecepcionada' => $producto->CantArticulo,
                    'Linea' => $key +1,
                    'IdStatusRecepcion' => 2
                ]);

                DB::table('DatHistorialMovimientos')->insert([
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'CodArticulo' => $producto->CodArticulo,
                    'CantArticulo' => $producto->CantArticulo,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => strtoupper($origen),
                    'IdMovimiento' => 13,
                    'IdUsuario' => Auth::user()->IdUsuario
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