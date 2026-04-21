<?php

namespace App\Http\Controllers;

use App\Models\ClienteCloudTienda;
use App\Models\CorteTienda;
use App\Models\SolicitudFactura;
use App\Models\Tienda;
use App\Services\TiendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashCorteController extends Controller
{
    protected $tiendaService;
    protected $tiendasIds;
    protected $tiendas;

    public function __construct(TiendaService $tiendaService)
    {
        $this->tiendaService = $tiendaService;

        $this->middleware(function ($request, $next) {
            $this->tiendas = $this->tiendaService->obtenerTiendasOpcional();
            $this->tiendasIds = $this->tiendaService->obtenerTiendasIds();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // return $request;
        // Obtener parámetros de la solicitud con valores por defecto
        $idTienda = $request->get('tienda_id');
        $detallado = $request->get('detallado');
        $fecha = $request->get('fecha_fin');
        $pos = $request->get('pos');
        $pos = str_replace('_', '', $pos);

        $idCaja = $request->get('idCaja', 0);
        $idReporte = $request->get('reporte', 0);
        $tiendas = $this->tiendas;
        $tiendaActual = Tienda::whereIn('IdTienda', $this->tiendasIds)->find($idTienda);
        $fechaActual = $fecha;

        if ($idTienda) {
            $tiendaActual = Tienda::find($idTienda);
            $fechaActual = $fecha;
        }

        if ($pos) {
            $item = CorteTienda::where('Source_Transaction_Identifier', $pos)->first();
            $idTienda = $idTienda ? $idTienda : $item->IdTienda;
            $tiendaActual = Tienda::find($idTienda);
            $fechaActual = $fecha ? $fecha : $item->FechaVenta;
        }

        if (!$pos && !$idTienda && $fecha) {
            return redirect()->route('DashTiendas', [
                'tienda_id' => $idTienda,
                'fecha_fin' => $fecha,
                'detallado' => $detallado,
                'pos' => $pos
            ]);
        }

        if ((!$detallado && $idTienda && $fecha) || (!$detallado && $pos)) {
            return redirect()->route('DashTienda', [
                'tienda_id' => $idTienda,
                'fecha_fin' => $fecha,
                'detallado' => $detallado,
                'pos' => $pos
            ]);
        }

        // Obtener cortes
        $cortesContadoOptimizado = $this->obtenerCortesContadoOptimizado($idTienda, $fecha, $idCaja, $pos);
        $cortesSolicitudesOptimizado = $this->obtenerCortesSolicitudesOptimizado($idTienda, $fecha, $idCaja, $pos);

        // Calcular totales por forma de pago
        $totales = $this->calcularTotales($idTienda, $fecha, $idCaja);

        return view('Dashboards/Corte', array_merge(
            compact(
                'tiendas',
                'cortesContadoOptimizado',
                'cortesSolicitudesOptimizado',
                'tiendaActual',
                'fechaActual',
            ),
            $totales
        ));
    }

    /**
     * Obtener cortes de tienda contado con una sola consulta a la tabla de datcortes
     */
    private function obtenerCortesContadoOptimizado($idTienda, $fecha, $idCaja, $pos)
    {
        // Una sola consulta que obtiene todo lo necesario
        $query = CorteTienda::query()
            ->select([
                'DatCortesTienda.Bill_To',
                'DatCortesTienda.IdArticulo',
                'DatCortesTienda.PrecioArticulo',
                'DatCortesTienda.IdListaPrecio',
                'DatCortesTienda.IdTipoPago',
                'DatCortesTienda.Source_Transaction_Identifier',
                DB::raw('SUM(DatCortesTienda.CantArticulo) as CantArticulo'),
                DB::raw('SUM(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo'),
                DB::raw('SUM(DatCortesTienda.IvaArticulo) as IvaArticulo'),
                DB::raw('SUM(DatCortesTienda.ImporteArticulo) as ImporteArticulo'),
                // Datos del artículo
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                // Datos de cancelación
                'sc.IdEncabezado as SolicitudCancelacion',
                'sc.SolicitudAprobada'
            ])
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', '=', 'DatCortesTienda.IdArticulo')
            ->leftJoin(
                'SolicitudCancelacionTicket as sc',
                'sc.IdEncabezado',
                '=',
                'DatCortesTienda.IdEncabezado'
            )
            ->where('DatCortesTienda.StatusVenta', 0);

        if (!empty($pos)) {
            if (!empty($idTienda)) {
                $query->where('DatCortesTienda.IdTienda', $idTienda);
            }
            if (!empty($fecha)) {
                $query->whereDate('DatCortesTienda.FechaVenta', $fecha);
            }
            $query->where('DatCortesTienda.Source_Transaction_Identifier', $pos);
        } else {
            $query->where('DatCortesTienda.IdTienda', $idTienda)
                ->whereDate('DatCortesTienda.FechaVenta', $fecha);
        }

        $query->whereNull('DatCortesTienda.IdSolicitudFactura')
            ->when($idCaja > 0, function ($query) use ($idCaja) {
                $query->where('DatCortesTienda.IdDatCaja', $idCaja);
            })
            ->groupBy([
                'DatCortesTienda.Bill_To',
                'DatCortesTienda.IdArticulo',
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                'DatCortesTienda.PrecioArticulo',
                'DatCortesTienda.IdListaPrecio',
                'DatCortesTienda.IdTipoPago',
                'DatCortesTienda.Source_Transaction_Identifier',
                'sc.IdEncabezado',
                'sc.SolicitudAprobada'
            ])
            ->orderBy('DatCortesTienda.Source_Transaction_Identifier');


        $resultados = $query->get();

        if ($resultados->isEmpty()) {
            return collect();
        }

        // $resultados->groupBy('Bill_To');
        // Agrupar por Bill_To para mantener la estructura esperada
        return $resultados->groupBy('Bill_To')->map(function ($items, $billTo) {
            // $primerItem = $items->first();
            $sourceIdentifiers = $items->pluck('Source_Transaction_Identifier')->unique()->filter()->values();

            $oracleData = collect();
            if ($sourceIdentifiers->isNotEmpty()) {
                $oracleData = DB::table('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS')
                    ->select('STATUS', 'MENSAJE_ERROR', 'Batch_Name', 'Transaction_On', 'Source_Transaction_Number', 'Source_Transaction_Identifier')
                    ->whereIn('Source_Transaction_Identifier', $sourceIdentifiers)
                    ->get()
                    ->keyBy('Source_Transaction_Identifier');
            }

            return (object)[
                'Bill_To' => $billTo,
                'cortes' => $items,
                'Customer' => ClienteCloudTienda::select('cc.*')
                    ->leftJoin('CatClientesCloud as cc', 'DatClientesCloudTienda.IdClienteCloud', '=', 'cc.IdClienteCloud')
                    ->where('Bill_To', $billTo)
                    ->first(),
                'OracleData' => $oracleData
            ];
        })->values();
    }

    /**
     * Obtener cortes de tienda con solicitudes de factura con una sola consulta a la tabla de datcortes
     */
    private function obtenerCortesSolicitudesOptimizado($idTienda, $fecha, $idCaja, $pos)
    {
        // Obtener cortes de tienda normales (sin solicitud de factura)
        $query = CorteTienda::query()
            ->select([
                'DatCortesTienda.Bill_To',
                'DatCortesTienda.IdArticulo',
                'DatCortesTienda.PrecioArticulo',
                'DatCortesTienda.IdListaPrecio',
                'DatCortesTienda.IdTipoPago',
                'DatCortesTienda.Source_Transaction_Identifier',
                DB::raw('SUM(DatCortesTienda.CantArticulo) as CantArticulo'),
                DB::raw('SUM(DatCortesTienda.SubtotalArticulo) as SubTotalArticulo'),
                DB::raw('SUM(DatCortesTienda.IvaArticulo) as IvaArticulo'),
                DB::raw('SUM(DatCortesTienda.ImporteArticulo) as ImporteArticulo'),
                // Datos del artículo
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                // Datos de cancelación
                'sc.IdEncabezado as SolicitudCancelacion',
                'sc.SolicitudAprobada',
                // Datos de solicitudFactura
                'DatCortesTienda.IdSolicitudFactura as IdSolicitudFactura'
            ])
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', '=', 'DatCortesTienda.IdArticulo')
            ->leftJoin(
                'SolicitudCancelacionTicket as sc',
                'sc.IdEncabezado',
                '=',
                'DatCortesTienda.IdEncabezado'
            );

        if (!empty($pos)) {
            if (!empty($idTienda)) {
                $query->where('DatCortesTienda.IdTienda', $idTienda);
            }
            if (!empty($fecha)) {
                $query->whereDate('DatCortesTienda.FechaVenta', $fecha);
            }
            $query->where('DatCortesTienda.Source_Transaction_Identifier', $pos);
        } else {
            $query->where('DatCortesTienda.IdTienda', $idTienda)
                ->whereDate('DatCortesTienda.FechaVenta', $fecha);
        }
        // ->where('DatCortesTienda.IdTienda', $idTienda)
        // ->whereDate('DatCortesTienda.FechaVenta', $fecha)
        $query->where('DatCortesTienda.StatusVenta', 0)
            ->whereNotNull('DatCortesTienda.IdSolicitudFactura')
            ->when($idCaja > 0, function ($query) use ($idCaja) {
                $query->where('DatCortesTienda.IdDatCaja', $idCaja);
            })
            ->groupBy([
                'DatCortesTienda.Bill_To',
                'DatCortesTienda.IdArticulo',
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                'DatCortesTienda.PrecioArticulo',
                'DatCortesTienda.IdListaPrecio',
                'DatCortesTienda.IdTipoPago',
                'DatCortesTienda.Source_Transaction_Identifier',
                'sc.IdEncabezado',
                'sc.SolicitudAprobada',
                'DatCortesTienda.IdSolicitudFactura'
            ])
            ->orderBy('DatCortesTienda.Source_Transaction_Identifier');


        $resultados = $query->get();

        if ($resultados->isEmpty()) {
            return collect();
        }

        $resultados = $resultados->groupBy(function ($item) {
            return $item->Bill_To . '-' . $item->Source_Transaction_Identifier . '-' . $item->IdSolicitudFactura;
        });

        // Agrupar por Bill_To para mantener la estructura esperada
        return $resultados->map(function ($items, $billTo) {
            $primerItem = $items->first();
            $sourceIdentifiers = $items->pluck('Source_Transaction_Identifier')->unique()->filter()->values();

            $oracleData = collect();
            if ($sourceIdentifiers->isNotEmpty()) {
                $oracleData = DB::table('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS')
                    ->select('STATUS', 'MENSAJE_ERROR', 'Batch_Name', 'Transaction_On', 'Source_Transaction_Number', 'Source_Transaction_Identifier')
                    ->whereIn('Source_Transaction_Identifier', $sourceIdentifiers)
                    ->get()
                    ->keyBy('Source_Transaction_Identifier');
            }

            return (object)[
                'Bill_To' => $billTo,
                'cortes' => $items,
                'Customer' => SolicitudFactura::select('IdSolicitudFactura', 'NomCliente', 'Editar')
                    ->where('IdSolicitudFactura', $primerItem->IdSolicitudFactura)
                    ->first(),
                'OracleData' => $oracleData
            ];
        })->values();
    }

    /**
     * Calcular todos los totales
     */
    private function calcularTotales($idTienda, $fecha, $idCaja)
    {
        return [
            'totalMonedero' => $this->calcularTotalMonedero($idTienda, $fecha, $idCaja),
            'totalTarjetaDebito' => $this->calcularTotalPorTipoPago(5, $idTienda, $fecha, $idCaja),
            'totalTarjetaCredito' => $this->calcularTotalPorTipoPago(4, $idTienda, $fecha, $idCaja),
            'totalEfectivo' => $this->calcularTotalPorTipoPago(1, $idTienda, $fecha, $idCaja),
            'totalTransferencia' => $this->calcularTotalPorTipoPago(3, $idTienda, $fecha, $idCaja),
            'creditoQuincenal' => $this->calcularCreditoPorNomina(4, $idTienda, $fecha, $idCaja),
            'creditoSemanal' => $this->calcularCreditoPorNomina(3, $idTienda, $fecha, $idCaja),
            'totalFactura' => $this->calcularTotalFacturas($idTienda, $fecha, $idCaja),
        ];
    }

    /**
     * Calcular total de monedero electrónico
     */
    private function calcularTotalMonedero($idTienda, $fecha, $idCaja)
    {
        return DB::table('DatCortesTienda as a')
            ->leftJoin('DatClientesCloudTienda as b', function ($join) {
                $join->on('b.Bill_To', 'a.Bill_To')
                    ->on('b.IdListaPrecio', 'a.IdListaPrecio')
                    ->on('b.IdTienda', 'a.IdTienda')
                    ->on('b.IdTipoPago', 'a.IdTipoPago');
            })
            ->leftJoin('CatClientesCloud as c', 'c.IdClienteCloud', 'b.IdClienteCloud')
            ->leftJoin('SolicitudFactura as d', 'd.IdSolicitudFactura', 'a.IdSolicitudFactura')
            ->select(
                'a.Bill_To',
                DB::raw('COALESCE(NomClienteCloud, \'SOLICITUDES DE FACTURAS\') as NomClienteCloud'),
                DB::raw('SUM(a.ImporteArticulo) as importe')
            )
            ->where('a.IdTienda', $idTienda)
            ->whereIn('a.IdTienda', $this->tiendasIds)
            ->whereDate('a.FechaVenta', $fecha)
            ->where('a.IdTipoPago', 7)
            ->where('a.StatusVenta', 0)
            ->when($idCaja > 0, fn($q) => $q->where('a.IdDatCaja', $idCaja))
            ->groupBy('a.Bill_To', 'NomClienteCloud')
            ->get();
    }

    /**
     * Calcular total por tipo de pago
     */
    private function calcularTotalPorTipoPago($tipoPago, $idTienda, $fecha, $idCaja)
    {
        return CorteTienda::where('IdTienda', $idTienda)
            ->whereIn('IdTienda', $this->tiendasIds)
            ->whereDate('FechaVenta', $fecha)
            ->where('IdTipoPago', $tipoPago)
            ->where('StatusVenta', 0)
            ->when($idCaja > 0, fn($q) => $q->where('IdDatCaja', $idCaja))
            ->sum('ImporteArticulo');
    }

    /**
     * Calcular crédito por tipo de nómina
     */
    private function calcularCreditoPorNomina($tipoNomina, $idTienda, $fecha, $idCaja)
    {
        return DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->where('IdTienda', $idTienda)
            // ->whereIn('IdTienda', $tiendasIds)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->where('IdTipoPago', 2)
            ->where('TipoNomina', $tipoNomina)
            ->when($idCaja > 0, fn($q) => $q->where('IdDatCaja', $idCaja))
            ->sum('ImporteArticulo');
    }

    /**
     * Calcular total de facturas
     */
    private function calcularTotalFacturas($idTienda, $fecha, $idCaja)
    {
        return CorteTienda::where('IdTienda', $idTienda)
            ->whereIn('IdTienda', $this->tiendasIds)
            ->whereDate('FechaVenta', $fecha)
            ->where('StatusVenta', 0)
            ->whereNotNull('IdSolicitudFactura')
            ->when($idCaja > 0, fn($q) => $q->where('IdDatCaja', $idCaja))
            ->sum('ImporteArticulo');
    }
}
