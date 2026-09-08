<?php

namespace App\Http\Controllers;

use App\Models\CorteTienda;
use App\Models\DatEncabezado;
use App\Models\Tienda;
use App\Services\TiendaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DashTiendaController extends Controller
{
    protected Collection $tiendas;
    protected array $tiendasIds;

    public function __construct(protected TiendaService $tiendaService)
    {
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
            if ($item) {
                $tiendaId = $tiendaId ? $tiendaId : $item->IdTienda;
                $tiendaActual = Tienda::find($tiendaId);
                $fechaActual = $fecha ? $fecha : $item->FechaVenta;
            }
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
    private function calcularKpis(?int $tiendaId, ?string $fecha, string $pos)
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

    private function obtenerGraficaVentas(?int $tiendaId, ?string $fecha, $periodo = 'hoy')
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

    private function obtenerGraficaDistribucionPagos(?int $tiendaId, ?string $fecha)
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

    private function obtenerCorteOptimizado(?int $tiendaId, ?string $fecha, string $pos)
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

    private function obtenerSolicitudFacturaOptimizado(?int $tiendaId, ?string $fecha, string $pos)
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

    // Métodos de cálculo auxiliares
    private function calcularVariacion(array $tiendas, ?int $tiendaId, string $ayer, string $ventasHoy)
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
    public function enviarPedidoOracle(Request $request, string $orden)
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

    public function doTimbrado(Request $request, $folio)
    {
        // return  $request;
        $decoded = Hashids::decode(strtoupper($folio));
        $folio = ! empty($decoded) ? $decoded[0] : $folio;

        // Database link configurable via .env
        $apexDbLink = env('APEX_DB_LINK');
        if (empty($apexDbLink)) {
            \Log::error('APEX_DB_LINK no configurado en .env');
            return response()->json([
                'success' => false,
                'message' => 'Error de configuración: APEX_DB_LINK no está definido',
                'message_type' => 'error',
            ], 500);
        }

        $pagoArray = [];
        if ($request->has('pago')) {
            $pagoArray = json_decode($request->pago, true);
        }

        if (empty($pagoArray)) {
            // Fallback to single payment
            $pagoArray = [[
                'ImporteTotal' => $request->monto_hidden ?? '',
                'FormaPago' => $request->forma_pago_hidden ?? '',
                'IdTipoPago' => $request->id_tipo_pago_hidden ?? '',
                'Order_Type_Cloud' => $request->order_type_cloud_hidden ?? '',
                'NomTipoPago' => '',
                'IdEncabezado' => $folio,
                'IdTienda' => $request->id_tienda_hidden ?? '',
                'Source_Transaction_Identifier' => $request->source_transaction_identifier_hidden ?? '',
            ]];
        }

        // ========== VERIFICACIÓN: Check if any folio + payment type already exists ==========
        $poswebConfig = config('database.connections.posweb');
        $poswebConnection = sqlsrv_connect($poswebConfig['host'], [
            'UID' => $poswebConfig['username'],
            'PWD' => $poswebConfig['password'],
            'Database' => $poswebConfig['database'],
            'CharacterSet' => 'UTF-8',
        ]);

        if ($poswebConnection) {
            // Verificar folios desde el request directamente
            if ($request->has('pago') && !empty($request->pago)) {
                // Múltiples pagos - iterar sobre el JSON
                $pagosData = json_decode($request->pago, true);
                if (is_array($pagosData)) {
                    foreach ($pagosData as $index => $pagoItem) {
                        $folioPago = $pagoItem['IdEncabezado'] ?? $folio;
                        $tipoPago = $pagoItem['IdTipoPago'] ?? null;

                        if (!$tipoPago) continue;

                        $checkExistingQuery = "SELECT TOP 1 IdEncabezado FROM SolicitudFactura WHERE IdEncabezado = ? AND IdTipoPago = ?";
                        $checkExistingStmt = sqlsrv_query($poswebConnection, $checkExistingQuery, [$folioPago, $tipoPago]);

                        if ($checkExistingStmt === false) {
                            \Log::error('Error verificando folio existente: ' . print_r(sqlsrv_errors(), true));
                            sqlsrv_close($poswebConnection);
                            return response()->json([
                                'success' => false,
                                'message' => 'Error verificando folio existente',
                                'message_type' => 'error',
                            ]);
                        }

                        $existingRecord = sqlsrv_fetch_array($checkExistingStmt, SQLSRV_FETCH_ASSOC);
                        sqlsrv_free_stmt($checkExistingStmt);

                        if ($existingRecord) {
                            \Log::warning('El folio con este tipo de pago ya está registrado: ' . $folioPago . ' - Tipo: ' . $tipoPago);
                            sqlsrv_close($poswebConnection);
                            return response()->json([
                                'success' => false,
                                'message' => 'El folio ' . $folioPago . ' con este tipo de pago ya está registrado en el sistema',
                                'message_type' => 'error',
                                'folio_exists' => true,
                                'payment_index' => $index,
                            ]);
                        }
                    }
                }
            } else {
                // Fallback - verificar folio único desde request hidden fields
                $tipoPagoFallback = $request->id_tipo_pago_hidden ?? null;
                if ($tipoPagoFallback) {
                    $checkExistingQuery = "SELECT TOP 1 IdEncabezado FROM SolicitudFactura WHERE IdEncabezado = ? AND IdTipoPago = ?";
                    $checkExistingStmt = sqlsrv_query($poswebConnection, $checkExistingQuery, [$folio, $tipoPagoFallback]);

                    if ($checkExistingStmt === false) {
                        \Log::error('Error verificando folio existente: ' . print_r(sqlsrv_errors(), true));
                        sqlsrv_close($poswebConnection);
                        return response()->json([
                            'success' => false,
                            'message' => 'Error verificando folio existente',
                            'message_type' => 'error',
                        ]);
                    }

                    $existingRecord = sqlsrv_fetch_array($checkExistingStmt, SQLSRV_FETCH_ASSOC);
                    sqlsrv_free_stmt($checkExistingStmt);

                    if ($existingRecord) {
                        \Log::warning('El folio con este tipo de pago ya está registrado: ' . $folio . ' - Tipo: ' . $tipoPagoFallback);
                        sqlsrv_close($poswebConnection);
                        return response()->json([
                            'success' => false,
                            'message' => 'El folio ' . $folio . ' con este tipo de pago ya está registrado en el sistema',
                            'message_type' => 'error',
                            'folio_exists' => true,
                        ]);
                    }
                }
            }
            sqlsrv_close($poswebConnection);
        } else {
            \Log::error('No se pudo conectar a posweb para verificación inicial');
        }

        $sqlServerConfig = config('database.connections.timbrado');
        $connection = sqlsrv_connect($sqlServerConfig['host'], [
            'Database' => $sqlServerConfig['database'],
            'Uid' => $sqlServerConfig['username'],
            'PWD' => $sqlServerConfig['password'],
            'CharacterSet' => 'UTF-8',
        ]);

        $results = [];
        $allSuccessful = true;
        $emailResults = []; // Para rastrear resultado de envío de emails
        $pendingEmails = []; // Emails pendientes de enviar (después del timbrado)

        try {
            // Process each payment in the array
            foreach ($pagoArray as $index => $payment) {
                // Get folio data for this payment
                $folioData = $this->getFolioData($folio);

                if (! empty($folioData) && ! isset($folioData['error'])) {
                    $data = $folioData[0];
                    $apiUrl = rtrim(config('services.timbrado.api_url'), '/');
                    $fullUrl = $apiUrl . '/api/Timbrar/PostTimbrar';
                    $payload = [
                        'Monto' => $payment['ImporteTotal'],
                        'Folio' => $payment['IdEncabezado'],
                        'TipoPago' => $payment['IdTipoPago'],
                        'RfcCte' => $request->rfc_hidden,
                        'NombreCte' => $request->razon_social_hidden,
                        'CodigoPostalCte' => $request->codigo_postal,
                        'RegimenFiscalCte' => $request->regimen_fiscal_hidden,
                        'MetodoPago' => 'PUE',
                        'FormaPago' => $payment['FormaPago'],
                        'UsoCFdi' => $request->uso_cfdi_compra,
                        'CorreoCliente' => 'facturacion_electronica@kowi.com.mx', //$request->email,
                        'IdEmisor' => 1,
                        'OrdetType' => $payment['Order_Type_Cloud'],
                    ];
                    \Log::info('API Timbrado URL: ' . $fullUrl);
                    \Log::info('API Timbrado Payload: ' . json_encode($payload));
                    $response = Http::withoutVerifying()->get($fullUrl, $payload);
                    $apiStatus = $response->status();
                    $apiBody = $response->body();
                    \Log::info('API Timbrado - Status: ' . $apiStatus . ' - Body: ' . $apiBody);

                    $apiResponse = $response->json();
                    // Limpiar UUID de espacios al inicio/final
                    if (isset($apiResponse['uuid'])) {
                        $apiResponse['uuid'] = trim($apiResponse['uuid']);
                    }
                    \Log::info('API Response keys: ' . implode(', ', array_keys($apiResponse ?? [])));
                    \Log::info('API Response completa: ' . json_encode($apiResponse));

                    // VALIDACIÓN: Si el timbrado falló, NO insertar en ninguna base de datos, pero sí en DAT_ERRORES
                    if (! ($apiResponse['timbradoOk'] ?? false) || empty($apiResponse['uuid'])) {
                        $allSuccessful = false;
                        $errorDetail = $apiResponse['mensaje'] ?? $apiResponse['error'] ?? $apiResponse['message'] ?? 'Sin mensaje de error';
                        $errorMsg = 'Timbrado falló para folio ' . $payment['IdEncabezado'] . ': ' . $errorDetail . ' (Status: ' . $apiStatus . ')';
                        \Log::error($errorMsg . ' - Respuesta completa: ' . $apiBody);
                        $results[] = ['error' => $errorMsg, 'payment_index' => $index, 'api_response' => $apiResponse, 'http_status' => $apiStatus];

                        // Insert error into DAT_ERRORES
                        try {
                            // Truncar valores para evitar errores de truncamiento usando el esquema real de la tabla
                            $safeFolio = substr($payment['IdEncabezado'] ?? '', 0, 30);
                            $safeTipoPago = (int) ($payment['IdTipoPago'] ?? 0);
                            $safeRfcCte = substr($request->rfc_hidden ?? '', 0, 50);
                            $safeNombreCte = substr($request->razon_social_hidden ?? '', 0, 100);
                            $safeCodigoPostal = substr($request->codigo_postal ?? '', 0, 50);
                            $safeRegimen = substr($request->regimen_fiscal_hidden ?? '', 0, 50);
                            $safeFormaPago = substr($payment['FormaPago'] ?? '', 0, 20);
                            $safeUsoCfdi = substr($request->uso_cfdi_compra ?? '', 0, 20);
                            $safeEmail = substr($request->email ?? '', 0, 100);

                            // Escapar comillas simples para SQL Server (doblarlas)
                            $escapedErrorMsg = str_replace("'", "''", $errorMsg);
                            // Limitar a 400 caracteres (tamaño exacto de la columna Mensaje en DAT_ERRORES)
                            $escapedErrorMsg = substr($escapedErrorMsg, 0, 400);

                            // Calcular hash del folio usando Hashids
                            $folioHash = Hashids::encode($payment['IdEncabezado']) ?? '';

                            $insertError = "INSERT INTO [dbo].[DAT_ERRORES]
                                ([Folio]
                                ,[TipoPago]
                                ,[RfcCte]
                                ,[NombreCte]
                                ,[CodigoPostalCte]
                                ,[RegimenFiscalCte]
                                ,[MetodoPago]
                                ,[FormaPago]
                                ,[UsoCfdi]
                                ,[CorreoCte]
                                ,[FechaTimbrado]
                                ,[Mensaje]
                                ,[FolioHash])
                                VALUES
                                ('{$safeFolio}',
                                 {$safeTipoPago},
                                 '{$safeRfcCte}',
                                 '{$safeNombreCte}',
                                 '{$safeCodigoPostal}',
                                 '{$safeRegimen}',
                                 'PUE',
                                 '{$safeFormaPago}',
                                 '{$safeUsoCfdi}',
                                 '{$safeEmail}',
                                 GETDATE(),
                                 '{$escapedErrorMsg}',
                                 '{$folioHash}')";

                            $stmt = sqlsrv_query($connection, $insertError);
                            if ($stmt === false) {
                                $sqlErrors = sqlsrv_errors();
                                \Log::error('Error SQL al insertar DAT_ERRORES: ' . json_encode($sqlErrors));
                            } else {
                                \Log::info('DAT_ERRORES insertado OK para folio: ' . $payment['IdEncabezado']);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Error insertando en DAT_ERRORES: ' . $e->getMessage());
                        }

                        continue; // Saltar al siguiente pago sin insertar en otras tablas
                    }

                    // Insert into DAT_SOLICITUDTIMBRADO for each payment
                    $escapeQuotes = fn($val) => str_replace("'", "''", (string)($val ?? ''));

                    $insertTimbrado = "INSERT INTO [dbo].[DAT_SOLICITUDTIMBRADO]
                           ([Folio]
                           ,[TipoPago]
                           ,[RfcCte]
                           ,[NombreCte]
                           ,[CodigoPostalCte]
                           ,[RegimenFiscalCte]
                           ,[MetodoPago]
                           ,[FormaPago]
                           ,[UsoCFdi]
                           ,[CorreoCte]
                           ,[UUID]
                           ,[FechaTimbrado]
                           ,[XmlBase]
                           ,[SelloSAT]
                           ,[SelloCFD]
                           ,[Status]
                           ,[Mensaje])
                           VALUES
                           ('{$escapeQuotes($payment['IdEncabezado'])}',
                            '{$escapeQuotes($payment['IdTipoPago'])}',
                           '{$escapeQuotes($request->rfc_hidden)}',
                           '{$escapeQuotes($request->razon_social_hidden)}',
                           '{$escapeQuotes($request->codigo_postal)}',
                           '{$escapeQuotes($request->regimen_fiscal_hidden)}',
                           'PUE',
                           '{$escapeQuotes($payment['FormaPago'])}',
                           '{$escapeQuotes($request->uso_cfdi_compra)}',
                           '{$escapeQuotes($request->email)}',
                           '{$escapeQuotes(trim($apiResponse['uuid'] ?? ''))}',
                           '{$escapeQuotes($apiResponse['fechaTimbrado'])}',
                           '{$escapeQuotes($apiResponse['xmlBase64'])}',
                           '{$escapeQuotes($apiResponse['selloSAT'])}',
                           '{$escapeQuotes($apiResponse['selloCFD'])}',
                           '{$escapeQuotes($apiResponse['codigo'])}',
                           '{$escapeQuotes($apiResponse['mensaje'])}')";

                    $selectQuery = "SELECT dbo.FUN_POS_NUMEROSOLFACT('{$payment['IdEncabezado']}', 1, '{$payment['Order_Type_Cloud']}') AS FOLIOSOLCITUD";
                    $stmt = sqlsrv_query($connection, $selectQuery);
                    $folioSolicitud = null;
                    if ($stmt) {
                        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                        $folioSolicitud = $row['FOLIOSOLCITUD'] ?? null;
                        sqlsrv_free_stmt($stmt);
                    }

                    // Reconnect for DAT_SOLICITUDTIMBRADO insert
                    $connection = sqlsrv_connect($sqlServerConfig['host'], [
                        'UID' => $sqlServerConfig['username'],
                        'PWD' => $sqlServerConfig['password'],
                        'Database' => $sqlServerConfig['database'],
                        'CharacterSet' => 'UTF-8',
                    ]);
                    sqlsrv_query($connection, $insertTimbrado);

                    // Insert into SolicitudFactura for each payment
                    $poswebConfig = config('database.connections.posweb');
                    $poswebConnection = sqlsrv_connect($poswebConfig['host'], [
                        'UID' => $poswebConfig['username'],
                        'PWD' => $poswebConfig['password'],
                        'Database' => $poswebConfig['database'],
                        'CharacterSet' => 'UTF-8',
                    ]);

                    $insertFactura = 'INSERT INTO [dbo].[SolicitudFactura] ([IdSolicitudFactura], [FechaSolicitud], [IdEncabezado], [IdTienda], [IdTipoPago], [IdClienteCloud], [TipoPersona], [RFC], [NomCliente], [Calle], [NumExt], [NumInt], [Colonia], [Ciudad], [Municipio], [Estado], [Pais], [CodigoPostal], [Email], [Telefono], [IdUsuarioSolicitud], [IdUsuarioCliente], [Fecha_Cliente], [Bill_To], [UsoCFDI], [Subir], [IdCaja], [Editar], [Bajar], [Status], [MetodoPago], [IdUsuarioCancelacion], [FechaCancelacion], [RegimenFiscal], [UUID], [ORDER_TYPE_ID], [Source_Origen], [FOLIO_HASH]) VALUES (?, GETDATE(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

                    // Determine editado value: null = no changes, 0 = new client, 1 = edited
                    $editadoValue = null;
                    if ($request->cliente_nuevo_hidden === 'true' || $request->cliente_nuevo_hidden === '1' || $request->cliente_nuevo_hidden === true) {
                        $editadoValue = 0; // New client
                    } elseif ($request->editado === '1' || $request->editado === 1 || $request->editado === true) {
                        $editadoValue = 1; // Edited client
                    }
                    // If editadoValue is 1 (edited), set IdClienteCloud and Bill_To to null
                    $idClienteCloud = ($editadoValue === 1) ? null : ($request->id_cliente_cloud_hidden ?? null);
                    $billTo = ($editadoValue === 1) ? null : ($request->bill_to_hidden ?? null);

                    $params = [
                        $folioSolicitud,
                        $payment['IdEncabezado'],
                        $payment['IdTienda'],
                        $payment['IdTipoPago'],
                        $idClienteCloud, // idclientecloud - null if edited, otherwise value
                        (strlen($request->rfc_hidden) == 13 ? 'PERSON' : 'ORGANIZATION'), // tipopersona
                        $request->rfc_hidden,
                        $request->razon_social_hidden,
                        $request->direccion,
                        $request->num_ext_hidden ?? null, // numext
                        $request->num_int_hidden ?? null, // numint
                        $request->colonia_hidden ?? null, // colonia
                        $request->ciudad_hidden ?? null, // ciudad
                        $request->municipio_hidden ?? null, // municipio
                        $request->estado_hidden ?? null, // estado
                        'MEXICO',
                        $request->codigo_postal,
                        $request->email,
                        $request->telefono,
                        null, // idusuariosolicitud
                        null, // idusuariocliente
                        null, // fechacliente
                        $billTo, // billto - null if edited, otherwise value
                        $request->uso_cfdi_compra,
                        0, // subir
                        0, // idcaja
                        $editadoValue, // editar: null=no changes, 0=new, 1=edited
                        null, // bajar
                        0, // status
                        'PUE', // metodopago
                        null, // idusuariocancelacion
                        null, // fechacancelacion
                        $request->regimen_fiscal_hidden,
                        trim($apiResponse['uuid'] ?? ''),
                        $payment['Order_Type_Cloud'],
                        $payment['Source_Transaction_Identifier'] ?? null, // Source_Origen from payment array
                        Hashids::encode($payment['IdEncabezado']), // FOLIO_HASH
                    ];

                    $stmt = sqlsrv_prepare($poswebConnection, $insertFactura, $params);
                    if (! $stmt) {
                        $errorMessage = 'Error preparando INSERT SolicitudFactura: ' . print_r(sqlsrv_errors(), true);
                        $allSuccessful = false;
                        $results[] = ['error' => $errorMessage, 'payment_index' => $index];

                        continue;
                    }

                    $result = sqlsrv_execute($stmt);
                    if (! $result) {
                        $errorMessage = 'Error ejecutando INSERT SolicitudFactura: ' . print_r(sqlsrv_errors(), true);
                        $allSuccessful = false;
                        $results[] = ['error' => $errorMessage, 'payment_index' => $index];

                        continue;
                    }

                    sqlsrv_free_stmt($stmt);

                    // Update DatCortesTienda with Source_Transaction_Identifier from payment array
                    if (! empty($payment['Source_Transaction_Identifier'])) {
                        $updateDatCortes = "UPDATE [dbo].[DatCortesTienda] SET [Source_Transaction_Identifier] = ? WHERE [IdEncabezado] = ? AND [IdTipoPago] = ?";
                        $updateStmt = sqlsrv_prepare($poswebConnection, $updateDatCortes, [
                            null,
                            $payment['IdEncabezado'],
                            $payment['IdTipoPago']
                        ]);

                        if ($updateStmt) {
                            $updateResult = sqlsrv_execute($updateStmt);
                            if (! $updateResult) {
                                \Log::warning('Error actualizando DatCortesTienda: ' . print_r(sqlsrv_errors(), true));
                            }
                            sqlsrv_free_stmt($updateStmt);
                        } else {
                            \Log::warning('Error preparando UPDATE DatCortesTienda: ' . print_r(sqlsrv_errors(), true));
                        }
                    }

                    sqlsrv_close($poswebConnection);

                    // If SolicitudFactura insert succeeded, insert into XXKW_FACTURAS_PORTALTIMBRADO_TMP
                    $timbradoConfig = config('database.connections.timbrado');
                    $timbradoConnection = sqlsrv_connect($timbradoConfig['host'], [
                        'UID' => $timbradoConfig['username'],
                        'PWD' => $timbradoConfig['password'],
                        'Database' => $timbradoConfig['database'],
                        'CharacterSet' => 'UTF-8',
                    ]);

                    // Download XML and generate PDF
                    $xmlContent = null;
                    $pdfContent = null;
                    $xmlFileName = null;
                    $pdfFileName = null;

                    try {
                        // Download XML
                        $apiUrl = rtrim(config('services.timbrado.api_url'), '/');
                        $xmlResponse = Http::withoutVerifying()->get($apiUrl . '/api/Timbrar/DownloadXml', [
                            'Folio' => $payment['IdEncabezado'],
                            'TipoPago' => $payment['IdTipoPago'],
                        ])->throw();

                        if ($xmlResponse->successful()) {
                            $xmlContent = $xmlResponse->body();
                            $xmlFileName = 'Factura_' . $payment['IdEncabezado'] . '.xml';

                            // Limpiar el XML para Oracle (eliminar declaración XML si existe)
                            $xmlContent = preg_replace('/<\?xml.*?\?>\s*/', '', $xmlContent);

                            \Log::info('XML Content Length: ' . ($xmlContent ? strlen($xmlContent) : 'null') . ' for folio: ' . $payment['IdEncabezado']);
                        } else {
                            \Log::warning('Failed to download XML for folio: ' . $payment['IdEncabezado']);
                        }

                        // Parse XML to extract CFDI data
                        $cfdiData = $this->parseCFDIXml($xmlContent);

                        // Generate QR Code for SAT verification
                        $qrCodeBinary = $this->generateQRCodeSAT($cfdiData);
                        $qrCode = $qrCodeBinary ? 'data:image/png;base64,' . base64_encode($qrCodeBinary) : null;

                        // Generate PDF from invoice data with CFDI parsed data
                        $pdf = $this->generateInvoicePDF($payment, $request, $apiResponse, $cfdiData, $qrCode);
                        if ($pdf) {
                            $pdfContent = $pdf;
                            $pdfFileName = 'Factura_' . $payment['IdEncabezado'] . '.pdf';
                            \Log::info('PDF Content Length: ' . strlen($pdfContent) . ' for folio: ' . $payment['IdEncabezado']);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error downloading XML or generating PDF: ' . $e->getMessage());
                    }

                    // ============= SOLUCIÓN 1: INSERCIÓN EN DOS PASOS =============
                    // Paso 1: Insertar el registro base sin los BLOB
                    $insertTimbradoTmpBase = "INSERT INTO OPENQUERY({$apexDbLink}, '
                                            SELECT
                                            FECHA, CADENA_ORIGINAL, SELLO_DIGITAL, NO_FOLIO, CREATION_DATE,
                                            CREATED_BY, ACEPTADA, UUID, CADENA_ORIGINAL_SAT, QR, VERSION,
                                            NOMBRE_PDF, NOMBRE_XML, RFC_EMISOR,
                                            NOMBRE_EMISOR, TIPO_DOCUMENTO, TOTAL, MONEDA, NUMERO_CLIENTE,
                                            RFC_RECEPTOR, NOMBRE_RECEPTOR
                                            FROM XXKW_FACTURAS_PORTALTIMBRADO_TMP
                                            ')
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $paramsTmpBase = [
                        date('Y-m-d'),                                          // FECHA
                        null,                                                   // CADENA_ORIGINAL
                        $apiResponse['selloCFD'] ?? null,                      // SELLO_DIGITAL
                        $folio,                                                // NO_FOLIO
                        date('Y-m-d'),                                         // CREATION_DATE
                        1,                                                     // CREATED_BY
                        ($apiResponse['timbradoOk'] ?? false) ? 'Y' : 'N',    // ACEPTADA
                        trim($apiResponse['uuid'] ?? ''),                    // UUID
                        null,                                                  // CADENA_ORIGINAL_SAT
                        null,                                                  // QR
                        '4.0',                                                // VERSION
                        $pdfFileName,                                          // NOMBRE_PDF
                        $xmlFileName,                                          // NOMBRE_XML
                        'AKO971007558',                                       // RFC_EMISOR
                        'ALIMENTOS KOWI',                                     // NOMBRE_EMISOR
                        'l',                                                  // TIPO_DOCUMENTO
                        (string) $payment['ImporteTotal'],                      // TOTAL
                        'MXN',                                                // MONEDA
                        '0',                                                  // NUMERO_CLIENTE
                        $request->rfc_hidden,                                 // RFC_RECEPTOR
                        $request->razon_social_hidden,                         // NOMBRE_RECEPTOR
                    ];

                    $stmtTmpBase = sqlsrv_prepare($timbradoConnection, $insertTimbradoTmpBase, $paramsTmpBase);
                    if (! $stmtTmpBase) {
                        $errorMessage = 'Error preparando INSERT base XXKW_FACTURAS_PORTALTIMBRADO_TMP: ' . print_r(sqlsrv_errors(), true);
                        \Log::error($errorMessage);
                        $allSuccessful = false;
                        $results[] = ['error' => $errorMessage, 'payment_index' => $index];
                        sqlsrv_close($timbradoConnection);

                        continue;
                    }

                    $resultTmpBase = sqlsrv_execute($stmtTmpBase);
                    if (! $resultTmpBase) {
                        $errorMessage = 'Error ejecutando INSERT base XXKW_FACTURAS_PORTALTIMBRADO_TMP: ' . print_r(sqlsrv_errors(), true);
                        \Log::error($errorMessage);
                        $allSuccessful = false;
                        $results[] = ['error' => $errorMessage, 'payment_index' => $index];
                        sqlsrv_free_stmt($stmtTmpBase);
                        sqlsrv_close($timbradoConnection);

                        continue;
                    }

                    sqlsrv_free_stmt($stmtTmpBase);

                    // Guardar datos para envío de email posterior (fuera del loop)
                    $emailData = [
                        'uuid' => trim($apiResponse['uuid'] ?? ''),
                        'folio' => $payment['IdEncabezado'],
                        'tipoPago' => $payment['IdTipoPago'],
                        'correoFacturista' => $payment['correoFacturista'] ?? 'fact1.navojoa@kowi.com.mx;fact2.navojoa@kowi.com.mx',
                        'correoTienda' => $payment['correoTienda'] ?? 'kowiexp16@kowi.com.mx',
                        'telefono' => '0000000000',
                        'correoDestino' => $request->email,
                    ];
                    $pendingEmails[] = $emailData;
                    \Log::info('SendEmailCte - Email pendiente guardado para UUID: ' . $emailData['uuid']);

                    // Paso 2: Actualizar los BLOB usando consultas separadas si hay contenido
                    $blobUpdateSuccess = true;

                    if ($xmlContent) {
                        try {
                            // Escapar el folio y UUID para la consulta
                            $escapedFolio = str_replace("'", "''", $folio);
                            $escapedUuid = str_replace("'", "''", trim($apiResponse['uuid'] ?? ''));

                            $updateXml = "UPDATE OPENQUERY({$apexDbLink}, 'SELECT XML_FILE FROM XXKW_FACTURAS_PORTALTIMBRADO_TMP WHERE NO_FOLIO = ''$escapedFolio'' AND UUID = ''$escapedUuid''') SET XML_FILE = ?";

                            $stmtXml = sqlsrv_prepare($timbradoConnection, $updateXml, [
                                [$xmlContent, SQLSRV_PARAM_IN, sqlsrv_phptype_string(SQLSRV_ENC_BINARY), sqlsrv_sqltype_varbinary('max')],
                            ]);

                            if ($stmtXml) {
                                $resultXml = sqlsrv_execute($stmtXml);
                                if (! $resultXml) {
                                    \Log::error('Error actualizando XML BLOB: ' . print_r(sqlsrv_errors(), true));
                                    $blobUpdateSuccess = false;
                                }
                                sqlsrv_free_stmt($stmtXml);
                            } else {
                                \Log::error('Error preparando UPDATE XML: ' . print_r(sqlsrv_errors(), true));
                                $blobUpdateSuccess = false;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Excepción actualizando XML BLOB: ' . $e->getMessage());
                            $blobUpdateSuccess = false;
                        }
                    }

                    if ($pdfContent) {
                        try {
                            // Escapar el folio y UUID para la consulta
                            $escapedFolio = str_replace("'", "''", $folio);
                            $escapedUuid = str_replace("'", "''", trim($apiResponse['uuid'] ?? ''));

                            $updatePdf = "UPDATE OPENQUERY({$apexDbLink}, 'SELECT PDF_FILE FROM XXKW_FACTURAS_PORTALTIMBRADO_TMP WHERE NO_FOLIO = ''$escapedFolio'' AND UUID = ''$escapedUuid''') SET PDF_FILE = ?";

                            $stmtPdf = sqlsrv_prepare($timbradoConnection, $updatePdf, [
                                [$pdfContent, SQLSRV_PARAM_IN, sqlsrv_phptype_string(SQLSRV_ENC_BINARY), sqlsrv_sqltype_varbinary('max')],
                            ]);

                            if ($stmtPdf) {
                                $resultPdf = sqlsrv_execute($stmtPdf);
                                if (! $resultPdf) {
                                    \Log::error('Error actualizando PDF BLOB: ' . print_r(sqlsrv_errors(), true));
                                    $blobUpdateSuccess = false;
                                }
                                sqlsrv_free_stmt($stmtPdf);
                            } else {
                                \Log::error('Error preparando UPDATE PDF: ' . print_r(sqlsrv_errors(), true));
                                $blobUpdateSuccess = false;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Excepción actualizando PDF BLOB: ' . $e->getMessage());
                            $blobUpdateSuccess = false;
                        }
                    }

                    // Update QR BLOB if QR image was generated
                    if ($qrCode) {
                        try {
                            // Escapar el folio y UUID para la consulta
                            $escapedFolio = str_replace("'", "''", $folio);
                            $escapedUuid = str_replace("'", "''", trim($apiResponse['uuid'] ?? ''));

                            $updateQr = "UPDATE OPENQUERY({$apexDbLink}, 'SELECT QR FROM XXKW_FACTURAS_PORTALTIMBRADO_TMP WHERE NO_FOLIO = ''$escapedFolio'' AND UUID = ''$escapedUuid''') SET QR = ?";

                            $stmtQr = sqlsrv_prepare($timbradoConnection, $updateQr, [
                                [$qrCode, SQLSRV_PARAM_IN, sqlsrv_phptype_string(SQLSRV_ENC_BINARY), sqlsrv_sqltype_varbinary('max')],
                            ]);

                            if ($stmtQr) {
                                $resultQr = sqlsrv_execute($stmtQr);
                                if (! $resultQr) {
                                    \Log::error('Error actualizando QR BLOB: ' . print_r(sqlsrv_errors(), true));
                                    $blobUpdateSuccess = false;
                                }
                                sqlsrv_free_stmt($stmtQr);
                            } else {
                                \Log::error('Error preparando UPDATE QR: ' . print_r(sqlsrv_errors(), true));
                                $blobUpdateSuccess = false;
                            }
                        } catch (\Exception $e) {
                            \Log::error('Excepción actualizando QR BLOB: ' . $e->getMessage());
                            $blobUpdateSuccess = false;
                        }
                    }

                    sqlsrv_close($timbradoConnection);

                    if (! $blobUpdateSuccess) {
                        \Log::warning('Registro insertado en XXKW_FACTURAS_PORTALTIMBRADO_TMP pero algunos BLOB no se actualizaron para folio: ' . $payment['IdEncabezado']);
                        // No marcamos como error completo, solo advertimos
                    }

                    // Add Source_Transaction_Identifier to the result for display in step 5
                    $apiResponse['source_transaction_identifier'] = $payment['Source_Transaction_Identifier'] ?? '';
                    // Aplicar trim al UUID para evitar espacios al final
                    if (isset($apiResponse['uuid'])) {
                        $apiResponse['uuid'] = trim($apiResponse['uuid']);
                    }
                    $results[] = $apiResponse;
                } else {
                    $allSuccessful = false;
                    $errorMsg = $folioData['error'] ?? 'No se encontraron resultados para: ' . $folio;
                    \Log::error('Folio data error para pago index ' . $index . ': ' . $errorMsg);
                    $results[] = ['error' => $errorMsg, 'payment_index' => $index];
                }
            }


            // Consultar APIs de Cadena de Origen y actualizar APEX antes de enviar correos
            if (! empty($pendingEmails)) {
                \Log::info('CadenaOrigen - Consultando APIs para ' . count($pendingEmails) . ' factura(s)...');

                foreach ($pendingEmails as $emailData) {
                    $folio = $emailData['folio'];
                    $tipoPago = $emailData['tipoPago'];
                    $uuid = trim($emailData['uuid'] ?? '');

                    try {
                        // 1. Consultar API Cadena de Origen (ToSat)
                        $apiUrl = rtrim(config('services.timbrado.api_url'), '/');
                        $urlToSat = "{$apiUrl}/api/Timbrar/DownloadFileToSat?Folio={$folio}&TipoPago={$tipoPago}";
                        \Log::info('CadenaOrigen - Consultando ToSat: ' . $urlToSat);
                        $responseToSat = Http::withoutVerifying()->get($urlToSat);
                        $cadenaOriginal = $responseToSat->successful() ? $responseToSat->body() : null;
                        \Log::info('CadenaOrigen - ToSat response status: ' . $responseToSat->status() . ', length: ' . strlen($cadenaOriginal ?? ''));

                        // 2. Consultar API Cadena de Origen SAT (OfSat)
                        $urlOfSat = "{$apiUrl}/api/Timbrar/DownloadFileOfSat?Folio={$folio}&TipoPago={$tipoPago}";
                        \Log::info('CadenaOrigen - Consultando OfSat: ' . $urlOfSat);
                        $responseOfSat = Http::withoutVerifying()->get($urlOfSat);
                        $cadenaOriginalSat = $responseOfSat->successful() ? $responseOfSat->body() : null;
                        \Log::info('CadenaOrigen - OfSat response status: ' . $responseOfSat->status() . ', length: ' . strlen($cadenaOriginalSat ?? ''));

                        // 3. Actualizar {$apexDbLink} con los datos obtenidos
                        if ($cadenaOriginal || $cadenaOriginalSat) {
                            $escapedFolio = str_replace("'", "''", $folio);
                            $escapedUuid = str_replace("'", "''", trim($uuid));

                            // Preparar valores para el UPDATE
                            $cadenaOriginalValue = $cadenaOriginal ? "'" . $cadenaOriginal . "'" : "NULL";
                            $cadenaOriginalSatValue = $cadenaOriginalSat ? "'" . $cadenaOriginalSat . "'" : "NULL";

                            $updateCadena = "UPDATE OPENQUERY({$apexDbLink}, 'SELECT CADENA_ORIGINAL, CADENA_ORIGINAL_SAT FROM XXKW_FACTURAS_PORTALTIMBRADO_TMP WHERE NO_FOLIO = ''{$escapedFolio}'' AND UUID = ''{$escapedUuid}''') SET CADENA_ORIGINAL = {$cadenaOriginalValue}, CADENA_ORIGINAL_SAT = {$cadenaOriginalSatValue}";

                            \Log::info('CadenaOrigen - Ejecutando UPDATE en APEX para folio: ' . $folio);
                            $stmtCadena = sqlsrv_query($connection, $updateCadena);

                            if ($stmtCadena) {
                                sqlsrv_free_stmt($stmtCadena);
                                \Log::info('CadenaOrigen - UPDATE exitoso para folio: ' . $folio);
                            } else {
                                \Log::error('CadenaOrigen - Error en UPDATE: ' . print_r(sqlsrv_errors(), true));
                            }
                        } else {
                            \Log::warning('CadenaOrigen - No se obtuvieron datos de las APIs para folio: ' . $folio);
                        }
                    } catch (\Exception $e) {
                        \Log::error('CadenaOrigen - Error procesando folio ' . $folio . ': ' . $e->getMessage());
                    }
                }
            }

            // Enviar correos electrónicos DESPUÉS de que todo el timbrado y actualización de cadenas haya terminado
            // Esto evita acumulación de tiempos de espera dentro del loop
            if (! empty($pendingEmails)) {
                \Log::info('SendEmailCte - Procesando ' . count($pendingEmails) . ' email(s) pendiente(s) después del timbrado...');

                foreach ($pendingEmails as $emailData) {
                    try {
                        $maxRetries = 4;
                        $retryCount = 0;
                        $success = false;
                        $lastResult = null;
                        $emailUuid = trim($emailData['uuid'] ?? '');

                        while ($retryCount < $maxRetries && ! $success) {
                            $retryCount++;

                            \Log::info('SendEmailCte - UUID ' . $emailUuid . ' - Intento #' . $retryCount . ' de ' . $maxRetries);

                            $apiUrl = rtrim(config('services.timbrado.api_url'), '/');
                            $url = "{$apiUrl}/api/Timbrar/SendEmailCte?"
                                . "Uuid=" . $emailUuid
                                . "&CorreoFacturista=" . $emailData['correoFacturista']
                                . "&CorreoTienda=" . $emailData['correoTienda']
                                . "&Telefono=" . $emailData['telefono']
                                . "&CorreoDestino=" . $emailData['correoDestino'];

                            \Log::info('SendEmailCte - URL: ' . $url);

                            $sendEmailResponse = Http::withoutVerifying()->get($url);

                            $lastResult = [
                                'success' => $sendEmailResponse->successful(),
                                'status' => $sendEmailResponse->status(),
                                'body' => $sendEmailResponse->json() ?? $sendEmailResponse->body(),
                                'url_sent' => $url,
                                'params' => $emailData,
                            ];

                            \Log::info('SendEmailCte - Response intento #' . $retryCount . ': ' . json_encode($lastResult));

                            if ($sendEmailResponse->successful()) {
                                $success = true;
                                \Log::info('SendEmailCte - Éxito en intento #' . $retryCount);
                            } elseif ($retryCount < $maxRetries) {
                                $waitTime = 2 * pow(2, $retryCount - 1);
                                \Log::info('SendEmailCte - Falló intento #' . $retryCount . ', esperando ' . $waitTime . 's antes de reintentar...');
                                sleep($waitTime);
                            }
                        }

                        if (! $success) {
                            \Log::error('SendEmailCte - Todos los intentos fallaron para UUID: ' . $emailUuid);
                        }

                        $emailResults[] = $lastResult;
                    } catch (\Exception $e) {
                        \Log::error('SendEmailCte - Error enviando email para UUID ' . $emailUuid . ': ' . $e->getMessage());
                        $emailResults[] = [
                            'success' => false,
                            'error' => $e->getMessage(),
                            'uuid' => $emailUuid,
                        ];
                    }
                }
            }

            sqlsrv_close($connection);

            $found = $allSuccessful;
            if ($found) {
                $message = 'Timbrado exitoso';
            } else {
                // Get first error detail to show in message
                $firstError = '';
                foreach ($results as $result) {
                    if (isset($result['error'])) {
                        $firstError = $result['error'];
                        break;
                    }
                }
                $message = $firstError ?: 'Algunos timbrados fallaron';
            }
            $messageType = $found ? 'success' : 'error';
            session()->flash('message', $message);
            session()->flash('message_type', $messageType);

            // Get all PDF URLs from successful timbrados
            $pdfUrls = [];
            if ($found && ! empty($results)) {
                foreach ($results as $result) {
                    if (isset($result['uuid']) && ! empty(trim($result['uuid']))) {
                        $cleanUuid = trim($result['uuid']);
                        $pdfUrls[] = 'http://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdftest?UUID=' . $cleanUuid;
                    }
                }
            }

            // Return JSON for AJAX requests
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => $found,
                    'message' => $message,
                    'message_type' => $messageType,
                    'results' => $results,
                    'folio' => $folio,
                    'folio_hashid' => Hashids::encode($folio),
                    'pdf_urls' => $pdfUrls, // Array of all PDF URLs
                    'pdf_url' => $pdfUrls[0] ?? null, // Backward compatibility
                    'email_results' => $emailResults, // Resultado envío de emails
                ]);
            }

            return view('home', compact('folio', 'found', 'results') + ['groupedData' => $this->getGroupedData()]);
        } catch (\Exception $e) {
            if (isset($connection) && is_resource($connection)) {
                sqlsrv_close($connection);
            }

            $found = false;
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'error';
            session()->flash('message', $message);
            session()->flash('message_type', $messageType);
            $results = [];

            \Log::error('Error en doTimbrado: ' . $e->getMessage() . '\n' . $e->getTraceAsString());

            // Return JSON for AJAX requests
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => $found,
                    'message' => $message,
                    'message_type' => $messageType,
                    'results' => $results,
                    'folio' => $folio,
                ]);
            }

            return view('home', compact('folio', 'found', 'results') + ['groupedData' => $this->getGroupedData()]);
        }
    }
}
