<?php

namespace App\Http\Controllers;

use App\Models\ClienteCloudTienda;
use App\Models\CorteTienda;
use App\Models\DatCaja;
use App\Models\SolicitudFactura;
use App\Models\Tienda;
use App\Services\TiendaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashCorteController extends Controller
{
    private $idTienda;
    private $fecha;
    private $idCaja;
    protected $tiendaService;
    private $idReporte;

    public function __construct(TiendaService $tiendaService)
    {
        $this->tiendaService = $tiendaService;
    }

    public function index(Request $request)
    {
        // Obtener parámetros de la solicitud con valores por defecto
        $this->fecha = $request->get('fecha_fin', Carbon::today());
        $this->idTienda = $request->get('tienda_id', $this->getTiendaDefault());
        $this->idCaja = $request->get('idCaja', 0);
        $this->idReporte = $request->get('reporte', 0);

        $tiendaActual = Tienda::find($this->idTienda);

        if (!$request->get('tienda_id') || $request->get('tienda_id') == -1) {
            return redirect()->route('DashTiendas', [
                'fecha_fin' => $this->fecha
            ]);
        }

        if ($this->idReporte == 1) {
            return redirect()->route('DashTienda', [
                'tienda_id' => $this->idTienda,
                'fecha_fin' => $this->fecha,
                'reporte' => $this->idReporte
            ]);
        }

        // Validar acceso del usuario a la tienda
        $this->validateUserAccess($this->idTienda);

        // Obtener datos principales
        $tiendas = $this->tiendaService->obtenerTiendasOpcional();

        if ($tiendas->isEmpty()) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }
        $billsTo = $this->obtenerBillsTo();
        $cortesTienda = $this->obtenerCortesTienda($billsTo);
        $facturas = $this->obtenerFacturas();

        // Calcular totales por forma de pago
        $totales = $this->calcularTotales();

        // Datos adicionales
        $datosAdicionales = [
            'numCaja' => $this->obtenerNumeroCaja(),
            'nomTienda' => $this->obtenerNombreTienda(),
        ];

        return view('Dashboards/Corte', array_merge(
            compact('tiendas', 'cortesTienda', 'facturas', 'tiendaActual'),
            $totales,
            $datosAdicionales,
            [
                'idTienda' => $this->idTienda,
                'fecha1' => $this->fecha,
                'idCaja' => $this->idCaja,
            ]
        ));
    }

    /**
     * Obtener ID de tienda por defecto
     */
    private function getTiendaDefault()
    {
        return optional(Tienda::first())->IdTienda;
    }

    /**
     * Validar acceso del usuario a la tienda
     */
    private function validateUserAccess($idTienda)
    {
        try {
            $this->tiendaService->validarAccesoTienda($idTienda);
        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * Obtener Bills To para el filtrado
     */
    private function obtenerBillsTo()
    {
        return CorteTienda::where('IdTienda', $this->idTienda)
            ->whereDate('FechaVenta', $this->fecha)
            ->where('StatusVenta', 0)
            ->whereNull('IdSolicitudFactura')
            ->when($this->idCaja > 0, fn($q) => $q->where('IdDatCaja', $this->idCaja))
            ->distinct('Bill_To')
            ->pluck('Bill_To');
    }

    /**
     * Obtener cortes de tienda con relaciones
     */
    private function obtenerCortesTienda($billsTo)
    {
        if ($billsTo->isEmpty()) {
            return collect();
        }

        return ClienteCloudTienda::with([
            'PedidoOracle' => function ($oraclePedido) {
                $oraclePedido->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH', 'XXH.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                    ->whereDate('FechaVenta', $this->fecha)
                    ->where('IdTienda', $this->idTienda)
                    ->where('StatusVenta', 0)
                    ->select(
                        'DatCortesTienda.Bill_To',
                        'DatCortesTienda.Source_Transaction_Identifier',
                        'XXH.STATUS'
                    )
                    ->distinct('DatCortesTienda.Source_Transaction_Identifier');
            },
            'Customer',
            'CorteTiendaOracle' => function ($query) {
                $query->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH2', 'XXH2.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                    ->leftJoin('SolicitudCancelacionTicket as sc', 'sc.IdEncabezado', 'DatCortesTienda.IdEncabezado')
                    ->where('DatCortesTienda.IdTienda', $this->idTienda)
                    ->where('DatCortesTienda.StatusVenta', 0)
                    // ->where('DatCortesTienda.IdDatCaja', $idCaja)
                    ->when($this->idCaja > 0, function ($query) {
                        $query->where('DatCortesTienda.IdDatCaja', $this->idCaja);
                    })
                    ->whereDate('FechaVenta', $this->fecha)
                    ->whereNull('DatCortesTienda.IdSolicitudFactura');
            },
        ])
            ->select('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
            ->groupBy('IdClienteCloud', 'Bill_To', 'IdTipoNomina')
            ->where('IdTienda', $this->idTienda)
            ->whereIn('Bill_To', $billsTo)
            ->get();
    }

    /**
     * Calcular todos los totales
     */
    private function calcularTotales()
    {
        return [
            'totalMonedero' => $this->calcularTotalMonedero(),
            'totalTarjetaDebito' => $this->calcularTotalPorTipoPago(5),
            'totalTarjetaCredito' => $this->calcularTotalPorTipoPago(4),
            'totalEfectivo' => $this->calcularTotalPorTipoPago(1),
            'totalTransferencia' => $this->calcularTotalPorTipoPago(3),
            'creditoQuincenal' => $this->calcularCreditoPorNomina(4),
            'creditoSemanal' => $this->calcularCreditoPorNomina(3),
            'totalFactura' => $this->calcularTotalFacturas(),
        ];
    }

    /**
     * Calcular total de monedero electrónico
     */
    private function calcularTotalMonedero()
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
            ->where('a.IdTienda', $this->idTienda)
            ->whereDate('a.FechaVenta', $this->fecha)
            ->where('a.IdTipoPago', 7)
            ->where('a.StatusVenta', 0)
            ->when($this->idCaja > 0, fn($q) => $q->where('a.IdDatCaja', $this->idCaja))
            ->groupBy('a.Bill_To', 'NomClienteCloud')
            ->get();
    }

    /**
     * Calcular total por tipo de pago
     */
    private function calcularTotalPorTipoPago($tipoPago)
    {
        return CorteTienda::where('IdTienda', $this->idTienda)
            ->whereDate('FechaVenta', $this->fecha)
            ->where('IdTipoPago', $tipoPago)
            ->where('StatusVenta', 0)
            ->when($this->idCaja > 0, fn($q) => $q->where('IdDatCaja', $this->idCaja))
            ->sum('ImporteArticulo');
    }

    /**
     * Calcular crédito por tipo de nómina
     */
    private function calcularCreditoPorNomina($tipoNomina)
    {
        return DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->where('IdTienda', $this->idTienda)
            ->whereDate('FechaVenta', $this->fecha)
            ->where('StatusVenta', 0)
            ->where('IdTipoPago', 2)
            ->where('TipoNomina', $tipoNomina)
            ->when($this->idCaja > 0, fn($q) => $q->where('IdDatCaja', $this->idCaja))
            ->sum('ImporteArticulo');
    }

    /**
     * Calcular total de facturas
     */
    private function calcularTotalFacturas()
    {
        return CorteTienda::where('IdTienda', $this->idTienda)
            ->whereDate('FechaVenta', $this->fecha)
            ->where('StatusVenta', 0)
            ->whereNotNull('IdSolicitudFactura')
            ->when($this->idCaja > 0, fn($q) => $q->where('IdDatCaja', $this->idCaja))
            ->sum('ImporteArticulo');
    }

    /**
     * Obtener facturas con relaciones
     */
    private function obtenerFacturas()
    {
        return  SolicitudFactura::with([
            'PedidoOracle' => function ($oraclePedido) {
                $oraclePedido
                    ->select(
                        'DatCortesTienda.IdSolicitudFactura',
                        'DatCortesTienda.Bill_To',
                        'DatCortesTienda.Source_Transaction_Identifier',
                        'XXH.STATUS'
                    )
                    ->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH', 'XXH.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                    ->whereDate('FechaVenta', $this->fecha)
                    ->where('IdTienda', $this->idTienda)
                    ->distinct('DatCortesTienda.Source_Transaction_Identifier');
            },
            'Factura' => function ($query) {
                $query->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXH2', 'XXH2.Source_Transaction_Identifier', 'DatCortesTienda.Source_Transaction_Identifier')
                    ->whereNotNull('DatCortesTienda.IdSolicitudFactura')
                    // ->where('DatCortesTienda.IdDatCaja', $idCaja);
                    ->when($this->idCaja > 0, function ($query) {
                        $query->where('DatCortesTienda.IdDatCaja', $this->idCaja);
                    });
            }
        ])
            ->where('IdTienda', $this->idTienda)
            // ->where('Status', 0)
            ->whereDate('FechaSolicitud', $this->fecha)
            ->get();
    }

    /**
     * Obtener número de caja
     */
    private function obtenerNumeroCaja()
    {
        return DatCaja::where('IdDatCajas', $this->idCaja)
            ->value('IdCaja');
    }

    /**
     * Obtener nombre de la tienda
     */
    private function obtenerNombreTienda()
    {
        return Tienda::where('IdTienda', $this->idTienda)
            ->value('NomTienda');
    }
}
