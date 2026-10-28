<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashEcommerceController extends Controller
{
    // Base de datos de ecommerce, se consulta por el servidor ligado
    private const ECOMMERCE = 'SERVER.ecommerceapp.dbo.';

    // Interfaz de Oracle, las ventas de ecommerce se ligan con su pedido ECO
    private const CLOUD_INTERFACE = 'SERVER.CLOUD_INTERFACE.dbo.';

    // Status_Pedido de DatPagos cuando el pedido ya fue entregado
    private const PEDIDO_ENTREGADO = '5';

    // Formato de fecha que SQL Server interpreta igual sin importar el idioma del login
    private const FORMATO_FECHA = 'Ymd';

    // IdCatTipoPago de ecommerce
    private const TARJETA_CREDITO = 1;
    private const TARJETA_DEBITO = 2;

    // Tipos de usuario que pueden procesar el corte, los mismos que en DashTienda
    private const USUARIOS_PROCESAR = [1, 4, 9, 11];

    public function Index(Request $request)
    {
        [$mes, $idCentroVenta, $inicio, $fin] = $this->filtros($request);
        $detallado = $request->get('detallado') == 'on';

        $centrosVenta = DB::table(self::ECOMMERCE . 'DatCentroVenta')
            ->where('Status', 1)
            ->orderBy('Descripcion')
            ->pluck('Descripcion', 'IdDatCentroVenta')
            ->toArray();

        $ventas = $this->obtenerVentas($inicio, $fin, $idCentroVenta);
        $this->agregarOracleData($ventas);

        if ($detallado) {
            $cortesContado = $this->obtenerCortesContado($ventas, $inicio, $fin, $idCentroVenta);
            $cortesSolicitudes = $this->obtenerCortesSolicitudes($ventas, $inicio, $fin, $idCentroVenta);
            $totales = $this->calcularTotales($ventas);

            return view('Dashboards/ecommerce-corte', compact(
                'mes',
                'inicio',
                'centrosVenta',
                'cortesContado',
                'cortesSolicitudes',
                'totales'
            ));
        }

        $kpis = $this->calcularKpis($ventas, $inicio, $idCentroVenta);
        $pedidos = $this->agruparPorPedido($ventas);
        $diasPorProcesar = $this->diasPorProcesar($ventas);
        $puedeProcesar = $this->puedeProcesar();

        return view('Dashboards/ecommerce', compact(
            'mes',
            'inicio',
            'centrosVenta',
            'pedidos',
            'kpis',
            'diasPorProcesar',
            'puedeProcesar'
        ));
    }

    public function ProcesarContado(Request $request)
    {
        return $this->procesarCorte($request, 'contado', 'SP_VTA_CONTADO_ECO');
    }

    public function ProcesarFacturas(Request $request)
    {
        return $this->procesarCorte($request, 'facturas', 'SP_VTA_CONFACTURA_ECO');
    }

    private function filtros(Request $request): array
    {
        $request->validate([
            'mes' => 'nullable|date_format:Y-m',
            'centro_venta' => 'nullable|integer',
        ]);

        $mes = $request->get('mes', Carbon::now()->format('Y-m'));
        $idCentroVenta = $request->get('centro_venta');

        $inicio = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
        $fin = $inicio->copy()->addMonth();

        return [$mes, $idCentroVenta, $inicio, $fin];
    }

    private function puedeProcesar(): bool
    {
        return in_array(Auth::user()->IdTipoUsuario, self::USUARIOS_PROCESAR);
    }

    // Genera los pedidos ECO de las ventas que no tienen. Los procedimientos toman todas las ventas
    // sin pedido de un centro y dia (Fecha_Pedido), por eso se ejecutan una sola vez por centro y dia
    private function procesarCorte(Request $request, string $tipo, string $procedimiento)
    {
        if (!$this->puedeProcesar()) {
            return back()->with('msjdelete', 'No tienes permiso para procesar el corte');
        }

        [, $idCentroVenta, $inicio, $fin] = $this->filtros($request);

        // Los dias se calculan de nuevo aqui para no depender de lo que mande la pagina
        $ventas = $this->obtenerVentas($inicio, $fin, $idCentroVenta);
        $this->agregarOracleData($ventas);
        $dias = $this->diasPorProcesar($ventas)[$tipo];

        if ($dias->isEmpty()) {
            return back()->with('msjdelete', 'No hay ventas sin pedido por procesar');
        }

        try {
            foreach ($dias as $dia) {
                DB::connection('server4.3')->statement(
                    "EXEC ecommerceapp.dbo.$procedimiento ?, ?",
                    [$dia->IdDatCentroVenta, Carbon::parse($dia->Fecha)->format(self::FORMATO_FECHA)]
                );
            }
        } catch (\Throwable $e) {
            Log::error("DashEcommerce: error al ejecutar $procedimiento", [
                'dias' => $dias,
                'error' => $e->getMessage(),
            ]);

            return back()->with('msjdelete', 'Error al procesar el corte, intente de nuevo!');
        }

        // Si la validacion del procedimiento falla no marca error, solo manda correo a sistemas,
        // por eso se revisa cuantas ventas quedaron ligadas a un pedido
        $ventas = $this->obtenerVentas($inicio, $fin, $idCentroVenta);
        $this->agregarOracleData($ventas);
        $ventasPendientes = $this->diasPorProcesar($ventas)[$tipo]->sum('Ventas');
        $ventasProcesadas = $dias->sum('Ventas') - $ventasPendientes;

        if ($ventasPendientes > 0) {
            return back()->with('msjdelete', "Se procesaron {$dias->count()} día(s), pero $ventasPendientes venta(s) siguen sin pedido. Revisar el correo de sistemas.");
        }

        return back()->with('msjAdd', "Corte procesado correctamente! $ventasProcesadas venta(s) en {$dias->count()} día(s) ya tienen pedido.");
    }

    // Centros y dias con ventas sin pedido ECO, separados en contado y facturas
    private function diasPorProcesar(Collection $ventas): array
    {
        $hoy = Carbon::today()->format('Y-m-d');

        // Igual que en DashTienda: no se procesa el dia actual ni los centros con el proceso detenido
        $centrosDetenidos = DB::table(self::ECOMMERCE . 'DatCentroVenta')
            ->where('procesarcorte', 1)
            ->pluck('IdDatCentroVenta')
            ->all();

        $sinPedido = $ventas->whereNull('OracleData')
            ->filter(fn($venta) => substr($venta->Fecha_Pedido, 0, 10) != $hoy && !in_array($venta->IdDatCentroVenta, $centrosDetenidos));

        $agruparPorDia = fn(Collection $ventasDia) => $ventasDia
            ->groupBy(fn($venta) => $venta->IdDatCentroVenta . '|' . substr($venta->Fecha_Pedido, 0, 10))
            ->map(fn($ventasDia) => (object)[
                'IdDatCentroVenta' => $ventasDia->first()->IdDatCentroVenta,
                'CentroVenta' => $ventasDia->first()->CentroVenta,
                'Fecha' => substr($ventasDia->first()->Fecha_Pedido, 0, 10),
                'Ventas' => $ventasDia->count(),
            ])
            ->sortBy('Fecha')
            ->values();

        return [
            'contado' => $agruparPorDia($sinPedido->whereNull('IdSolictudFe')),
            'facturas' => $agruparPorDia($sinPedido->whereNotNull('IdSolictudFe')->filter(fn($venta) => $this->datosFacturaCompletos($venta))),
        ];
    }

    // SP_VTA_CONFACTURA_ECO necesita estos datos del cliente para crear la cabecera del pedido
    private function datosFacturaCompletos(object $venta): bool
    {
        return !empty($venta->IdClienteFactura)
            && !empty($venta->NombreFactura)
            && !empty($venta->TipoClienteFactura)
            && !empty($venta->ShipToFactura)
            && !empty($venta->BillToFactura);
    }

    // Una fila por pedido ECO como en el corte de tienda: las ventas sin factura se juntan por
    // cliente cloud y pedido, y cada solicitud de factura va en su propia fila
    private function agruparPorPedido(Collection $ventas): Collection
    {
        $contado = $ventas->whereNull('IdSolictudFe')
            ->groupBy(fn($venta) => $venta->IdDatCentroVenta . '-' . $venta->IdCatTipoPago . '-' . ($venta->OracleData->Source_Transaction_Identifier ?? ''))
            ->map(fn($ventasPedido) => $this->resumirPedido(
                $ventasPedido,
                $ventasPedido->first()->NomClienteCloud ?? 'Cliente sin dar de alta en cloud'
            ));

        $solicitudes = $ventas->whereNotNull('IdSolictudFe')
            ->map(fn($venta) => $this->resumirPedido(collect([$venta]), $venta->NombreFactura ?? $venta->Cliente));

        return $contado->sortBy('FechaPago')->values()
            ->concat($solicitudes->sortBy('FechaPago')->values());
    }

    private function resumirPedido(Collection $ventasPedido, string $cliente): object
    {
        $primeraVenta = $ventasPedido->first();

        return (object)[
            'Cliente' => $cliente,
            'SolicitudFactura' => !empty($primeraVenta->IdSolictudFe),
            'FaltanDatosFactura' => !empty($primeraVenta->IdSolictudFe) && !$primeraVenta->OracleData && !$this->datosFacturaCompletos($primeraVenta),
            'ClienteEcommerce' => $primeraVenta->Cliente,
            'NomTipoPago' => $primeraVenta->NomTipoPago,
            'ConstanciaFiscal' => $primeraVenta->ConstanciaFiscal,
            'CentroVenta' => $primeraVenta->CentroVenta,
            'OracleData' => $primeraVenta->OracleData,
            'FechaPago' => $ventasPedido->min('Fecha_Pago'),
            'Kilos' => $ventasPedido->sum('Kilos'),
            'CostoEnvio' => $ventasPedido->sum('Costo_Envio'),
            'ImportePago' => $ventasPedido->sum('Importe_Pago'),
        ];
    }

    // Ventas entregadas en el periodo, base de todas las consultas del reporte
    private function ventasEntregadas(Carbon $inicio, Carbon $fin, ?int $idCentroVenta): Builder
    {
        return DB::table(self::ECOMMERCE . 'DatVentas as v')
            ->join(self::ECOMMERCE . 'DatPagos as p', 'p.IdDatVentas', '=', 'v.IdDatVentas')
            ->leftJoin(self::ECOMMERCE . 'DatSolicitudFactura as sf', 'sf.IdDatVentas', '=', 'v.IdDatVentas')
            ->where('p.Status_Pedido', self::PEDIDO_ENTREGADO)
            ->where('p.Fecha_Pago', '>=', $inicio->format(self::FORMATO_FECHA))
            ->where('p.Fecha_Pago', '<', $fin->format(self::FORMATO_FECHA))
            ->when($idCentroVenta, function ($query) use ($idCentroVenta) {
                $query->where('v.IdDatCentroVenta', $idCentroVenta);
            });
    }

    // Una fila por venta entregada en el periodo
    private function obtenerVentas(Carbon $inicio, Carbon $fin, ?int $idCentroVenta): Collection
    {
        // Se agrupa el detalle aparte para que los kilos no se multipliquen con el pago
        $detalle = DB::table(self::ECOMMERCE . 'DatVtaDetalle')
            ->select(
                'IdDatVentas',
                DB::raw('SUM(Cantidad) as Kilos'),
                DB::raw('SUM(CantidadSecundario) as Piezas')
            )
            ->groupBy('IdDatVentas');

        return $this->ventasEntregadas($inicio, $fin, $idCentroVenta)
            ->leftJoinSub($detalle, 'd', 'd.IdDatVentas', '=', 'v.IdDatVentas')
            ->leftJoin(self::ECOMMERCE . 'CatClientes as c', 'c.IdUser', '=', 'v.IdCatUsers')
            ->leftJoin(self::ECOMMERCE . 'DatCentroVenta as cv', 'cv.IdDatCentroVenta', '=', 'v.IdDatCentroVenta')
            ->leftJoin(self::ECOMMERCE . 'CatTipoPago as tp', 'tp.IdTipoPago', '=', 'v.IdCatTipoPago')
            ->leftJoin(self::ECOMMERCE . 'DatClientesCloudCentroVenta as cc', function ($join) {
                $join->on('cc.IdDatCentroVenta', '=', 'v.IdDatCentroVenta')
                    ->on('cc.IdCatTipopago', '=', 'v.IdCatTipoPago');
            })
            ->select(
                'v.IdDatVentas',
                'v.IdDatCentroVenta',
                'v.IdCatTipoPago',
                'v.Fecha_Pedido',
                'p.Fecha_Pago',
                'cc.NOMBRE as NomClienteCloud',
                'c.Nombre as Cliente',
                'c.Ciudad',
                'cv.Descripcion as CentroVenta',
                'tp.NomTipoPago',
                'd.Kilos',
                'd.Piezas',
                'p.Costo_Envio',
                'p.Importe_Pago',
                'sf.IdSolictudFe',
                'sf.NOMBRE as NombreFactura',
                'sf.ID_CLIENTE as IdClienteFactura',
                'sf.TIPO_CLIENTE as TipoClienteFactura',
                'sf.SHIP_TO as ShipToFactura',
                'sf.BILL_TO as BillToFactura',
                'sf.Archivo as ConstanciaFiscal'
            )
            ->orderBy('p.Fecha_Pago')
            ->get();
    }

    // Agrega a cada venta su pedido ECO y el estatus en Oracle
    private function agregarOracleData(Collection $ventas): void
    {
        if ($ventas->isEmpty()) {
            return;
        }

        $oracleData = DB::table(self::CLOUD_INTERFACE . 'XXKW_SOURCES_TIKETS_ECOM as l')
            ->leftJoin(self::CLOUD_INTERFACE . 'XXKW_HEADERS_IVENTAS as hi', 'hi.Source_Transaction_Identifier', '=', 'l.Source_Transaction_Identifier')
            ->select(
                'l.IdDatVentas',
                'l.Source_Transaction_Identifier',
                'hi.Source_Transaction_Number',
                'hi.STATUS',
                'hi.MENSAJE_ERROR',
                'hi.Transaction_On'
            )
            ->whereIn('l.IdDatVentas', $ventas->pluck('IdDatVentas'))
            ->get()
            ->keyBy('IdDatVentas');

        $ventas->each(function ($venta) use ($oracleData) {
            $venta->OracleData = $oracleData->get($venta->IdDatVentas);
        });
    }

    // Articulos de las ventas, sumados por articulo y precio
    private function detalleArticulos(Carbon $inicio, Carbon $fin, ?int $idCentroVenta): Builder
    {
        return $this->ventasEntregadas($inicio, $fin, $idCentroVenta)
            ->join(self::ECOMMERCE . 'DatVtaDetalle as d', 'd.IdDatVentas', '=', 'v.IdDatVentas')
            ->leftJoin(self::ECOMMERCE . 'DatArticulos as da', 'da.IdDatArticulos', '=', 'd.IdDatArticulos')
            ->leftJoin(self::ECOMMERCE . 'CatArticulos as a', 'a.IdCatArticulos', '=', 'da.IdCatArticulos')
            ->addSelect(
                'a.Codigo',
                'a.Descripcion',
                'd.Precio',
                DB::raw('SUM(d.Cantidad) as Kilos'),
                DB::raw('SUM(d.CantidadSecundario) as Piezas'),
                DB::raw('SUM(d.Importe) as Importe')
            )
            ->groupBy('a.Codigo', 'a.Descripcion', 'd.Precio')
            ->orderBy('a.Descripcion');
    }

    // Ventas sin factura, agrupadas por cliente cloud (centro de venta y tipo de pago) como el corte de tienda
    private function obtenerCortesContado(Collection $ventas, Carbon $inicio, Carbon $fin, ?int $idCentroVenta): Collection
    {
        $articulos = $this->detalleArticulos($inicio, $fin, $idCentroVenta)
            ->leftJoin(self::ECOMMERCE . 'DatClientesCloudCentroVenta as cc', function ($join) {
                $join->on('cc.IdDatCentroVenta', '=', 'v.IdDatCentroVenta')
                    ->on('cc.IdCatTipopago', '=', 'v.IdCatTipoPago');
            })
            ->addSelect('v.IdDatVentas', 'v.IdDatCentroVenta', 'v.IdCatTipoPago', 'cc.NOMBRE as NomClienteCloud', 'cc.BILL_TO')
            ->whereNull('sf.IdSolictudFe')
            ->groupBy('v.IdDatVentas', 'v.IdDatCentroVenta', 'v.IdCatTipoPago', 'cc.NOMBRE', 'cc.BILL_TO')
            ->get();

        $ventasContado = $ventas->whereNull('IdSolictudFe')->keyBy('IdDatVentas');

        return $articulos
            ->groupBy(fn($item) => $item->IdDatCentroVenta . '-' . $item->IdCatTipoPago)
            ->map(function ($items) use ($ventasContado) {
                $primerItem = $items->first();
                $ventasCliente = $ventasContado->only($items->pluck('IdDatVentas')->unique()->all());

                // Dentro del cliente, un bloque por pedido ECO (puede juntar varias ventas)
                // y las ventas que todavia no tienen pedido van juntas
                $pedidos = $items
                    ->groupBy(fn($item) => $ventasContado[$item->IdDatVentas]->OracleData->Source_Transaction_Identifier ?? '')
                    ->map(function ($itemsPedido) use ($ventasContado) {
                        $ventasPedido = $ventasContado->only($itemsPedido->pluck('IdDatVentas')->unique()->all());

                        return (object)[
                            'OracleData' => $ventasPedido->first()->OracleData,
                            'FechaPago' => $ventasPedido->min('Fecha_Pago'),
                            'CostoEnvio' => $ventasPedido->sum('Costo_Envio'),
                            'ImportePago' => $ventasPedido->sum('Importe_Pago'),
                            'articulos' => $this->sumarArticulos($itemsPedido),
                        ];
                    })
                    ->sortBy('FechaPago')
                    ->values();

                return (object)[
                    'NomClienteCloud' => $primerItem->NomClienteCloud,
                    'Bill_To' => $primerItem->BILL_TO,
                    'CentroVenta' => $ventasCliente->first()->CentroVenta ?? null,
                    'NomTipoPago' => $ventasCliente->first()->NomTipoPago ?? null,
                    'pedidos' => $pedidos,
                    'Kilos' => $ventasCliente->sum('Kilos'),
                    'ImportePago' => $ventasCliente->sum('Importe_Pago'),
                ];
            })
            ->sortBy(fn($corte) => $corte->CentroVenta . $corte->NomClienteCloud)
            ->values();
    }

    // Suma por articulo y precio los renglones de varias ventas
    private function sumarArticulos(Collection $items): Collection
    {
        return $items
            ->groupBy(fn($item) => $item->Codigo . '|' . $item->Descripcion . '|' . $item->Precio)
            ->map(function ($renglones) {
                $primerRenglon = $renglones->first();

                return (object)[
                    'Codigo' => $primerRenglon->Codigo,
                    'Descripcion' => $primerRenglon->Descripcion,
                    'Precio' => $primerRenglon->Precio,
                    'Kilos' => $renglones->sum('Kilos'),
                    'Piezas' => $renglones->sum('Piezas'),
                    'Importe' => $renglones->sum('Importe'),
                ];
            })
            ->sortBy('Descripcion')
            ->values();
    }

    // Ventas con solicitud de factura, una por venta como en el corte de tienda
    private function obtenerCortesSolicitudes(Collection $ventas, Carbon $inicio, Carbon $fin, ?int $idCentroVenta): Collection
    {
        $articulos = $this->detalleArticulos($inicio, $fin, $idCentroVenta)
            ->addSelect('v.IdDatVentas')
            ->whereNotNull('sf.IdSolictudFe')
            ->groupBy('v.IdDatVentas')
            ->get();

        return $articulos
            ->groupBy('IdDatVentas')
            ->map(function ($items, $idDatVentas) use ($ventas) {
                $venta = $ventas->firstWhere('IdDatVentas', $idDatVentas);

                return (object)[
                    'venta' => $venta,
                    'articulos' => $items,
                ];
            })
            ->sortBy(fn($corte) => $corte->venta->Fecha_Pago)
            ->values();
    }

    private function calcularTotales(Collection $ventas): array
    {
        return [
            'tarjeta_credito' => $ventas->where('IdCatTipoPago', self::TARJETA_CREDITO)->sum('Importe_Pago'),
            'tarjeta_debito' => $ventas->where('IdCatTipoPago', self::TARJETA_DEBITO)->sum('Importe_Pago'),
            'contado' => $ventas->whereNull('IdSolictudFe')->sum('Importe_Pago'),
            'facturas' => $ventas->whereNotNull('IdSolictudFe')->sum('Importe_Pago'),
            'costo_envio' => $ventas->sum('Costo_Envio'),
            'kilos' => $ventas->sum('Kilos'),
            'tickets' => $ventas->count(),
            'total' => $ventas->sum('Importe_Pago'),
        ];
    }

    private function calcularKpis(Collection $ventas, Carbon $inicio, ?int $idCentroVenta): array
    {
        $ventasMes = $ventas->sum('Importe_Pago');
        $tickets = $ventas->count();
        $kilos = $ventas->sum('Kilos');

        $ventasMesAnterior = $this->ventasEntregadas($inicio->copy()->subMonth(), $inicio, $idCentroVenta)
            ->sum('p.Importe_Pago');

        return [
            'ventas_mes' => $ventasMes,
            'ventas_vs_mes_anterior' => $ventasMesAnterior == 0
                ? 0
                : round((($ventasMes - $ventasMesAnterior) / $ventasMesAnterior) * 100, 1),
            'tickets' => $tickets,
            'promedio_ticket' => $tickets > 0 ? $ventasMes / $tickets : 0,
            'kilos' => $kilos,
            'kilos_por_ticket' => $tickets > 0 ? $kilos / $tickets : 0,
            'solicitudes_factura' => $ventas->whereNotNull('IdSolictudFe')->count(),
            'costo_envio' => $ventas->sum('Costo_Envio'),
        ];
    }
}
