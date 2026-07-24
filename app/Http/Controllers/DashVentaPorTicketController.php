<?php

namespace App\Http\Controllers;

use App\Exports\MovimientosDeArticulos;
use App\Exports\VentasDetalladasExport;
use App\Services\TiendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashVentaPorTicketController extends Controller
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

    private function query(Request $request, $exports = false)
    {
        // Obtener todos los parámetros del filtro
        $idTienda = $request->idTienda;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $fecha = $request->fecha;
        $codArticulo = $request->cod_articulo;
        $nomArticulo = $request->nom_articulo;
        $usuario = $request->usuario;
        $numNomina = $request->num_nomina;
        $idTicket = $request->id_ticket;
        $idEncabezado = $request->id_encabezado;
        $statusVenta = $request->status_venta;
        $solicitudFE = $request->solicitud_fe;
        $recorte = $request->recorte;
        $cancelado = $request->cancelado;
        $familia = $request->familia;
        $grupo = $request->grupo;
        $idListaPrecio = $request->id_lista_precio;
        $idPaquete = $request->id_paquete;
        $idDescuento = $request->id_descuento;
        $nombreCliente = $request->nombre_cliente;
        $subtotalMin = $request->subtotal_min;
        $subtotalMax = $request->subtotal_max;
        $importeMin = $request->importe_min;
        $importeMax = $request->importe_max;
        $soloConDescuento = $request->solo_con_descuento;
        $soloPaquetes = $request->solo_paquetes;
        $soloRecorte = $request->solo_recorte;
        $soloEmpleados = $request->solo_empleados;

        $tiendasIds = $this->tiendasIds;

        $folio = $request->folio;
        if ($folio) {
            $idEncabezado = \Vinkla\Hashids\Facades\Hashids::decode($folio);
        }

        // $idEncabezadoDecoded;
        // Construir la consulta
        $query = DB::connection('server')->table('DatEncabezado as DE')
            ->leftJoin('DatDetalle as DD', 'DD.IdEncabezado', '=', 'DE.IdEncabezado')
            ->leftJoin('CatPaquetes as CP', 'CP.IdPaquete', '=', 'DD.IdPaquete')
            ->leftJoin('DatEncDescuentos as DED', 'DED.IdEncDescuento', '=', 'DD.IdEncDescuento')
            ->leftJoin('CatArticulos as CA', 'CA.IdArticulo', '=', 'DD.IdArticulo')
            ->leftJoin('CatFamilias as CF', 'CF.IdFamilia', '=', 'CA.IdFamilia')
            ->leftJoin('CatGrupos as CG', 'CG.IdGrupo', '=', 'CA.IdGrupo')
            ->leftJoin('CatEmpleados as CEC', 'CEC.NumNomina', '=', 'DE.NumNomina')
            ->leftJoin('CatTiendas as CT', 'CT.IdTienda', '=', 'DE.IdTienda')
            ->leftJoin('CatUsuarios as CU', 'CU.IdUsuario', '=', 'DE.IdUsuario')
            ->leftJoin('CatEmpleados as CE', 'CE.NumNomina', '=', 'CU.NumNomina')
            ->leftJoin('CatUsuarios as CUC', 'CUC.IdUsuario', '=', 'DE.IdUsuarioCancelacion')
            ->leftJoin('CatEmpleados as CECC', 'CECC.NumNomina', '=', 'CUC.NumNomina')

            // ->leftJoin(DB::raw("(SELECT * FROM DatCortesTienda) AS DC"), function ($join) {
            //     $join->on('DC.IdEncabezado', '=', 'DE.IdEncabezado')
            //         ->on('DC.IdArticulo', '=', 'DD.IdArticulo')
            //         ->on('DC.Linea', '=', 'DD.Linea');
            // })

            // ->leftJoin(DB::raw("(
            //     SELECT IdEncabezado, IdArticulo, Linea, Source_Transaction_Identifier, IdSolicitudFactura,
            //            ROW_NUMBER() OVER (PARTITION BY IdEncabezado, IdArticulo, Linea ORDER BY IdCortesTienda) as rn
            //     FROM DatCortesTienda
            // ) as DC"), function ($join) {
            //     $join->on('DC.IdEncabezado', '=', 'DE.IdEncabezado')
            //         ->on('DC.IdArticulo', '=', 'DD.IdArticulo')
            //         ->on('DC.Linea', '=', 'DD.Linea')
            //         ->where('DC.rn', '=', DB::raw('1'));
            // })
            ->leftJoin(DB::raw("(
                    SELECT IdEncabezado, IdArticulo, CantArticulo, ImporteArticulo, IdTipoPago, Linea, Source_Transaction_Identifier, IdSolicitudFactura
                    FROM DatCortesTienda dct1
                    WHERE dct1.IdCortesTienda in (
                        SELECT dct2.IdCortesTienda
                        FROM DatCortesTienda dct2
                        WHERE dct2.IdEncabezado = dct1.IdEncabezado
                        AND dct2.IdArticulo = dct1.IdArticulo
                        AND dct2.Linea = dct1.Linea
                    )
                ) as DC"), function ($join) {
                $join->on('DC.IdEncabezado', '=', 'DE.IdEncabezado')
                    ->on('DC.IdArticulo', '=', 'DD.IdArticulo')
                    ->on('DC.Linea', '=', 'DD.Linea');
            })
            ->leftJoin('SolicitudFactura as SF', 'SF.IdSolicitudFactura', '=', 'DC.IdSolicitudFactura')
            ->leftJoin('CatTipoPago as CPP', 'CPP.IdTipoPago', '=', 'DC.IdTipoPago')
            ->select(
                'DE.IdTicket',
                'DE.IdEncabezado',
                'DD.Linea',
                'DE.FechaVenta',
                // 'DE.FechaSubida',
                'DE.SubTotal',
                'DE.Iva',
                'DE.ImporteVenta',
                'DE.StatusVenta',
                'DE.IdUsuarioCancelacion',
                'CECC.Nombre as NombreUsuarioCancelacion',
                'CECC.Apellidos as ApellidoUsuarioCancelacion',
                'DE.MotivoCancel',
                'DE.FechaCancelacion',

                'DC.Source_Transaction_Identifier',
                'CPP.NomTipoPago',

                'DE.SolicitudFE',
                'SF.IdSolicitudFactura',
                'SF.NomCliente',
                'SF.UUID',

                'DE.NumNomina',
                'CEC.Nombre as NombreEmpleadoComprador',
                'CEC.Apellidos as ApellidosEmpleadoComprador',
                'DD.IdArticulo',
                'CA.CodArticulo',
                'CA.NomArticulo',
                'CF.NomFamilia',
                'CG.NomGrupo',

                // 'DD.CantArticulo',
                'DC.CantArticulo',

                'DD.PrecioArticulo',
                'DD.PrecioLista',

                // 'DD.ImporteArticulo',
                'DC.ImporteArticulo',

                'DD.IvaArticulo',
                'DD.SubTotalArticulo',
                'DD.IdListaPrecio',
                'DD.Recorte',
                'DD.IdPaquete',
                'CP.NomPaquete',
                'DD.IdEncDescuento',
                'DED.NomDescuento',
                'DE.IdTienda',
                'CT.NomTienda',
                'DE.IdUsuario',
                'CU.NomUsuario',
                DB::raw("CONCAT(CE.Nombre, ' ', CE.Apellidos) as NombreEmpleado")
            )

            // Filtros básicos
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('DE.IdTienda', $idTienda);
            })
            ->when($fecha, function ($query) use ($fecha) {
                $query->whereDate('DE.FechaVenta', $fecha);
            })
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween(DB::raw('CAST(DE.FechaVenta AS DATE)'), [$fechaInicio, $fechaFin]);
            })
            ->when($fechaInicio && !$fechaFin && !$fecha, function ($query) use ($fechaInicio) {
                $query->whereDate('DE.FechaVenta', '>=', $fechaInicio);
            })
            ->when($fechaFin && !$fechaInicio && !$fecha, function ($query) use ($fechaFin) {
                $query->whereDate('DE.FechaVenta', '<=', $fechaFin);
            })

            // Filtro de estado de venta
            ->when($statusVenta == 'on', function ($query) {
                $query->where('DE.StatusVenta', 1);
            })

            // Si NO se incluyen cancelados, filtrar StatusVenta = 1
            // ->when(!$cancelado, function ($query) {
            //     $query->where('DE.StatusVenta', 1);
            // })

            // Filtros checkbox
            ->when($solicitudFE !== null && $solicitudFE !== '', function ($query) use ($solicitudFE) {
                $query->whereNotNull('DE.SolicitudFE');
            })
            // ->when($recorte !== null && $recorte !== '', function ($query) use ($recorte) {
            //     $query->where('DD.Recorte', $recorte);
            // })
            // ->when($soloConDescuento, function ($query) {
            //     $query->whereNotNull('DD.IdEncDescuento');
            // })
            // ->when($soloPaquetes, function ($query) {
            //     $query->whereNotNull('DD.IdPaquete');
            // })
            // ->when($soloRecorte, function ($query) {
            //     $query->where('DD.Recorte', 1);
            // })
            // ->when($soloEmpleados, function ($query) {
            //     $query->whereNotNull('DE.NumNomina');
            // })

            // Filtros de artículos
            ->when($codArticulo, function ($query) use ($codArticulo) {
                $query->where('CA.CodArticulo', 'LIKE', "%{$codArticulo}%");
            })
            ->when($nomArticulo, function ($query) use ($nomArticulo) {
                $query->where('CA.NomArticulo', 'LIKE', "%{$nomArticulo}%");
            })
            ->when($familia, function ($query) use ($familia) {
                $query->where('CF.NomFamilia', 'LIKE', "%{$familia}%");
            })
            ->when($grupo, function ($query) use ($grupo) {
                $query->where('CG.NomGrupo', 'LIKE', "%{$grupo}%");
            })

            // Filtros de ticket y encabezado
            ->when($idTicket, function ($query) use ($idTicket) {
                $query->where('DE.IdTicket', $idTicket);
            })
            ->when($idEncabezado, function ($query) use ($idEncabezado) {
                $query->where('DE.IdEncabezado', $idEncabezado);
            })

            // Filtros de usuario y cliente
            ->when($usuario, function ($query) use ($usuario) {
                $query->where('CU.NomUsuario', 'LIKE', "%{$usuario}%");
            })
            ->when($numNomina, function ($query) use ($numNomina) {
                $query->where('DE.NumNomina', $numNomina);
            })
            ->when($nombreCliente, function ($query) use ($nombreCliente) {
                $query->where(function ($q) use ($nombreCliente) {
                    $q->where('CEC.Nombre', 'LIKE', "%{$nombreCliente}%")
                        ->orWhere('CEC.Apellidos', 'LIKE', "%{$nombreCliente}%");
                });
            })

            // Filtros adicionales
            ->when($idListaPrecio, function ($query) use ($idListaPrecio) {
                $query->where('DD.IdListaPrecio', $idListaPrecio);
            })
            ->when($idPaquete, function ($query) use ($idPaquete) {
                $query->where('DD.IdPaquete', $idPaquete);
            })
            ->when($idDescuento, function ($query) use ($idDescuento) {
                $query->where('DD.IdEncDescuento', $idDescuento);
            })

            // Filtros de rangos
            ->when($subtotalMin, function ($query) use ($subtotalMin) {
                $query->where('DE.SubTotal', '>=', $subtotalMin);
            })
            ->when($subtotalMax, function ($query) use ($subtotalMax) {
                $query->where('DE.SubTotal', '<=', $subtotalMax);
            })
            ->when($importeMin, function ($query) use ($importeMin) {
                $query->where('DE.ImporteVenta', '>=', $importeMin);
            })
            ->when($importeMax, function ($query) use ($importeMax) {
                $query->where('DE.ImporteVenta', '<=', $importeMax);
            })

            // Si no hay filtros, forzar resultado vacío
            ->when(!$idTienda && !$fecha && !$fechaInicio && !$fechaFin && !$codArticulo && !$nomArticulo &&
                !$usuario && !$numNomina && !$idTicket && !$idEncabezado && !$statusVenta &&
                !$solicitudFE && !$recorte && !$familia && !$grupo && !$idListaPrecio &&
                !$idPaquete && !$idDescuento && !$nombreCliente && !$subtotalMin && !$subtotalMax &&
                !$importeMin && !$importeMax && !$cancelado && !$soloConDescuento &&
                !$soloPaquetes && !$soloRecorte && !$soloEmpleados, function ($query) {
                $query->whereRaw('1 = 0');
            })

            ->whereIn('DE.IdTienda', $tiendasIds)
            ->orderBy('DE.IdTicket', 'desc')
            ->orderBy('DD.Linea');

        if ($exports)
            return $query;
        else
            return $query->get();
    }

    private function queryDetallado(string $idEncabezado)
    {
        $tiendasIds = $this->tiendasIds;

        // Construir la consulta
        // $query = DB::connection('server')->table('DatEncabezado as DE')
        $query = DB::table('DatEncabezado as DE')
            ->leftJoin('DatDetalle as DD', 'DD.IdEncabezado', '=', 'DE.IdEncabezado')
            ->leftJoin('CatPaquetes as CP', 'CP.IdPaquete', '=', 'DD.IdPaquete')
            ->leftJoin('DatEncDescuentos as DED', 'DED.IdEncDescuento', '=', 'DD.IdEncDescuento')
            ->leftJoin('CatArticulos as CA', 'CA.IdArticulo', '=', 'DD.IdArticulo')
            ->leftJoin('CatFamilias as CF', 'CF.IdFamilia', '=', 'CA.IdFamilia')
            ->leftJoin('CatGrupos as CG', 'CG.IdGrupo', '=', 'CA.IdGrupo')
            ->leftJoin('CatEmpleados as CEC', 'CEC.NumNomina', '=', 'DE.NumNomina')
            ->leftJoin('CatTiendas as CT', 'CT.IdTienda', '=', 'DE.IdTienda')
            ->leftJoin('CatUsuarios as CU', 'CU.IdUsuario', '=', 'DE.IdUsuario')
            ->leftJoin('CatEmpleados as CE', 'CE.NumNomina', '=', 'CU.NumNomina')
            ->leftJoin('CatUsuarios as CUC', 'CUC.IdUsuario', '=', 'DE.IdUsuarioCancelacion')
            ->leftJoin('CatEmpleados as CECC', 'CECC.NumNomina', '=', 'CUC.NumNomina')
            ->select(
                'DE.IdTicket',
                'DE.IdEncabezado',
                'DD.Linea',
                'DE.FechaVenta',
                // 'DE.FechaSubida',
                'DE.SubTotal',
                'DE.Iva',
                'DE.ImporteVenta',
                'DE.StatusVenta',
                'DE.IdUsuarioCancelacion',
                'CECC.Nombre as NombreUsuarioCancelacion',
                'CECC.Apellidos as ApellidoUsuarioCancelacion',
                'DE.MotivoCancel',
                'DE.FechaCancelacion',

                'DE.SolicitudFE',

                'DE.NumNomina',
                'CEC.Nombre as NombreEmpleadoComprador',
                'CEC.Apellidos as ApellidosEmpleadoComprador',
                'DD.IdArticulo',
                'CA.CodArticulo',
                'CA.NomArticulo',
                'CF.NomFamilia',
                'CG.NomGrupo',
                'DD.CantArticulo',
                'DD.PrecioArticulo',
                'DD.PrecioLista',
                'DD.ImporteArticulo',
                'DD.IvaArticulo',
                'DD.SubTotalArticulo',
                'DD.IdListaPrecio',
                'DD.Recorte',
                'DD.IdPaquete',
                'CP.NomPaquete',
                'DD.IdEncDescuento',
                'DED.NomDescuento',
                'DE.IdTienda',
                'CT.NomTienda',
                'DE.IdUsuario',
                'CU.NomUsuario',
                DB::raw("CONCAT(CE.Nombre, ' ', CE.Apellidos) as NombreEmpleado")
            )
            ->when($idEncabezado, function ($query) use ($idEncabezado) {
                $query->where('DE.IdEncabezado', $idEncabezado);
            })
            ->whereIn('DE.IdTienda', $tiendasIds)
            ->orderBy('DE.IdTicket', 'desc')
            ->orderBy('DD.Linea');

        $queryPagos = DB::connection('server')
            ->table('DatTipoPago as dt')
            ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', '=', 'dt.IdTipoPago')
            ->where('dt.IdEncabezado', $idEncabezado)
            ->select(
                'dt.*',
                'ct.NomTipoPago'
            );

        $querySolicitud = DB::connection('server')
            ->table('SolicitudFactura as sf')
            ->where('sf.IdEncabezado', $idEncabezado)
            ->select(
                'sf.*'
            );
        return [
            'detalle' => $query->get(),
            'pagos' => $queryPagos->get(),
            'facturas' => $querySolicitud->get(),
        ];
    }

    public function index(Request $request)
    {
        // Solo validar si hay algún filtro activo (se hizo clic en Filtrar)
        $hasAnyFilter = $request->filled('idTienda') ||
            $request->filled('fecha') ||
            $request->filled('id_ticket') ||
            $request->filled('id_encabezado') ||
            $request->filled('folio') ||
            $request->filled('status_venta') ||
            $request->filled('solicitud_fe') ||
            $request->filled('cancelado');

        if ($hasAnyFilter) {
            if ($request->filled('id_encabezado') || $request->filled('folio')) {
                // Si viene id_encabezado o folio, no requiere más filtros
                $request->validate([
                    'id_encabezado' => 'nullable|integer',
                    'folio' => 'nullable|string',
                ]);
            } else {
                // Si no, tienda y fecha son obligatorias
                $request->validate([
                    'idTienda' => 'required|integer',
                    'fecha' => 'required|date',
                ], [
                    'idTienda.required' => 'Selecciona una tienda o busca por ID Encabezado/Folio.',
                    'fecha.required' => 'La fecha es obligatoria o busca por ID Encabezado/Folio.',
                ]);
            }
        }

        $idEncabezado = $request->id_encabezado;
        $folio = $request->folio;
        if ($folio) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($folio);
            $idEncabezado = is_array($decoded) && count($decoded) > 0 ? $decoded[0] : null;
        }

        $filtrosAvanzadosActivos =
            $request->filled('folio') ||
            $request->filled('status_venta') ||
            $request->filled('solicitud_fe') ||
            $request->filled('id_movimiento') ||
            $request->filled('usuario') ||
            $request->filled('num_nomina') ||
            $request->filled('referencia') ||
            $request->filled('id_caja');

        $tiendas = $this->tiendas;

        // return
        $data = $this->query($request);

        $dataDetallado = collect();
        $detalleData = null;
        $pagosData = null;
        $facturasData = null;
        // return $idEncabezado;
        if ($idEncabezado) {
            $dataDetallado = $this->queryDetallado($idEncabezado);

            $detalleData = $dataDetallado['detalle'] ?? null;
            $pagosData = $dataDetallado['pagos'] ?? null;
            $facturasData = $dataDetallado['facturas'] ?? null;
        }

        return view('Dashboards/venta-por-ticket', compact(
            'data',
            'dataDetallado',
            'tiendas',
            'idEncabezado',
            'filtrosAvanzadosActivos',
            'detalleData',
            'pagosData',
            'facturasData'
        ));
    }

    public function exports(Request $request)
    {
        $query = $this->query($request, true);

        $name = 'exports.xlsx';
        return Excel::download(new VentasDetalladasExport($query), $name);
    }
}
