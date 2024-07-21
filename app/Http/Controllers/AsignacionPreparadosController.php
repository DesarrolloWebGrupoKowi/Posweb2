<?php

namespace App\Http\Controllers;

use App\Models\CatPreparado;
use App\Models\DatAsignacionPreparados;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignacionPreparadosController extends Controller
{
    public function Asignados(Request $request)
    {
        $fecha = $request->fecha;
        if ($request->fecha) {
            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
        }

        $asignados = [];

        if ($fecha) {
            $asignados = DatAsignacionPreparados::with('Detalle')
                ->select(
                    'DatAsignacionPreparados.IdDatAsignacionPreparado',
                    'DatAsignacionPreparados.IdPreparado',
                    'DatAsignacionPreparados.IdTienda',
                    'CatPreparado.Nombre',
                    'CatPreparado.Fecha',
                    'CatTiendas.NomTienda',
                    'DatAsignacionPreparados.CantidadEnvio',
                    'CatPreparado.Subir',
                )
                ->leftJoin('CatPreparado', 'CatPreparado.preparado', 'DatAsignacionPreparados.IdPreparado')
                ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'DatAsignacionPreparados.IdTienda')
                ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
                ->whereDate('CatPreparado.Fecha', $fecha)
                ->orderBy('CatPreparado.Fecha', 'DESC')
                ->paginate(10)
                ->withQueryString();
        } else {
            $asignados = DatAsignacionPreparados::with('Detalle')
                ->select(
                    'DatAsignacionPreparados.IdDatAsignacionPreparado',
                    'DatAsignacionPreparados.IdPreparado',
                    'DatAsignacionPreparados.IdTienda',
                    'CatPreparado.Nombre',
                    'CatPreparado.Fecha',
                    'CatTiendas.NomTienda',
                    'DatAsignacionPreparados.CantidadEnvio',
                    'CatPreparado.Subir',
                )
                ->leftJoin('CatPreparado', 'CatPreparado.preparado', 'DatAsignacionPreparados.IdPreparado')
                ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'DatAsignacionPreparados.IdTienda')
                ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
                ->orderBy('CatPreparado.Fecha', 'DESC')
                ->paginate(10)
                ->withQueryString();
        }

        return view('AsignacionPreparados.index', compact('asignados', 'fecha'));
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
