<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CatPreparado;
use App\Models\DatPreparados;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreparadosController extends Controller
{
    public function Preparados(Request $request)
    {
        $idPreparado = $request->idPreparado;

        $preparados = CatPreparado::where('IdCatStatusPreparado', 1)
            ->where('IdUsuario', Auth::user()->IdUsuario)
            ->get();

        $detallePreparado = DatPreparados::where('IdPreparado', $idPreparado)
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
            ->get();

        $articulos = Articulo::get();

        return view('Preparados.index', compact('preparados', 'idPreparado', 'detallePreparado', 'articulos'));
    }

    public function AgregarPreparados(Request $request)
    {

        if (!Auth::user()->usuarioTienda->IdTienda) {
            return back()->with('msjdelete', 'Error: Este usuario no puede crear una preparado');
        }

        $preparado = new CatPreparado();
        $preparado->Nombre = $request->nombre . '_' . Carbon::now()->format('Y-d-m');
        $preparado->Cantidad = $request->cantidad;
        $preparado->IdUsuario = Auth::user()->IdUsuario;
        $preparado->IdTienda = Auth::user()->usuarioTienda->IdTienda;
        $preparado->Fecha = Carbon::now()->format('Y-d-m');
        $preparado->IdCatStatusPreparado = 1;
        $preparado->save();

        return back()->with('msjAdd', 'Preparado agregado correctamente');
    }

    public function EditarPreparados($idPreparado, Request $request)
    {
        $resultado = intval(preg_replace('/[^0-9]+/', '', $request->nombre), 10);

        CatPreparado::where('IdPreparado', $idPreparado)->update([
            'Nombre' => $resultado != 0 ? $request->nombre : $request->nombre . '_' . Carbon::now()->format('Y-m-d'),
            'Cantidad' => $request->cantidad,
        ]);

        DB::update("UPDATE [dbo].[DatPreparados]
            SET CantidadFormula=CantidadPaquete / $request->cantidad
            WHERE [IdPreparado]=$idPreparado");

        return back();
    }

    public function EnviarPreparados($idPreparado)
    {
        CatPreparado::where('IdPreparado', $idPreparado)->update([
            'IdCatStatusPreparado' => 2,
        ]);

        return redirect()->route('Preparados.index');
    }

    public function EliminarPreparados($idPreparado)
    {
        $detalle = DatPreparados::where('IdPreparado', $idPreparado)->get();

        foreach ($detalle as $item) {
            $item->delete();
        }

        $preparado = CatPreparado::where('IdPreparado', $idPreparado)->first();

        $preparado->delete();

        return redirect()->route('Preparados.index');
    }

    public function AgregarArticulo($idPreparado, Request $request)
    {
        $idArticulo = Articulo::where('CodArticulo', $request->codigo)->value('IdArticulo');

        $cantidad = CatPreparado::where('IdPreparado', $idPreparado)->value('Cantidad');

        $preparado = new DatPreparados();
        $preparado->IdPreparado = $idPreparado;
        $preparado->IdArticulo = $idArticulo;
        $preparado->CantidadPaquete = $request->cantidad;
        $preparado->CantidadFormula = $cantidad ? $request->cantidad / $cantidad : null;
        $preparado->save();

        return back();
    }

    public function EliminarArticulo($id)
    {
        $preparado = DatPreparados::where('IdDatPreparado', $id)->first();

        $preparado->delete();

        return back();
    }
}
