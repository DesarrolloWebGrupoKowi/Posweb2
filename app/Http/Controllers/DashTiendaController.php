<?php

namespace App\Http\Controllers;

use App\Models\CorteTienda;
use App\Models\DatEncabezado;
use App\Models\Tienda;
use App\Services\TiendaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DashTiendaController extends Controller
{
    protected $tiendaService;
    protected $tiendasIds;

    public function __construct(TiendaService $tiendaService)
    {
        $this->tiendaService = $tiendaService;
    }
    public function Index(Request $request)
    {
        $tiendaId = $request->get('tienda_id', Tienda::first()->IdTienda ?? null);
        $reporte = $request->get('reporte', 0);
        $fecha = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));
        $this->tiendasIds = $this->tiendaService->obtenerTiendasIds();

        if (!$request->get('tienda_id') || $request->get('tienda_id') == -1) {
            return redirect()->route('DashTiendas', [
                'fecha_fin' => $fecha
            ]);
        }

        if ($reporte == 2) {
            return redirect()->route('DashCorte', [
                'tienda_id' => $tiendaId,
                'fecha_fin' => $fecha,
                'idReporte' => $reporte
            ]);
        }

        // Tienda actual
        $tiendaActual = Tienda::find($tiendaId);

        $tiendas = $this->tiendaService->obtenerTiendasOpcional();

        if ($tiendas->isEmpty()) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        // KPIs Principales
        $kpis = $this->calcularKpis($tiendaId, $fecha);
        // $kpis = [];

        // Gráfica de ventas
        $graficaVentas = $this->obtenerGraficaVentas($tiendaId, $fecha);
        $graficaDistribucionPagos = $this->obtenerGraficaDistribucionPagos($tiendaId, $fecha);

        // Corte tienda, agrupado por bill, y POS
        $corteTienda = $this->obtenerCorte($tiendaId, $fecha);
        $corteTiendaSolicitudes = $this->obtenerCorteSolicitudes($tiendaId, $fecha);

        // Top productos
        // $topProductos = $this->obtenerTopProductos($tiendaId, $fecha);
        $topProductos = [];

        // Detalle de metricas por tienda
        // $metricas = $this->obtenerDetalleMetricas($tiendaId);
        $metricas = [];

        return view('Dashboards/Tienda', compact(
            'tiendaActual',
            'tiendas',
            'kpis',
            'graficaVentas',
            'graficaDistribucionPagos',
            'corteTienda',
            'corteTiendaSolicitudes',
            'topProductos',
            'metricas'
        ));
    }

    public function Grafica(Request $request)
    {
        $periodo = $request->get('periodo', 'hoy');
        $tiendaId = $request->get('tienda_id', Tienda::first()->IdTienda ?? null);
        $fecha = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));

        return $this->obtenerGraficaVentas($tiendaId, $fecha, $periodo);
    }

    // Métodos de cálculo principales
    private function calcularKpis($tiendaId, $fecha)
    {
        // return $tiendaId;
        $hoy  = Carbon::parse($fecha)->format('Y-m-d');
        $ayer = Carbon::parse($fecha)->subDay()->format('Y-m-d');
        $tiendasIds = $this->tiendaService->obtenerTiendasIds();

        $ventasTienda = DatEncabezado::select(
            DB::raw('
                    CatTiendas.NombreCorto AS tienda,
                    SUM(ImporteArticulo) AS total_ventas,
                    COUNT(DISTINCT DatEncabezado.IdEncabezado) AS tickets,
                    SUM(ImporteArticulo) / NULLIF(COUNT(DISTINCT DatEncabezado.IdEncabezado), 0) AS promedio_ticket,
                    COUNT(DISTINCT CASE WHEN DatEncabezado.SolicitudFE = 0 THEN DatEncabezado.IdEncabezado ELSE NULL END) AS solicitudes_factura,
                    SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
                ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', '=', 'DatEncabezado.IdTienda')
            ->whereIn('DatEncabezado.IdTienda', $tiendasIds)
            ->where('DatEncabezado.IdTienda', $tiendaId)
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereDate('DatEncabezado.FechaVenta', $fecha)
            ->groupBy('DatEncabezado.IdTienda', 'CatTiendas.NombreCorto')
            ->orderBy('DatEncabezado.IdTienda')
            ->first();

        $ventasHoy =           $ventasTienda->total_ventas ?? 0;
        $tickets =             $ventasTienda->tickets ?? 0;
        $promedio_ticket =     $ventasTienda->promedio_ticket ?? 0;
        $kilosHoy =            $ventasTienda->total_kilos ?? 0;
        $solicitudes_factura = $ventasTienda->solicitudes_factura ?? 0;

        return [
            // Ventas de hoy
            'ventas_hoy' => $ventasHoy,
            'ventas_vs_ayer' => $this->calcularVariacion($tiendasIds, $tiendaId, $ayer, $ventasHoy),
            'tickets' => $tickets,
            'promedio_ticket' => $promedio_ticket,

            // Facturas
            'facturas_pendientes' => DB::table('SolicitudFactura')
                ->whereNotNull('Editar')
                ->whereIn('IdTienda', $tiendasIds)
                ->where('IdTienda', $tiendaId)
                ->whereDate('FechaSolicitud', $hoy)
                ->count(),
            'solicitudes_factura' => $solicitudes_factura,

            // Kilos
            'kilos_hoy' => $kilosHoy,
            'kilos_promedio' => 1 > 0 ? round($kilosHoy / 1, 1) : 0,
        ];
    }

    private function obtenerGraficaVentas($tiendaId, $fecha, $periodo = 'hoy')
    {
        $tiendasIds = $this->tiendaService->obtenerTiendasIds();

        $data = [];
        $labels = [];
        $query = CorteTienda::where('IdTienda', $tiendaId)
            ->whereIn('DatCortesTienda.IdTienda', $tiendasIds)
            ->selectRaw('SUM(ImporteArticulo) as ventas');

        switch ($periodo) {
            case 'hoy':
                $query = $query
                    ->selectRaw("
                        DATEPART(HOUR, FechaVenta) AS hora,
                        RIGHT('0' + CAST(
                            CASE
                                WHEN DATEPART(HOUR, FechaVenta) = 0 THEN 12
                                WHEN DATEPART(HOUR, FechaVenta) > 12 THEN DATEPART(HOUR, FechaVenta) - 12
                                ELSE DATEPART(HOUR, FechaVenta)
                            END AS VARCHAR
                        ), 2)
                        + ' ' +
                        CASE
                            WHEN DATEPART(HOUR, FechaVenta) BETWEEN 0 AND 11 THEN 'AM'
                            ELSE 'PM'
                        END AS tiempo
                    ")
                    ->whereDate('FechaVenta', $fecha)
                    ->groupByRaw('DATEPART(HOUR, FechaVenta)')
                    ->orderBy('hora')
                    ->get();

                break;

            case '7d':
                // Ventas por día de los últimos 7 días
                $fechaInicio = Carbon::parse($fecha)->subDays(8)->startOfDay();
                $query = $query
                    ->selectRaw('YEAR(FechaVenta) as ano')
                    ->selectRaw("CONCAT(DATENAME(MONTH, FechaVenta), ' ', DATEPART(DAY, FechaVenta)) as tiempo")
                    ->whereDate('FechaVenta', '>=', $fechaInicio)
                    ->groupByRaw("YEAR(FechaVenta), CONCAT(DATENAME(MONTH, FechaVenta), ' ', DATEPART(DAY, FechaVenta))")
                    ->orderBy('ano')
                    ->orderBy('tiempo')
                    ->get();
                break;

            case '30d':
                $fechaInicio = Carbon::parse($fecha)->subDays(29)->startOfDay();
                $query = $query
                    ->selectRaw("CONCAT(YEAR(FechaVenta), ' SEM ', DATEPART(WEEK, FechaVenta)) as tiempo")
                    ->whereDate('FechaVenta', '>=', $fechaInicio)
                    ->groupByRaw("CONCAT(YEAR(FechaVenta), ' SEM ', DATEPART(WEEK, FechaVenta))")
                    ->orderBy('tiempo')
                    ->get();
                break;
        }

        $data = $query->pluck('ventas')->toArray();
        $labels = $query->pluck('tiempo')->toArray();

        return [
            'labels' => $labels,
            'data' => $data,
            'periodo' => $periodo,
            'tiendaId' => $tiendaId,
            'fecha' => $fecha
        ];
    }

    private function obtenerGraficaDistribucionPagos($tiendaId, $fecha)
    {
        $data = [];
        $labels = [];
        $query = CorteTienda::leftjoin('CatTipoPago', 'CatTipoPago.IdTipoPago', 'DatCortesTienda.IdTipoPago ')
            ->selectRaw('DatCortesTienda.IdTipoPago, CatTipoPago.NomTipoPago, SUM(ImporteArticulo) as cantidad')
            ->where('IdTienda', $tiendaId)
            ->whereIn('DatCortesTienda.IdTienda', $this->tiendasIds)
            ->whereDate('FechaVenta', $fecha)
            ->groupBy('DatCortesTienda.IdTipoPago', 'CatTipoPago.NomTipoPago')
            ->orderBy('DatCortesTienda.IdTipoPago')
            ->get();

        $data = $query->pluck('cantidad')->toArray();
        $labels = $query->pluck('NomTipoPago')->toArray();

        return [
            'labels' => $labels,
            'data' => $data,
            'tiendaId' => $tiendaId,
            'fecha' => $fecha
        ];
    }

    private function obtenerCorte($tiendaId, $fecha)
    {
        return CorteTienda::from('DatCortesTienda as ct')
            ->leftjoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXXV', 'XXXV.Source_Transaction_Identifier', 'ct.Source_Transaction_Identifier')
            // ->leftjoin('DatClientesCloudTienda as cct', 'cct.Bill_To', 'ct.Bill_To')
            ->leftJoin('DatClientesCloudTienda as cct', function ($join) {
                $join->on('cct.Bill_To', '=', 'ct.Bill_To')
                    ->on('cct.IdTienda', '=', 'ct.IdTienda')
                    ->on('cct.IdListaPrecio', '=', 'ct.IdListaPrecio')
                    ->on('cct.IdTipoPago', '=', 'ct.IdTipoPago');
            })
            ->leftjoin('CatClientesCloud as cc', 'cc.IdClienteCloud', 'cct.IdClienteCloud')
            ->select(
                'ct.Bill_To',
                'cc.NomClienteCloud',
                'ct.Source_Transaction_Identifier',
                'XXXV.Source_Transaction_Number',
                'XXXV.STATUS',
                'XXXV.MENSAJE_ERROR',
                DB::raw('SUM(ct.ImporteArticulo) as total_importe'),
                DB::raw('SUM(ct.CantArticulo) as total_cantidad')
            )
            ->where('ct.IdTienda', $tiendaId)
            ->whereDate('ct.FechaVenta', $fecha)
            ->whereIn('ct.IdTienda', $this->tiendasIds)
            ->where('ct.StatusVenta', 0)
            ->whereNull('ct.IdSolicitudFactura')
            ->groupBy(
                'ct.Bill_To',
                'cc.NomClienteCloud',
                'ct.Source_Transaction_Identifier',
                'XXXV.Source_Transaction_Number',
                'XXXV.STATUS',
                'XXXV.MENSAJE_ERROR'
            )
            ->orderBy('ct.Source_Transaction_Identifier')
            ->get();
    }

    private function obtenerCorteSolicitudes($tiendaId, $fecha)
    {
        return CorteTienda::from('DatCortesTienda as ct')
            ->leftjoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXXV', 'XXXV.Source_Transaction_Identifier', 'ct.Source_Transaction_Identifier')
            ->leftjoin('SolicitudFactura as sf', 'sf.IdSolicitudFactura', 'ct.IdSolicitudFactura')
            ->select(
                'ct.IdEncabezado',
                'ct.Bill_To',
                'sf.NomCliente',
                'ct.Source_Transaction_Identifier',
                'XXXV.Source_Transaction_Number',
                'XXXV.STATUS',
                'XXXV.MENSAJE_ERROR',
                'sf.Editar',
                DB::raw('SUM(ct.ImporteArticulo) as total_importe'),
                DB::raw('SUM(ct.CantArticulo) as total_cantidad')
            )
            ->where('ct.IdTienda', $tiendaId)
            ->whereDate('ct.FechaVenta', $fecha)
            ->whereIn('ct.IdTienda', $this->tiendasIds)
            ->where('ct.StatusVenta', 0)
            ->whereNotNull('ct.IdSolicitudFactura')
            ->groupBy(
                'ct.IdEncabezado',
                'ct.Bill_To',
                'sf.NomCliente',
                'ct.Source_Transaction_Identifier',
                'XXXV.Source_Transaction_Number',
                'XXXV.STATUS',
                'XXXV.MENSAJE_ERROR',
                'sf.Editar'
            )
            ->orderBy('ct.Source_Transaction_Identifier')
            ->get();
    }

    // Métodos de cálculo auxiliares
    private function calcularVariacion($tiendas, $tiendaId, $ayer, $ventasHoy)
    {
        $valor2 = DB::table('DatEncabezado')
            ->whereIn('DatEncabezado.IdTienda', $tiendas)
            ->where('DatEncabezado.IdTienda', $tiendaId)
            ->where('StatusVenta', 0)
            ->whereDate('FechaVenta', $ayer)
            ->sum('ImporteVenta');

        if ($valor2 == 0) return 0;

        return round((($ventasHoy - $valor2) / $valor2) * 100, 1);
    }

    // Metodos para enviar pedidos a Oracle
    public function enviarPedidoOracle(Request $request, $orden)
    {
        try {
            // Validar que la orden tenga el formato correcto
            // $request->validate([
            //     'orden' => 'required|string|max:50',
            // ]);
            if (empty($orden)) {
                throw ValidationException::withMessages([
                    'orden' => 'La orden es requerida'
                ]);
            }

            // Construir la URL del endpoint HTTP (oracle)
            $urlOracle = "http://oracleordenrest.kowi.com.mx/api/SalesOrder/PostSales?OrdenVta={$orden}&Origen=POS";

            Log::info('Proxy: Enviando pedido a Oracle', [
                'orden' => $orden,
                'url' => $urlOracle
            ]);

            // Hacer la petición al endpoint HTTP
            $response = Http::timeout(60) // 60 segundos timeout
                ->retry(3, 1000) // 3 intentos, 1 segundo entre intentos
                ->get($urlOracle);

            // Registrar la respuesta para depuración
            Log::info('Proxy: Respuesta recibida de Oracle', [
                'orden' => $orden,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Devolver la respuesta tal cual de Oracle
            return response()->json(
                $response->json(),
                $response->status(),
                ['Content-Type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (\Exception $e) {
            Log::error('Proxy: Error al enviar pedido a Oracle', [
                'orden' => $orden,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'ok' => false,
                'status' => 'Error',
                'message' => 'Error en el proxy: ' . $e->getMessage(),
                'errors' => null
            ], 500);
        }
    }
}
