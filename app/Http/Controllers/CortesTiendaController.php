<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tienda;
use App\Models\CorteTienda;
use App\Models\ClienteCloudTienda;
use App\Models\SolicitudFactura;
use App\Models\DatEncabezado;

class OpcionReportes{
    public $IdReporte;
    public $NomReporte;
}

class CortesTiendaController extends Controller
{
    public function VerCortesTienda(Request $request){
        $usuarioTienda = Auth::user()->usuarioTienda;

        if($usuarioTienda->doesntExist()){
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        if($usuarioTienda->Todas == 0){
            $tiendas = Tienda::where('Status', 0)
            ->get();
        }
        if(!empty($usuarioTienda->IdTienda)){
            $tiendas = Tienda::where('Status', 0)
            ->where('IdTienda', $usuarioTienda->IdTienda)
            ->get();
        }
        if(!empty($usuarioTienda->IdPlaza)){
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)
            ->where('Status', 0)
            ->get();
        }

        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $idReporte = $request->idReporte;
        
        $nomTienda = Tienda::where('IdTienda', $idTienda)
            ->value('NomTienda');

        for ($i=0; $i < 4 ; $i++) { 
            $opcionesReporte[] = new OpcionReportes;
            $opcionesReporte[$i]->IdReporte = $i+1;
            $i + 1 == 1 ? $opcionesReporte[$i]->NomReporte = 'Corte Diario' : '';
            $i + 1 == 2 ? $opcionesReporte[$i]->NomReporte = 'Concentrado de Ventas' : '';
            $i + 1 == 3 ? $opcionesReporte[$i]->NomReporte = 'Venta Por Ticket Diario' : '';
            $i + 1 == 4 ? $opcionesReporte[$i]->NomReporte = 'Tickets Cancelados' : '';
        }

        if($idReporte == 1){

            $billsTo = CorteTienda::where('IdTienda', $idTienda)
            ->distinct('Bill_To')
            ->whereDate('FechaVenta', $fecha1)
            ->where('StatusVenta', 0)
            ->whereNull('IdSolicitudFactura')
            ->pluck('Bill_To');

            $cortesTienda = ClienteCloudTienda::with(['Customer', 'CorteTienda' => function ($query) use ($idTienda, $fecha1){
                $query->where('DatCortesTienda.IdTienda', $idTienda)
                    ->where('DatCortesTienda.StatusVenta', 0)
                    ->whereDate('FechaVenta', $fecha1)
                    ->whereNull('DatCortesTienda.IdSolicitudFactura');
            }])
                ->where('IdTienda', $idTienda)
                ->select('IdClienteCloud', 'Bill_To', 'IdListaPrecio', 'IdTipoNomina')
                ->distinct('Bill_To')
                ->whereIn('Bill_To', $billsTo)
                ->get();

            //return $cortesTienda;

            $totalMonederoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha1)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 4)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $totalMonederoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha1)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 3)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            //return $totalMonederoSemanal;

            $totalTarjetaDebito = CorteTienda::where('IdTienda', $idTienda)
                                ->whereDate('FechaVenta', $fecha1)
                                ->where('IdTipoPago', 5)
                                ->where('StatusVenta', 0)
                                ->sum('ImporteArticulo');

            $totalTarjetaCredito = CorteTienda::where('IdTienda', $idTienda)
                                ->whereDate('FechaVenta', $fecha1)
                                ->where('IdTipoPago', 4)
                                ->where('StatusVenta', 0)
                                ->sum('ImporteArticulo');

            $totalEfectivo = CorteTienda::where('IdTienda', $idTienda)
                            ->whereDate('FechaVenta', $fecha1)
                            ->where('IdTipoPago', 1)
                            ->where('StatusVenta', 0)
                            ->sum('ImporteArticulo');

            $creditoQuincenal = DB::table('DatCortesTienda as a')
                                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                                ->where('IdTienda', $idTienda)
                                ->whereDate('FechaVenta', $fecha1)
                                ->where('StatusVenta', 0)
                                ->whereIn('IdTipoPago', [2, 7])
                                ->where('TipoNomina', 4)
                                ->sum('ImporteArticulo');

            $creditoSemanal = DB::table('DatCortesTienda as a')
                                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                                ->where('IdTienda', $idTienda)
                                ->whereDate('FechaVenta', $fecha1)
                                ->where('StatusVenta', 0)
                                ->whereIn('IdTipoPago', [2, 7])
                                ->where('TipoNomina', 3)
                                ->sum('ImporteArticulo');

            $totalTransferencia = DB::table('DatCortesTienda as a')
                                ->where('IdTienda', $idTienda)
                                ->whereDate('FechaVenta', $fecha1)
                                ->where('StatusVenta', 0)
                                ->where('IdTipoPago', 3)
                                ->sum('ImporteArticulo');

            $totalFactura = CorteTienda::where('IdTienda', $idTienda)
                            ->whereDate('FechaVenta', $fecha1)
                            ->where('StatusVenta', 0)
                            ->whereNotNull('IdSolicitudFactura')
                            ->sum('ImporteArticulo');

            //return $totalFactura;

            $facturas = SolicitudFactura::with(['Factura' => function ($query){
                $query->whereNotNull('DatCortesTienda.IdSolicitudFactura');
            }])
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaSolicitud', $fecha1)
                ->get();

            //return $facturas;

            return view('CortesTienda.VerCortesTienda', compact('tiendas', 'idTienda', 'fecha1', 'fecha2',
            'idReporte', 'opcionesReporte', 'cortesTienda', 'facturas', 'totalMonederoQuincenal', 'totalMonederoSemanal',
            'creditoQuincenal', 'creditoSemanal', 'totalTarjetaDebito', 'totalTarjetaCredito', 'totalTransferencia', 'totalFactura',
            'totalEfectivo', 'nomTienda'));
        }
        if($idReporte == 2){
            $concentrado = DB::table('DatEncabezado as a')
                        ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                        ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
                        ->select(DB::raw('c.CodArticulo, c.NomArticulo, SUM(b.CantArticulo) as Peso,
                                b.PrecioArticulo, SUM(b.IvaArticulo) as Iva , SUM(b.ImporteArticulo) as Importe'))
                        ->where('a.IdTienda', $idTienda)
                        ->where('a.StatusVenta', 0)
                        ->whereRaw("cast(a.FechaVenta as date) between '".$fecha1."' and '".$fecha2."'")
                        ->groupBy('c.CodArticulo', 'c.NomArticulo', 'b.PrecioArticulo')
                        ->orderBy('c.CodArticulo')
                        ->get();

            $totalPeso = DB::table('DatEncabezado as a')
                        ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                        ->where('a.IdTienda', $idTienda)
                        ->where('a.StatusVenta', 0)
                        ->whereRaw("cast(a.FechaVenta as date) between '".$fecha1."' and '".$fecha2."'")
                        ->sum('b.CantArticulo');

            $totalImporte = DB::table('DatEncabezado as a')
                        ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                        ->where('a.IdTienda', $idTienda)
                        ->where('a.StatusVenta', 0)
                        ->whereRaw("cast(a.FechaVenta as date) between '".$fecha1."' and '".$fecha2."'")
                        ->sum('b.ImporteArticulo');

            $totalIva = DB::table('DatEncabezado as a')
                        ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                        ->where('a.IdTienda', $idTienda)
                        ->where('a.StatusVenta', 0)
                        ->whereRaw("cast(a.FechaVenta as date) between '".$fecha1."' and '".$fecha2."'")
                        ->sum('b.IvaArticulo');

            return view('CortesTienda.VerCortesTienda', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'idReporte',
            'opcionesReporte', 'concentrado', 'totalPeso', 'totalImporte', 'totalIva', 'nomTienda'));
        }
        if($idReporte == 3){
            $tickets = DatEncabezado::with(['detalle' => function ($detalle){
                $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                    ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
                    }, 'TipoPago', 'SolicitudFactura'])
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha1)
                ->orderBy('IdTicket')
                ->get();
    
            $total = DatEncabezado::where('IdTienda', $idTienda)
                            ->whereDate('FechaVenta', $fecha1)
                            ->where('StatusVenta', 0)
                            ->sum('ImporteVenta');
    
            $totalIva = DatEncabezado::where('IdTienda', $idTienda)
                            ->whereDate('FechaVenta', $fecha1)
                            ->where('StatusVenta', 0)
                            ->sum('Iva');

            return view('CortesTienda.VerCortesTienda', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'idReporte', 'opcionesReporte',
                        'tickets', 'total', 'totalIva', 'nomTienda'));
        }
        if($idReporte == 4){
            $ticketsCancelados = DatEncabezado::with(['detalle' => function ($detalle){
                $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                    ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
                    }, 'TipoPago', 'SolicitudFactura', 'UsuarioCancelacion'])
                ->where('IdTienda', $idTienda)
                ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."' ")
                ->where('StatusVenta', 1)
                ->orderBy('FechaVenta')
                ->get();
    
            $total = DatEncabezado::where('IdTienda', $idTienda)
            ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."' ")
                            ->where('StatusVenta', 1)
                            ->sum('ImporteVenta');
    
            $totalIva = DatEncabezado::where('IdTienda', $idTienda)
            ->whereRaw("cast(FechaVenta as date) between '". $fecha1 ."' and '". $fecha2 ."' ")
                            ->where('StatusVenta', 1)
                            ->sum('Iva');

            //return $ticketsCancelados;

            return view('CortesTienda.VerCortesTienda', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'idReporte', 'opcionesReporte',
                        'ticketsCancelados', 'total', 'totalIva', 'nomTienda'));
        }


        return view('CortesTienda.VerCortesTienda', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'idReporte', 'opcionesReporte'));
    }   
}
