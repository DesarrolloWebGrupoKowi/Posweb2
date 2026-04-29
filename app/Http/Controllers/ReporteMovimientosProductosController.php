<?php

namespace App\Http\Controllers;

use App\Exports\MovimientosDeArticulos;
use Illuminate\Http\Request;
use App\Services\TiendaService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteMovimientosProductosController extends Controller
{
    protected $tiendaService;
    protected $tiendasIds;
    protected $tiendas;
    protected $movimientosProducto;

    public function __construct(TiendaService $tiendaService)
    {
        $this->tiendaService = $tiendaService;

        $this->middleware(function ($request, $next) {
            $this->tiendas = $this->tiendaService->obtenerTiendasOpcional();
            $this->tiendasIds = $this->tiendaService->obtenerTiendasIds();
            return $next($request);
        });

        $this->movimientosProducto = DB::table('CatMovimientosProducto')
            ->select('IdMovimiento', 'NomMovimiento')
            ->orderBy('NomMovimiento')
            ->get();
    }

    private function query(Request $request, $exports = false)
    {
        // Obtener todos los parámetros del filtro
        $idTienda = $request->idTienda;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $fecha = $request->fecha; // Fecha única
        $codArticulo = $request->cod_articulo;
        $nomArticulo = $request->nom_articulo;
        $idMovimiento = $request->id_movimiento;
        $usuario = $request->usuario;
        $numNomina = $request->num_nomina;
        $referencia = $request->referencia;
        $idCaja = $request->id_caja;

        // Construir la consulta
        $query = DB::table('DatHistorialMovimientos as dh')
            ->leftJoin('CatTiendas as ct', 'ct.IdTienda', '=', 'dh.IdTienda')
            ->leftJoin('CatArticulos as ca', function ($join) {
                $join->on('ca.CodArticulo', '=', 'dh.CodArticulo')
                    ->where('ca.Status', '=', 0);
            })
            ->leftJoin('CatMovimientosProducto as cm', 'cm.IdMovimiento', '=', 'dh.IdMovimiento')
            ->leftJoin('CatUsuarios as cu', 'cu.IdUsuario', '=', 'dh.IdUsuario')
            ->leftJoin('CatEmpleados as ce', 'ce.NumNomina', '=', 'cu.NumNomina')
            ->select(
                'dh.IdDatHistorialMovimientos',
                'dh.IdTienda',
                'ct.NomTienda',
                'dh.CodArticulo',
                'ca.NomArticulo',
                'dh.CantArticulo',
                'ca.UOM',
                'dh.FechaMovimiento',
                'dh.Referencia',
                'dh.IdMovimiento',
                'cm.NomMovimiento',
                'dh.IdUsuario',
                'cu.NomUsuario',
                'ce.NumNomina',
                DB::raw("CONCAT(ce.Nombre, ' ', ce.Apellidos) as NombreEmpleado"),
                'dh.ReferenciaId',
                'dh.IDCAJA'
            )

            // Filtro por tienda
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('dh.IdTienda', $idTienda);
            })

            // Filtro por fecha única
            ->when($fecha, function ($query) use ($fecha) {
                $query->whereDate('dh.FechaMovimiento', $fecha);
            })

            // Filtro por rango de fechas
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween(DB::raw('cast(dh.FechaMovimiento as date)'), [$fechaInicio, $fechaFin]);
            })

            // Si solo viene fecha inicio
            ->when($fechaInicio && !$fechaFin && !$fecha, function ($query) use ($fechaInicio) {
                $query->whereDate('dh.FechaMovimiento', '>=', $fechaInicio);
            })

            // Si solo viene fecha fin
            ->when($fechaFin && !$fechaInicio && !$fecha, function ($query) use ($fechaFin) {
                $query->whereDate('dh.FechaMovimiento', '<=', $fechaFin);
            })

            // Filtro por código de artículo
            ->when($codArticulo, function ($query) use ($codArticulo) {
                $query->where('dh.CodArticulo', 'LIKE', "%{$codArticulo}%");
            })

            // Filtro por nombre de artículo
            ->when($nomArticulo, function ($query) use ($nomArticulo) {
                $query->where('ca.NomArticulo', 'LIKE', "%{$nomArticulo}%");
            })

            // Filtro por tipo de movimiento
            ->when($idMovimiento, function ($query) use ($idMovimiento) {
                $query->where('dh.IdMovimiento', $idMovimiento);
            })

            // Filtro por usuario
            ->when($usuario, function ($query) use ($usuario) {
                $query->where('cu.NomUsuario', 'like', '%' . $usuario . '%');
            })

            // Filtro por número de nómina
            ->when($numNomina, function ($query) use ($numNomina) {
                $query->where('ce.NumNomina', $numNomina);
            })

            // Filtro por referencia
            ->when($referencia, function ($query) use ($referencia) {
                $query->where('dh.Referencia', 'LIKE', "%{$referencia}%");
            })

            // Filtro por ID de caja
            ->when($idCaja, function ($query) use ($idCaja) {
                $query->where('dh.IDCAJA', $idCaja);
            })

            // Si no hay filtros, forzar resultado vacío (opcional)
            ->when(!$idTienda && !$fecha && !$fechaInicio && !$fechaFin && !$codArticulo && !$nomArticulo && !$idMovimiento && !$usuario && !$numNomina && !$referencia && !$idCaja, function ($query) {
                $query->whereRaw('1 = 0');
            })

            ->orderBy('dh.FechaMovimiento', 'DESC')
            ->orderBy('dh.IdDatHistorialMovimientos', 'DESC');

        if ($exports)
            return $query;
        else
            return $query->paginate(10)
                ->appends(request()->query());
    }

    public function index(Request $request)
    {
        $filtrosAvanzadosActivos =
            $request->filled('cod_articulo') ||
            $request->filled('nom_articulo') ||
            $request->filled('id_movimiento') ||
            $request->filled('usuario') ||
            $request->filled('num_nomina') ||
            $request->filled('referencia') ||
            $request->filled('id_caja');

        $tiendas = $this->tiendas;
        $movimientosProducto = $this->movimientosProducto;

        $data = $this->query($request);

        // Retornar la vista con los datos
        return view('ReportesMovimientos.index', compact(
            'data',
            'tiendas',
            'movimientosProducto',
            'filtrosAvanzadosActivos'
        ));
    }

    public function exports(Request $request)
    {
        $query = $this->query($request, true);

        $name = 'exports.xlsx';
        return Excel::download(new MovimientosDeArticulos($query), $name);
    }
}
