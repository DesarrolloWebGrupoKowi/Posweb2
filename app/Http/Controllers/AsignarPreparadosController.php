<?php

namespace App\Http\Controllers;

use App\Models\CatPreparado;
use App\Models\DatAsignacionPreparados;
use App\Models\Tienda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignarPreparadosController extends Controller
{
    public function Preparados(Request $request)
    {
        $fecha = $request->fecha;
        if ($request->fecha) {
            $fecha = Carbon::parse($request->fecha)->format('Y-d-m');
        }

        $preparados = [];

        if ($fecha) {
            $preparados = CatPreparado::with('Detalle', 'Tiendas')
                ->select(
                    'CatPreparado.IdPreparado',
                    'CatPreparado.Nombre',
                    'CatPreparado.Fecha',
                    'CatPreparado.Cantidad',
                    DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada')
                )
                ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.IdPreparado')
                ->where('Status', 'Preparado')
                ->where('Fecha', $fecha)
                ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Fecha', 'CatPreparado.Cantidad')
                ->orderBy('CatPreparado.Fecha', 'DESC')
                ->paginate(10);
        } else {
            $preparados = CatPreparado::with('Detalle', 'Tiendas')
                ->select(
                    'CatPreparado.IdPreparado',
                    'CatPreparado.Nombre',
                    'CatPreparado.Fecha',
                    'CatPreparado.Cantidad',
                    DB::raw('SUM(DatAsignacionPreparados.CantidadEnvio) as CantidadAsignada')
                )
                ->leftJoin('DatAsignacionPreparados', 'DatAsignacionPreparados.IdPreparado', 'CatPreparado.IdPreparado')
                ->where('Status', 'Preparado')
                ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Fecha', 'CatPreparado.Cantidad')
                ->orderBy('CatPreparado.Fecha', 'DESC')
                ->paginate(10);
        }

        $tiendas = Tienda::select('IdTienda', 'NomTienda')->get();

        return view('AsignarPreparados.index', compact('preparados', 'tiendas', 'fecha'));
    }

    public function RegresarPreparado($idPreparado)
    {
        CatPreparado::where('IdPreparado', $idPreparado)->update([
            'Status' => 'En Preparacion',
        ]);

        return back();
    }

    public function AsignarTienda($idAsignacion, Request $request)
    {
        $asignacion = new DatAsignacionPreparados();
        $asignacion->IdPreparado = $idAsignacion;
        $asignacion->IdTienda = $request->idTienda;
        $asignacion->CantidadEnvio = $request->cantidad;
        $asignacion->save();

        return back();
    }

    public function EliminarTiendaAsignada($idAsignacion)
    {
        $asignacion = DatAsignacionPreparados::where('IdDatAsignacionPreparado', $idAsignacion)->first();

        $asignacion->delete();

        return back();
    }
}
