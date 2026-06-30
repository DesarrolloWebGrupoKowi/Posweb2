<?php

namespace App\Http\Controllers;

use App\Exports\VentasAEmpleadoExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\CorteTienda;
use App\Models\LimiteCredito;
use App\Services\TiendaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadosController extends Controller
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

    public function AdeudosEmpleado(Request $request)
    {
        $txtFiltro = $request->input('txtFiltro', '');

        $adeudo = Empleado::with(['Adeudos' => function ($query) {
            $query->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'DatCreditos.IdTienda')
                ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'DatCreditos.IdEncabezado')
                ->where('DatEncabezado.StatusVenta', 0)
                ->where('DatCreditos.StatusCredito', 0)
                ->orderBy('DatCreditos.FechaVenta', 'desc');
        }, 'VentasExternas' => function ($query) {
            $query->whereNull('IdEncabezado');
        }])
            ->where('NumNomina', $txtFiltro)
            ->get();

        // $adeudoTotal = CreditoEmpleado::where('DatCreditos.NumNomina', $txtFiltro)
        //     ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'DatCreditos.IdEncabezado')
        //     ->where('DatEncabezado.StatusVenta', 0)
        //     ->where('DatCreditos.StatusCredito', 0)
        //     ->sum('DatCreditos.ImporteCredito');

        return view('Empleados.AdeudosEmpleado', compact('adeudo', 'txtFiltro'));
    }

    public function CreditosPagados(Request $request)
    {
        $numNomina = $request->numNomina;

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        $creditosPagados = Empleado::with(['Adeudos' => function ($query) use ($fecha1, $fecha2) {
            $query->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'DatCreditos.IdTienda')
                ->where('StatusCredito', 1)
                ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->orderBy('FechaVenta', 'desc');
        }])
            ->where('NumNomina', $numNomina)
            ->get();

        //return $creditosPagados;

        return view('Empleados.CreditosPagados', compact('creditosPagados', 'numNomina', 'fecha1', 'fecha2'));
    }

    public function VentaEmpleados(Request $request)
    {
        $tiendas = $this->tiendas;
        $tiendasIds = $this->tiendasIds;

        // return $request;
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $chkNomina = $request->chkNomina;
        $soloAdeudos = $request->soloAdeudos;
        $numNomina = $request->numNomina;
        $tipoNomina = $request->tipoNomina;
        $codigoInterfaz = $request->codigoInterfaz;
        $filtrosAvanzadosActivos = $request->filled('idTienda') ||
            $request->filled('tipoNomina') ||
            $request->filled('soloAdeudos') ||
            $request->filled('fechaInterfaz') ||
            $request->filled('codigoInterfaz');

        $ventasEmpleado = DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->leftJoin('CatTiendas as c', 'c.IdTienda', 'a.IdTienda')
            ->leftJoin('DatEncabezado as d', 'd.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as e', 'e.IdArticulo', 'a.IdArticulo')
            ->leftJoin('HistorialCreditos as f', 'f.IdHistorialCredito', 'a.Interfazado')
            ->leftJoin('CatTipoPago as g', 'g.IdTipoPago', 'a.IdTipoPago')
            ->select(
                'a.IdEncabezado',
                'a.IdTipoPago',
                'a.NumNomina',
                'a.FechaVenta',
                'c.NomTienda',
                'b.Nombre',
                'b.Apellidos',
                'b.TipoNomina',
                'b.Empresa',
                'd.IdTicket',
                'a.ImporteArticulo',
                'e.NomArticulo',
                'e.CodArticulo',
                'a.StatusCredito',
                'b.Status',
                'f.IdHistorialCredito',
                'f.FechaInterfaz',
                'g.NomTipoPago',
            )
            // Filtro de fecha SOLO si vienen ambas fechas Y NO está activado el filtro de nómina con adeudos
            ->when((!empty($fecha1) && !empty($fecha2)), function ($query) use ($fecha1, $fecha2) {
                $query->whereBetween(DB::raw('cast(a.FechaVenta as date)'), [$fecha1, $fecha2]);
            })
            // Si NO vienen fechas Y NO viene código de interfaz, forzar un resultado vacío
            // PERO excluir cuando está activado nómina con adeudos
            ->when(!$fecha1 && !$fecha2 && !$codigoInterfaz && !($soloAdeudos == 'on'), function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            ->when($numNomina, function ($query) use ($numNomina) {
                $query->where('a.NumNomina', $numNomina);
            })
            // ->when($chkNomina != 'on', function ($query) {
            //     $query->whereNotNull('a.NumNomina');
            // })
            ->when($soloAdeudos == 'on', function ($query) {
                $query->where('a.StatusCredito', 0);
            })
            ->when($tipoNomina, function ($query) use ($tipoNomina) {
                $query->where('b.TipoNomina', $tipoNomina);
            })
            ->when($codigoInterfaz, function ($query) use ($codigoInterfaz) {
                $query->where('f.IdHistorialCredito', $codigoInterfaz);
            })
            ->whereNotNull('a.NumNomina')
            ->whereIn('a.IdTienda', $tiendasIds)
            ->where('d.StatusVenta', 0)
            ->orderBy('a.FechaVenta')
            ->get();
        // ->paginate(1000);

        $importeTotal = CorteTienda::leftJoin('CatEmpleados as b', 'b.NumNomina', 'DatCortesTienda.NumNomina')
            ->leftJoin('HistorialCreditos as f', 'f.IdHistorialCredito', 'DatCortesTienda.Interfazado')
            // ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->when((!empty($fecha1) && !empty($fecha2)), function ($query) use ($fecha1, $fecha2) {
                $query->whereBetween(DB::raw('cast(FechaVenta as date)'), [$fecha1, $fecha2]);
            })
            // Si NO vienen fechas Y NO viene código de interfaz, forzar un resultado vacío
            // PERO excluir cuando está activado nómina con adeudos
            ->when(!$fecha1 && !$fecha2 && !$codigoInterfaz && !($soloAdeudos == 'on'), function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('IdTienda', $idTienda);
            })
            ->when($numNomina, function ($query) use ($numNomina) {
                $query->where('DatCortesTienda.NumNomina', $numNomina);
            })
            // ->when($chkNomina != 'on', function ($query) {
            //     $query->whereNotNull('DatCortesTienda.NumNomina');
            // })
            ->when($soloAdeudos == 'on', function ($query) {
                $query->where('StatusCredito', 0);
            })
            ->when($tipoNomina, function ($query) use ($tipoNomina) {
                $query->where('b.TipoNomina', $tipoNomina);
            })
            ->when($codigoInterfaz, function ($query) use ($codigoInterfaz) {
                $query->where('f.IdHistorialCredito', $codigoInterfaz);
            })
            ->whereNotNull('DatCortesTienda.NumNomina')
            ->whereIn('IdTienda', $tiendasIds)
            ->where('StatusVenta', 0)
            ->sum('ImporteArticulo');

        $importeCredito = CorteTienda::leftJoin('CatEmpleados as b', 'b.NumNomina', 'DatCortesTienda.NumNomina')
            ->leftJoin('HistorialCreditos as f', 'f.IdHistorialCredito', 'DatCortesTienda.Interfazado')
            // ->whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->when((!empty($fecha1) && !empty($fecha2)), function ($query) use ($fecha1, $fecha2) {
                $query->whereBetween(DB::raw('cast(FechaVenta as date)'), [$fecha1, $fecha2]);
            })
            // Si NO vienen fechas Y NO viene código de interfaz, forzar un resultado vacío
            // PERO excluir cuando está activado nómina con adeudos
            ->when(!$fecha1 && !$fecha2 && !$codigoInterfaz && !($soloAdeudos == 'on'), function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('IdTienda', $idTienda);
            })
            ->when($numNomina, function ($query) use ($numNomina) {
                $query->where('DatCortesTienda.NumNomina', $numNomina);
            })
            // ->when($chkNomina != 'on', function ($query) {
            //     $query->whereNotNull('DatCortesTienda.NumNomina');
            // })
            ->when($soloAdeudos == 'on', function ($query) {
                $query->where('StatusCredito', 0);
            })
            ->when($tipoNomina, function ($query) use ($tipoNomina) {
                $query->where('b.TipoNomina', $tipoNomina);
            })
            ->when($codigoInterfaz, function ($query) use ($codigoInterfaz) {
                $query->where('f.IdHistorialCredito', $codigoInterfaz);
            })
            ->whereNotNull('DatCortesTienda.NumNomina')
            ->whereIn('IdTienda', $tiendasIds)
            ->where('StatusCredito', 0)
            ->where('StatusVenta', 0)
            ->sum('ImporteArticulo');

        //return $ventasEmpleado;

        return view('Empleados.VentaEmpleados', compact(
            'tiendas',
            'ventasEmpleado',
            'importeTotal',
            'importeCredito',
            'filtrosAvanzadosActivos',
        ));
    }

    public function VentaEmpleadosExcel(Request $request)
    {
        // return $request;
        $tiendas = $this->tiendas;
        $tiendasIds = $this->tiendasIds;

        // return $request;
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $chkNomina = $request->chkNomina;
        $soloAdeudos = $request->soloAdeudos;
        $numNomina = $request->numNomina;
        $tipoNomina = $request->tipoNomina;
        $codigoInterfaz = $request->codigoInterfaz;

        $ventasEmpleado = DB::table('DatCortesTienda as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->leftJoin('CatTiendas as c', 'c.IdTienda', 'a.IdTienda')
            ->leftJoin('DatEncabezado as d', 'd.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as e', 'e.IdArticulo', 'a.IdArticulo')
            ->leftJoin('HistorialCreditos as f', 'f.IdHistorialCredito', 'a.Interfazado')
            ->leftJoin('CatTipoPago as g', 'g.IdTipoPago', 'a.IdTipoPago')
            ->select(
                'a.IdEncabezado',
                'a.IdTipoPago',
                'a.NumNomina',
                'a.FechaVenta',
                'c.NomTienda',
                'b.Nombre',
                'b.Apellidos',
                'b.TipoNomina',
                'b.Empresa',
                'd.IdTicket',
                'a.ImporteArticulo',
                'e.NomArticulo',
                'e.CodArticulo',
                'a.StatusCredito',
                'b.Status',
                'f.IdHistorialCredito',
                'f.FechaInterfaz',
                'g.NomTipoPago',
            )
            // Filtro de fecha SOLO si vienen ambas fechas
            ->when(!empty($fecha1) && !empty($fecha2), function ($query) use ($fecha1, $fecha2) {
                $query->whereBetween(DB::raw('cast(a.FechaVenta as date)'), [$fecha1, $fecha2]);
            })
            // Si NO vienen fechas Y NO viene código de interfaz, forzar un resultado vacío
            ->when(!$fecha1 && !$fecha2 && !$codigoInterfaz, function ($query) {
                $query->whereRaw('1 = 0'); // Esto hace que la consulta no devuelva nada
            })
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            ->when($chkNomina == 'on', function ($query) use ($numNomina) {
                $query->where('a.NumNomina', $numNomina);
            })
            ->when($chkNomina != 'on', function ($query) {
                $query->whereNotNull('a.NumNomina');
            })
            ->when($soloAdeudos == 'on', function ($query) {
                $query->where('a.StatusCredito', 0);
            })
            ->when($tipoNomina, function ($query) use ($tipoNomina) {
                $query->where('b.TipoNomina', $tipoNomina);
            })
            ->when($codigoInterfaz, function ($query) use ($codigoInterfaz) {
                $query->where('f.IdHistorialCredito', $codigoInterfaz);
            })
            ->where('d.StatusVenta', 0)
            ->orderBy('a.FechaVenta')
            ->get();

        // return $ventasEmpleado;
        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'ventasaempleado.xlsx';
        return Excel::download(new VentasAEmpleadoExport($ventasEmpleado), $name);

        return view('Empleados.VentaEmpleados', compact('ventasEmpleado', 'fecha1', 'fecha2', 'importeTotal', 'importeCredito', 'chkNomina', 'numNomina'));
    }

    public function VentasCredito(Request $request)
    {
        $tiposNomina = LimiteCredito::all();
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $idTipoNomina = $request->tipoNomina;

        $ventasCredito = DB::table('DatCreditos as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->leftJoin('CatTiendas as c', 'c.IdTienda', 'a.IdTienda')
            ->leftJoin('CatCiudades as d', 'd.IdCiudad', 'c.IdCiudad')
            ->select([
                'a.NumNomina',
                'b.Nombre',
                'b.Apellidos',
                'a.ImporteCredito',
                'c.NomTienda',
                'd.NomCiudad',
                'b.Empresa',
                'a.StatusCredito'
            ])
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
            ->where('b.TipoNomina', $idTipoNomina)
            ->get();

        $iTotalCredito = DB::table('DatCreditos as a')
            ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
            ->where('b.TipoNomina', $idTipoNomina)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
            ->sum('a.ImporteCredito');

        //return $iTotalCredito;

        return view('Empleados.VentasCredito', compact('tiposNomina', 'ventasCredito', 'fecha1', 'fecha2', 'idTipoNomina', 'iTotalCredito'));
    }

    public function ConcentradoAdeudos(Request $request)
    {
        $tiposNomina = LimiteCredito::all();

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $idTipoNomina = $request->tipoNomina;
        $chkNomina = $request->chkNomina;
        $numNomina = $request->numNomina;

        if (empty($chkNomina)) {
            $concentradoAdeudos = DB::table('DatCreditos as a')
                ->leftJoin('DatEncabezado as en', 'en.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->leftJoin('CatTiendas as c', 'c.IdTienda', 'a.IdTienda')
                ->select(DB::raw('a.NumNomina, b.Nombre, b.Apellidos, c.NomTienda, sum(a.ImporteCredito) as ImporteCredito'))
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                ->where('b.TipoNomina', $idTipoNomina)
                ->where('en.StatusVenta', 0)
                ->groupBy('a.NumNomina', 'c.NomTienda', 'b.Nombre', 'b.Apellidos')
                ->get();

            $iTotalCredito = DB::table('DatCreditos as a')
                ->leftJoin('DatEncabezado as en', 'en.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                ->where('b.TipoNomina', $idTipoNomina)
                ->where('en.StatusVenta', 0)
                ->sum('a.ImporteCredito');
        } else {
            $concentradoAdeudos = DB::table('DatCreditos as a')
                ->leftJoin('DatEncabezado as en', 'en.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->leftJoin('CatTiendas as c', 'c.IdTienda', 'a.IdTienda')
                ->select(DB::raw('a.NumNomina, b.Nombre, b.Apellidos, c.NomTienda, sum(a.ImporteCredito) as ImporteCredito'))
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                ->where('a.NumNomina', $numNomina)
                ->where('en.StatusVenta', 0)
                ->groupBy('a.NumNomina', 'c.NomTienda', 'b.Nombre', 'b.Apellidos')
                ->get();

            $iTotalCredito = DB::table('DatCreditos as a')
                ->leftJoin('DatEncabezado as en', 'en.IdEncabezado', 'a.IdEncabezado')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
                ->where('a.NumNomina', $numNomina)
                ->where('en.StatusVenta', 0)
                ->sum('a.ImporteCredito');
        }

        // return $concentradoAdeudos;

        return view('Empleados.ConcentradoAdeudos', compact('tiposNomina', 'fecha1', 'fecha2', 'idTipoNomina', 'chkNomina', 'numNomina', 'concentradoAdeudos', 'iTotalCredito'));
    }
}
