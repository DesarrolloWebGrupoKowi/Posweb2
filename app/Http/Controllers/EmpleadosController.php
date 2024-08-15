<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\CorteTienda;
use App\Models\CreditoEmpleado;
use App\Models\LimiteCredito;
use App\Models\VentaCreditoEmpleado;

class EmpleadosController extends Controller
{
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
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $chkNomina = $request->chkNomina;
        $numNomina = $request->numNomina;

        if ($chkNomina == 'on') {
            $ventasEmpleado = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->leftJoin('CatTiendas as c', 'c.IdTienda', 'a.IdTienda')
                ->leftJoin('DatEncabezado as d', 'd.IdEncabezado', 'a.IdEncabezado')
                ->select(
                    'a.NumNomina',
                    'a.FechaVenta',
                    'c.NomTienda',
                    'b.Nombre',
                    'b.Apellidos',
                    'b.Empresa',
                    'd.IdTicket',
                    'a.ImporteArticulo',
                    'a.StatusCredito',
                    'b.Status'
                )
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->where('a.NumNomina', $numNomina)
                ->where('d.StatusVenta', 0)
                ->orderBy('a.FechaVenta')
                ->get();

            $importeTotal = CorteTienda::whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->where('StatusVenta', 0)
                ->where('NumNomina', $numNomina)
                ->sum('ImporteArticulo');

            $importeCredito = CorteTienda::whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->whereNotNull('StatusCredito')
                ->where('StatusVenta', 0)
                ->where('NumNomina', $numNomina)
                ->sum('ImporteArticulo');
        } else {
            $ventasEmpleado = DB::table('DatCortesTienda as a')
                ->leftJoin('CatEmpleados as b', 'b.NumNomina', 'a.NumNomina')
                ->leftJoin('CatTiendas as c', 'c.IdTienda', 'a.IdTienda')
                ->leftJoin('DatEncabezado as d', 'd.IdEncabezado', 'a.IdEncabezado')
                ->select(
                    'a.NumNomina',
                    'a.FechaVenta',
                    'c.NomTienda',
                    'b.Nombre',
                    'b.Apellidos',
                    'b.Empresa',
                    'd.IdTicket',
                    'a.ImporteArticulo',
                    'a.StatusCredito',
                    'b.Status'
                )
                ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->whereNotNull('a.NumNomina')
                ->get();

            $importeTotal = CorteTienda::whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->where('StatusVenta', 0)
                ->whereNotNull('NumNomina')
                ->sum('ImporteArticulo');

            $importeCredito = CorteTienda::whereRaw("cast(FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->whereNotNull('StatusCredito')
                ->where('StatusVenta', 0)
                ->sum('ImporteArticulo');
        }

        //return $ventasEmpleado;

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
                'a.NumNomina', 'b.Nombre', 'b.Apellidos', 'a.ImporteCredito', 'c.NomTienda', 'd.NomCiudad', 'b.Empresa', 'a.StatusCredito'
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
