<?php

namespace App\Http\Controllers;

use App\Exports\ConcentradoDeArticulosExport;
use App\Exports\VentasPorTipoDePrecioExport;
use App\Models\DatEncabezado;
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
}
