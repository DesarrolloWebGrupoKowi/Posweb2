<?php

namespace App\Http\Controllers;

use App\Exports\ConcentradoDeArticulosExport;
use App\Exports\ConcentradoDeTicketsExport;
use App\Exports\ConcentradoPorCiudadYFamilia;
use App\Exports\DineroElectronicoExport;
use App\Exports\GrupoYTipoPrecio;
use App\Exports\Mermas;
use App\Exports\ReporteDescuentos;
use App\Exports\ReportePaquetes;
use App\Exports\VentasPorTipoDePrecioExport;
use App\Models\CapMerma;
use App\Models\CatPaquete;
use App\Models\DatEncabezado;
use App\Models\DatRosticero;
use App\Models\DatTipoPago;
use App\Models\Familia;
use App\Models\Grupo;
use App\Models\Tienda;
use App\Services\TiendaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{

    protected Collection $tiendas;
    protected array $tiendasIds;

    public function __construct(protected TiendaService $tiendaService)
    {
        $this->tiendas = new Collection;
        $this->tiendasIds = [];

        $this->middleware(function ($request, $next) {
            $this->tiendas = $this->tiendaService->obtenerTiendasOpcional();
            $this->tiendasIds = $this->tiendaService->obtenerTiendasIds();
            return $next($request);
        });
    }

    /* ==========================================================================
    |  CONCENTRADO DE ARTÍCULOS
    |========================================================================== */
    // Reporte
    public function ReporteConcentradoDeArticulos(Request $request)
    {
        $tiendas = $this->tiendas;
        $tiendasIds = $this->tiendasIds;

        // Obtener valores de los filtros
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $txtFiltro = $request->txtFiltro;
        $optionsOnline = $request->optionsOnline;
        $agrupado = $request->agrupado;
        $agrupadoArticulo = $request->agrupadoArticulo;

        $filtrosAvanzadosActivos =
            $request->filled('agrupadoArticulo') ||
            $request->filled('agrupado');

        // Agregando comedores cuando el menudeo saca reporte de ventas
        $tiendaKukita = Auth::user()->usuarioTienda->IdTienda;
        if ($tiendaKukita == 3) {
            $tiendas = collect($this->tiendas)
                ->push(
                    (object)[
                        "IdTienda" => 1,
                        "NomTienda" => "KOWI EXPRESS COMEDOR PLANTA"
                    ],
                    (object)[
                        "IdTienda" => 2,
                        "NomTienda" => "KOWI EXPRESS COMEDOR 2 PLANTA"
                    ]
                );
            $tiendasIds = array_merge($this->tiendasIds, [1, 2]);
        }

        // Definir columnas seleccionadas
        $select = [
            'g.NomCiudad',
            'f.NomTienda',
            'e.NomGrupo',
            // 'h.NomListaPrecio',
            'c.CodArticulo',
            'c.NomArticulo',
            DB::raw('SUM(b.CantArticulo) as Peso'),
            DB::raw('SUM(b.IvaArticulo) as Iva'),
            DB::raw('SUM(b.ImporteArticulo) as Importe'),
        ];

        // Definir agrupación base
        $groupBy = [
            'g.NomCiudad',
            'f.NomTienda',
            'e.NomGrupo',
            // 'h.NomListaPrecio',
            'c.CodArticulo',
            'c.NomArticulo',
        ];

        // Agregar PrecioArticulo al groupBy si NO está agrupado por artículo
        if ($agrupadoArticulo != 'on') {
            // $groupBy[] = 'b.PrecioArticulo';
            // $select[] = 'b.PrecioArticulo';
            $groupBy = array_merge($groupBy, ['b.PrecioArticulo', 'h.NomListaPrecio']);
            $select = array_merge($select, ['b.PrecioArticulo', 'h.NomListaPrecio']);
            // $select[] = 'h.NomListaPrecio';
            // $groupBy[] = 'h.NomListaPrecio';
        }

        // Agregar fecha al groupBy si está agrupado por fecha
        if ($agrupado == 'on') {
            $select[] = DB::raw('CAST(a.FechaVenta AS DATE) as FechaVenta');
            $groupBy[] = DB::raw('CAST(a.FechaVenta AS DATE)');
        }

        // Consulta principal usando when()
        $concentrado = DB::connection($optionsOnline == 'on' ? 'server' : null)
            ->table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatFamilias as d', 'c.IdFamilia', 'd.IdFamilia')
            ->leftJoin('CatGrupos as e', 'c.IdGrupo', 'e.IdGrupo')
            ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->leftJoin('CatListasPrecio as h', 'b.IdListaPrecio', 'h.IdListaPrecio')
            ->select($select)
            // Filtro de tiendas permitidas
            // ->when($idTiendas->isNotEmpty(), function ($query) use ($idTiendas) {
            //     $query->whereIn('a.IdTienda', $idTiendas);
            // })
            // Filtro específico de tienda
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            // Filtro de fechas SOLO si vienen ambas fechas
            ->when((!empty($fecha1) && !empty($fecha2)), function ($query) use ($fecha1, $fecha2) {
                $query->whereBetween(DB::raw('cast(a.FechaVenta as date)'), [$fecha1, $fecha2]);
            })
            // Si NO vienen fechas, forzar un resultado vacío
            ->when(empty($fecha1) || empty($fecha2), function ($query) {
                $query->whereRaw('1 = 0');
            })
            // Filtro por código o nombre de artículo
            ->when($txtFiltro, function ($query) use ($txtFiltro) {
                $query->where(function ($sub) use ($txtFiltro) {
                    $sub->where('c.CodArticulo', 'like', "%$txtFiltro%")
                        ->orWhere('c.NomArticulo', 'like', "%$txtFiltro%");
                });
            })
            // Condiciones fijas
            ->whereIn('a.IdTienda', $tiendasIds)
            ->where('a.StatusVenta', 0)
            ->whereNotNull('c.CodArticulo')
            // Agrupación
            ->groupBy($groupBy)
            // Ordenamiento condicional
            ->when($agrupado == 'on', function ($query) {
                $query->orderBy('FechaVenta')
                    ->orderBy('c.CodArticulo');
            }, function ($query) {
                $query->orderBy('c.CodArticulo');
            })
            ->get();

        return view('Reportes.ConcentradoDeArticulos', compact(
            'tiendas',
            'filtrosAvanzadosActivos',
            'concentrado',
            'optionsOnline',
            'agrupado',
            'agrupadoArticulo'
        ));
    }

    // Exports
    public function ExportReporteConcentradoDeArticulos(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1 ?? Carbon::now()->format('Y-m-d');
        $fecha2 = $request->fecha2 ?? Carbon::now()->format('Y-m-d');
        $txtFiltro = $request->txtFiltro;
        $optionsOnline = $request->optionsOnline ?? 'off';
        $agrupado = $request->agrupado == 'on' ? true : false;
        $agrupadoArticulo = $request->agrupadoArticulo == 'on' ? true : false;

        $usuarioTienda = Auth::user()->usuarioTienda;

        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)->where('Status', 0)->orderBy('IdTienda')->get();
        } elseif (!empty($usuarioTienda->IdTienda)) {
            $tiendas = Tienda::where('IdTienda', $usuarioTienda->IdTienda)->where('Status', 0)->orderBy('IdTienda')->get();
        } elseif ($usuarioTienda->Todas == 0) {
            $tiendas = Tienda::orderBy('IdTienda')->get();
        } else {
            $tiendas = collect(); // Por si no entra a ningún caso
        }

        $idTiendas = $tiendas->pluck('IdTienda');

        // Agregando comedores cuando el menudeo saca reporte de ventas
        $tiendaKukita = Auth::user()->usuarioTienda->IdTienda;
        if ($tiendaKukita == 3) {
            $idTiendas = array_merge($this->tiendasIds, [1, 2]);
        }

        // Definir columnas seleccionadas
        $select = [
            'g.NomCiudad',
            'f.NomTienda',
            'e.NomGrupo',
            // 'h.NomListaPrecio',
            'c.CodArticulo',
            'c.NomArticulo',
            DB::raw('SUM(b.CantArticulo) as Peso'),
            DB::raw('SUM(b.IvaArticulo) as Iva'),
            DB::raw('SUM(b.ImporteArticulo) as Importe'),
        ];

        // Definir agrupación base
        $groupBy = [
            'g.NomCiudad',
            'f.NomTienda',
            'e.NomGrupo',
            // 'h.NomListaPrecio',
            'c.CodArticulo',
            'c.NomArticulo',
        ];

        // Agregar PrecioArticulo al groupBy si NO está agrupado por artículo
        if ($agrupadoArticulo != 'on') {
            // $groupBy[] = 'b.PrecioArticulo';
            // $select[] = 'b.PrecioArticulo';
            $groupBy = array_merge($groupBy, ['b.PrecioArticulo', 'h.NomListaPrecio']);
            $select = array_merge($select, ['b.PrecioArticulo', 'h.NomListaPrecio']);
            // $select[] = 'h.NomListaPrecio';
            // $groupBy[] = 'h.NomListaPrecio';
        }

        // Agregar fecha al groupBy si está agrupado por fecha
        if ($agrupado == 'on') {
            $select[] = DB::raw('CAST(a.FechaVenta AS DATE) as FechaVenta');
            $groupBy[] = DB::raw('CAST(a.FechaVenta AS DATE)');
        }

        $concentradoQ = DB::connection($optionsOnline == 'on' ? 'server' : null)
            ->table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatFamilias as d', 'c.IdFamilia', 'd.IdFamilia')
            ->leftJoin('CatGrupos as e', 'c.IdGrupo', 'e.IdGrupo')
            ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->leftJoin('CatListasPrecio as h', 'b.IdListaPrecio', 'h.IdListaPrecio')
            ->select($select)
            ->whereIn('a.IdTienda', $idTiendas)
            ->when($idTienda, fn($q) => $q->where('a.IdTienda', $idTienda))
            ->where('a.StatusVenta', 0)
            ->whereNotNull('c.CodArticulo')
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->when($txtFiltro, function ($q) use ($txtFiltro) {
                $q->where(function ($sub) use ($txtFiltro) {
                    $sub->where('c.CodArticulo', 'like', "%$txtFiltro%")
                        ->orWhere('c.NomArticulo', 'like', "%$txtFiltro%");
                });
            })
            ->groupBy($groupBy);

        if ($agrupado) {
            $concentradoQ->orderBy('FechaVenta');
            $concentradoQ->orderBy('c.CodArticulo');
        } else {
            $concentradoQ->orderBy('c.CodArticulo');
        }

        // Resultado final
        $concentrado = $concentradoQ->get();

        // $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'exports.xlsx';
        $name = 'exports.xlsx';
        return Excel::download(new ConcentradoDeArticulosExport($concentrado, $agrupado, $agrupadoArticulo), $name);
    }

    /* ==========================================================================
    |  CONCENTRADO DE DESCUENTOS
    |========================================================================== */
    // Query
    private function queryDescuentos(Request $request, $exports = false)
    {
        // Obtener todos los parámetros del filtro
        $idTienda = $request->idTienda; // CHECK
        $fechaInicio = $request->fecha_inicio; // CHECK
        $fechaFin = $request->fecha_fin; // CHECK
        $codArticulo = $request->cod_articulo;
        $nomArticulo = $request->nom_articulo;
        $idEncDescuento = $request->id_enc_descuento;
        $nomDescuento = $request->nom_descuento;
        $idFamilia = $request->id_familia;
        $nomFamilia = $request->nom_familia;
        $idGrupo = $request->id_grupo;

        $tiendasIds = $this->tiendasIds;

        // Construir la consulta
        $query = DB::connection('server')->table('DatEncabezado as DE')
            ->leftJoin('DatDetalle as DD', 'DD.IdEncabezado', '=', 'DE.IdEncabezado')
            ->leftJoin('DatEncDescuentos as ED', 'ED.IdEncDescuento', '=', 'DD.IdEncDescuento')
            ->leftJoin('CatArticulos as CA', 'CA.IdArticulo', '=', 'DD.IdArticulo')
            ->leftJoin('CatFamilias as CF', 'CF.IdFamilia', '=', 'CA.IdFamilia')
            ->leftJoin('CatGrupos as CG', 'CG.IdGrupo', '=', 'CA.IdGrupo')
            ->leftJoin('CatTiendas as CT', 'CT.IdTienda', '=', 'DE.IdTienda')
            ->select(
                'DD.IdEncDescuento',
                'ED.NomDescuento',
                'CT.NomTienda',
                DB::raw("CONVERT(VARCHAR(10), DE.FechaVenta, 103) as FechaVenta"),
                'CA.CodArticulo',
                'CA.NomArticulo',
                'CF.NomFamilia',
                'CG.NomGrupo',
                'DD.CantArticulo',
                'DD.PrecioLista',
                'DD.PrecioArticulo',
                'DD.IvaArticulo',
                'DD.ImporteArticulo',
                'DD.SubTotalArticulo'
            )
            ->whereNotNull('DD.IdEncDescuento') // Equivalente a "IS NOT NULL"
            ->where('DE.StatusVenta', 0)

            // Filtro por tienda
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('DE.IdTienda', $idTienda);
            })
            ->when(!$idTienda, function ($query) use ($tiendasIds) {
                $query->whereIn('DE.IdTienda', $tiendasIds);
            })

            // Filtro por rango de fechas
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween(DB::raw('CAST(DE.FechaVenta as DATE)'), [$fechaInicio, $fechaFin]);
            })

            // Si solo viene fecha inicio
            ->when($fechaInicio && !$fechaFin, function ($query) use ($fechaInicio) {
                $query->whereDate('DE.FechaVenta', '>=', $fechaInicio);
            })

            // Si solo viene fecha fin
            ->when($fechaFin && !$fechaInicio, function ($query) use ($fechaFin) {
                $query->whereDate('DE.FechaVenta', '<=', $fechaFin);
            })

            // Filtro por código de artículo
            ->when($codArticulo, function ($query) use ($codArticulo) {
                $query->where('CA.CodArticulo', 'LIKE', "%{$codArticulo}%");
            })

            // Filtro por nombre de artículo
            ->when($nomArticulo, function ($query) use ($nomArticulo) {
                $query->where('CA.NomArticulo', 'LIKE', "%{$nomArticulo}%");
            })

            // Filtro por ID de encabezado de descuento
            ->when($idEncDescuento, function ($query) use ($idEncDescuento) {
                $query->where('DD.IdEncDescuento', $idEncDescuento);
            })

            // Filtro por nombre de descuento
            ->when($nomDescuento, function ($query) use ($nomDescuento) {
                $query->where('ED.NomDescuento', 'LIKE', "%{$nomDescuento}%");
            })

            // Filtro por ID de familia
            ->when($idFamilia, function ($query) use ($idFamilia) {
                $query->where('CA.IdFamilia', $idFamilia);
            })

            // Filtro por nombre de familia
            ->when($nomFamilia, function ($query) use ($nomFamilia) {
                $query->where('CF.NomFamilia', 'LIKE', "%{$nomFamilia}%");
            })

            // Filtro por ID de familia
            ->when($idGrupo, function ($query) use ($idGrupo) {
                $query->where('CG.IdGrupo', $idGrupo);
            })

            // Si no hay filtros (excepto los obligatorios), forzar resultado vacío
            ->when(!$idTienda && !$fechaInicio && !$fechaFin && !$codArticulo && !$nomArticulo && !$idEncDescuento && !$nomDescuento && !$idFamilia && !$nomFamilia && !$idGrupo, function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->orderBy('DE.FechaVenta', 'DESC')
            ->orderBy('DD.IdEncDescuento', 'DESC');

        if ($exports)
            return $query;
        else
            // return $query->paginate(10)
            //     ->appends(request()->query());
            return $query->get();
    }

    // Reporte
    public function reporteDescuentos(Request $request)
    {
        $filtrosAvanzadosActivos =
            $request->filled('id_familia') ||
            $request->filled('id_grupo') ||
            $request->filled('id_enc_descuento') ||
            $request->filled('nom_descuento');

        $tiendas = $this->tiendas;
        $familias = Familia::orderBy('NomFamilia')->get();
        $grupos = Grupo::orderBy('NomGrupo')->get();

        $data = $this->queryDescuentos($request);

        return view('Reportes.Descuentos', compact(
            'filtrosAvanzadosActivos',
            'tiendas',
            'familias',
            'grupos',
            'data',
        ));
    }

    // Exports
    public function exportsDescuentos(Request $request)
    {
        $query = $this->queryDescuentos($request, true);

        $name = 'exports.xlsx';
        return Excel::download(new ReporteDescuentos($query), $name);
    }

    /* ==========================================================================
    |  REPORTE DE VENTAS DE PAQUETES
    |========================================================================== */
    // Query
    private function queryPaquetes(Request $request, $exports = false)
    {
        // Obtener todos los parámetros del filtro
        $idTienda = $request->idTienda;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $codArticulo = $request->cod_articulo;
        $nomArticulo = $request->nom_articulo;
        $idPaquete = $request->id_paquete;
        $nomPaquete = $request->nom_paquete;
        $idFamilia = $request->id_familia;
        $nomFamilia = $request->nom_familia;
        $idGrupo = $request->id_grupo;

        $tiendasIds = $this->tiendasIds;

        // Construir la consulta
        $query = DB::connection('server')->table('DatEncabezado as DE')
            ->leftJoin('DatDetalle as DD', 'DD.IdEncabezado', '=', 'DE.IdEncabezado')
            ->leftJoin('CatPaquetes as CP', 'CP.IdPaquete', '=', 'DD.IdPaquete')
            ->leftJoin('CatArticulos as CA', 'CA.IdArticulo', '=', 'DD.IdArticulo')
            ->leftJoin('CatFamilias as CF', 'CF.IdFamilia', '=', 'CA.IdFamilia')
            ->leftJoin('CatGrupos as CG', 'CG.IdGrupo', '=', 'CA.IdGrupo')
            ->leftJoin('CatTiendas as CT', 'CT.IdTienda', '=', 'DE.IdTienda')
            ->select(
                'DE.IdEncabezado',
                DB::raw("CASE WHEN CP.IdPreparado IS NULL THEN DD.IdPaquete ELSE NULL END as IdPaquete"),
                // 'CP.IdPaquete',
                'CP.IdPreparado',
                'CP.NomPaquete',
                'CT.NomTienda',
                DB::raw("CONVERT(VARCHAR(10), DE.FechaVenta, 103) as FechaVenta"),
                'CA.CodArticulo',
                'CA.NomArticulo',
                'CF.NomFamilia',
                'CG.NomGrupo',
                'DD.CantArticulo',
                'DD.PrecioArticulo',
                'DD.IvaArticulo',
                'DD.ImporteArticulo'
            )
            ->where('DE.StatusVenta', 0)
            ->whereIn('DE.IdEncabezado', function ($subquery) use ($idPaquete, $nomPaquete) {
                $subquery->select('DD2.IdEncabezado')
                    ->from('DatDetalle as DD2')
                    ->join('CatPaquetes as CP2', 'CP2.IdPaquete', '=', 'DD2.IdPaquete')

                    // Filtro por ID de paquete
                    ->when($idPaquete, function ($query) use ($idPaquete) {
                        $query->where('CP2.IdPaquete', $idPaquete);
                    })

                    // Filtro por nombre de paquete
                    ->when($nomPaquete, function ($query) use ($nomPaquete) {
                        $query->where('CP2.NomPaquete', 'LIKE', "%{$nomPaquete}%");
                    })

                    ->whereNull('CP2.IdPreparado');
            })

            // Filtro por tienda
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('DE.IdTienda', $idTienda);
            })
            ->when(!$idTienda, function ($query) use ($tiendasIds) {
                $query->whereIn('DE.IdTienda', $tiendasIds);
            })

            // Filtro por rango de fechas
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween(DB::raw('CAST(DE.FechaVenta as DATE)'), [$fechaInicio, $fechaFin]);
            })

            // Si solo viene fecha inicio
            ->when($fechaInicio && !$fechaFin, function ($query) use ($fechaInicio) {
                $query->whereDate('DE.FechaVenta', '>=', $fechaInicio);
            })

            // Si solo viene fecha fin
            ->when($fechaFin && !$fechaInicio, function ($query) use ($fechaFin) {
                $query->whereDate('DE.FechaVenta', '<=', $fechaFin);
            })

            // Filtro por código de artículo
            ->when($codArticulo, function ($query) use ($codArticulo) {
                $query->where('CA.CodArticulo', 'LIKE', "%{$codArticulo}%");
            })

            // Filtro por nombre de artículo
            ->when($nomArticulo, function ($query) use ($nomArticulo) {
                $query->where('CA.NomArticulo', 'LIKE', "%{$nomArticulo}%");
            })

            // Filtro por ID de paquete
            // ->when($idPaquete, function ($query) use ($idPaquete) {
            //     $query->where('DD.IdPaquete', $idPaquete);
            // })

            // Filtro por nombre de paquete
            // ->when($nomPaquete, function ($query) use ($nomPaquete) {
            //     $query->where('CP.NomPaquete', 'LIKE', "%{$nomPaquete}%");
            // })

            // Filtro por ID de familia
            ->when($idFamilia, function ($query) use ($idFamilia) {
                $query->where('CA.IdFamilia', $idFamilia);
            })

            // Filtro por nombre de familia
            ->when($nomFamilia, function ($query) use ($nomFamilia) {
                $query->where('CF.NomFamilia', 'LIKE', "%{$nomFamilia}%");
            })

            // Filtro por ID de grupo
            ->when($idGrupo, function ($query) use ($idGrupo) {
                $query->where('CG.IdGrupo', $idGrupo);
            })

            // Si no hay filtros (excepto los obligatorios), forzar resultado vacío
            ->when(!$idTienda && !$fechaInicio && !$fechaFin && !$codArticulo && !$nomArticulo && !$idPaquete && !$nomPaquete && !$idFamilia && !$nomFamilia && !$idGrupo, function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->orderBy('DE.FechaVenta', 'DESC')
            // ->orderBy(DB::raw("CASE WHEN CP.IdPreparado IS NULL THEN NULL ELSE DD.IdPaquete END"), 'DESC');
            ->orderBy('DD.IdPaquete', 'DESC');

        if ($exports)
            return $query;
        else
            return $query->get();
    }

    private function getPaquetesKPIs(Collection $data): array
    {
        // Estructuras de datos
        $paquetesInfo = [];
        $ticketsInfo = [];
        $totalGeneral = [
            'importe_paquetes' => 0,
            'cantidad_articulos_paquetes' => 0,
            'tickets_unicos' => []
        ];

        foreach ($data as $item) {
            $ticketId = $item->IdEncabezado;

            // Registrar tickets únicos
            if (!in_array($ticketId, $totalGeneral['tickets_unicos'])) {
                $totalGeneral['tickets_unicos'][] = $ticketId;
            }

            // Procesar solo items que pertenecen a paquetes
            if ($item->IdPaquete) {
                $paqueteId = $item->IdPaquete;

                // Inicializar paquete si no existe
                if (!isset($paquetesInfo[$paqueteId])) {
                    $paquetesInfo[$paqueteId] = [
                        'id' => $paqueteId,
                        'nombre' => $item->NomPaquete,
                        'total_importe' => 0,
                        'total_importe_todos_articulos' => 0,
                        'total_cantidad' => 0,
                        'tickets' => [],
                        'articulos' => [],
                        'veces_vendido' => 0
                    ];
                }

                // Registrar ticket del paquete
                if (!in_array($ticketId, $paquetesInfo[$paqueteId]['tickets'])) {
                    $paquetesInfo[$paqueteId]['tickets'][] = $ticketId;
                    $paquetesInfo[$paqueteId]['veces_vendido']++;
                }

                // Acumular valores del paquete
                $paquetesInfo[$paqueteId]['total_importe'] += floatval($item->ImporteArticulo);
                $paquetesInfo[$paqueteId]['total_cantidad'] += floatval($item->CantArticulo);

                // Registrar artículos del paquete
                $articuloKey = $item->CodArticulo;
                if (!isset($paquetesInfo[$paqueteId]['articulos'][$articuloKey])) {
                    $paquetesInfo[$paqueteId]['articulos'][$articuloKey] = [
                        'codigo' => $item->CodArticulo,
                        'nombre' => $item->NomArticulo,
                        'cantidad' => 0,
                        'importe' => 0,
                        'precio_unitario' => floatval($item->PrecioArticulo)
                    ];
                }
                $paquetesInfo[$paqueteId]['articulos'][$articuloKey]['cantidad'] += floatval($item->CantArticulo);
                $paquetesInfo[$paqueteId]['articulos'][$articuloKey]['importe'] += floatval($item->ImporteArticulo);

                // Acumular totales generales
                $totalGeneral['importe_paquetes'] += floatval($item->ImporteArticulo);
                $totalGeneral['cantidad_articulos_paquetes'] += floatval($item->CantArticulo);
            }
            $paquetesInfo[$paqueteId]['total_importe_todos_articulos'] += floatval($item->ImporteArticulo);
        }

        // Calcular métricas adicionales
        $totalPaquetes = count($paquetesInfo);
        $totalTicketsConPaquetes = count($totalGeneral['tickets_unicos']);
        $ticketPromedioPorPaquete = $totalPaquetes > 0 ? round($totalTicketsConPaquetes / $totalPaquetes, 2) : 0;
        $importePromedioPorPaquete = $totalPaquetes > 0 ? $totalGeneral['importe_paquetes'] / $totalPaquetes : 0;

        // Encontrar el paquete más vendido (mayor importe)
        $paqueteMasVendido = null;
        $paqueteMasFrecuente = null;
        $paqueteMayorImporte = null;

        foreach ($paquetesInfo as $paquete) {
            // Paquete con mayor importe
            if (!$paqueteMayorImporte || $paquete['total_importe'] > $paqueteMayorImporte['total_importe']) {
                $paqueteMayorImporte = $paquete;
            }

            // Paquete más frecuente (más veces vendido)
            if (!$paqueteMasFrecuente || $paquete['veces_vendido'] > $paqueteMasFrecuente['veces_vendido']) {
                $paqueteMasFrecuente = $paquete;
            }
        }

        return [
            'total_paquetes' => $totalPaquetes,
            'total_tickets_con_paquetes' => $totalTicketsConPaquetes,
            'total_importe_paquetes' => $totalGeneral['importe_paquetes'],
            'total_articulos_paquetes' => $totalGeneral['cantidad_articulos_paquetes'],
            'ticket_promedio_por_paquete' => $ticketPromedioPorPaquete,
            'importe_promedio_por_paquete' => $importePromedioPorPaquete,
            'paquete_mas_frecuente' => $paqueteMasFrecuente,
            'paquete_mayor_importe' => $paqueteMayorImporte,
            'paquetes_detalle' => $paquetesInfo
        ];
    }

    // Reporte
    public function reportePaquetes(Request $request)
    {
        $filtrosAvanzadosActivos =
            $request->filled('id_familia') ||
            $request->filled('id_grupo') ||
            $request->filled('id_paquete') ||
            $request->filled('nom_paquete');

        $tiendas = $this->tiendas;
        $familias = Familia::orderBy('NomFamilia')->get();
        $grupos = Grupo::orderBy('NomGrupo')->get();
        $paquetes = CatPaquete::whereNull('IdPreparado')
            ->where(function ($query) {
                $query->whereNull('FechaEliminacion')
                    ->orWhereDate('FechaEliminacion', '>', now()->subYear());
            })
            ->select('IdPaquete', 'NomPaquete')
            ->orderBy('Status')
            ->orderBy('NomPaquete')
            ->get();



        // return
        $data = $this->queryPaquetes($request);

        // Calcular KPIs de paquetes
        $paquetesKPIs = $this->getPaquetesKPIs($data);

        return view('Reportes.Paquetes', compact(
            'filtrosAvanzadosActivos',
            'tiendas',
            'familias',
            'grupos',
            'paquetes',
            'paquetesKPIs',
            'data',
        ));
    }

    // Exports
    public function exportsPaquetes(Request $request)
    {
        $query = $this->queryPaquetes($request, true);

        $name = 'exports.xlsx';
        return Excel::download(new ReportePaquetes($query), $name);
    }

    public function ReporteConcentradoDeTickets(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1 ?? Carbon::now()->format('Y-m-d');
        $fecha2 = $request->fecha2 ?? Carbon::now()->format('Y-m-d');

        $usuarioTienda = Auth::user()->usuarioTienda;
        $tiendasQuery = Tienda::where('Status', 0)->orderBy('IdTienda');

        if (!empty($usuarioTienda->IdTienda)) {
            $tiendasQuery->where('IdTienda', $usuarioTienda->IdTienda);
        }

        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendasQuery->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        $tiendas = $tiendasQuery->get();
        $idsTiendas = $tiendas->pluck('IdTienda')->toArray();

        $concentrado = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw('g.NomCiudad, f.NomTienda,  SUM(b.ImporteArticulo) as Importe, cast(a.FechaVenta as date) as Fecha, count(DISTINCT a.IdTicket) as Tickets'))
            ->whereIn('a.IdTienda', $idsTiendas)
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('g.NomCiudad', 'f.IdTienda', 'f.NomTienda', DB::raw('cast(a.FechaVenta as date)'))
            ->orderBy('Fecha')
            ->orderBy('f.IdTienda')
            ->get();

        return view('Reportes.ConcentradoDeTickets', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'concentrado'));
    }

    public function ExportReporteConcentradoDeTickets(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1 ?? Carbon::now()->format('Y-m-d');
        $fecha2 = $request->fecha2 ?? Carbon::now()->format('Y-m-d');

        $usuarioTienda = Auth::user()->usuarioTienda;
        $tiendasQuery = Tienda::where('Status', 0)->orderBy('IdTienda');

        if (!empty($usuarioTienda->IdTienda)) {
            $tiendasQuery->where('IdTienda', $usuarioTienda->IdTienda);
        }

        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendasQuery->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        $tiendas = $tiendasQuery->get();
        $idsTiendas = $tiendas->pluck('IdTienda')->toArray();

        $concentrado = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw('g.NomCiudad, f.NomTienda,  SUM(b.ImporteArticulo) as Importe, cast(a.FechaVenta as date) as Fecha, count(DISTINCT a.IdTicket) as Tickets'))
            ->whereIn('a.IdTienda', $idsTiendas)
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('g.NomCiudad', 'f.IdTienda', 'f.NomTienda', DB::raw('cast(a.FechaVenta as date)'))
            ->orderBy('Fecha')
            ->orderBy('f.IdTienda')
            ->get();

        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'concentradodetickets.xlsx';
        return Excel::download(new ConcentradoDeTicketsExport($concentrado), $name);
    }

    public function ReportePorTipoDePrecio(Request $request)
    {
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DatEncabezado::leftJoin('DatDetalle as b', 'b.IdEncabezado', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
            ->leftJoin('CatTiendas as f', 'DatEncabezado.IdTienda', 'f.IdTienda')
            ->select(DB::raw("f.NomTienda,
                c.NomListaPrecio,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe,
                count(DatEncabezado.IdEncabezado) as clientes,
                (select count(ta.encabezado) from (
                    select count(DISTINCT en.IdEncabezado) as encabezado from DatEncabezado as en
                    left join DatDetalle as det on en.IdEncabezado = det.IdEncabezado
                    where cast(en.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' and en.IdTienda = f.IdTienda and det.IdListaPrecio = c.IdListaPrecio
                    group by en.IdEncabezado)  as ta ) as tickets"))
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereNotNull('c.NomListaPrecio')
            ->whereRaw("cast(DatEncabezado.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('f.IdTienda', 'f.NomTienda', 'c.IdListaPrecio', 'c.NomListaPrecio')
            ->orderBy('f.NomTienda', 'desc')
            ->get();

        $totales = ['DETALLE' => 0, 'MENUDEO' => 0, 'MINORISTA' => 0, 'EMPYSOC' => 0, 'TOTAL' => 0];
        $clientes = ['DETALLE' => 0, 'MENUDEO' => 0, 'MINORISTA' => 0, 'EMPYSOC' => 0, 'TOTAL' => 0];
        foreach ($concentrado as $item) {
            if ($item->NomListaPrecio == 'DETALLE') {
                $totales['DETALLE'] += $item->importe;
                $clientes['DETALLE'] += $item->tickets;
            }
            if ($item->NomListaPrecio == 'MENUDEO') {
                $totales['MENUDEO'] += $item->importe;
                $clientes['MENUDEO'] += $item->tickets;
            }
            if ($item->NomListaPrecio == 'MINORISTA') {
                $totales['MINORISTA'] += $item->importe;
                $clientes['MINORISTA'] += $item->tickets;
            }
            if ($item->NomListaPrecio == 'EMPYSOC') {
                $totales['EMPYSOC'] += $item->importe;
                $clientes['EMPYSOC'] += $item->tickets;
            }
            $totales['TOTAL'] += $item->importe;
            $clientes['TOTAL'] += $item->tickets;
        }

        return view('Reportes.PorTipoDePrecio', compact('fecha1', 'fecha2', 'concentrado', 'totales', 'clientes'));
    }

    public function ExportReportePorTipoDePrecio(Request $request)
    {
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DatEncabezado::leftJoin('DatDetalle as b', 'b.IdEncabezado', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
            ->leftJoin('CatTiendas as f', 'DatEncabezado.IdTienda', 'f.IdTienda')
            ->select(DB::raw("f.NomTienda,
                c.NomListaPrecio,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe,
                count(DatEncabezado.IdEncabezado) as clientes,
                (select count(ta.encabezado) from (
                    select count(DISTINCT en.IdEncabezado) as encabezado from DatEncabezado as en
                    left join DatDetalle as det on en.IdEncabezado = det.IdEncabezado
                    where cast(en.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' and en.IdTienda = f.IdTienda and det.IdListaPrecio = c.IdListaPrecio
                    group by en.IdEncabezado)  as ta ) as tickets"))
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereNotNull('c.NomListaPrecio')
            ->whereRaw("cast(DatEncabezado.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('f.IdTienda', 'f.NomTienda', 'c.IdListaPrecio', 'c.NomListaPrecio')
            ->orderBy('f.NomTienda', 'desc')
            ->get();

        $totales = ['DETALLE' => 0, 'MENUDEO' => 0, 'MINORISTA' => 0, 'EMPYSOC' => 0, 'TOTAL' => 0];
        $clientes = ['DETALLE' => 0, 'MENUDEO' => 0, 'MINORISTA' => 0, 'EMPYSOC' => 0, 'TOTAL' => 0];
        foreach ($concentrado as $item) {
            if ($item->NomListaPrecio == 'DETALLE') {
                $totales['DETALLE'] += $item->importe;
                $clientes['DETALLE'] += $item->tickets;
            }
            if ($item->NomListaPrecio == 'MENUDEO') {
                $totales['MENUDEO'] += $item->importe;
                $clientes['MENUDEO'] += $item->tickets;
            }
            if ($item->NomListaPrecio == 'MINORISTA') {
                $totales['MINORISTA'] += $item->importe;
                $clientes['MINORISTA'] += $item->tickets;
            }
            if ($item->NomListaPrecio == 'EMPYSOC') {
                $totales['EMPYSOC'] += $item->importe;
                $clientes['EMPYSOC'] += $item->tickets;
            }
            $totales['TOTAL'] += $item->importe;
            $clientes['TOTAL'] += $item->tickets;
        }

        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'ventasportipodeprecio.xlsx';
        return Excel::download(new VentasPorTipoDePrecioExport($concentrado, $totales, $clientes), $name);
    }

    public function ReporteConcentradoPorCiudadYFamilia(Request $request)
    {
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DatEncabezado::leftJoin('DatDetalle as b', 'b.IdEncabezado', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatGrupos as d', 'd.IdGrupo', 'c.IdGrupo')
            ->leftJoin('CatTiendas as f', 'DatEncabezado.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw("g.NomCiudad,
                d.NomGrupo,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe"))
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereNotNull('d.NomGrupo')
            ->whereRaw("cast(DatEncabezado.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('g.NomCiudad', 'd.NomGrupo')
            ->orderBy('g.NomCiudad', 'desc')
            ->get();

        $totales = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        $kilos = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        foreach ($concentrado as $item) {
            if ($item->NomGrupo == 'PRIMARIOS') {
                $totales['PRIMARIOS'] += $item->importe;
                $kilos['PRIMARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'SECUNDARIOS') {
                $totales['SECUNDARIOS'] += $item->importe;
                $kilos['SECUNDARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'TERCEROS') {
                $totales['TERCEROS'] += $item->importe;
                $kilos['TERCEROS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'PROCESADOS') {
                $totales['PROCESADOS'] += $item->importe;
                $kilos['PROCESADOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'VARIOS') {
                $totales['VARIOS'] += $item->importe;
                $kilos['VARIOS'] += $item->kilos;
            }
            $totales['TOTAL'] += $item->importe;
            $kilos['TOTAL'] += $item->kilos;
        }

        return view('Reportes.ConcentradoPorCiudadYFamilia', compact('fecha1', 'fecha2', 'concentrado', 'totales', 'kilos'));
    }

    public function ExportReporteConcentradoPorCiudadYFamilia(Request $request)
    {
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DatEncabezado::leftJoin('DatDetalle as b', 'b.IdEncabezado', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatGrupos as d', 'd.IdGrupo', 'c.IdGrupo')
            ->leftJoin('CatTiendas as f', 'DatEncabezado.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw("g.NomCiudad,
                d.NomGrupo,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe"))
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereNotNull('d.NomGrupo')
            ->whereRaw("cast(DatEncabezado.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('g.NomCiudad', 'd.NomGrupo')
            ->orderBy('g.NomCiudad', 'desc')
            ->get();

        $totales = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        $kilos = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        foreach ($concentrado as $item) {
            if ($item->NomGrupo == 'PRIMARIOS') {
                $totales['PRIMARIOS'] += $item->importe;
                $kilos['PRIMARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'SECUNDARIOS') {
                $totales['SECUNDARIOS'] += $item->importe;
                $kilos['SECUNDARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'TERCEROS') {
                $totales['TERCEROS'] += $item->importe;
                $kilos['TERCEROS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'PROCESADOS') {
                $totales['PROCESADOS'] += $item->importe;
                $kilos['PROCESADOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'VARIOS') {
                $totales['VARIOS'] += $item->importe;
                $kilos['VARIOS'] += $item->kilos;
            }
            $totales['TOTAL'] += $item->importe;
            $kilos['TOTAL'] += $item->kilos;
        }

        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'concentradoporciudadyfamilia.xlsx';
        return Excel::download(new ConcentradoPorCiudadYFamilia($concentrado, $totales, $kilos), $name);
    }

    public function ReporteConcentradoPorTiendaYFamilia(Request $request)
    {
        $idTienda = $request->idTienda;
        $optionsOnline = $request->optionsOnline ?? 'off';
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $usuarioTienda = Auth::user()->usuarioTienda;

        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)->where('Status', 0)->orderBy('IdTienda')->get();
        } elseif (!empty($usuarioTienda->IdTienda)) {
            $tiendas = Tienda::where('IdTienda', $usuarioTienda->IdTienda)->where('Status', 0)->orderBy('IdTienda')->get();
        } elseif ($usuarioTienda->Todas == 0) {
            $tiendas = Tienda::orderBy('IdTienda')->get();
        } else {
            $tiendas = collect(); // Por si no entra a ningún caso
        }

        $idTiendas = $tiendas->pluck('IdTienda');

        // $concentrado = DatEncabezado::connection($optionsOnline == 'on' ? 'server' : null)
        $concentrado = DB::connection($optionsOnline == 'on' ? 'server' : null)
            ->table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatGrupos as d', 'd.IdGrupo', 'c.IdGrupo')
            ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
            // ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw("f.NomTienda,
                d.NomGrupo,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe"))
            ->where('a.StatusVenta', 0)
            ->whereIn('a.IdTienda', $idTiendas)
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            ->whereNotNull('d.NomGrupo')
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('f.NomTienda', 'd.NomGrupo')
            ->orderBy('f.NomTienda', 'desc')
            ->get();

        $totales = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        $kilos = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        foreach ($concentrado as $item) {
            if ($item->NomGrupo == 'PRIMARIOS') {
                $totales['PRIMARIOS'] += $item->importe;
                $kilos['PRIMARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'SECUNDARIOS') {
                $totales['SECUNDARIOS'] += $item->importe;
                $kilos['SECUNDARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'TERCEROS') {
                $totales['TERCEROS'] += $item->importe;
                $kilos['TERCEROS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'PROCESADOS') {
                $totales['PROCESADOS'] += $item->importe;
                $kilos['PROCESADOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'VARIOS') {
                $totales['VARIOS'] += $item->importe;
                $kilos['VARIOS'] += $item->kilos;
            }
            $totales['TOTAL'] += $item->importe;
            $kilos['TOTAL'] += $item->kilos;
        }

        return view('Reportes.ConcentradoPorTiendaYFamilia', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'optionsOnline', 'concentrado', 'totales', 'kilos'));
    }

    public function ExportReporteConcentradoPorTiendaYFamilia(Request $request)
    {
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DatEncabezado::leftJoin('DatDetalle as b', 'b.IdEncabezado', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatGrupos as d', 'd.IdGrupo', 'c.IdGrupo')
            ->leftJoin('CatTiendas as f', 'DatEncabezado.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw("g.NomCiudad,
                d.NomGrupo,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe"))
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereNotNull('d.NomGrupo')
            ->whereRaw("cast(DatEncabezado.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('g.NomCiudad', 'd.NomGrupo')
            ->orderBy('g.NomCiudad', 'desc')
            ->get();

        $totales = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        $kilos = ['PRIMARIOS' => 0, 'SECUNDARIOS' => 0, 'TERCEROS' => 0, 'PROCESADOS' => 0, 'VARIOS' => 0, 'TOTAL' => 0];
        foreach ($concentrado as $item) {
            if ($item->NomGrupo == 'PRIMARIOS') {
                $totales['PRIMARIOS'] += $item->importe;
                $kilos['PRIMARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'SECUNDARIOS') {
                $totales['SECUNDARIOS'] += $item->importe;
                $kilos['SECUNDARIOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'TERCEROS') {
                $totales['TERCEROS'] += $item->importe;
                $kilos['TERCEROS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'PROCESADOS') {
                $totales['PROCESADOS'] += $item->importe;
                $kilos['PROCESADOS'] += $item->kilos;
            }
            if ($item->NomGrupo == 'VARIOS') {
                $totales['VARIOS'] += $item->importe;
                $kilos['VARIOS'] += $item->kilos;
            }
            $totales['TOTAL'] += $item->importe;
            $kilos['TOTAL'] += $item->kilos;
        }

        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'concentradoporciudadyfamilia.xlsx';
        return Excel::download(new ConcentradoPorCiudadYFamilia($concentrado, $totales, $kilos), $name);
    }

    public function ReporteGrupoYTipoPrecio(Request $request)
    {
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DatEncabezado::leftJoin('DatDetalle as b', 'b.IdEncabezado', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
            ->leftJoin('CatTiendas as f', 'DatEncabezado.IdTienda', 'f.IdTienda')
            ->leftJoin('CatArticulos as g', 'g.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatGrupos as h', 'h.IdGrupo', 'g.IdGrupo')
            ->select(DB::raw("f.NomTienda,
                h.NomGrupo,
                c.NomListaPrecio,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe"))
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereNotNull('c.NomListaPrecio')
            ->whereRaw("cast(DatEncabezado.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('f.NomTienda', 'h.NomGrupo', 'c.NomListaPrecio')
            ->orderBy('f.NomTienda', 'desc')
            ->get();

        return view('Reportes.ReporteGrupoYTipoPrecio', compact('fecha1', 'fecha2', 'concentrado'));
    }

    public function ExportReporteGrupoYTipoPrecio(Request $request)
    {
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DatEncabezado::leftJoin('DatDetalle as b', 'b.IdEncabezado', 'DatEncabezado.IdEncabezado')
            ->leftJoin('CatListasPrecio as c', 'c.IdListaPrecio', 'b.IdListaPrecio')
            ->leftJoin('CatTiendas as f', 'DatEncabezado.IdTienda', 'f.IdTienda')
            ->leftJoin('CatArticulos as g', 'g.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatGrupos as h', 'h.IdGrupo', 'g.IdGrupo')
            ->select(DB::raw("f.NomTienda,
                h.NomGrupo,
                c.NomListaPrecio,
                SUM(b.CantArticulo) as kilos,
                SUM(b.ImporteArticulo) as importe"))
            ->where('DatEncabezado.StatusVenta', 0)
            ->whereNotNull('c.NomListaPrecio')
            ->whereRaw("cast(DatEncabezado.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('f.NomTienda', 'h.NomGrupo', 'c.NomListaPrecio')
            ->orderBy('f.NomTienda', 'desc')
            ->get();

        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'concentradoporgrupoytipodeprecio.xlsx';
        return Excel::download(new GrupoYTipoPrecio($concentrado), $name);
    }

    public function ReporteMermasAdmin(Request $request)
    {
        $txtFiltro = $request->txtFiltro;
        $idTienda = $request->idTienda;
        $fecha1 =  $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $usuarioTienda = Auth::user()->usuarioTienda;

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

        $concentrado = CapMerma::select(
            'CapMermas.FolioMerma',
            'CapMermas.CodArticulo',
            'ca.NomArticulo',
            'CapMermas.FechaCaptura',
            'tm.NomTipoMerma',
            'CapMermas.CantArticulo',
            'CapMermas.FechaInterfaz',
            'CapMermas.Comentario',
            'CapMermas.IdTienda',
            'ct.NomTienda'
        )
            ->leftjoin('CatArticulos as ca', 'ca.CodArticulo', 'CapMermas.CodArticulo')
            ->leftjoin('CatTiposMerma as tm', 'tm.IdTipoMerma', 'CapMermas.IdTipoMerma')
            ->leftjoin('CatTiendas as ct', 'ct.IdTienda', 'CapMermas.IdTienda')
            ->where('ca.status', 0)
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('CapMermas.IdTienda', $idTienda);
            })
            // ->whereDate('CapMermas.FechaCaptura', '>=', $fecha1)
            // ->whereDate('CapMermas.FechaCaptura', '<=', $fecha2)
            ->when($fecha1, function ($query) use ($fecha1) {
                $query->whereDate('CapMermas.FechaCaptura', '>=', $fecha1);
            })
            ->when($fecha2, function ($query) use ($fecha2) {
                $query->whereDate('CapMermas.FechaCaptura', '<=', $fecha2);
            })
            ->when($txtFiltro, function ($query) use ($txtFiltro) {
                $query->where(function ($q) use ($txtFiltro) {
                    $q->where('ca.CodArticulo', 'like', '%' . $txtFiltro . '%')
                        ->orWhere('ca.NomArticulo', 'like', '%' . $txtFiltro . '%');
                });
            })
            ->orderBy('CapMermas.FechaCaptura', 'desc')
            ->paginate(10);

        return view('Reportes.ConcentradoDeMermas', compact('tiendas', 'idTienda', 'txtFiltro', 'fecha1', 'fecha2', 'concentrado'));
    }

    public function ReporteMermasAdminExcel(Request $request)
    {
        try {
            DB::beginTransaction();
            $txtFiltro = $request->txtFiltro;
            $idTienda = $request->idTienda;
            $fecha1 =  $request->fecha1;
            $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

            $tienda = Tienda::where('Status', 0)
                ->where('idtienda', $idTienda)
                ->value('NomTienda');

            $concentrado = CapMerma::select(
                'CapMermas.FolioMerma',
                'CapMermas.CodArticulo',
                'ca.NomArticulo',
                'CapMermas.FechaCaptura',
                'tm.NomTipoMerma',
                'CapMermas.CantArticulo',
                'CapMermas.FechaInterfaz',
                'CapMermas.Comentario',
                'CapMermas.IdTienda',
                'ct.NomTienda'
            )
                ->leftjoin('CatArticulos as ca', 'ca.CodArticulo', 'CapMermas.CodArticulo')
                ->leftjoin('CatTiposMerma as tm', 'tm.IdTipoMerma', 'CapMermas.IdTipoMerma')
                ->leftjoin('CatTiendas as ct', 'ct.IdTienda', 'CapMermas.IdTienda')
                ->where('ca.status', 0)
                ->when($idTienda, function ($query) use ($idTienda) {
                    $query->where('CapMermas.IdTienda', $idTienda);
                })
                // ->whereRaw("cast(CapMermas.FechaCaptura as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->when($fecha1, function ($query) use ($fecha1) {
                    $query->whereDate('CapMermas.FechaCaptura', '>=', $fecha1);
                })
                ->when($fecha2, function ($query) use ($fecha2) {
                    $query->whereDate('CapMermas.FechaCaptura', '<=', $fecha2);
                })
                ->when($txtFiltro, function ($query) use ($txtFiltro) {
                    $query->where(function ($q) use ($txtFiltro) {
                        $q->where('ca.CodArticulo', 'like', '%' . $txtFiltro . '%')
                            ->orWhere('ca.NomArticulo', 'like', '%' . $txtFiltro . '%');
                    });
                })
                ->orderBy('CapMermas.FechaCaptura', 'desc')
                ->get();

            $data = [
                'tienda' => $tienda,
                'fecha1' => $fecha1,
                'fecha2' => $fecha2,
                'concentrado' => $concentrado
            ];

            DB::commit();

            $name = 'MERMAS--' . Carbon::now()->parse(date(now()))->format('Y--m--d') . '.xlsx';

            return Excel::download(new Mermas($data), $name);
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function ReporteRosticeroAdmin(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        $usuarioTienda = Auth::user()->usuarioTienda;

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

        $concentrado = DatRosticero::with(['Detalle' => function ($q) {
            $q->where('DatDetalleRosticero.Status', 0)
                ->whereNull('CantMermaRecalentado')
                ->select('Cantidad', 'IdRosticero'); // solo traer Cantidad y foránea
        }])

            ->select(
                'DatRosticero.*',
                'CAMP.NomArticulo as ArticuloMatPrima',
                'CAV.NomArticulo as ArticuloVenta',
                'ct.NomTienda'
            )
            ->leftjoin('CatTiendas as ct', 'ct.IdTienda', 'DatRosticero.IdTienda')
            ->leftjoin('CatArticulos as CAMP', 'CAMP.CodArticulo', 'DatRosticero.CodigoMatPrima')
            ->leftjoin('CatArticulos as CAV', 'CAV.CodArticulo', 'DatRosticero.CodigoVenta')
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('DatRosticero.IdTienda', $idTienda);
            })
            ->where('DatRosticero.Status', 0)
            ->whereRaw("cast(DatRosticero.Fecha as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->orderBy('DatRosticero.Fecha', 'desc')
            ->paginate(10);

        // $concentrado = CapMerma::select(
        //     'CapMermas.FolioMerma',
        //     'CapMermas.CodArticulo',
        //     'ca.NomArticulo',
        //     'CapMermas.FechaCaptura',
        //     'tm.NomTipoMerma',
        //     'CapMermas.CantArticulo',
        //     'CapMermas.FechaInterfaz',
        //     'CapMermas.Comentario',
        //     'CapMermas.IdTienda',
        //     'ct.NomTienda'
        // )
        //     ->leftjoin('CatArticulos as ca', 'ca.CodArticulo', 'CapMermas.CodArticulo')
        //     ->leftjoin('CatTiposMerma as tm', 'tm.IdTipoMerma', 'CapMermas.IdTipoMerma')
        //     ->leftjoin('CatTiendas as ct', 'ct.IdTienda', 'CapMermas.IdTienda')
        //     ->when($idTienda, function ($query) use ($idTienda) {
        //         $query->where('CapMermas.IdTienda', $idTienda);
        //     })
        //     ->whereRaw("cast(CapMermas.FechaCaptura as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
        //     ->orderBy('CapMermas.FechaCaptura', 'desc')
        //     ->paginate(10);

        return view('Reportes.ConcentradoDeRostisados', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'concentrado'));
    }

    public function ReporteDineroElectronido(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $pFecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('d/m/Y') : Carbon::parse($request->fecha1)->format('d/m/Y');
        $pFecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('d/m/Y') : Carbon::parse($request->fecha2)->format('d/m/Y');

        $usuarioTienda = Auth::user()->usuarioTienda;

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

        $concentrado = collect(DB::select("SELECT NomTienda,
                CONVERT(varchar(12), Fecha, 103) as Fecha,
                sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS SEMANALES' THEN Monedero ELSE 0 END) as semanal_creadito,
                sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS QUINCENALES' THEN Monedero ELSE 0 END) as quincenal_creadito,
                sum(case WHEN cliente = 'CONTADO PUBLICO GENERAL EMPLEADOS Y SOCIOS' OR cliente IS NULL THEN Monedero ELSE 0 END) as contado
            from (
                select
                    t.NomTienda,
                    NomClienteCloud cliente,
                    dm.IdEncabezado,
                    abs(isnull(dm.Monedero,0)) monedero,
                    cast(dm.FechaGenerado as date) as Fecha
                from DatMonederoElectronico dm
                left join (SELECT * FROM  DBO.FN_BIL_MON_CORTE ('$idTienda','$pFecha1','$pFecha2')) dt on dm.IdEncabezado = dt.IdEncabezado
                left join DatClientesCloudTienda dc on dc.Bill_To = dt.Bill_To and dc.IdListaPrecio = dt.IdListaPrecio and dc.IdTipoPago = dt.IdTipoPago and dt.IdTienda = dc.IdTienda
                left join CatClientesCloud cc on cc.IdClienteCloud = dc.IdClienteCloud
                left join CatTiendas as t on dt.IdTienda = t.IdTienda
                where
                    cast(dm.FechaGenerado as date) >='$pFecha1'
                    and cast(dm.FechaGenerado as date) < '$pFecha2'
                    and dm.IdTienda = '$idTienda'
                    and dm.BatchGasto is not null
                    and dt.IdTipoPago = 7
                    and dt.StatusVenta=0
                group by t.NomTienda, NomClienteCloud, cast(dm.FechaGenerado as date),dm.IdEncabezado,dm.Monedero
            ) as a group by NomTienda, Fecha
            order by NomTienda, Fecha"));

        // $concentrado = collect(DB::select("SELECT NomTienda,
        //         CONVERT(varchar(12), Fecha, 103) as Fecha,
        //         sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS SEMANALES' THEN Monedero ELSE 0 END) as semanal_creadito,
        //         sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS QUINCENALES' THEN Monedero ELSE 0 END) as quincenal_creadito,
        //         sum(case WHEN cliente = 'CONTADO PUBLICO GENERAL EMPLEADOS Y SOCIOS' THEN Monedero ELSE 0 END) as contado
        //     from (
        //         select
        //             dm.IdDatMonedero,
        //             t.NomTienda,
        //             NomClienteCloud cliente,
        //             dm.IdEncabezado,
        //             sum(abs(isnull(dm.Monedero,0))) monedero,
        //             cast(dm.FechaGenerado as date) as Fecha
        //         from DatMonederoElectronico dm
        //         left join DatCortesTienda dt on dm.IdEncabezado = dt.IdEncabezado
        //         left join DatClientesCloudTienda dc on dc.Bill_To = dt.Bill_To and dc.IdListaPrecio = dt.IdListaPrecio and dc.IdTipoPago = dt.IdTipoPago and dt.IdTienda = dc.IdTienda
        //         left join CatClientesCloud cc on cc.IdClienteCloud = dc.IdClienteCloud
        //         left join CatTiendas as t on dt.IdTienda = t.IdTienda
        //         where
        //             cast(dm.FechaGenerado as date) >='$fecha1' and
        //             cast(dm.FechaGenerado as date) <'$fecha2' and
        //             dm.IdTienda = '$idTienda' and
        //             dm.BatchGasto is not null and
        //             dt.IdTipoPago = 7
        //         group by dm.IdDatMonedero, t.NomTienda, NomClienteCloud, cast(dm.FechaGenerado as date),dm.IdEncabezado,dm.Monedero
        //     ) as a group by NomTienda, Fecha
        //     order by NomTienda, Fecha"));
        // $concentrado = collect(DB::select("SELECT NomTienda,
        //         CONVERT(varchar(12), Fecha, 103) as Fecha,
        //         sum(case WHEN Tipo = 3 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as semanal_creadito,
        //         sum(case WHEN Tipo = 3 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as semanal_contado,
        //         sum(case WHEN Tipo = 4 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as quincenal_creadito,
        //         sum(case WHEN Tipo = 4 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as quincenal_contado,
        //         sum(case WHEN Tipo = 1 THEN Monedero ELSE 0 END) as contado
        //     from (
        //         select t.NomTienda,
        //             cast(FechaVenta as date) as Fecha,
        //             dt.IdTipoPago,
        //             sum(abs(isnull(dm.Monedero,0))) as Monedero,
        //             case WHEN ce.TipoNomina is not null THEN ce.TipoNomina ELSE cf.IdTipoCliente END as Tipo
        //         from DatMonederoElectronico as dm
        //             left join DatEncabezado as de on de.IdEncabezado = dm.IdEncabezado
        //             left join DatTipoPago as dt on dm.IdEncabezado = dt.IdEncabezado
        //             left join CatTiendas as t on dm.IdTienda = t.IdTienda
        //             left join CatEmpleados as ce on ce.NumNomina = dm.NumNomina
        //             left join CatFrecuentesSocios as cf on cf.FolioViejo = dm.NumNomina
        //         where cast(FechaVenta as date) >='$fecha1' and
        //             cast(FechaVenta as date) <'$fecha2' and
        //             dm.IdTienda = '$idTienda' and
        //             dt.IdTipoPago in (1, 7) and
        //             dm.Monedero < 0
        //         group by t.NomTienda, cast(FechaVenta as date), dt.IdTipoPago, ce.TipoNomina, cf.IdTipoCliente, dm.Monedero
        //     ) as a group by NomTienda, Fecha
        //     order by NomTienda, Fecha"));

        return view('Reportes.DineroElectronico', compact('fecha1', 'fecha2', 'idTienda', 'tiendas', 'concentrado'));
    }

    public function ExportReporteDineroElectronido(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('d/m/Y') : Carbon::parse($request->fecha1)->format('d/m/Y');
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('d/m/Y') : Carbon::parse($request->fecha2)->format('d/m/Y');

        $concentrado = collect(DB::select("SELECT NomTienda,
                CONVERT(varchar(12), Fecha, 103) as Fecha,
                sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS SEMANALES' THEN Monedero ELSE 0 END) as semanal_creadito,
                sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS QUINCENALES' THEN Monedero ELSE 0 END) as quincenal_creadito,
                sum(case WHEN cliente = 'CONTADO PUBLICO GENERAL EMPLEADOS Y SOCIOS' OR cliente IS NULL THEN Monedero ELSE 0 END) as contado
            from (
                select
                    t.NomTienda,
                    NomClienteCloud cliente,
                    dm.IdEncabezado,
                    abs(isnull(dm.Monedero,0)) monedero,
                    cast(dm.FechaGenerado as date) as Fecha
                from DatMonederoElectronico dm
                left join (SELECT * FROM  DBO.FN_BIL_MON_CORTE ('$idTienda','$fecha1','$fecha2')) dt on dm.IdEncabezado = dt.IdEncabezado
                left join DatClientesCloudTienda dc on dc.Bill_To = dt.Bill_To and dc.IdListaPrecio = dt.IdListaPrecio and dc.IdTipoPago = dt.IdTipoPago and dt.IdTienda = dc.IdTienda
                left join CatClientesCloud cc on cc.IdClienteCloud = dc.IdClienteCloud
                left join CatTiendas as t on dt.IdTienda = t.IdTienda
                where
                    cast(dm.FechaGenerado as date) >='$fecha1'
                    and cast(dm.FechaGenerado as date) < '$fecha2'
                    and dm.IdTienda = '$idTienda'
                    and dm.BatchGasto is not null
                    and dt.IdTipoPago = 7
                    and dt.StatusVenta=0
                group by t.NomTienda, NomClienteCloud, cast(dm.FechaGenerado as date),dm.IdEncabezado,dm.Monedero
            ) as a group by NomTienda, Fecha
            order by NomTienda, Fecha"));
        // $concentrado = collect(DB::select("SELECT NomTienda,
        //         CONVERT(varchar(12), Fecha, 103) as Fecha,
        //         sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS SEMANALES' THEN Monedero ELSE 0 END) as semanal_creadito,
        //         sum(0) as semanal_contado,
        //         sum(case WHEN cliente = 'CREDITO EMPLEADOS Y SOCIOS QUINCENALES' THEN Monedero ELSE 0 END) as quincenal_creadito,
        //         sum(0) as quincenal_contado,
        //         sum(case WHEN cliente = 'CONTADO PUBLICO GENERAL EMPLEADOS Y SOCIOS' THEN Monedero ELSE 0 END) as contado
        //     from (
        //         select
        //             t.NomTienda,
        //             NomClienteCloud cliente,
        //             sum(dt.ImporteArticulo) monedero,
        //             cast(FechaVenta as date) as Fecha
        //         from DatCortesTienda dt
        //         left join DatClientesCloudTienda dc on dc.Bill_To = dt.Bill_To and dc.IdListaPrecio = dt.IdListaPrecio and dc.IdTipoPago = dt.IdTipoPago and dt.IdTienda = dc.IdTienda
        //         left join CatClientesCloud cc on cc.IdClienteCloud = dc.IdClienteCloud
        //         left join CatTiendas as t on dt.IdTienda = t.IdTienda
        //         where
        //             IdEncabezado in (
        //             select IdEncabezado from DatMonederoElectronico where
        //             cast(FechaGenerado as date) >='$fecha1' and
        //             cast(FechaGenerado as date) <'$fecha2' and
        //             IdTienda = '$idTienda' and
        //             BatchGasto is not null
        //             ) and
        //             dt.IdTipoPago = 7
        //         group by t.NomTienda, NomClienteCloud, cast(FechaVenta as date)
        //     ) as a group by NomTienda, Fecha
        //     order by NomTienda, Fecha"));
        // $concentrado = collect(DB::select("SELECT NomTienda,
        //         CONVERT(varchar(12), Fecha, 103) as Fecha,
        //         sum(case WHEN Tipo = 3 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as semanal_creadito,
        //         sum(case WHEN Tipo = 3 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as semanal_contado,
        //         sum(case WHEN Tipo = 4 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as quincenal_creadito,
        //         sum(case WHEN Tipo = 4 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as quincenal_contado,
        //         sum(case WHEN Tipo = 1 THEN Monedero ELSE 0 END) as contado
        //     from (
        //         select t.NomTienda,
        //             cast(FechaVenta as date) as Fecha,
        //             dt.IdTipoPago,
        //             abs(isnull(dm.Monedero,0)) as Monedero,
        //             case WHEN ce.TipoNomina is not null THEN ce.TipoNomina ELSE cf.IdTipoCliente END as Tipo
        //         from DatMonederoElectronico as dm
        //             left join DatEncabezado as de on de.IdEncabezado = dm.IdEncabezado
        //             left join DatTipoPago as dt on dm.IdEncabezado = dt.IdEncabezado
        //             left join CatTiendas as t on dm.IdTienda = t.IdTienda
        //             left join CatEmpleados as ce on ce.NumNomina = dm.NumNomina
        //             left join CatFrecuentesSocios as cf on cf.FolioViejo = dm.NumNomina
        //         where cast(FechaVenta as date) >='$fecha1' and
        //             cast(FechaVenta as date) <'$fecha2' and
        //             dm.IdTienda = '$idTienda' and
        //             dt.IdTipoPago in (1, 7) and
        //             dm.Monedero < 0
        //         group by t.NomTienda, cast(FechaVenta as date), dt.IdTipoPago, ce.TipoNomina, cf.IdTipoCliente, dm.Monedero
        //     ) as a group by NomTienda, Fecha
        //     order by NomTienda, Fecha"));

        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'dineroelectronico.xlsx';
        return Excel::download(new DineroElectronicoExport($concentrado), $name);
    }

    function ReportePedidosOracle(Request $request)
    {
        $txtFiltro = substr_replace($request->txtFiltro, '', 3, 1);
        // $pos = 'POS482305';
        $concentrado = DB::table('DatCortesTienda as a')
            ->leftJoin('DatEncabezado as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatTiendas as c', 'a.IdTienda', 'c.IdTienda')
            ->leftJoin('SERVER.CLOUD_INTERFACE.dbo.XXKW_HEADERS_IVENTAS as d', 'd.Source_Transaction_Identifier', 'a.Source_Transaction_Identifier')
            ->select(DB::raw('a.Source_Transaction_Identifier, b.IdTicket, a.FechaVenta, c.NomTienda, d.MENSAJE_ERROR'))
            ->where('a.Source_Transaction_Identifier', $txtFiltro)
            ->groupBy('a.Source_Transaction_Identifier', 'b.IdTicket', 'a.FechaVenta', 'c.NomTienda', 'd.MENSAJE_ERROR')
            ->get();

        $txtFiltro = $txtFiltro ? substr_replace($txtFiltro, '_', 3, 0) : $txtFiltro;

        return view('Reportes.PedidosOracle', compact('txtFiltro', 'concentrado'));
    }

    public function ReporteInformacionVentas(Request $request)
    {
        // return $request;
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1 ?? Carbon::now()->format('Y-m-d');
        $fecha2 = $request->fecha2 ?? Carbon::now()->format('Y-m-d');
        $agrupar = $request->agrupar;

        $usuarioTienda = Auth::user()->usuarioTienda;
        $tiendasQuery = Tienda::where('Status', 0)->orderBy('IdTienda');

        if (!empty($usuarioTienda->IdTienda)) {
            $tiendasQuery->where('IdTienda', $usuarioTienda->IdTienda);
        }

        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendasQuery->where('IdPlaza', $usuarioTienda->IdPlaza);
        }

        $tiendas = $tiendasQuery->get();
        $idsTiendas = $tiendas->pluck('IdTienda')->toArray();

        if ($agrupar != 'on') {
            $concentrado = DB::table('DatEncabezado as a')
                ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
                ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
                ->select(DB::raw('g.NomCiudad, f.NomTienda,  SUM(b.ImporteArticulo) as Importe, SUM(b.CantArticulo) as cantidad, cast(a.FechaVenta as date) as Fecha, count(DISTINCT a.IdTicket) as Tickets'))
                ->whereIn('a.IdTienda', $idsTiendas)
                ->when($idTienda, function ($query) use ($idTienda) {
                    $query->where('a.IdTienda', $idTienda);
                })
                ->where('a.StatusVenta', 0)
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->groupBy('g.NomCiudad', 'f.IdTienda', 'f.NomTienda', DB::raw('cast(a.FechaVenta as date)'))
                ->orderBy('f.IdTienda')
                ->orderBy('Fecha')
                ->get();
        } else {
            $concentrado = DB::table('DatEncabezado as a')
                ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
                ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
                ->select(DB::raw('SUM(b.ImporteArticulo) as Importe, SUM(b.CantArticulo) as cantidad, cast(a.FechaVenta as date) as Fecha, count(DISTINCT a.IdTicket) as Tickets'))
                ->whereIn('a.IdTienda', $idsTiendas)
                ->when($idTienda, function ($query) use ($idTienda) {
                    $query->where('a.IdTienda', $idTienda);
                })
                ->where('a.StatusVenta', 0)
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->groupBy(DB::raw('cast(a.FechaVenta as date)'))
                ->orderBy('Fecha')
                ->get();
        }

        return view('Reportes.InformacionVentas', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'agrupar', 'concentrado'));
    }
}
