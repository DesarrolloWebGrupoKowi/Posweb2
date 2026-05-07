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

    public function Index(Request $request)
    {
        // Datos del request
        $tiendaId = $request->get('tienda_id');
        $detallado = $request->get('detallado');
        $fecha = $request->get('fecha_fin');
        $pos = $request->get('pos');
        $pos = str_replace('_', '', $pos);

        // Para trabajr con la tienda actual y fecha actual, dependiendo de los parametros
        $tiendaActual = null;
        $fechaActual = null;

        if ($tiendaId) {
            $tiendaActual = Tienda::find($tiendaId);
            $fechaActual = $fecha;
        }

        if ($pos) {
            $item = CorteTienda::where('Source_Transaction_Identifier', $pos)->first();
            $tiendaId = $tiendaId ? $tiendaId : $item->IdTienda;
            $tiendaActual = Tienda::find($tiendaId);
            $fechaActual = $fecha ? $fecha : $item->FechaVenta;
        }

        // Cuando se pida el form detallado, nos mande a la pantalla de detallado
        if (!$pos && !$tiendaId && $fecha) {
            return redirect()->route('DashTiendas', [
                'tienda_id' => $tiendaId,
                'fecha_fin' => $fecha,
                'detallado' => $detallado,
                'pos' => $pos
            ]);
        }

        if ($detallado == 'on') {
            return redirect()->route('DashCorte', [
                'tienda_id' => $tiendaId,
                'fecha_fin' => $fecha,
                'detallado' => $detallado,
                'pos' => $pos
            ]);
        }

        // Tiendas a las que tengo acceso
        $tiendas = $this->tiendas;

        // KPIs Principales
        $kpis = $this->calcularKpis($tiendaId, $fecha, $pos);
        // $kpis = [];

        // Gráfica de ventas
        $graficaVentas = $this->obtenerGraficaVentas($tiendaId, $fecha);
        $graficaDistribucionPagos = $this->obtenerGraficaDistribucionPagos($tiendaId, $fecha);

        // Corte tienda, agrupado por bill, y POS
        $corteTienda = $this->obtenerCorteOptimizado($tiendaId, $fecha, $pos);
        // return $corteTiendaSolicitudes = $this->obtenerCorteSolicitudes($tiendaId, $fecha, $pos);
        $corteTiendaSolicitudes = $this->obtenerSolicitudFacturaOptimizado($tiendaId, $fecha, $pos);

        // return $tiendaActual;

        return view('Dashboards/Tienda', compact(
            'tiendaActual',
            'fechaActual',
            'tiendas',
            'kpis',
            'graficaVentas',
            'graficaDistribucionPagos',
            'corteTienda',
            'corteTiendaSolicitudes'
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
    private function calcularKpis($tiendaId, $fecha, $pos)
    {
        // Si hay POS, retornar KPIs en cero
        if (!empty($pos)) {
            return [
                'ventas_hoy' => 0,
                'ventas_vs_ayer' => 0,
                'tickets' => 0,
                'promedio_ticket' => 0,
                'facturas_pendientes' => 0,
                'solicitudes_factura' => 0,
                'kilos_hoy' => 0,
                'kilos_promedio' => 0,
            ];
        }

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
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereIn('DatEncabezado.IdTienda', $tiendasIds)

            ->where('DatEncabezado.IdTienda', $tiendaId)
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
            ->where('DatCortesTienda.StatusVenta', 0)
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
                // $query = $query
                //     ->selectRaw('YEAR(FechaVenta) as ano')
                //     ->selectRaw("DATENAME(MONTH, FechaVenta) as mes")
                //     ->selectRaw("DATEPART(DAY, FechaVenta) as dia")
                //     ->selectRaw("CONCAT(DATENAME(MONTH, FechaVenta), ' ', DATEPART(DAY, FechaVenta)) as tiempo")
                //     ->whereDate('FechaVenta', '>=', $fechaInicio)
                //     ->groupByRaw("YEAR(FechaVenta), DATENAME(MONTH, FechaVenta), DATEPART(DAY, FechaVenta)")
                //     ->orderBy('ano')
                //     ->orderBy('mes')
                //     ->orderBy('dia')
                //     ->get();
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
                    ->whereDate('FechaVenta', '>=', $fechaInicio)
                    ->groupByRaw('DATEPART(HOUR, FechaVenta)')
                    ->orderBy('hora')
                    ->get();
                break;

            case '30d':
                $fechaInicio = Carbon::parse($fecha)->subDays(29)->startOfDay();
                // $query = $query
                //     ->selectRaw("CONCAT(YEAR(FechaVenta), ' SEM ', DATEPART(WEEK, FechaVenta)) as tiempo")
                //     ->whereDate('FechaVenta', '>=', $fechaInicio)
                //     ->groupByRaw("CONCAT(YEAR(FechaVenta), ' SEM ', DATEPART(WEEK, FechaVenta))")
                //     ->orderBy('tiempo')
                //     ->get();
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
                    ->whereDate('FechaVenta', '>=', $fechaInicio)
                    ->groupByRaw('DATEPART(HOUR, FechaVenta)')
                    ->orderBy('hora')
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
            ->where('DatCortesTienda.StatusVenta', 0)
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

    private function obtenerCorteOptimizado($tiendaId, $fecha, $pos)
    {
        $query =  CorteTienda::from('DatCortesTienda as ct')
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
                DB::raw('SUM(ct.ImporteArticulo) as total_importe'),
                DB::raw('SUM(ct.CantArticulo) as total_cantidad')
            )
            ->whereIn('ct.IdTienda', $this->tiendasIds)
            ->where('ct.StatusVenta', 0)
            ->whereNull('ct.IdSolicitudFactura');

        if (!empty($pos)) {
            $query->where('ct.Source_Transaction_Identifier', $pos);
            if (!empty($tiendaId)) {
                $query->where('ct.IdTienda', $tiendaId);
            }
            if (!empty($fecha)) {
                $query->whereDate('ct.FechaVenta', $fecha);
            }
        } else {
            $query->where('ct.IdTienda', $tiendaId)
                ->whereDate('ct.FechaVenta', $fecha);
        }

        $resultados = $query->groupBy(
            'ct.Bill_To',
            'cc.NomClienteCloud',
            'ct.Source_Transaction_Identifier',
        )
            ->orderBy('ct.Source_Transaction_Identifier')
            ->get();

        if ($resultados->isEmpty()) {
            return collect();
        }

        return $resultados->map(function ($item) {
            // Agregar OracleData al item existente
            $item->OracleData = null;

            if (!empty($item->Source_Transaction_Identifier)) {
                $item->OracleData = DB::table('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS')
                    ->select('STATUS', 'MENSAJE_ERROR', 'Batch_Name', 'Transaction_On', 'Source_Transaction_Number', 'Source_Transaction_Identifier')
                    ->where('Source_Transaction_Identifier', $item->Source_Transaction_Identifier)
                    ->first();
            }

            return $item;
        })->values();
    }

    private function obtenerSolicitudFacturaOptimizado($tiendaId, $fecha, $pos)
    {
        $query = CorteTienda::from('DatCortesTienda as ct')
            ->leftJoin('SolicitudFactura as sf', 'sf.IdSolicitudFactura', '=', 'ct.IdSolicitudFactura')
            ->select(
                'ct.IdEncabezado',
                'ct.IdSolicitudFactura',
                'ct.Bill_To',
                'sf.NomCliente',
                'sf.Email',
                'ct.Source_Transaction_Identifier',
                'sf.Editar',
                'sf.UUID',
                'sf.Source_Origen',
                DB::raw('SUM(ct.ImporteArticulo) as total_importe'),
                DB::raw('SUM(ct.CantArticulo) as total_cantidad')
            )
            ->where('ct.StatusVenta', 0)
            ->where('sf.Status', 0)
            ->whereNotNull('ct.IdSolicitudFactura')
            ->whereIn('ct.IdTienda', $this->tiendasIds);

        if (!empty($pos)) {
            $query->where('ct.Source_Transaction_Identifier', $pos);
            if (!empty($tiendaId)) {
                $query->where('ct.IdTienda', $tiendaId);
            }
            if (!empty($fecha)) {
                $query->whereDate('ct.FechaVenta', $fecha);
            }
        } else {
            $query->where('ct.IdTienda', $tiendaId)
                ->whereDate('ct.FechaVenta', $fecha);
        }

        $resultados = $query->groupBy(
            'ct.IdEncabezado',
            'ct.IdSolicitudFactura',
            'ct.Bill_To',
            'sf.NomCliente',
            'sf.Email',
            'ct.Source_Transaction_Identifier',
            'sf.Editar',
            'sf.UUID',
            'sf.Source_Origen'
        )
            ->orderBy('ct.Source_Transaction_Identifier')
            ->get();

        if ($resultados->isEmpty()) {
            return collect();
        }

        return $resultados->map(function ($item) {
            // Agregar OracleData al item existente
            $item->OracleData = null;

            if (!empty($item->Source_Transaction_Identifier)) {
                $item->OracleData = DB::table('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS')
                    ->select('STATUS', 'MENSAJE_ERROR', 'Batch_Name', 'Transaction_On', 'Source_Transaction_Number', 'Source_Transaction_Identifier')
                    ->where('Source_Transaction_Identifier', $item->Source_Transaction_Identifier)
                    ->first();
            }

            return $item;
        })->values();
    }

    private function obtenerCorte($tiendaId, $fecha, $pos)
    {
        $query =  CorteTienda::from('DatCortesTienda as ct')
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
                'XXXV.Transaction_On',
                DB::raw('SUM(ct.ImporteArticulo) as total_importe'),
                DB::raw('SUM(ct.CantArticulo) as total_cantidad')
            )
            ->whereIn('ct.IdTienda', $this->tiendasIds)
            ->where('ct.StatusVenta', 0)
            ->whereNull('ct.IdSolicitudFactura');

        if (!empty($pos)) {
            $query->where('ct.Source_Transaction_Identifier', $pos);
            if (!empty($tiendaId)) {
                $query->where('ct.IdTienda', $tiendaId);
            }
            if (!empty($fecha)) {
                $query->whereDate('ct.FechaVenta', $fecha);
            }
        } else {
            $query->where('ct.IdTienda', $tiendaId)
                ->whereDate('ct.FechaVenta', $fecha);
        }

        return $query->groupBy(
            'ct.Bill_To',
            'cc.NomClienteCloud',
            'ct.Source_Transaction_Identifier',
            'XXXV.Source_Transaction_Number',
            'XXXV.STATUS',
            'XXXV.MENSAJE_ERROR',
            'XXXV.Transaction_On'
        )
            ->orderBy('ct.Source_Transaction_Identifier')
            ->get();
    }

    private function obtenerCorteSolicitudes($tiendaId, $fecha, $pos)
    {
        $query =  CorteTienda::from('DatCortesTienda as ct')
            ->leftjoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as XXXV', 'XXXV.Source_Transaction_Identifier', 'ct.Source_Transaction_Identifier')
            ->leftjoin('SolicitudFactura as sf', 'sf.IdSolicitudFactura', 'ct.IdSolicitudFactura')
            ->select(
                'ct.IdEncabezado',
                'ct.Bill_To',
                'sf.NomCliente',
                'sf.Email',
                'ct.Source_Transaction_Identifier',
                'XXXV.Source_Transaction_Number',
                'XXXV.STATUS',
                'XXXV.MENSAJE_ERROR',
                'XXXV.Transaction_On',
                'sf.Editar',
                // 'sf.UUID',
                DB::raw('SUM(ct.ImporteArticulo) as total_importe'),
                DB::raw('SUM(ct.CantArticulo) as total_cantidad')
            )
            ->where('ct.StatusVenta', 0)
            ->where('sf.Status', 0)
            ->whereNotNull('ct.IdSolicitudFactura')
            ->whereIn('ct.IdTienda', $this->tiendasIds);

        if (!empty($pos)) {
            $query->where('ct.Source_Transaction_Identifier', $pos);
            if (!empty($tiendaId)) {
                $query->where('ct.IdTienda', $tiendaId);
            }
            if (!empty($fecha)) {
                $query->whereDate('ct.FechaVenta', $fecha);
            }
        } else {
            $query->where('ct.IdTienda', $tiendaId)
                ->whereDate('ct.FechaVenta', $fecha);
        }

        // ->where('ct.IdTienda', $tiendaId)
        // ->whereDate('ct.FechaVenta', $fecha)
        return $query->groupBy(
            'ct.IdEncabezado',
            'ct.Bill_To',
            'sf.NomCliente',
            'sf.Email',
            'ct.Source_Transaction_Identifier',
            'XXXV.Source_Transaction_Number',
            'XXXV.STATUS',
            'XXXV.MENSAJE_ERROR',
            'XXXV.Transaction_On',
            'sf.Editar',
            // 'sf.UUID'
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
            // Si la orden contiene un guion bajo, lo quitamos
            $orden = str_replace('_', '', $orden);

            // Construir la URL del endpoint HTTP (oracle)
            $urlOracle = "https://oracleordenrest.kowi.com.mx/api/SalesOrder/PostSales?OrdenVta={$orden}&Origen=POS";

            Log::info('Proxy: Enviando pedido a Oracle', [
                'orden' => $orden,
                'url' => $urlOracle
            ]);

            // Hacer la petición al endpoint HTTP
            $response = Http::withoutVerifying()
                ->timeout(60) // 60 segundos timeout
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

    // Metodos para enviar correos al cliente
    public function enviarCorreoOracle(Request $request)
    {
        try {
            // Validar los datos recibidos
            $validated = $request->validate([
                'orden' => 'required|string',
                'correo_destino' => 'required|email',
                'correo_facturista' => 'nullable|string',
                'correo_tienda' => 'nullable|string',
                'telefono' => 'nullable|string',
                'enviar_copia_facturista' => 'nullable|boolean'
            ]);

            // Construir los parámetros para la API
            $baseUrl = 'http://oraclefacturasrest.kowi.com.mx/api/Documentos/Email';

            // Preparar parámetros
            $params = [
                'Orden' => $validated['orden'],
                // 'CorreoDestino' => $validated['correo_destino'],
                'CorreoDestino' => 'daniel.hernandez@kowi.com.mx',
                'CorreoFacturista' => '',
                // 'CorreoTienda' => $validated['correo_tienda'] ?? '',
                'CorreoTienda' => 'daniel.hernandez@kowi.com.mx',
                'Telefono' => $validated['telefono'] ?? ''
            ];

            Log::info('Enviando correo a Oracle API', [
                'params' => $params
            ]);

            // Agregar correo facturista solo si está marcado el checkbox
            if ($request->has('enviar_copia_facturista') && $request->enviar_copia_facturista == '1') {
                $params['CorreoFacturista'] = $validated['correo_facturista'] ?? '';
            }

            // Filtrar parámetros vacíos
            $params = array_filter($params, function ($value) {
                return !empty($value);
            });

            // Construir la URL con parámetros
            $queryString = http_build_query($params);
            $apiUrl = $baseUrl . '?' . $queryString;

            Log::info('Enviando correo a Oracle API', [
                'url' => $apiUrl,
                'params' => $params
            ]);

            // Hacer la petición GET a la API
            $response = Http::withOptions([
                'verify' => false,
            ])->timeout(60)->get($apiUrl);

            // Log de la respuesta
            Log::info('Respuesta de Oracle API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $responseData = $response->json();

                return response()->json([
                    'success' => true,
                    'message' => 'Correo enviado exitosamente',
                    'data' => $responseData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al enviar el correo: ' . $response->status(),
                    'error' => $response->body()
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}
