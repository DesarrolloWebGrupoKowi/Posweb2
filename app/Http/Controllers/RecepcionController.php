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

class RecepcionController extends Controller
{
    public function RecepcionProducto(Request $request){
        $tienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->first();

        $recepcion = CapRecepcion::with('StatusRecepcion')
            ->where('Almacen', $tienda->Almacen)
            ->where('IdStatusRecepcion', 1)
            ->get();

        $idRecepcion = $request->idRecepcion;
        empty($idRecepcion) ? $idRecepcion = 0 : $idRecepcion = $idRecepcion;

        $detalleRecepcion = DB::select("select c.PackingList, c.Almacen, a.*, b.NomArticulo".
                                        " from DatRecepcion as a".
                                        " left join CatArticulos as b on b.CodArticulo=a.CodArticulo".
                                        " left join CapRecepcion as c on c.IdCapRecepcion=a.IdCapRecepcion".
                                        " where a.IdCapRecepcion = ".$idRecepcion."".
                                        " and a.IdStatusRecepcion = 1".
                                        " union all".
                                        " select Referencia, '".$tienda->Almacen."', 0, 0, a.CodArticulo, a.CantArticulo, 0, 1, b.NomArticulo".
                                        " from CapRecepcionManualTmp as a".
                                        " left join CatArticulos as b on b.CodArticulo=a.CodArticulo");

        $totalRecepcion = DatRecepcion::where('IdCapRecepcion', $idRecepcion)
            ->where('IdStatusRecepcion', 1)
            ->sum('CantEnviada');

        $totalManual = CapturaManualTmp::sum('CantArticulo');
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
            $dRecepcion = DB::table('CapRecepcion as a')
                ->leftJoin('DatRecepcion as b', 'b.IdCapRecepcion', 'b.IdCapRecepcion')
                ->where('a.Almacen', $tienda->Almacen)
                ->where('a.IdStatusRecepcion', 1)
                ->where('b.IdStatusRecepcion', 1)
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

        $articulosManual = DB::table('CapRecepcionManualTmp as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
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
                        }
                        else{
                            InventarioTienda::where('CodArticulo', ''.$key.'')
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
                            'IdMovimiento' => $referencia == 'MANUAL' ? 3 : 1,
                            'IdUsuario' => Auth::user()->IdUsuario
                        ]);
                    }
                } 
            }
    
            CapturaManualTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->truncate();
    
            $faltantesPorRecepcionar = DatRecepcion::where('IdCapRecepcion', $idRecepcion)
                ->where('IdStatusRecepcion', 1)
                ->count();
    
            if($faltantesPorRecepcionar == 0){
                CapRecepcion::where('IdCapRecepcion', $idRecepcion)
                    ->update([
                        'IdStatusRecepcion' => 2,
                        'FechaRecepcion' => date('d-m-Y H:i:s'),
                        'IdUsuario' => Auth::user()->IdUsuario
                    ]);
            }
            DB::commit();
    
            return redirect('RecepcionProducto')->with('msjAdd', 'Productos Recepcionados Correctamente!');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('RecepcionProducto')->with('msjdelete', 'Error'. $th->getMessage());
        }
    }

    public function CancelarRecepcion($idRecepcion, Request $request){
        $motivoCancelacion = $request->motivoCancelacion;

        CapRecepcion::where('IdCapRecepcion', $idRecepcion)
            ->update([
                'IdStatusRecepcion' => 3,
                'FechaCancelacion' => date('d-m-Y H:i:s'),
                'MotivoCancelacion' => $motivoCancelacion,
                'IdUsuario' => Auth::user()->IdUsuario
            ]);

        return redirect('RecepcionProducto')->with('msjdelete', 'Recepción Cancelada!');
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
}
