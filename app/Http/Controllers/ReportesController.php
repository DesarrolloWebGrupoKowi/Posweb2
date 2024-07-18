<?php

namespace App\Http\Controllers;

use App\Exports\ConcentradoDeArticulosExport;
use App\Exports\ConcentradoPorCiudadYFamilia;
use App\Exports\DineroElectronicoExport;
use App\Exports\GrupoYTipoPrecio;
use App\Exports\VentasPorTipoDePrecioExport;
use App\Models\CapMerma;
use App\Models\DatEncabezado;
use App\Models\DatTipoPago;
use App\Models\Tienda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OpcionReportes
{
    public $IdReporte;
    public $NomReporte;
}

class ReportesController extends Controller
{
    public function ReporteConcentradoDeArticulos(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
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

        $concentrado = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatFamilias as d', 'c.IdFamilia', 'd.IdFamilia')
            ->leftJoin('CatGrupos as e', 'c.IdGrupo', 'e.IdGrupo')
            ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw('g.NomCiudad, f.NomTienda, c.CodArticulo, c.NomArticulo, e.NomGrupo, SUM(b.CantArticulo) as Peso,
                            b.PrecioArticulo, SUM(b.IvaArticulo) as Iva , SUM(b.ImporteArticulo) as Importe'))
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('g.NomCiudad', 'f.NomTienda', 'c.CodArticulo', 'c.NomArticulo', 'b.PrecioArticulo', 'e.NomGrupo')
            ->orderBy('c.CodArticulo')
            ->get();

        return view('Reportes.ConcentradoDeArticulos', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'concentrado'));
    }

    public function ExportReporteConcentradoDeArticulos(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = DB::table('DatEncabezado as a')
            ->leftJoin('DatDetalle as b', 'b.IdEncabezado', 'a.IdEncabezado')
            ->leftJoin('CatArticulos as c', 'c.IdArticulo', 'b.IdArticulo')
            ->leftJoin('CatFamilias as d', 'c.IdFamilia', 'd.IdFamilia')
            ->leftJoin('CatGrupos as e', 'c.IdGrupo', 'e.IdGrupo')
            ->leftJoin('CatTiendas as f', 'a.IdTienda', 'f.IdTienda')
            ->leftJoin('CatCiudades as g', 'f.IdCiudad', 'g.IdCiudad')
            ->select(DB::raw('g.NomCiudad, f.NomTienda, c.CodArticulo, c.NomArticulo, e.NomGrupo, SUM(b.CantArticulo) as Peso,
                            b.PrecioArticulo, SUM(b.IvaArticulo) as Iva , SUM(b.ImporteArticulo) as Importe'))
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('a.IdTienda', $idTienda);
            })
            ->where('a.StatusVenta', 0)
            ->whereRaw("cast(a.FechaVenta as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->groupBy('g.NomCiudad', 'f.NomTienda', 'c.CodArticulo', 'c.NomArticulo', 'b.PrecioArticulo', 'e.NomGrupo')
            ->orderBy('c.CodArticulo')
            ->get();
        $name = Carbon::now()->parse(date(now()))->format('Ymd') . 'concentradodeventas.xlsx';
        return Excel::download(new ConcentradoDeArticulosExport($concentrado), $name);
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
        $idTienda = $request->idTienda;
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
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
            ->when($idTienda, function ($query) use ($idTienda) {
                $query->where('CapMermas.IdTienda', $idTienda);
            })
            ->whereRaw("cast(CapMermas.FechaCaptura as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->orderBy('CapMermas.FechaCaptura', 'desc')
            ->paginate(10);

        return view('Reportes.ConcentradoDeMermas', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'concentrado'));
    }

    public function ReporteDineroElectronido(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
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

        $concentrado = collect(DB::select("SELECT NomTienda,
                CONVERT(varchar(12), Fecha, 103) as Fecha,
                sum(case WHEN Tipo = 3 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as semanal_creadito,
                sum(case WHEN Tipo = 3 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as semanal_contado,
                sum(case WHEN Tipo = 4 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as quincenal_creadito,
                sum(case WHEN Tipo = 4 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as quincenal_contado,
                sum(case WHEN Tipo = 1 THEN Monedero ELSE 0 END) as contado
            from (
                select t.NomTienda,
                    cast(FechaVenta as date) as Fecha,
                    dt.IdTipoPago,
                    abs(isnull(dm.Monedero,0)) as Monedero,
                    case WHEN ce.TipoNomina is not null THEN ce.TipoNomina ELSE cf.IdTipoCliente END as Tipo
                from DatMonederoElectronico as dm
                    left join DatEncabezado as de on de.IdEncabezado = dm.IdEncabezado
                    left join DatTipoPago as dt on dm.IdEncabezado = dt.IdEncabezado
                    left join CatTiendas as t on dm.IdTienda = t.IdTienda
                    left join CatEmpleados as ce on ce.NumNomina = dm.NumNomina
                    left join CatFrecuentesSocios as cf on cf.FolioViejo = dm.NumNomina
                where cast(FechaVenta as date) >='$fecha1' and
                    cast(FechaVenta as date) <'$fecha2' and
                    dm.IdTienda = '$idTienda' and
                    dt.IdTipoPago in (1, 7) and
                    dm.Monedero < 0
                group by t.NomTienda, cast(FechaVenta as date), dt.IdTipoPago, ce.TipoNomina, cf.IdTipoCliente, dm.Monedero
            ) as a group by NomTienda, Fecha
            order by NomTienda, Fecha"));

        return view('Reportes.DineroElectronico', compact('fecha1', 'fecha2', 'idTienda', 'tiendas', 'concentrado'));
    }

    public function ExportReporteDineroElectronido(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = !$request->fecha1 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha1;
        $fecha2 = !$request->fecha2 ? Carbon::now()->parse(date(now()))->format('Y-m-d') : $request->fecha2;

        $concentrado = collect(DB::select("SELECT NomTienda,
                CONVERT(varchar(12), Fecha, 103) as Fecha,
                sum(case WHEN Tipo = 3 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as semanal_creadito,
                sum(case WHEN Tipo = 3 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as semanal_contado,
                sum(case WHEN Tipo = 4 AND IdTipoPago = 7 THEN Monedero ELSE 0 END) as quincenal_creadito,
                sum(case WHEN Tipo = 4 AND IdTipoPago = 1 THEN Monedero ELSE 0 END) as quincenal_contado,
                sum(case WHEN Tipo = 1 THEN Monedero ELSE 0 END) as contado
            from (
                select t.NomTienda,
                    cast(FechaVenta as date) as Fecha,
                    dt.IdTipoPago,
                    abs(isnull(dm.Monedero,0)) as Monedero,
                    case WHEN ce.TipoNomina is not null THEN ce.TipoNomina ELSE cf.IdTipoCliente END as Tipo
                from DatMonederoElectronico as dm
                    left join DatEncabezado as de on de.IdEncabezado = dm.IdEncabezado
                    left join DatTipoPago as dt on dm.IdEncabezado = dt.IdEncabezado
                    left join CatTiendas as t on dm.IdTienda = t.IdTienda
                    left join CatEmpleados as ce on ce.NumNomina = dm.NumNomina
                    left join CatFrecuentesSocios as cf on cf.FolioViejo = dm.NumNomina
                where cast(FechaVenta as date) >='$fecha1' and
                    cast(FechaVenta as date) <'$fecha2' and
                    dm.IdTienda = '$idTienda' and
                    dt.IdTipoPago in (1, 7) and
                    dm.Monedero < 0
                group by t.NomTienda, cast(FechaVenta as date), dt.IdTipoPago, ce.TipoNomina, cf.IdTipoCliente, dm.Monedero
            ) as a group by NomTienda, Fecha
            order by NomTienda, Fecha"));

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
}
