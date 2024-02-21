<?php

namespace App\Http\Controllers;

use App\Exports\ConcentradoDeArticulosExport;
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
}
