<?php

namespace App\Http\Controllers;

use App\Models\CorteTienda;
use App\Models\DatEncabezado;
use App\Services\TiendaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashTiendasController extends Controller
{
    protected $tiendaService;

    public function __construct(TiendaService $tiendaService)
    {
        $this->tiendaService = $tiendaService;
    }

    public function Tiendas(Request $request)
    {
        $fecha = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));
        $tiendaId = $request->get('tienda_id');

        if ($tiendaId && $tiendaId > 0) {
            return redirect()->route('DashTienda', [
                'tienda_id' => $tiendaId,
                'fecha_fin' => $fecha
            ]);
        }

        $tiendasForm = $this->tiendaService->obtenerTiendasOpcional();

        if ($tiendasForm->isEmpty()) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        $tiendas = CorteTienda::select(
            DB::raw('
                    DatCortesTienda.IdTienda,
                    CatTiendas.NombreCorto AS tienda,
                    CatTiendas.NomTienda AS NomTienda,
                    COUNT(DISTINCT DatCortesTienda.IdEncabezado) AS tickets,
                    SUM(ImporteArticulo) / NULLIF(COUNT(DISTINCT DatCortesTienda.IdEncabezado), 0) AS promedio_ticket,
                    SUM(ImporteArticulo) AS total_ventas,
                    SUM(CASE WHEN DatCortesTienda.CantArticulo IS NOT NULL THEN DatCortesTienda.CantArticulo ELSE 0 END) AS total_kilos,
                    COUNT(DISTINCT CASE WHEN DatCortesTienda.IdSolicitudFactura IS NOT NULL THEN DatCortesTienda.IdEncabezado ELSE NULL END) AS solicitudes_factura,
                    COUNT(DISTINCT CASE WHEN DatCortesTienda.Source_Transaction_Identifier IS NULL THEN DatCortesTienda.IdEncabezado ELSE NULL END) AS tickets_sin_pedido,
                    COUNT(DISTINCT CASE WHEN DatCortesTienda.Bill_To IS NULL THEN DatCortesTienda.IdEncabezado ELSE NULL END) AS tickets_sin_bill
                ')
        )
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', '=', 'DatCortesTienda.IdTienda')
            // ->whereIn('DatEncabezado.IdTienda', $tiendas)
            ->where('DatCortesTienda.StatusVenta', 0)
            ->whereDate('DatCortesTienda.FechaVenta', $fecha)
            ->groupBy('DatCortesTienda.IdTienda', 'CatTiendas.NombreCorto', 'CatTiendas.NomTienda')
            ->get();

        // KPIs Principales
        $kpis = $this->calcularKpis($fecha);

        // Gráfica de ventas
        $graficaVentas = $this->obtenerGraficaVentas($fecha);
        $graficaUltimoMes = $this->obtenerGraficaUltimoMes($fecha);

        // Top productos
        $topProductos = $this->obtenerTopProductos($fecha);
        $topMermas = $this->obtenerTopMermas($fecha);

        return view('Dashboards/Tiendas', compact(
            'tiendasForm',
            'tiendas',
            'kpis',
            'graficaVentas',
            'graficaUltimoMes',
            'topProductos',
            'topMermas'
        ));
    }

    public function Grafica(Request $request)
    {
        $periodo = $request->get('periodo', 'hoy');
        $fechaInicio = date('Y-m-d');
        $fechaFin = date('Y-m-d', strtotime('+1 days'));

        // Ajustar fechas según el período seleccionado
        if ($periodo == '7d') {
            $fechaInicio = date('Y-m-d', strtotime('-7 days'));
        } elseif ($periodo == '30d') {
            $fechaInicio = date('Y-m-d', strtotime('-30 days'));
        } elseif ($periodo == '90d') {
            $fechaInicio = date('Y-m-d', strtotime('-90 days'));
        }

        // Determinar el tipo de agrupación según el rango de fechas
        // $diferenciaDias = Carbon::parse($fechaFin)->diffInDays(Carbon::parse($fechaInicio));

        $ventasPorTienda = DatEncabezado::select(
            DB::raw('
                    CatTiendas.NombreCorto AS tienda,
                    SUM(ImporteArticulo) AS total_ventas,
                    COUNT(CASE WHEN SolicitudFE = 0 THEN 1 END) AS solicitudes_factura,
                    SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
                ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', '=', 'DatEncabezado.IdTienda')
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereBetween(
                DB::raw('CAST(DatEncabezado.FechaVenta AS DATE)'),
                [$fechaInicio, $fechaFin]
            )

            ->groupBy('DatEncabezado.IdTienda', 'CatTiendas.NombreCorto')
            ->orderBy('DatEncabezado.IdTienda')
            ->get();

        $labels = [];
        $data = [];

        foreach ($ventasPorTienda as $venta) {
            // $labels[] = Carbon::parse($venta->fecha)->format('d/m');
            $labels[] = $venta->tienda;
            $data[] = $venta->total_ventas;
        }

        // Si no hay datos, crear array vacío
        if (empty($data)) {
            $current = Carbon::parse($fechaInicio);
            $end = Carbon::parse($fechaFin);

            while ($current <= $end) {
                $labels[] = $current->format('d/m');
                $data[] = 0;
                $current->addDay();
            }
        }

        // Obtener datos adicionales para comparativa
        // $datosComparativa = $this->obtenerDatosComparativa($fechaInicio, $fechaFin);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'periodo' => $periodo,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            // 'comparativa' => $datosComparativa
        ]);
    }

    // Métodos de cálculo principales
    private function calcularKpis($fecha)
    {
        $hoy  = Carbon::parse($fecha)->format('Y-m-d');
        $ayer = Carbon::parse($fecha)->subDay()->format('Y-m-d');

        $ventasPorTienda = DatEncabezado::select(
            DB::raw('
                    CatTiendas.NombreCorto AS tienda,
                    SUM(ImporteArticulo) AS total_ventas,
                    COUNT(CASE WHEN SolicitudFE = 0 THEN 1 END) AS solicitudes_factura,
                    SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
                ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', '=', 'DatEncabezado.IdTienda')
            // ->whereIn('DatEncabezado.IdTienda', $tiendas)
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereDate('DatEncabezado.FechaVenta', $fecha)
            ->groupBy('DatEncabezado.IdTienda', 'CatTiendas.NombreCorto')
            ->orderBy('DatEncabezado.IdTienda')
            ->get();

        $ventasHoy = $ventasPorTienda->sum('total_ventas');

        $totalTiendas = DB::table('CatTiendas')->where('Status', 0)->count();
        $totalTiendasActivas = $ventasPorTienda->count();

        $kilosHoy = $ventasPorTienda->sum('total_kilos');

        return [
            // Ventas de hoy
            'ventas_hoy' => $ventasHoy,

            'ventas_vs_ayer' => $this->calcularVariacion($ayer, $ventasHoy),

            // Tiendas
            'total_tiendas' => $totalTiendas,
            'tiendas_activas' => $totalTiendasActivas,
            'porcentaje_activas' => $totalTiendas > 0 ? round(($totalTiendasActivas / $totalTiendas) * 100, 1) : 0,

            // Facturas
            'facturas_pendientes' => DB::table('SolicitudFactura')
                ->whereNotNull('Editar')
                ->whereDate('FechaSolicitud', $hoy)
                ->count(),
            'facturas_hoy' => DB::table('SolicitudFactura')
                ->whereDate('FechaSolicitud', $hoy)
                ->count(),

            // Kilos
            'kilos_hoy' => $kilosHoy,
            'kilos_promedio' => 1 > 0 ? round($kilosHoy / 1, 1) : 0,
        ];
    }

    private function obtenerGraficaVentas($fechaFin)
    {
        // $ventas = DB::table('ventas_diarias')
        //     ->whereBetween('fecha', [$fechaInicio, $fechaFin])
        //     ->orderBy('fecha')
        //     ->get();

        $ventasPorTienda = DatEncabezado::select(
            DB::raw('
                    CatTiendas.NombreCorto AS tienda,
                    SUM(ImporteArticulo) AS total_ventas,
                    COUNT(CASE WHEN SolicitudFE = 0 THEN 1 END) AS solicitudes_factura,
                    SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
                ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', '=', 'DatEncabezado.IdTienda')
            // ->whereIn('DatEncabezado.IdTienda', $tiendas)
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereDate('DatEncabezado.FechaVenta', $fechaFin)
            ->groupBy('DatEncabezado.IdTienda', 'CatTiendas.NombreCorto')
            ->orderBy('DatEncabezado.IdTienda')
            ->get();

        $labels = [];
        $data = [];

        foreach ($ventasPorTienda as $venta) {
            // $labels[] = Carbon::parse($venta->fecha)->format('d/m');
            $labels[] = $venta->tienda;
            $data[] = $venta->total_ventas;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function obtenerGraficaUltimoMes($fechaFin)
    {
        $query = DatEncabezado::select(
            DB::raw('
                        CONVERT(VARCHAR(10), DatEncabezado.FechaVenta, 103) AS fecha,
                        SUM(ImporteArticulo) AS total_ventas,
                        COUNT(CASE WHEN SolicitudFE = 0 THEN 1 END) AS solicitudes_factura,
                        SUM(CASE WHEN DatDetalle.CantArticulo IS NOT NULL THEN DatDetalle.CantArticulo ELSE 0 END) AS total_kilos
                    ')
        )
            ->leftJoin('DatDetalle', 'DatDetalle.IdEncabezado', '=', 'DatEncabezado.IdEncabezado')
            // ->whereIn('DatEncabezado.IdTienda', $tiendas)
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereDate('DatEncabezado.FechaVenta', '>', Carbon::parse($fechaFin)->subMonth())
            ->groupBy(DB::raw('CONVERT(VARCHAR(10), DatEncabezado.FechaVenta, 103)'))  // Agrupamos solo por la fecha sin la parte de hora
            ->orderBy(DB::raw('MIN(DatEncabezado.FechaVenta)'))  // Usamos MIN para ordenar por la fecha más antigua de cada grupo
            ->get();

        $labels = [];
        $data = [];

        foreach ($query as $venta) {
            // $labels[] = Carbon::parse($venta->fecha)->format('d/m');
            $labels[] = $venta->fecha;
            $data[] = $venta->total_ventas;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function obtenerTopProductos($fecha)
    {
        return DB::table('DatEncabezado as e')
            ->leftJoin('DatDetalle as d', 'e.IdEncabezado', '=', 'd.IdEncabezado')
            ->leftJoin('CatArticulos as a', 'a.IdArticulo', '=', 'd.IdArticulo')
            ->where('e.StatusVenta', 0)
            ->where('e.FechaVenta', '>=', Carbon::parse($fecha)->format('d-m-Y') . ' 00:00:00')
            ->where('e.FechaVenta', '<=', Carbon::parse($fecha)->format('d-m-Y') . ' 23:59:59')
            ->groupBy('d.IdArticulo', 'a.NomArticulo')
            ->orderByDesc('ventas')
            ->limit(5)
            ->select(
                'd.IdArticulo',
                'a.NomArticulo',
                DB::raw('SUM(d.ImporteArticulo) as ventas'),
                DB::raw('SUM(d.CantArticulo) as kilos')
            )
            ->get();
    }

    private function obtenerTopMermas($fecha)
    {
        return DB::table('CapMermas')
            ->join('CatArticulos', 'CatArticulos.CodArticulo', '=', 'CapMermas.CodArticulo')
            ->whereDate('CapMermas.FechaCaptura', $fecha)
            ->select(
                'CatArticulos.CodArticulo',
                'CatArticulos.NomArticulo',
                DB::raw('SUM(CapMermas.CantArticulo) as kilos_merma')
            )
            ->groupBy('CatArticulos.CodArticulo', 'CatArticulos.NomArticulo')
            ->orderByDesc('kilos_merma')
            ->limit(5)
            ->get();
    }

    // Métodos de cálculo auxiliares
    private function calcularVariacion($ayer, $ventasHoy)
    {
        $valor2 = DB::table('DatEncabezado')
            ->where('StatusVenta', 0)
            ->whereDate('FechaVenta', $ayer)
            ->sum('ImporteVenta');

        if ($valor2 == 0) return 0;

        return round((($ventasHoy - $valor2) / $valor2) * 100, 1);
    }
}
