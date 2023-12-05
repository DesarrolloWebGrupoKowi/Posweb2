<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Caja;
use App\Models\CatPreparado;
use App\Models\DatCaja;
use App\Models\DatInventario;
use App\Models\DatPreparados;
use App\Models\InventarioTienda;
use App\Models\ListaPrecio;
use App\Models\Precio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreparadosController extends Controller
{
    public function Preparados(Request $request)
    {
        $idPreparado = $request->idPreparado;

        $preparados = CatPreparado::select(
            'CatPreparado.IdPreparado',
            'CatPreparado.Nombre',
            'CatPreparado.Cantidad',
            DB::raw('SUM(DatPrecios.PrecioArticulo * DatPreparados.CantidadFormula) AS Total')
        )
            ->leftJoin('DatPreparados', 'CatPreparado.IdPreparado', 'DatPreparados.IdPreparado')
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
            ->leftJoin('DatPrecios', [['CatArticulos.CodArticulo', 'DatPrecios.CodArticulo'], ['DatPreparados.IDLISTAPRECIO', 'DatPrecios.IdListaPrecio']])
            ->where('CatPreparado.IdCatStatusPreparado', 1)
            ->where('CatPreparado.IdUsuario', Auth::user()->IdUsuario)
            ->groupBy('CatPreparado.IdPreparado', 'CatPreparado.Nombre', 'CatPreparado.Cantidad')
            ->get();

        $detallePreparado = DatPreparados::where('IdPreparado', $idPreparado)
            ->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
            ->leftJoin('DatPrecios', [['CatArticulos.CodArticulo', 'DatPrecios.CodArticulo'], ['DatPreparados.IDLISTAPRECIO', 'DatPrecios.IdListaPrecio']])
            ->get();

        $articulos = Articulo::get();

        $listaPrecios = ListaPrecio::get();
        $people = array(
            2 => array(
                'name' => 'John',
                'fav_color' => 'green',
            ),
            5 => array(
                'name' => 'Samuel',
                'fav_color' => 'blue',
            ),
        );

        $total = 0;
        foreach ($preparados as $key => $objeto) {
            if ($objeto->IdPreparado == $idPreparado) {
                $total = $objeto->Total;
            }
        }

        return view('Preparados.index', compact('preparados', 'idPreparado', 'detallePreparado', 'articulos', 'listaPrecios', 'total'));
    }

    public function AgregarPreparados(Request $request)
    {
        $idcaja = DatCaja::where('Status', 0)->value('IdCaja');

        if (!Auth::user()->usuarioTienda->IdTienda) {
            return back()->with('msjdelete', 'Error: Este usuario no puede crear una preparado');
        }

        $preparado = new CatPreparado();
        $preparado->Nombre = $request->nombre . '_' . Carbon::now()->format('Y-d-m');
        $preparado->Cantidad = $request->cantidad;
        $preparado->IdUsuario = Auth::user()->IdUsuario;
        $preparado->IdTienda = Auth::user()->usuarioTienda->IdTienda;
        $preparado->IdCaja = $idcaja;
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

    public function EditarListaPreciosPreparados($idPreparado, Request $request)
    {
        $preparados = DatPreparados::where('IdPreparado', $idPreparado)->get();

        foreach ($preparados as $preparado) {
            $codArticulo = Articulo::where('IdArticulo', $preparado->IdArticulo)->value('CodArticulo');
            $precio = Precio::where('CodArticulo', $codArticulo)
                ->where('IdListaPrecio', $request->IdListaPrecio)
                ->first();
            if (!$precio || $precio->PrecioArticulo == 0) {
                return back()->with('msjdelete', 'Error: El articulo con el código ' . $codArticulo . ' no cuenta con precio en esa lista de precios');
            }
        }

        DatPreparados::where('IdPreparado', $idPreparado)->update([
            'IDLISTAPRECIO' => $request->IdListaPrecio,
        ]);
        return back();
    }

    public function EnviarPreparados($idPreparado)
    {
        CatPreparado::where('IdPreparado', $idPreparado)->update([
            'IdCatStatusPreparado' => 2,
        ]);

        // return DatPreparados::leftjoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatPreparados.IdArticulo')
        //     ->where('IdPreparado', $idPreparado)->get();

        // return CatPreparado::where('IdPreparado', $idPreparado)->first();

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
        $idListaPrecio = DatPreparados::where('IdPreparado', $idPreparado)->value('IDLISTAPRECIO');
        $idArticulo = Articulo::where('CodArticulo', $request->codigo)->value('IdArticulo');

        // Validamos que el articulo tenga lista de precio
        $articulo = Precio::where('CodArticulo', $request->codigo)
            ->where('IdListaPrecio', $idListaPrecio ? $idListaPrecio : 4)
            ->first();

        if (!$articulo || $articulo->PrecioArticulo == 0) {
            return back()->with('msjdelete', 'Error: El articulo que desea agregar no cuenta con precio');
        }

        // Validamos que el articulo tenga stock
        $stock = InventarioTienda::where('CodArticulo', $request->codigo)->first();
        if ($stock == null || $stock->StockArticulo < $request->cantidad) {
            return back()->with('msjdelete', 'Error: El articulo no cuenta con stock suficiente');;
        }

        $cantidad = CatPreparado::where('IdPreparado', $idPreparado)->value('Cantidad');

        $preparado = new DatPreparados();
        $preparado->IdPreparado = $idPreparado;
        $preparado->IdArticulo = $idArticulo;
        $preparado->CantidadPaquete = $request->cantidad;
        $preparado->CantidadFormula = $cantidad ? $request->cantidad / $cantidad : null;
        $preparado->IDLISTAPRECIO = $idListaPrecio ? $idListaPrecio : 4;
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
