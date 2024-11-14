<?php

namespace App\Http\Controllers;

use App\Models\ClienteCloudTienda;
use App\Models\CorteTienda;
use App\Models\DatCaja;
use App\Models\DatEncabezado;
use App\Models\SolicitudFactura;
use App\Models\Tienda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class OpcionReportes
{
    public $IdReporte;
    public $NomReporte;
}

class CortesTiendaController extends Controller
{
    public function VerCortesTienda(Request $request)
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if ($usuarioTienda->doesntExist()) {
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

        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $idReporte = $request->idReporte;
        $idCaja = $request->idCaja;

        $nomTienda = Tienda::where('IdTienda', $idTienda)
            ->value('NomTienda');

        $cajasTienda = DatCaja::where('IdTienda', $idTienda)
            ->get();

        //Aqui creo las opciones del menú
        for ($i = 0; $i < 4; $i++) {
            $opcionesReporte[] = new OpcionReportes;
            $opcionesReporte[$i]->IdReporte = $i + 1;
            $i + 1 == 1 ? $opcionesReporte[$i]->NomReporte = 'Corte Diario' : '';
            $i + 1 == 2 ? $opcionesReporte[$i]->NomReporte = 'Concentrado de Ventas' : '';
            $i + 1 == 3 ? $opcionesReporte[$i]->NomReporte = 'Venta Por Ticket Diario' : '';
            $i + 1 == 4 ? $opcionesReporte[$i]->NomReporte = 'Tickets Cancelados' : '';
        }

        //Corte diario
        if ($idReporte == 1) {
            if ($idCaja == 0) {
                $billsTo = CorteTienda::where('IdTienda', $idTienda)
                    ->distinct('Bill_To')
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->whereNull('IdSolicitudFactura')
                    ->pluck('Bill_To');

                $cortesTienda = ClienteCloudTienda::with([
                    'Customer',
                    'CorteTienda' => function ($query) use ($idTienda, $fecha1, $idCaja) {
                        $query->where('DatCortesTienda.IdTienda', $idTienda)
                            ->where('DatCortesTienda.StatusVenta', 0)
                            ->whereDate('FechaVenta', $fecha1)
                            ->whereNull('DatCortesTienda.IdSolicitudFactura');
                    }
                ])
                    ->where('IdTienda', $idTienda)
                    ->select('IdClienteCloud', 'Bill_To', 'IdListaPrecio', 'IdTipoNomina')
                    ->distinct('Bill_To')
                    ->whereIn('Bill_To', $billsTo)
                    ->get();

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

                $facturas = SolicitudFactura::with(['Factura' => function ($query) use ($idCaja) {
                    $query->whereNotNull('DatCortesTienda.IdSolicitudFactura');
                }])
                    ->where('IdTienda', $idTienda)
                    ->whereDate('FechaSolicitud', $fecha1)
                    ->get();
            } else {
                $billsTo = CorteTienda::where('IdTienda', $idTienda)
                    ->distinct('Bill_To')
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->whereNull('IdSolicitudFactura')
                    ->where('IdDatCaja', $idCaja)
                    ->pluck('Bill_To');

                // TODO: CORTE GENERAL
                $cortesTienda = ClienteCloudTienda::with([
                    'PedidoOracle' => function ($oraclePedido) use ($fecha1, $idTienda) {
                        $oraclePedido->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH', 'XXH.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                            ->whereDate('FechaVenta', $fecha1)
                            ->where('IdTienda', $idTienda)
                            ->where('StatusVenta', 0)
                            ->select(
                                'DatCortesTienda.Bill_To',
                                'DatCortesTienda.Source_Transaction_Identifier',
                                'XXH.STATUS'
                            )
                            ->distinct('DatCortesTienda.Source_Transaction_Identifier');
                    },
                    'Customer',
                    'CorteTiendaOracle' => function ($query) use ($idTienda, $fecha1, $idCaja) {
                        $query->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH2', 'XXH2.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                            ->where('DatCortesTienda.IdTienda', $idTienda)
                            ->where('DatCortesTienda.StatusVenta', 0)
                            ->where('DatCortesTienda.IdDatCaja', $idCaja)
                            ->whereDate('FechaVenta', $fecha1)
                            ->whereNull('DatCortesTienda.IdSolicitudFactura');
                    },
                ])
                    ->select('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
                    ->groupBy('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
                    ->where('IdTienda', $idTienda)
                    ->whereIn('Bill_To', $billsTo)
                    ->get();

                $totalMonedero = DB::table('DatCortesTienda as a')
                    ->leftjoin(
                        'DatClientesCloudTienda as b',
                        function ($join) {
                            $join->on('b.Bill_To', 'a.Bill_To')
                                ->on('b.IdListaPrecio', 'a.IdListaPrecio')
                                ->on('b.IdTienda', 'a.IdTienda')
                                ->on('b.IdTipoPago', 'a.IdTipoPago');
                        }
                    )
                    ->leftJoin('CatClientesCloud as c', 'c.IdClienteCloud', 'b.IdClienteCloud')
                    ->select(DB::raw('a.Bill_To, NomClienteCloud, SUM(a.ImporteArticulo) as importe'))
                    ->where('a.IdTienda', $idTienda)
                    ->where('b.IdTienda', $idTienda)
                    ->whereDate('a.FechaVenta', $fecha1)
                    ->where('a.IdTipoPago', 7)
                    ->where('a.IdListaPrecio', 4)
                    ->where('a.StatusVenta', 0)
                    ->groupBy('a.Bill_To', 'NomClienteCloud')
                    ->get();

                $totalTarjetaDebito = CorteTienda::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('IdTipoPago', 5)
                    ->where('StatusVenta', 0)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('ImporteArticulo');

                $totalTarjetaCredito = CorteTienda::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('IdTipoPago', 4)
                    ->where('StatusVenta', 0)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('ImporteArticulo');

                $totalEfectivo = CorteTienda::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('IdTipoPago', 1)
                    ->where('StatusVenta', 0)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('ImporteArticulo');

                $creditoQuincenal = DB::table('DatCortesTienda as a')
                    ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                    ->where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->whereIn('IdTipoPago', [2])
                    ->where('TipoNomina', 4)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('ImporteArticulo');

                $creditoSemanal = DB::table('DatCortesTienda as a')
                    ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                    ->where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->whereIn('IdTipoPago', [2])
                    ->where('TipoNomina', 3)
                    ->where('a.IdDatCaja', $idCaja)
                    ->sum('ImporteArticulo');

                $totalTransferencia = DB::table('DatCortesTienda as a')
                    ->where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->where('IdTipoPago', 3)
                    ->where('a.IdDatCaja', $idCaja)
                    ->sum('ImporteArticulo');

                $totalFactura = CorteTienda::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->whereNotNull('IdSolicitudFactura')
                    ->where('IdDatCaja', $idCaja)
                    ->sum('ImporteArticulo');

                // TODO: CLIENTES FACTURAS
                $facturas = SolicitudFactura::with([
                    'PedidoOracle' => function ($oraclePedido) use ($fecha1, $idTienda) {
                        $oraclePedido
                            ->select(
                                'DatCortesTienda.IdSolicitudFactura',
                                'DatCortesTienda.Bill_To',
                                'DatCortesTienda.Source_Transaction_Identifier',
                                'XXH.STATUS'
                            )
                            ->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH', 'XXH.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                            ->whereDate('FechaVenta', $fecha1)
                            ->where('IdTienda', $idTienda)
                            ->distinct('DatCortesTienda.Source_Transaction_Identifier');
                    },
                    'Factura' => function ($query) use ($idCaja) {
                        $query->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH2', 'XXH2.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                            ->whereNotNull('DatCortesTienda.IdSolicitudFactura')
                            ->where('DatCortesTienda.IdDatCaja', $idCaja);
                    }
                ])
                    ->where('IdTienda', $idTienda)
                    // ->where('Status', 0)
                    ->whereDate('FechaSolicitud', $fecha1)
                    ->get();
            }

            $numCaja = DatCaja::where('IdDatCajas', $idCaja)
                ->value('IdCaja');


            return view('CortesTienda.VerCortesTienda', compact(
                'tiendas',
                'idTienda',
                'fecha1',
                'fecha2',
                'cajasTienda',
                'idReporte',
                'opcionesReporte',
                'cortesTienda',
                'facturas',
                // 'totalMonederoQuincenal',
                // 'totalMonederoSemanal',
                'totalMonedero',
                'creditoQuincenal',
                'creditoSemanal',
                'totalTarjetaDebito',
                'totalTarjetaCredito',
                'totalTransferencia',
                'totalFactura',
                'totalEfectivo',
                'nomTienda',
                'idCaja',
                'numCaja'
            ));
        }
        //Concentrado de ventas por rango de fechas
        if ($idReporte == 2) {
            if ($idCaja == 0) {
                $concentrado = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
                    ->select(DB::raw('c.CodArticulo, c.NomArticulo, SUM(b.CantArticulo) as Peso,
                            b.PrecioArticulo, SUM(b.IvaArticulo) as Iva , SUM(b.ImporteArticulo) as Importe'))
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->groupBy('c.CodArticulo', 'c.NomArticulo', 'b.PrecioArticulo')
                    ->orderBy('c.CodArticulo')
                    ->get();

                $totalPeso = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->sum('b.CantArticulo');

                $totalImporte = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->sum('b.ImporteArticulo');

                $totalIva = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->sum('b.IvaArticulo');
            } else {
                $concentrado = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
                    ->leftJoin('CatFamilias as d', 'c.IdFamilia', 'd.IdFamilia')
                    ->leftJoin('CatGrupos as e', 'c.IdGrupo', 'e.IdGrupo')
                    ->select(DB::raw('c.CodArticulo, c.NomArticulo, d.NomFamilia, e.NomGrupo, SUM(b.CantArticulo) as Peso,
                            b.PrecioArticulo, SUM(b.IvaArticulo) as Iva , SUM(b.ImporteArticulo) as Importe'))
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->where('a.IdDatCaja', $idCaja)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->groupBy('c.CodArticulo', 'c.NomArticulo', 'b.PrecioArticulo', 'd.NomFamilia', 'e.NomGrupo')
                    ->orderBy('c.CodArticulo')
                    ->get();

                $totalPeso = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->where('a.IdDatCaja', $idCaja)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->sum('b.CantArticulo');

                $totalImporte = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->where('a.IdDatCaja', $idCaja)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->sum('b.ImporteArticulo');

                $totalIva = DB::table('DatEncabezado as a')
                    ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                    ->where('a.IdTienda', $idTienda)
                    ->where('a.StatusVenta', 0)
                    ->where('a.IdDatCaja', $idCaja)
                    ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                    ->sum('b.IvaArticulo');
            }

            $numCaja = DatCaja::where('IdDatCajas', $idCaja)
                ->value('IdCaja');

            return view('CortesTienda.VerCortesTienda', compact(
                'tiendas',
                'idTienda',
                'fecha1',
                'fecha2',
                'idReporte',
                'opcionesReporte',
                'concentrado',
                'totalPeso',
                'totalImporte',
                'totalIva',
                'nomTienda',
                'cajasTienda',
                'idCaja',
                'numCaja'
            ));
        }
        //Venta por ticket diario
        if ($idReporte == 3) {
            if ($idCaja == 0) {
                $cajas = DatCaja::where('IdTienda', $idTienda)
                    ->get();

                foreach ($cajas as $key => $caja) {
                    $tickets[$key] = DatEncabezado::with(['detalle' => function ($detalle) {
                        $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                            ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                            ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
                    }, 'Caja', 'TipoPago', 'SolicitudFactura', 'SolicitudCancelacionTicket'])
                        ->where('IdTienda', $idTienda)
                        ->where('IdDatCaja', $caja->IdDatCajas)
                        ->whereDate('FechaVenta', $fecha1)
                        ->orderBy('IdTicket')
                        ->get();

                    $total[$key] = DatEncabezado::where('IdTienda', $idTienda)
                        ->whereDate('FechaVenta', $fecha1)
                        ->where('StatusVenta', 0)
                        ->where('IdDatCaja', $caja->IdDatCajas)
                        ->sum('ImporteVenta');

                    $totalIva[$key] = DatEncabezado::where('IdTienda', $idTienda)
                        ->whereDate('FechaVenta', $fecha1)
                        ->where('StatusVenta', 0)
                        ->where('IdDatCaja', $caja->IdDatCajas)
                        ->sum('Iva');
                }
            } else {
                $tickets = DatEncabezado::with(['detalle' => function ($detalle) {
                    $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                        ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                        ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
                }, 'Caja', 'TipoPago', 'SolicitudFactura', 'SolicitudCancelacionTicket'])
                    ->where('IdTienda', $idTienda)
                    ->where('IdDatCaja', $idCaja)
                    ->whereDate('FechaVenta', $fecha1)
                    ->orderBy('IdTicket')
                    ->get();

                $total = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('ImporteVenta');

                $totalIva = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereDate('FechaVenta', $fecha1)
                    ->where('StatusVenta', 0)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('Iva');
            }

            $numCaja = DatCaja::where('IdDatCajas', $idCaja)
                ->value('IdCaja');

            //return $tickets;

            return view('CortesTienda.VerCortesTienda', compact(
                'tiendas',
                'idTienda',
                'fecha1',
                'fecha2',
                'idReporte',
                'opcionesReporte',
                'tickets',
                'total',
                'totalIva',
                'nomTienda',
                'cajasTienda',
                'idCaja',
                'numCaja'
            ));
        }
        //Tickets cancelados por rango de fechas
        if ($idReporte == 4) {
            if ($idCaja == 0) {
                $ticketsCancelados = DatEncabezado::with(['detalle' => function ($detalle) {
                    $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                        ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                        ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
                }, 'Caja', 'TipoPago', 'SolicitudFactura', 'UsuarioCancelacion'])
                    ->where('IdTienda', $idTienda)
                    ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                    ->where('StatusVenta', 1)
                    ->orderBy('FechaVenta')
                    ->get();

                $total = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                    ->where('StatusVenta', 1)
                    ->sum('ImporteVenta');

                $totalIva = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                    ->where('StatusVenta', 1)
                    ->sum('Iva');
            } else {
                $ticketsCancelados = DatEncabezado::with(['detalle' => function ($detalle) {
                    $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                        ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                        ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
                }, 'Caja', 'TipoPago', 'SolicitudFactura', 'UsuarioCancelacion'])
                    ->where('IdTienda', $idTienda)
                    ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                    ->where('StatusVenta', 1)
                    ->where('IdDatCaja', $idCaja)
                    ->orderBy('FechaVenta')
                    ->get();

                $total = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                    ->where('StatusVenta', 1)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('ImporteVenta');

                $totalIva = DatEncabezado::where('IdTienda', $idTienda)
                    ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                    ->where('StatusVenta', 1)
                    ->where('IdDatCaja', $idCaja)
                    ->sum('Iva');
            }
            //return $ticketsCancelados;

            $numCaja = DatCaja::where('IdDatCajas', $idCaja)
                ->value('IdCaja');

            return view('CortesTienda.VerCortesTienda', compact(
                'tiendas',
                'idTienda',
                'fecha1',
                'fecha2',
                'idReporte',
                'opcionesReporte',
                'ticketsCancelados',
                'total',
                'totalIva',
                'nomTienda',
                'cajasTienda',
                'idCaja',
                'numCaja'
            ));
        }

        return view('CortesTienda.VerCortesTienda', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'idReporte', 'opcionesReporte', 'cajasTienda', 'idCaja'));
    }

    public function BuscarCajasTienda(Request $request)
    {
        $idTienda = $request->idTienda;

        return DatCaja::where('IdTienda', $idTienda)
            ->orderBy('IdCaja')
            ->get();
    }

    public function GenerarCorteOraclePDF($fecha, $idTienda, $idDatCaja)
    {
        $tienda = Tienda::where('IdTienda', $idTienda)
            ->first();

        $numCaja = DatCaja::where('IdDatCajas', $idDatCaja)
            ->value('IdCaja');

        if ($idDatCaja == 0) {
            $billsTo = CorteTienda::where('IdTienda', $idTienda)
                ->distinct('Bill_To')
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereNull('IdSolicitudFactura')
                ->pluck('Bill_To');

            $cortesTienda = ClienteCloudTienda::with(['Customer', 'CorteTienda' => function ($query) use ($fecha, $idTienda) {
                $query->where('DatCortesTienda.IdTienda', $idTienda)
                    ->where('DatCortesTienda.StatusVenta', 0)
                    ->whereDate('DatCortesTienda.FechaVenta', $fecha)
                    ->whereNull('DatCortesTienda.IdSolicitudFactura');
            }])
                ->where('IdTienda', $idTienda)
                ->select('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
                ->groupBy('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
                ->whereIn('Bill_To', $billsTo)
                ->get();

            $facturas = SolicitudFactura::with(['Factura' => function ($query) {
                $query->whereNotNull('DatCortesTienda.IdSolicitudFactura');
            }])
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaSolicitud', $fecha)
                ->get();

            $totalTarjetaDebito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 5)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $totalTarjetaCredito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 4)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $totalEfectivo = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 1)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $creditoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 4)
                ->sum('ImporteArticulo');

            $creditoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 3)
                ->sum('ImporteArticulo');

            $totalTransferencia = DB::table('DatCortesTienda as a')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdTipoPago', 3)
                ->sum('ImporteArticulo');

            $totalFactura = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereNotNull('IdSolicitudFactura')
                ->sum('ImporteArticulo');

            $totalMonederoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 4)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $totalMonederoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 7)
                ->where('IdListaPrecio', 4)
                ->where('b.TipoNomina', 3)
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');

            $info = [
                'titulo' => 'Corte Diario de Tienda',
                'nomTienda' => $tienda->NomTienda,
                'numCaja' => $numCaja,
                'fecha' => strftime("%d %B del %Y", strtotime($fecha)),
                'cortesTienda' => $cortesTienda,
                'facturas' => $facturas,
                'totalTarjetaDebito' => $totalTarjetaDebito,
                'totalTarjetaCredito' => $totalTarjetaCredito,
                'totalEfectivo' => $totalEfectivo,
                'creditoQuincenal' => $creditoQuincenal,
                'creditoSemanal' => $creditoSemanal,
                'totalTransferencia' => $totalTransferencia,
                'totalFactura' => $totalFactura,
                'totalMonederoQuincenal' => $totalMonederoQuincenal,
                'totalMonederoSemanal' => $totalMonederoSemanal,
            ];
        } else {
            $billsTo = CorteTienda::where('IdTienda', $idTienda)
                ->distinct('Bill_To')
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->whereNull('IdSolicitudFactura')
                ->pluck('Bill_To');

            $cortesTienda = ClienteCloudTienda::with([
                'PedidoOracle' => function ($oraclePedido) use ($fecha, $idTienda) {
                    $oraclePedido->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH', 'XXH.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                        ->whereDate('FechaVenta', $fecha)
                        ->where('IdTienda', $idTienda)
                        ->select(
                            'DatCortesTienda.Bill_To',
                            'DatCortesTienda.Source_Transaction_Identifier',
                            'XXH.STATUS'
                        )
                        ->distinct('DatCortesTienda.Source_Transaction_Identifier');
                },
                'Customer',
                'CorteTiendaOracle' => function ($query) use ($idTienda, $fecha, $idDatCaja) {
                    $query->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH2', 'XXH2.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                        ->where('DatCortesTienda.IdTienda', $idTienda)
                        ->where('DatCortesTienda.StatusVenta', 0)
                        ->where('DatCortesTienda.IdDatCaja', $idDatCaja)
                        ->whereDate('FechaVenta', $fecha)
                        ->whereNull('DatCortesTienda.IdSolicitudFactura');
                },
            ])
                ->select('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
                ->groupBy('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
                ->where('IdTienda', $idTienda)
                ->whereIn('Bill_To', $billsTo)
                ->get();

            // return $cortesTienda;

            $facturas = SolicitudFactura::with([
                'Factura' => function ($query) use ($idDatCaja) {
                    $query->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH2', 'XXH2.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                        ->whereNotNull('DatCortesTienda.IdSolicitudFactura')
                        ->where('DatCortesTienda.IdDatCaja', $idDatCaja);
                }
            ])
                ->where('IdTienda', $idTienda)
                ->where('Status', 0)
                ->whereDate('FechaSolicitud', $fecha)
                ->get();

            $totalTarjetaDebito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 5)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalTarjetaCredito = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 4)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalEfectivo = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('IdTipoPago', 1)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $creditoQuincenal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 4)
                ->where('a.IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $creditoSemanal = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->whereIn('IdTipoPago', [2, 7])
                ->where('TipoNomina', 3)
                ->where('a.IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalTransferencia = DB::table('DatCortesTienda as a')
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdTipoPago', 3)
                ->where('a.IdDatCaja', $idDatCaja)
                ->sum('ImporteArticulo');

            $totalFactura = CorteTienda::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fecha)
                ->where('StatusVenta', 0)
                ->where('IdDatCaja', $idDatCaja)
                ->whereNotNull('IdSolicitudFactura')
                ->sum('ImporteArticulo');

            // $totalMonederoQuincenal = DB::table('DatCortesTienda as a')
            //     ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            //     ->where('IdTienda', $idTienda)
            //     ->whereDate('FechaVenta', $fecha)
            //     ->where('IdTipoPago', 7)
            //     ->where('IdListaPrecio', 4)
            //     ->where('b.TipoNomina', 4)
            //     ->where('StatusVenta', 0)
            //     ->where('a.IdDatCaja', $idDatCaja)
            //     ->sum('ImporteArticulo');

            // $totalMonederoSemanal = DB::table('DatCortesTienda as a')
            //     ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            //     ->where('IdTienda', $idTienda)
            //     ->whereDate('FechaVenta', $fecha)
            //     ->where('IdTipoPago', 7)
            //     ->where('IdListaPrecio', 4)
            //     ->where('b.TipoNomina', 3)
            //     ->where('a.IdDatCaja', $idDatCaja)
            //     ->where('StatusVenta', 0)
            //     ->sum('ImporteArticulo');

            $totalMonedero = DB::table('DatCortesTienda as a')
                ->leftjoin(
                    'DatClientesCloudTienda as b',
                    function ($join) {
                        $join->on('b.Bill_To', 'a.Bill_To')
                            ->on('b.IdListaPrecio', 'a.IdListaPrecio')
                            ->on('b.IdTienda', 'a.IdTienda')
                            ->on('b.IdTipoPago', 'a.IdTipoPago');
                    }
                )
                ->leftJoin('CatClientesCloud as c', 'c.IdClienteCloud', 'b.IdClienteCloud')
                ->select(DB::raw('a.Bill_To, NomClienteCloud, SUM(a.ImporteArticulo) as importe'))
                ->where('a.IdTienda', $idTienda)
                ->where('b.IdTienda', $idTienda)
                ->whereDate('a.FechaVenta', $fecha)
                ->where('a.IdTipoPago', 7)
                ->where('a.IdListaPrecio', 4)
                ->where('a.StatusVenta', 0)
                ->groupBy('a.Bill_To', 'NomClienteCloud')
                ->get();

            $info = [
                'titulo' => 'Corte Diario de Tienda',
                'nomTienda' => $tienda->NomTienda,
                'numCaja' => $numCaja,
                'fecha' => strftime("%d %B del %Y", strtotime($fecha)),
                'cortesTienda' => $cortesTienda,
                'facturas' => $facturas,
                'totalTarjetaDebito' => $totalTarjetaDebito,
                'totalTarjetaCredito' => $totalTarjetaCredito,
                'totalEfectivo' => $totalEfectivo,
                'creditoQuincenal' => $creditoQuincenal,
                'creditoSemanal' => $creditoSemanal,
                'totalTransferencia' => $totalTransferencia,
                'totalFactura' => $totalFactura,
                'totalMonedero' => $totalMonedero,
                // 'totalMonederoQuincenal' => $totalMonederoQuincenal,
                // 'totalMonederoSemanal' => $totalMonederoSemanal,
            ];
        }

        //return $info;

        view()->share('GenerarCorteOraclePDF', $info);
        $pdf = PDF::loadView('CortesTienda.GenerarCorteOraclePDF', $info);
        return $pdf->stream('Corte ' . $fecha . ' ' . $tienda->NomTienda . ' Caja ' . $numCaja . '.pdf');
    }

    public function ProcesarClientesContado($fecha, $idTienda, $idDatCaja)
    {
        try {
            // sleep(3);
            // return back()->with('msjAdd', 'Corte procesado correctamente!');

            // Convierte la fecha en un objeto Carbon
            $carbonDate = Carbon::parse($fecha);

            // Ahora puedes formatear la fecha como quieras, por ejemplo: Día, Mes, Año
            $fecha1 = $carbonDate->format('d/m/Y');

            // Sumar un día
            $carbonDate->addDay();
            $fecha2 = $carbonDate->format('d/m/Y');

            DB::select('EXEC CONTADO_POS_SP_VWN ?, ?, ?', array_values([$idTienda, $fecha1, $fecha2]))[0];
            // DB::select('EXEC FACTURA_POS_SP_VWN ?, ?, ?', array_values([$idTienda, $fecha1, $fecha2]))[0];

            return back()->with('msjAdd', 'Corte procesado correctamente!');
        } catch (\Exception $e) {
            return back()->with('msjdelete', 'Error al procesar el corte, intente de nuevo!');
            return $e->getMessage();
        }
    }

    public function ProcesarClientesFacturas($fecha, $idTienda, $idDatCaja)
    {
        try {
            // sleep(3);
            // return back()->with('msjAdd', 'Corte procesado correctamente!');

            // Convierte la fecha en un objeto Carbon
            $carbonDate = Carbon::parse($fecha);

            // Ahora puedes formatear la fecha como quieras, por ejemplo: Día, Mes, Año
            $fecha1 = $carbonDate->format('d/m/Y');

            // Sumar un día
            $carbonDate->addDay();
            $fecha2 = $carbonDate->format('d/m/Y');

            // DB::select('EXEC CONTADO_POS_SP_VWN ?, ?, ?', array_values([$idTienda, $fecha1, $fecha2]))[0];
            DB::select('EXEC FACTURA_POS_SP_VWN ?, ?, ?', array_values([$idTienda, $fecha1, $fecha2]))[0];

            return back()->with('msjAdd', 'Corte procesado correctamente!');
        } catch (\Exception $e) {
            return back()->with('msjdelete', 'Error al procesar el corte, intente de nuevo!');
            return $e->getMessage();
        }
    }
}
