<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Articulo;
use App\Models\Precio;
use App\Models\DatDetPedidoTmp;
use App\Models\Tienda;
use App\Models\DatEncPedido;
use App\Models\DatDetPedido;
use App\Models\PreventaTmp;
use DateTime;

class PedidosController extends Controller
{
    public function Pedidos(){
        $tienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                            ->select('NomTienda')
                            ->first();
                            
        return view('Pedidos.Pedidos', compact('tienda'));
    }

    public function MostrarPedidos(){

        $usuarioIdTienda = Auth::user()->usuarioTienda->IdTienda;

        $datPedidos = DB::table('DatDetPedidoTmp as a')
                    ->leftJoin('CatArticulos as b', 'b.IdArticulo', 'a.IdArticulo')
                    ->select('a.*', 'b.IdArticulo', 'b.NomArticulo')
                    ->where('a.IdTienda', $usuarioIdTienda)
                    ->where('a.Status', 0)
                    ->get();
        //return $datPedidos;

        $sumImporte = DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                                        ->sum('SubTotalArticulo');

        $sumIva = DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                                    ->sum('IvaArticulo');

        $total = $sumImporte + $sumIva;

        $banderaEnabled = DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                                            ->count();

        $fechaHoy = date('Y-m-d');

        $manana = date("Y-m-d", strtotime($fechaHoy."+ 1 days"));

        return view('Pedidos.DatPedidos', compact('datPedidos', 'sumImporte', 'sumIva', 'total', 'banderaEnabled', 'fechaHoy', 'manana'));
    }

    public function EliminarArticuloPedido($id){
        DatDetPedidoTmp::where('IdDetPedidoTmp', $id)
                        ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->delete();

        return redirect('MostrarPedidos');
    }

    public function DatPedidos(Request $request){
        $usuarioIdTienda = Auth::user()->usuarioTienda->IdTienda;

        $codBarras = $request->txtCodEtiqueta;

        //200 0002 10010 2 -> Desglozar Codigo de Barras 
        $inicio = substr($codBarras, 0, 3);
        $plu = substr($codBarras, 3, 4);
        $primerPeso = substr($codBarras, 7, 5);
        $peso = ($primerPeso/1000);
        //Termina desgloce de codigo de barras

        //Sacar maximo IdDetPedidoTmp consecutivo
        $idDetPedidoTmp = DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                                        ->max('IdDetPedidoTmp')+1;

        if($inicio != '200'){
            $articulo = Articulo::where('Amece', $codBarras)
                                ->first();
                                
            if(empty($articulo)){
                //return 'Etiqueta mal escrita!';
                return redirect('MostrarPedidos')->with('AlertPedido', 'Etiqueta mal escrita!');
            }

            if($articulo->Peso != 0){
            $articuloPrecio = DB::table('DatPrecios as a')
                                ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
                                ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'a.IdListaPrecio')
                                ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'a.IdListaPrecio')
                                ->select('a.PrecioArticulo', 'b.IdArticulo', 'c.IdListaPrecio', 'b.CodArticulo', 'b.NomArticulo', 'c.PorcentajeIva')
                                ->where('Amece', $codBarras)
                                ->where('d.IdTienda', $usuarioIdTienda)
                                ->where('c.IdListaPrecio', '<>', 4)
                                ->first();

                if($articuloPrecio->PrecioArticulo == 0){
                    return redirect('MostrarPedidos')->with('AlertPedido', 'El Articulo ' .$articulo->NomArticulo. ' No Tiene Precio Asignado, Comuniquese Con El Gerente!');
                    //return 'El Articulo ' .$articulo->NomArticulo. ' No Tiene Precio Asignado, Comuniquese Con El Gerente!';
                    //return redirect('MostrarPedidos')->with('divAlerta', 'El Articulo ' .$articulo->NomArticulo. ' No Tiene Precio Asignado, Comuniquese Con El Gerente!');
                }

                $articulo->Iva == 0 ? $iva = $articuloPrecio->PorcentajeIva * ($articulo->Peso * $articuloPrecio->PrecioArticulo) : $iva = 0;
                $iva = number_format($iva, 2);
                $subTotal = number_format($articuloPrecio->PrecioArticulo * $articulo->Peso, 2);
                $importeArticulo = number_format($articulo->Peso * $articuloPrecio->PrecioArticulo + $iva, 2) ;

                $nomArticulo = $articuloPrecio->NomArticulo;
                $peso = $articulo->Peso;
                $precioArticulo = $articuloPrecio->PrecioArticulo;

                //Insertar en la Temporal para hacer calculos
                $datPedido = new DatDetPedidoTmp();
                $datPedido -> IdDetPedidoTmp = $idDetPedidoTmp;
                $datPedido -> IdTienda = $usuarioIdTienda;
                $datPedido -> IdArticulo = $articuloPrecio->IdArticulo;
                $datPedido -> IdListaPrecio = $articuloPrecio->IdListaPrecio;
                $datPedido -> CantArticulo = $peso;
                $datPedido -> SubTotalArticulo = $subTotal;
                $datPedido -> PrecioArticulo = $precioArticulo;
                $datPedido -> IvaArticulo = $iva;
                $datPedido -> ImporteArticulo = $importeArticulo;
                $datPedido -> Status = 0;
                $datPedido -> save();
            }
            else{
                return redirect('MostrarPedidos')->with('AlertPedido', 'El articulo ' .$articulo->NomArticulo. ' no tiene peso fijo comuniquese con el Gerente!');
                //return 'El articulo ' .$articulo->NomArticulo. ' no tiene peso fijo comuniquese con el Gerente!';
            }
        }
        else{
            $articulo = Articulo::where('CodEtiqueta', $plu)
                                ->first();

            if(empty($articulo)){
                return redirect('MostrarPedidos')->with('AlertPedido', 'Etiqueta Mal Escrita!');
                //return ("<script>window.top.location.href = \"/Pedidos\";</script>");
                //return back()->with('msjdelete', 'Etiqueta mal escrita!');
            }
            
            if($peso == 0){
                if($articulo->Peso != 0){
                    $articuloPrecio = DB::table('DatPrecios as a')
                                    ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
                                    ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'a.IdListaPrecio')
                                    ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'a.IdListaPrecio')
                                    ->select('a.PrecioArticulo', 'b.IdArticulo', 'c.IdListaPrecio', 'b.CodArticulo', 'b.NomArticulo', 'c.PorcentajeIva')
                                    ->where('b.CodEtiqueta', $plu)
                                    ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', $articulo->Peso)
                                    ->where('d.IdTienda', $usuarioIdTienda)
                                    ->where('c.IdListaPrecio', '<>', 4)
                                    ->first();

                    if($articuloPrecio->PrecioArticulo == 0){
                        return redirect('MostrarPedidos')->with('AlertPedido', 'El Articulo ' .$articulo->NomArticulo. ' No Tiene Precio Asignado, Comuniquese Con El Gerente!');
                    }

                    $articulo->Iva == 0 ? $iva = $articuloPrecio->PorcentajeIva * ($articulo->Peso * $articuloPrecio->PrecioArticulo) : $iva = 0;
                    $iva = number_format($iva, 2);
                    $subTotal = number_format($articulo->Peso * $articuloPrecio->PrecioArticulo, 2);
                    $importeArticulo = number_format($articulo->Peso * $articuloPrecio->PrecioArticulo + $iva, 2) ;

                    $nomArticulo = $articuloPrecio->NomArticulo;
                    $peso = $articulo->Peso;
                    $precioArticulo = $articuloPrecio->PrecioArticulo;
                    
                    //Insertar en la Temporal para hacer calculos
                    $datPedido = new DatDetPedidoTmp();
                    $datPedido -> IdDetPedidoTmp = $idDetPedidoTmp;
                    $datPedido -> IdTienda = $usuarioIdTienda;
                    $datPedido -> IdArticulo = $articuloPrecio->IdArticulo;
                    $datPedido -> IdListaPrecio = $articuloPrecio->IdListaPrecio;
                    $datPedido -> CantArticulo = $peso;
                    $datPedido -> SubTotalArticulo = $subTotal;
                    $datPedido -> PrecioArticulo = $precioArticulo;
                    $datPedido -> IvaArticulo = $iva;
                    $datPedido -> ImporteArticulo = $importeArticulo;
                    $datPedido -> Status = 0;
                    $datPedido -> save();

                }
                else{
                    return redirect('MostrarPedidos')->with('AlertPedido', 'El articulo ' .$articulo->NomArticulo. ' no tiene peso fijo, imprima de nuevo la etiqueta ó coloque el peso!');
                    //return 'El articulo ' .$articulo->NomArticulo. ' no tiene peso fijo, imprima de nuevo la etiqueta ó coloque el peso!';
                }
            }
            else{
                $articuloPrecio = DB::table('DatPrecios as a')
                                ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
                                ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'a.IdListaPrecio')
                                ->leftJoin('DatListaPrecioTienda as d', 'd.IdListaPrecio', 'a.IdListaPrecio')
                                ->select('a.PrecioArticulo', 'b.IdArticulo', 'c.IdListaPrecio', 'b.CodArticulo', 'b.NomArticulo', 'c.PorcentajeIva')
                                ->where('b.CodEtiqueta', $plu)
                                ->whereRaw('? between c.PesoMinimo and c.PesoMaximo', [$peso])
                                ->where('d.IdTienda', $usuarioIdTienda)
                                ->where('c.IdListaPrecio', '<>', 4)
                                ->first();

                                if(empty($articuloPrecio)){
                                    return redirect('MostrarPedidos')->with('AlertPedido', 'Etiqueta Mal Escrita!');
                                    //return 'no se encontro nada';
                                }
                                else{
                                    if($articuloPrecio->PrecioArticulo == 0){
                                        return redirect('MostrarPedidos')->with('AlertPedido', 'El Articulo ' .$articulo->NomArticulo. ' No Tiene Precio Asignado, Comuniquese Con El Gerente!');
                                    }

                                    $articulo->Iva == 0 ? $iva = $articuloPrecio->PorcentajeIva * ($peso * $articuloPrecio->PrecioArticulo) : $iva = 0;
                                    $iva = number_format($iva, 2);
                                    $subTotal = number_format($peso * $articuloPrecio->PrecioArticulo, 2);
                                    $importeArticulo = number_format($peso * $articuloPrecio->PrecioArticulo + $iva, 2);

                                    $nomArticulo = $articuloPrecio->NomArticulo;
                                    $precioArticulo = $articuloPrecio->PrecioArticulo;
                                    
                                    //Insertar en la Temporal para hacer calculos
                                    $datPedido = new DatDetPedidoTmp();
                                    $datPedido -> IdDetPedidoTmp = $idDetPedidoTmp;
                                    $datPedido -> IdTienda = $usuarioIdTienda;
                                    $datPedido -> IdArticulo = $articuloPrecio->IdArticulo;
                                    $datPedido -> IdListaPrecio = $articuloPrecio->IdListaPrecio;
                                    $datPedido -> CantArticulo = $peso;
                                    $datPedido -> SubTotalArticulo = $subTotal;
                                    $datPedido -> PrecioArticulo = $precioArticulo;
                                    $datPedido -> IvaArticulo = $iva;
                                    $datPedido -> ImporteArticulo = $importeArticulo;
                                    $datPedido -> Status = 0;
                                    $datPedido -> save();
                                }
            }
        }
        return redirect('MostrarPedidos');
    }

    public function GuardarPedido(Request $request){
        $usuarioIdTienda = Auth::user()->usuarioTienda->IdTienda;
        $idPedido = DatEncPedido::max('IdPedido')+1;
        $nomCliente = $request->NomCliente;
        $telefono = $request->Telefono;
        $fechaPedido = date('d-m-Y H:i:s');
        $fechaRecoger = $request->FechaRecoger;
        $subTotalDetPedido = DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                                        ->sum('SubTotalArticulo');
        $ivaDetPedido = DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                                        ->sum('IvaArticulo');
        $subTotalPedido = number_format($subTotalDetPedido, 2);
        $ivaPedido = number_format($ivaDetPedido, 2);
        $importePedidoTmp = DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                                        ->sum('ImporteArticulo');
        $importePedido = number_format($importePedidoTmp, 2);
        $idUsuario = Auth::user()->IdUsuario;
        $dateCreate = date_create($fechaRecoger);

        $caja = DB::table('DatCajas as a')
                    ->leftJoin('CatCajas as b', 'b.IdCaja', 'a.IdCaja')
                    ->where('IdTienda', $usuarioIdTienda)
                    ->where('a.Activa', 0)
                    ->where('a.Status', 0)
                    ->first();

        if(empty($caja)){
            return redirect('Pos')->with('Pos', 'La Tienda No Tiene Caja Activa, Comuniquese con Sistemas!');
        }

        //Guardar Encabezado del Pedido
        $datEncPedido = new DatEncPedido();
        $datEncPedido -> IdPedido = 0;
        $datEncPedido -> IdTienda = $usuarioIdTienda;
        $datEncPedido -> Cliente = $nomCliente;
        $datEncPedido -> Telefono = $telefono;
        $datEncPedido -> FechaPedido = $fechaPedido;
        $datEncPedido -> FechaRecoger = date_format($dateCreate, "d-m-Y H:i:s");
        $datEncPedido -> SubTotalPedido = $subTotalPedido;
        $datEncPedido -> IvaPedido = $ivaPedido;
        $datEncPedido -> ImportePedido = $importePedido;
        $datEncPedido -> IdUsuario = $idUsuario;
        $datEncPedido -> Status = 0;
        $datEncPedido -> save();

        $idDatEncPedido = DatEncPedido::where('IdTienda', $usuarioIdTienda)
                            ->max('IdDatEncPedido');

        $idPedido = $usuarioIdTienda . $caja->NumCaja . $idDatEncPedido;

        DatEncPedido::where('IdTienda', $usuarioIdTienda)
                            ->where('IdDatEncPedido', $idDatEncPedido)
                            ->update([
                                'IdPedido' => $idPedido
        ]);
                
        //Guardar Detalle del Pedido
        $sqlInsertDetalle = "insert into DatDetPedido". 
                            " select ".$idPedido.", IdArticulo, CantArticulo, IdListaPrecio,". 
                            " SubTotalArticulo, IvaArticulo, ImporteArticulo, PrecioArticulo".
                            " from DatDetPedidoTmp".
                            " where IdTienda = " . $usuarioIdTienda;

        DB::statement($sqlInsertDetalle);

        DatDetPedidoTmp::where('IdTienda', $usuarioIdTienda)
                        ->delete();
        return back()->with('PedidoGuardado', 'Pedido Guardado con Exito!');
    }

    public function PedidosGuardados(Request $request){

        $usuarioIdTienda = Auth::user()->usuarioTienda->IdTienda;

        $tienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                        ->first();

        $fechaPedido = $request->FechaPedido;
        $txtCliente = $request->txtCliente;
        $selectBusqueda = $request->selectBusqueda;

        $fechaHoy = date('d-m-Y');
        $haceDosDias = date("d-m-Y", strtotime($fechaHoy."- 2 days"));

        if(empty($fechaPedido) && empty($txtCliente)){
            $pedidos = DatEncPedido::with('ArticuloDetalle', 'Venta')
                        ->where('IdTienda', $usuarioIdTienda)
                        //->whereDate('FechaPedido', '>=', $haceDosDias)
                        ->orderBy('FechaPedido', 'desc')
                        ->get();

            //return $pedidos;
        }
        if($selectBusqueda == 1){
            $pedidos = DatEncPedido::with('ArticuloDetalle')
                        ->where('IdTienda', $usuarioIdTienda)
                        ->where('Cliente', 'like', '%'.$txtCliente.'%')
                        ->orderBy('FechaPedido')
                        ->get();
        }
        if($selectBusqueda == 2){
            $pedidos = DatEncPedido::with('ArticuloDetalle')
                        ->where('IdTienda', $usuarioIdTienda)
                        ->whereDate('FechaPedido', $fechaPedido)
                        ->orderBy('FechaPedido')
                        ->get();
        }

        //return $pedidos;
        return view('Pedidos.PedidosGuardados', compact('pedidos', 'tienda', 'fechaPedido', 'txtCliente', 'selectBusqueda'));
    }

    public function EnviarAPreventa($idPedido){

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $idDatVentaTmp = PreventaTmp::where('IdTienda', $idTienda)
                ->max('IdDatVentaTmp')+1;
        
        $detallePedido = DatDetPedido::where('IdPedido', $idPedido)
                        ->get();

        //Iterar detalle del pedido para insertar al calculo de POS(preventa)
        foreach ($detallePedido as $index => $detalle) {
            PreventaTmp::insert([
                'IdDatVentaTmp' => $index+1,
                'IdTienda' => $idTienda,
                'IdArticulo' => $detalle->IdArticulo,
                'CantArticulo' => $detalle->CantArticulo,
                'PrecioLista' => $detalle->PrecioArticulo,
                'PrecioVenta' => $detalle->PrecioArticulo,
                'IdListaPrecio' => $detalle->IdListaPrecio,
                'IvaArticulo' => $detalle->IvaArticulo,
                'SubTotalArticulo' => $detalle->SubTotalArticulo,
                'ImporteArticulo' => $detalle->ImporteArticulo,
                'IdPedido' => $detalle->IdPedido,
                'Status' => 0
            ]);
        }

        return redirect('/Pos');
    }

    public function CancelarPedido($idPedido){
        DatEncPedido::where('IdPedido', $idPedido)
                    ->update([
                        'Status' => 1
                    ]);

        return back();
    }
}
