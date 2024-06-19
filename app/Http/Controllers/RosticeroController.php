<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CatRosticeroArticulos;
use App\Models\DatCaja;
use App\Models\DatDetalleRosticero;
use Illuminate\Http\Request;
use App\Models\DatRosticero;
use App\Models\HistorialMovimientoProducto;
use App\Models\InventarioTienda;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RosticeroController extends Controller
{
    // ECHO
    public function VerRosticero(Request $request)
    {
        $rostisados = DatRosticero::with('Detalle')
            ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'DatRosticero.CodigoVenta')
            ->orderBy('Fecha', 'desc')
            ->paginate(10);

        $articulos = CatRosticeroArticulos::select('CatRosticeroArticulos.*', 'a.NomArticulo as articuloPrima', 'b.NomArticulo as articuloVenta')
            ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'CatRosticeroArticulos.CodigoMatPrima')
            ->leftjoin('CatArticulos as b', 'b.CodArticulo', 'CatRosticeroArticulos.CodigoVenta')
            ->get();

        return view('Rosticero.VerRosticero', compact('rostisados', 'articulos'));
    }

    public function CrearRosticero(Request $request)
    {
        try {
            DB::beginTransaction();

            $idTienda = Auth::user()->usuarioTienda->IdTienda;
            $caja = DatCaja::where('Activa', 0)->where('Status', 0)->first();
            $rostisado = CatRosticeroArticulos::where('IdCatRosticeroArticulos', $request->CodigoVenta)->first();
            $MermaStnd = $request->CantidadMatPrima * ($rostisado->PorcentajeMerma / 100);

            // Validamos stock en el producto prima
            $stock = InventarioTienda::where('CodArticulo', $rostisado->CodigoMatPrima)->first();
            if ($stock == null || $stock->StockArticulo < $request->CantidadMatPrima) {
                return back()->with('msjdelete', 'Error: El articulo no cuenta con stock suficiente');;
            }

            // Agregamos el rostisado
            DatRosticero::create([
                'IdRosticero' => 0,
                'CodigoMatPrima' => $rostisado->CodigoMatPrima,
                'CantidadMatPrima' => $request->CantidadMatPrima,
                'CodigoVenta' => $rostisado->CodigoVenta,
                'CantidadVenta' => 0,
                'IdTienda' => $caja->IdTienda,
                'IdCaja' => $caja->IdCaja,
                'Fecha' => date("Y-d-m H:i:s"),
                'MermaStnd' => $MermaStnd,
                'MermaReal' => $request->CantidadMatPrima,
                'Disponible' => 0,
                'IdUsuario' => Auth::user()->IdUsuario
            ]);

            //DESCONTAR PRODUCTO MERMADO DEL INVENTARIO LOCAL
            InventarioTienda::where('IdTienda', $idTienda)
                ->where('CodArticulo', $rostisado->CodigoMatPrima)
                ->update([
                    'StockArticulo' => $stock->StockArticulo - $request->CantidadMatPrima
                ]);

            DB::commit();
            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    public function EditarRosticero($id, Request $request)
    {
        $rostisado = CatRosticeroArticulos::where('IdCatRosticeroArticulos', $request->CodigoMatPrima)
            ->first();


        $MermaStnd = $request->CantidadMatPrima * ($rostisado->PorcentajeMerma / 100);
        $MermaReal = $request->CantidadMatPrima - $request->CantidadVenta;

        DatRosticero::where('IdDatRosticero', $id)
            ->update([
                'CodigoMatPrima' => $rostisado->CodigoMatPrima,
                'CantidadMatPrima' => $request->CantidadMatPrima,
                'CodigoVenta' => $rostisado->CodigoVenta,
                'CantidadVenta' => $request->CantidadVenta,
                'MermaStnd' => $MermaStnd,
                'MermaReal' => $MermaReal,
                'Disponible' => $request->CantidadVenta,
                'IdUsuario' => Auth::user()->IdUsuario,
                'Subir' => 0
            ]);
        return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
    }

    public function AgregarDetalleRosticero($id, Request $request)
    {
        $codigo = $request->codigo;
        $codEtiqueta = substr($codigo, 3, 4);
        $primerPeso = substr($codigo, 7, 5);
        $peso = $primerPeso / 1000;

        $CodArticulo = Articulo::where('codEtiqueta', $codEtiqueta)->value('CodArticulo');
        $rostisado = DatRosticero::where('IdRosticero', $id)->first();

        if ($rostisado->CodigoVenta != $CodArticulo) {
            return back()->with('msjdelete', 'El producto no se pertenece a este rostisado.');
        }

        // Agregamos el detalle del rostisado
        DatDetalleRosticero::create([
            'IdRosticero' => $id,
            'CodigoArticulo' => $CodArticulo,
            'Cantidad' => $peso,
            'FechaCreacion' => date("Y-d-m H:i:s"),
            'subir' => 0
        ]);

        // Actualizamos el rostisado
        DatRosticero::where('IdRosticero', $id)
            ->update([
                'CantidadVenta' => $rostisado->CantidadVenta + $peso,
                'MermaReal' => $rostisado->MermaReal - $peso,
            ]);

        return back()->with(['msjAdd' => 'La sentencia se ejecuto correctamente', 'id' => $id]);
    }

    public function ApiAgregarDetalleRosticero($id, Request $request)
    {
        $codigo = $request->codigo;
        $codEtiqueta = substr($codigo, 3, 4);
        $primerPeso = substr($codigo, 7, 5);
        $peso = $primerPeso / 1000;

        $CodArticulo = Articulo::where('codEtiqueta', $codEtiqueta)->value('CodArticulo');
        $rostisado = DatRosticero::where('IdRosticero', $id)->first();

        if ($rostisado->CodigoVenta != $CodArticulo) {
            return response()->json([
                'ok' => 'false',
                'msj' => 'El producto no se pertenece a este rostisado.',
            ]);
        }

        // Agregamos el detalle del rostisado
        // DatDetalleRosticero::create([
        //     'IdRosticero' => $id,
        //     'CodigoArticulo' => $CodArticulo,
        //     'Cantidad' => $peso,
        //     'FechaCreacion' => date("Y-d-m H:i:s"),
        //     'subir' => 0
        // ]);

        // // Actualizamos el rostisado
        // DatRosticero::where('IdRosticero', $id)
        //     ->update([
        //         'CantidadVenta' => $rostisado->CantidadVenta + $peso,
        //         'MermaReal' => $rostisado->MermaReal - $peso,
        //     ]);


        return response()->json([
            'ok' => 'true',
            'msj' => 'La sentencia se ejecuto correctamente',
        ]);
    }

    public function RecalentadoRosticero($id, Request $request)
    {
        try {
            $codigo = $request->codigo;
            $codEtiqueta = substr($codigo, 3, 4);
            $primerPeso = substr($codigo, 7, 5);
            $peso = $primerPeso / 1000;

            $CodArticulo = Articulo::where('codEtiqueta', $codEtiqueta)->value('CodArticulo');
            $detalle = DatDetalleRosticero::where('IdDatDetalleRosticero', $id)->first();
            $rostisado = DatRosticero::where('IdRosticero', $detalle->IdRosticero)->first();

            if ($rostisado->CodigoVenta != $CodArticulo) {
                return back()->with('msjdelete', 'El producto no se pertenece a este rostisado.');
            }

            if ($detalle->Cantidad <= $peso) {
                return back()->with('msjdelete', 'No se puede mermar ninguna cantidad.');
            }

            // Agregamos el detalle del rostisado
            DatDetalleRosticero::where('IdDatDetalleRosticero', $id)
                ->update([
                    'Cantidad' => $peso,
                ]);

            // Actualizamos el rostisado
            DatRosticero::where('IdRosticero', $detalle->IdRosticero)
                ->update([
                    'CantidadVenta' => $rostisado->CantidadVenta - ($detalle->Cantidad - $peso),
                    'MermaReal' => $rostisado->MermaReal + ($detalle->Cantidad - $peso),
                ]);

            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $e) {
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    public function EliminarRosticero($id)
    {
        try {
            DatRosticero::where('IdDatRosticero', $id)
                ->delete();

            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $e) {
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    public function EliminarDetalleRosticero($id)
    {
        try {
            $detalle = DatDetalleRosticero::where('IdDatDetalleRosticero', $id)->first();
            $rosticero = DatRosticero::where('IdRosticero', $detalle->IdRosticero)->first();

            DatRosticero::where('IdRosticero', $detalle->IdRosticero)
                ->update([
                    'CantidadVenta' => $rosticero->CantidadVenta - $detalle->Cantidad,
                    'MermaReal' => $rosticero->MermaReal + $detalle->Cantidad,
                ]);

            DatDetalleRosticero::where('IdDatDetalleRosticero', $id)
                ->delete();

            $cantidad = DatDetalleRosticero::where('IdRosticero', $detalle->IdRosticero)
                ->count();

            // return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
            return back()->with(['msjAdd' => 'La sentencia se ejecuto correctamente', 'id' => $cantidad > 0 ? $detalle->IdRosticero : -1]);
        } catch (\Throwable $e) {
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }
}
