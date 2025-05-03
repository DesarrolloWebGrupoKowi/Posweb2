<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CapMerma;
use App\Models\CatRosticeroArticulos;
use App\Models\DatArticulosTipoMerma;
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
        $rostisados = DatRosticero::select(
            'DatRosticero.*',
            'a.CodArticulo',
            'a.NomArticulo'
        )
            ->with(['Detalle' => function ($query) {
                $query->select('DatDetalleRosticero.*', 'CatArticulos.CodArticulo', 'CatArticulos.NomArticulo');
                $query->orderBy('IdDatDetalleRosticero', 'asc');
            }])
            ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'DatRosticero.CodigoVenta')
            ->whereNull('Finalizado')
            ->orderBy('Finalizado')
            ->orderBy('Fecha', 'desc')
            ->paginate(10);

        $articulos = CatRosticeroArticulos::select('CatRosticeroArticulos.*', 'a.NomArticulo as articuloPrima', 'b.NomArticulo as articuloVenta')
            ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'CatRosticeroArticulos.CodigoMatPrima')
            ->leftjoin('CatArticulos as b', 'b.CodArticulo', 'CatRosticeroArticulos.CodigoVenta')
            ->get();

        foreach ($rostisados as $rostisado) {
            // Ordenamos el detalle por linea
            $rostisadosDetalle = [];
            foreach ($rostisado->Detalle as $detalle) {
                // Solo agregamos los que no tienen codigo de referecia
                if ($detalle->CodigoEtiquetaRef == null) {
                    $rostisadosDetalle[] = $detalle;
                    $lastDetalle = $detalle;
                    // Buscamos los que tienen dependiente
                    for ($i = 0; $i < count($rostisado->Detalle); $i++) {
                        $d = $rostisado->Detalle[$i];
                        if ($lastDetalle->IdDatDetalleRosticero == $d->CodigoEtiquetaRef) {
                            $rostisadosDetalle[] = $d;
                            $lastDetalle = $d;
                            $i = 0;
                        }
                    }
                }
            }
            $rostisado->newdetalle = $rostisadosDetalle;
        }
        // return $rostisados;

        //Buscamos los tipos de mermas
        $articulosRosticero =  CatRosticeroArticulos::select('CodigoVenta')->get();
        $tiposMermas = DatArticulosTipoMerma::select(
            'DatArticulosTipoMerma.IdTipoMerma',
            'CatTiposMerma.NomTipoMerma'
        )
            ->with('Detalle')
            ->leftJoin('CatTiposMerma', 'CatTiposMerma.IdTipoMerma', 'DatArticulosTipoMerma.IdTipoMerma')
            ->whereIn('CodArticulo', $articulosRosticero)
            ->distinct()
            ->get();

        $codigosEtiqueta = DatDetalleRosticero::select('CodigoEtiqueta')
            ->with('Fechas')
            ->where('Status', 0)
            ->where('Vendida', 1)
            ->groupBy('CodigoEtiqueta')
            ->havingRaw('COUNT(*) > 1')
            ->get();


        return view('Rosticero.VerRosticero', compact('rostisados', 'articulos', 'tiposMermas', 'codigosEtiqueta'));
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
                return back()->with('msjdelete', 'Error: El articulo no cuenta con stock suficiente: ' . $rostisado->CodigoMatPrima);;
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
                'IdUsuario' => Auth::user()->IdUsuario,
                'STATUS' => 0
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
        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        $codigo = $request->codigo;
        $codEtiqueta = substr($codigo, 3, 4);
        $primerPeso = substr($codigo, 7, 5);
        $peso = $primerPeso / 1000;

        $CodArticulo = Articulo::where('codEtiqueta', $codEtiqueta)->value('CodArticulo');
        $rostisado = DatRosticero::where('IdRosticero', $id)->first();
        $linea = DatDetalleRosticero::where('IdRosticero', $id)->count() + 1;

        if ($rostisado->CodigoVenta != $CodArticulo) {
            return back()->with('msjdelete', 'El producto no se pertenece a este rostisado.');
        }

        // Validar que el la etiqueta no este en otro rostisado
        $etiqueta = DatDetalleRosticero::where('CodigoEtiqueta', $codigo)
            ->where('Status', 0)
            ->Where('Vendida', 1)
            ->first();

        // if ($etiqueta) {
        //     return back()->with('msjdelete', 'La etiqueta ya esta en un rostisado.');
        // }

        // Agregamos el detalle del rostisado
        DatDetalleRosticero::create([
            'IdRosticero' => $id,
            'CodigoArticulo' => $CodArticulo,
            'Cantidad' => $peso,
            'FechaCreacion' => date("Y-d-m H:i:s"),
            'subir' => 0,
            'CodigoEtiqueta' => $codigo,
            'STATUS' => 0,
            'Linea' => $linea,
            'Vendida' => 1,
        ]);

        // Actualizamos el rostisado
        DatRosticero::where('IdRosticero', $id)
            ->update([
                'CantidadVenta' => $rostisado->CantidadVenta + $peso,
                'Disponible' => $rostisado->Disponible + $peso,
                'MermaReal' => $rostisado->MermaReal - $peso,
                'subir' => 0
            ]);

        //AGREGAMOS PRODUCTO A INVENTARIO LOCAL
        $inventario = InventarioTienda::where('IdTienda', $idTienda)
            ->where('CodArticulo', $CodArticulo)
            ->first();

        if ($inventario) {
            $inventario->StockArticulo = $inventario->StockArticulo + $peso;
            $inventario->save();
        } else {
            InventarioTienda::create([
                'IdTienda' => $idTienda,
                'CodArticulo' => $CodArticulo,
                'StockArticulo' => $peso
            ]);
        }

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
                    // 'CantidadVenta' => $rostisado->CantidadVenta - ($detalle->Cantidad - $peso),
                    'Disponible' => $rostisado->CantidadVenta - ($detalle->Cantidad - $peso),
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
            $idRosticero =  DatRosticero::where('IdDatRosticero', $id)->value('IdRosticero');

            $vendidos = DatDetalleRosticero::where('IdRosticero', $idRosticero)
                ->where('Status', 0)
                ->where('Vendida', 0)
                ->count();

            if ($vendidos > 0) {
                return back()->with('msjdelete', 'No se puede mermar, cuenta con lineas procesadas');
            }

            DatDetalleRosticero::where('IdRosticero', $idRosticero)
                ->where('Status', 0)
                ->update([
                    'Subir' => 0,
                    'Status' => 1
                ]);

            DatRosticero::where('IdDatRosticero', $id)
                ->update([
                    'Subir' => 0,
                    'Status' => 1,
                    'FechaEliminar' => date("Y-d-m H:i:s"),
                    'Finalizado' => 1,
                    'FechaFinzalizado' => date("Y-d-m H:i:s"),
                ]);

            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $e) {
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    // Solo se cambia el estatus y se marca como vendido
    public function CambiarDetalleRosticero($id)
    {
        try {
            $detalle = DatDetalleRosticero::where('IdDatDetalleRosticero', $id)->first();
            $rosticero = DatRosticero::where('IdRosticero', $detalle->IdRosticero)->first();

            DatRosticero::where('IdRosticero', $detalle->IdRosticero)
                ->update([
                    'CantidadVenta' => $rosticero->CantidadVenta - $detalle->Cantidad,
                    'Disponible' => $rosticero->Disponible - $detalle->Cantidad,
                    'MermaReal' => $rosticero->MermaReal + $detalle->Cantidad,
                    'Subir' => 0
                ]);

            DatDetalleRosticero::where('IdDatDetalleRosticero', $id)
                ->update([
                    'Subir' => 0,
                    'Vendida' => 0,
                    'Status' => 0
                ]);

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
                    'Disponible' => $rosticero->Disponible - $detalle->Cantidad,
                    'MermaReal' => $rosticero->MermaReal + $detalle->Cantidad,
                    'Subir' => 0
                ]);

            DatDetalleRosticero::where('IdDatDetalleRosticero', $id)
                ->update([
                    'Subir' => 0,
                    'Vendida' => 0,
                    'Status' => 1
                ]);

            $cantidad = DatDetalleRosticero::where('IdRosticero', $detalle->IdRosticero)
                ->count();

            // return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
            return back()->with(['msjAdd' => 'La sentencia se ejecuto correctamente', 'id' => $cantidad > 0 ? $detalle->IdRosticero : -1]);
        } catch (\Throwable $e) {
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    public function FinalizarRosticero($id)
    {
        try {
            DB::beginTransaction();
            $idTienda = Auth::user()->usuarioTienda->IdTienda;
            $rosticero = DatRosticero::where('IdDatRosticero', $id)->first();
            $idRosticero = $rosticero->IdRosticero;
            $caja = DB::table('DatCajas as a')
                ->where('IdTienda', $idTienda)
                ->where('a.Activa', 0)
                ->where('a.Status', 0)
                ->value('IdCaja');

            // Validamos que el detalle de rosticero no tenga lineas pendientes
            $detalle = DatDetalleRosticero::where('IdRosticero', $idRosticero)
                ->where('Vendida', 1)
                ->first();

            if ($detalle) {
                return back()->with('msjdelete', 'El rostisado tiene lineas pendientes.');
            }

            $mermaRecalentado = DatDetalleRosticero::where('IdRosticero', $idRosticero)
                ->where('Vendida', 0)
                // ->whereNull('FolioMerma')
                ->sum('CantMermaRecalentado');

            // Guardamos la merma del recalentado
            // Guardamos la merma del producto en caso de que exista merma
            if ($mermaRecalentado > 0) {
                $merma = new CapMerma;
                $merma->IdTienda = $idTienda;
                $merma->FechaCaptura = date('d-m-Y H:i:s');
                $merma->CodArticulo = $rosticero->CodigoVenta;
                $merma->CantArticulo = $mermaRecalentado;
                $merma->IdTipoMerma = 3; // Degustacion
                // $merma->IdSubTipoMerma = $subTipoMerma;
                $merma->Comentario = 'MERMA ROSTICERO RECALENTADO';
                $merma->IdUsuarioCaptura = Auth::user()->IdUsuario;
                $merma->IdCaja = $caja;
                $merma->Subir = 0;
                $merma->save();
            }

            DatRosticero::where('IdDatRosticero', $id)
                ->update([
                    'Subir' => 0,
                    'Finalizado' => 1,
                    'FechaFinzalizado' => date("Y-d-m H:i:s"),
                ]);

            DB::commit();
            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    public function RecalentarRosticero(Request $request)
    {
        try {
            $idTienda = Auth::user()->usuarioTienda->IdTienda;
            $codigoEtiquetaAnterior = $request->CodigoEtiquetaAnterior;
            $codigoEtiquetaNueva = $request->CodigoEtiquetaNueva;
            $primerPeso = substr($codigoEtiquetaNueva, 7, 5);
            $peso = $primerPeso / 1000;

            //Validamos que las dos etiquetas pertenescan al mismo produto
            $codEtiquetaAnterior = substr($codigoEtiquetaAnterior, 3, 4);
            $codEtiquetaNueva = substr($codigoEtiquetaNueva, 3, 4);
            if ($codEtiquetaAnterior != $codEtiquetaNueva) {
                return back()->with('msjdelete', 'Las etiquetas no pertenecen al mismo producto');
            }

            $IdDatDetalleRosticero = $request->IdDatDetalleRosticeroAN;

            if (!$IdDatDetalleRosticero)
                $IdDatDetalleRosticero = DatDetalleRosticero::where('CodigoEtiqueta',  $codigoEtiquetaAnterior)
                    ->where('Status', 0)
                    ->where('Vendida', 1)
                    ->value('IdDatDetalleRosticero');

            //Validamos que la nueva etiquera no exista en el detalle
            // $nuevaEtiqueta = DatDetalleRosticero::where('CodigoEtiqueta', $codigoEtiquetaNueva)
            //     ->where('STATUS', 0)
            //     ->where('Vendida', 1)
            //     ->first();

            // if ($nuevaEtiqueta) {
            //     return back()->with('msjdelete', 'La nueva etiqueta ya existe en el detalle');
            // }

            $detalle = DatDetalleRosticero::where('IdDatDetalleRosticero', $IdDatDetalleRosticero)
                ->where('STATUS', 0)
                ->where('Vendida', 1)
                ->first();

            if (empty($detalle)) {
                return back()->with('msjdelete', 'La etiqueta a mermar no se encuenta disponible');
            }

            //Validamos que la nueva cantidad no sea mayor a la cantidad del detalle
            if ($detalle->Cantidad < $peso) {
                return back()->with('msjdelete', 'La nueva cantidad no puede ser mayor a la cantidad del detalle');
            }

            $linea = DatDetalleRosticero::where('IdRosticero', $detalle->IdRosticero)->count() + 1;

            // Agregamos el nuevo detalle del rostisado
            DatDetalleRosticero::create([
                'IdRosticero' => $detalle->IdRosticero,
                'CodigoArticulo' => $detalle->CodigoArticulo,
                'Cantidad' => $peso,
                'FechaCreacion' => date("Y-d-m H:i:s"),
                'subir' => 0,
                'CodigoEtiqueta' => $codigoEtiquetaNueva,
                'STATUS' => 0,
                'Linea' => $linea,
                'Vendida' => 1,
                'CodigoEtiquetaRef' => $detalle->IdDatDetalleRosticero,
                'CantMermaRecalentado ' => $detalle->Cantidad - $peso,
            ]);

            // Actualizamos el rostisado actual a vendido
            // $detalle->CantMermaRecalentado = $detalle->Cantidad - $peso;
            $detalle->subir = 0;
            $detalle->Vendida = 0;
            $detalle->save();

            // Actualizamos el rostisado para que suba el detalle
            // Actualizar en stock total del rosticero
            $rosticero = DatRosticero::where('IdRosticero', $detalle->IdRosticero)->first();
            // $rosticero->CantidadVenta = $rosticero->CantidadVenta - ($detalle->Cantidad - $peso);
            $rosticero->Disponible = $rosticero->Disponible - ($detalle->Cantidad - $peso);
            $rosticero->Subir = 0;
            $rosticero->save();

            // DatRosticero::where('IdRosticero', $detalle->IdRosticero)
            //     ->update([
            //         'Subir' => 0,
            //     ]);

            // Actualizamos el inventario
            // $inventario = InventarioTienda::where('IdTienda', $idTienda)
            //     ->where('CodArticulo', $detalle->CodigoArticulo)
            //     ->first();

            // $inventario->StockArticulo = $inventario->StockArticulo - ($detalle->Cantidad - $peso);
            // $inventario->save();

            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $e) {
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    public function MermarRosticero(Request $request)
    {
        try {
            DB::beginTransaction();
            $idTienda = Auth::user()->usuarioTienda->IdTienda;
            $tipoMerma = $request->tipoMerma;
            $subTipoMerma = $request->get($tipoMerma);
            $CodigoEtiqueta = $request->CodigoEtiqueta;
            $primerPeso = substr($CodigoEtiqueta, 7, 5);
            $peso = $primerPeso / 1000;
            $caja = DB::table('DatCajas as a')
                ->where('IdTienda', $idTienda)
                ->where('a.Activa', 0)
                ->where('a.Status', 0)
                ->value('IdCaja');

            $IdDatDetalleRosticero = $request->IdDatDetalleRosticero;

            if (!$IdDatDetalleRosticero)
                $IdDatDetalleRosticero = DatDetalleRosticero::where('CodigoEtiqueta',  $CodigoEtiqueta)
                    ->where('Status', 0)
                    ->where('Vendida', 1)
                    ->value('IdDatDetalleRosticero');

            //Validamos que la nueva etiquera no exista en el detalle
            $detalle = DatDetalleRosticero::where('IdDatDetalleRosticero',  $IdDatDetalleRosticero)
                ->where('STATUS', 0)
                ->where('Vendida', 1)
                ->first();

            if (!$detalle) {
                return back()->with('msjdelete', 'No existe una etiqueta disponible');
            }

            // Guardamos la merma del producto
            $merma = new CapMerma;
            $merma->IdTienda = $idTienda;
            $merma->FechaCaptura = date('d-m-Y H:i:s');
            $merma->CodArticulo = $detalle->CodigoArticulo;
            $merma->CantArticulo = $peso;
            $merma->IdTipoMerma = $tipoMerma;
            $merma->IdSubTipoMerma = $subTipoMerma;
            $merma->Comentario = 'MERMA ROSTICERO';
            $merma->IdUsuarioCaptura = Auth::user()->IdUsuario;
            $merma->IdCaja = $caja;
            $merma->Subir = 0;
            $merma->save();

            // Actualizamos el rostisado actual a vendido
            $detalle->subir = 0;
            $detalle->Vendida = 0;
            $detalle->FolioMerma = $merma->IdMerma;
            $detalle->save();

            // Actualizamos el rostisado para que suba el detalle
            // Actualizar en stock total del rosticero
            $rosticero = DatRosticero::where('IdRosticero', $detalle->IdRosticero)->first();
            // $rosticero->CantidadVenta = $rosticero->CantidadVenta - $peso;
            $rosticero->Disponible = $rosticero->Disponible - $peso;
            $rosticero->Subir = 0;
            $rosticero->save();

            // Actualizamos el inventario
            $inventario = InventarioTienda::where('IdTienda', $idTienda)
                ->where('CodArticulo', $detalle->CodigoArticulo)
                ->first();

            $inventario->StockArticulo = $inventario->StockArticulo - $peso;
            $inventario->save();

            DB::commit();
            return back()->with('msjAdd', 'La sentencia se ejecuto correctamente');
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $e->getMessage());
        }
    }

    public function HistorialRosticero(Request $request)
    {
        $paginate = $request->input('paginate', 10);
        $fecha1 = $request->input('fecha1');
        $fecha2 = $request->input('fecha2', date('Y-m-d'));

        $rostisados = DatRosticero::select(
            'DatRosticero.*',
            'a.CodArticulo',
            'a.NomArticulo'
        )
            ->with(['Detalle' => function ($query) {
                $query->select('DatDetalleRosticero.*', 'CatArticulos.CodArticulo', 'CatArticulos.NomArticulo');
                $query->orderBy('IdDatDetalleRosticero', 'asc');
            }])
            ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'DatRosticero.CodigoVenta')
            ->whereRaw("cast(Fecha as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
            ->whereNotNull('Finalizado')
            ->orderBy('Finalizado')
            ->orderBy('Fecha', 'desc')
            ->paginate(10)
            ->withQueryString();

        foreach ($rostisados as $rostisado) {
            // Ordenamos el detalle por linea
            $rostisadosDetalle = [];
            foreach ($rostisado->Detalle as $detalle) {
                // Solo agregamos los que no tienen codigo de referecia
                if ($detalle->CodigoEtiquetaRef == null) {
                    $rostisadosDetalle[] = $detalle;
                    $lastDetalle = $detalle;
                    // Buscamos los que tienen dependiente
                    for ($i = 0; $i < count($rostisado->Detalle); $i++) {
                        $d = $rostisado->Detalle[$i];
                        if ($lastDetalle->IdDatDetalleRosticero == $d->CodigoEtiquetaRef) {
                            $rostisadosDetalle[] = $d;
                            $lastDetalle = $d;
                            $i = 0;
                        }
                    }
                }
            }
            $rostisado->newdetalle = $rostisadosDetalle;
        }

        return view('Rosticero.HistorialRosticero', compact('rostisados', 'fecha1', 'fecha2'));
    }
}
