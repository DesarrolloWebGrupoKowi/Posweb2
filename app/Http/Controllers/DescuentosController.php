<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CatTipoDescuento;
use App\Models\DatDetDescuentos;
use App\Models\DatEncDescuentos;
use App\Models\ListaPrecio;
use App\Models\Plaza;
use App\Models\Tienda;

class DescuentosController extends Controller
{
    // ECHO
    public function VerDescuentos(Request $request)
    {
        $nomDescuento = $request->nomDescuento;

        $descuentos = DatEncDescuentos::with(['ArticulosDescuento' => function ($articulos) {
            $articulos->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetDescuentos.IdArticulo');
            $articulos->leftJoin('CatListasPrecio', 'CatListasPrecio.IdListaPrecio', 'DatDetDescuentos.IdListaPrecio');
        }])
            ->select(
                'DatEncDescuentos.*',
                'CatTipoDescuento.NomTipoDescuento',
                'CatTiendas.NomTienda',
                'CatPlazas.NomPlaza'
            )
            ->leftjoin('CatTipoDescuento', 'CatTipoDescuento.IdTipoDescuento', 'DatEncDescuentos.TipoDescuento')
            ->leftjoin('CatPlazas', 'CatPlazas.IdPlaza', 'DatEncDescuentos.IdPlaza')
            ->leftjoin('CatTiendas', 'CatTiendas.IdTienda', 'DatEncDescuentos.IdTienda')
            ->where('NomDescuento', 'like', '%' . $nomDescuento . '%')
            ->where('DatEncDescuentos.Status', 0)
            ->orderBy('DatEncDescuentos.FechaCreacion', 'DESC')
            ->paginate(10);

        $descuentosActivos = DatEncDescuentos::where('Status', 0)
            ->count();

        // return $descuentos;

        return view('Descuentos.VerDescuentos', compact('descuentos', 'nomDescuento', 'descuentosActivos'));
    }

    // ECHO
    public function CatDescuentos(Request $request)
    {
        $nomDescuento = $request->nomDescuento;

        $tiposdescuentos = CatTipoDescuento::where('status', 0)->get();
        $tiendas = Tienda::where('status', 0)->get();
        $plazas = Plaza::where('status', 0)->get();

        return view('Descuentos.CatDescuentos', compact('nomDescuento', 'tiposdescuentos', 'tiendas', 'plazas'));
    }

    public function BuscarCodArticuloPaquqete(Request $request)
    {
        $codArticulo = $request->codArticulo;

        $articulo =  DB::table('CatArticulos as a')
            ->select('a.NomArticulo', 'b.PrecioArticulo')
            ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('a.CodArticulo', $codArticulo)
            ->first();

        $pArticulo = empty($articulo) ? '' : $articulo->NomArticulo . ' - $' . $articulo->PrecioArticulo;

        return $pArticulo;
    }

    // ECHO
    public function GuardarDescuento(Request $request)
    {
        $IdEncDescuento = $request->IdEncDescuento;
        $nomDescuento = $request->nomDescuento;
        try {
            if ($request->fechaInicio > $request->fechaFin) {
                return back()->with('msjdelete', 'La fecha de inicio no puede ser mayor a la fecha de fin.');
            }
            DB::beginTransaction();
            // Comprobamos si trae id de descuento, para saber si es actualizacion o insercion
            if ($IdEncDescuento != null) {
                DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
                    ->update([
                        'NomDescuento' => strtoupper($nomDescuento),
                        'TipoDescuento' => $request->tipoDescuento,
                        'FechaInicio' => $request->fechaInicio,
                        'FechaFin' => $request->fechaFin,
                        'FechaCreacion' => date('d-m-Y H:i:s'),
                        'IdTienda' => $request->idTienda,
                        'IdPlaza' => $request->idPlaza,
                        'Status' => 0
                    ]);
            } else {
                $catDescuento = new DatEncDescuentos();
                $catDescuento->NomDescuento = strtoupper($nomDescuento);
                $catDescuento->TipoDescuento = $request->tipoDescuento;
                $catDescuento->FechaInicio = $request->fechaInicio;
                $catDescuento->FechaFin = $request->fechaFin;
                $catDescuento->FechaCreacion = date('d-m-Y H:i:s');
                $catDescuento->IdTienda = $request->idTienda;
                $catDescuento->IdPlaza = $request->idPlaza;
                $catDescuento->Status = 0;
                $catDescuento->save();
            }
            DB::commit();

            $IdEncDescuento = $IdEncDescuento != null ? $IdEncDescuento : $catDescuento->IdEncDescuento;
            $msg = $IdEncDescuento != null ? 'El descuento quedo actualizado correctamente.' : 'Se Agrego el Descuento: ' . $catDescuento->nomDescuento;

            return redirect('EditarDescuento/' . $IdEncDescuento)->with('msjAdd', $msg);
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error : ' . $th->getMessage());
            DB::rollback();
        }
    }

    // ECHO
    public function EditarDescuento(Request $request, $IdEncDescuento)
    {
        try {
            DB::beginTransaction();

            $descuento = DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
                ->where('status', 0)
                ->whereNull('FechaDesactivar')
                ->first();

            if ($descuento == null) {
                return back()->with('msjdelete', 'No Existe el Descuento con el Id: ' . $IdEncDescuento);
            }

            $detalle = DatDetDescuentos::where('IdEncDescuento', $IdEncDescuento)
                ->leftjoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetDescuentos.IdArticulo')
                ->leftjoin('CatListasPrecio', 'CatListasPrecio.IdListaPrecio', 'DatDetDescuentos.IdListaPrecio')->get();
            $tiposdescuentos = CatTipoDescuento::where('status', 0)->get();
            $tiendas = Tienda::where('status', 0)->get();
            $plazas = Plaza::where('status', 0)->get();
            $ListaPrecio = ListaPrecio::get();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        return view('Descuentos.EditarDescuento', compact('descuento', 'detalle', 'tiposdescuentos', 'tiendas', 'plazas', 'ListaPrecio'));
    }

    // ECHO
    public function EditarDescuentoExistente(Request $request, $idDescuento)
    {
        $codsArticulo = $request->CodArticulo ?? [];
        $listaPrecios = $request->listaPrecios;
        $preciosArticulo = $request->PrecioArticulo;

        try {
            DB::beginTransaction();

            // Obtenemos la fechas del descuento a actualizar
            $descuento = DatEncDescuentos::where('IdEncDescuento', $idDescuento)->first();
            $tipoDescuento = $descuento->TipoDescuento;
            $fechaInicio = $descuento->FechaInicio;
            $fechaFin = $descuento->FechaFin;
            $idTienda = $descuento->IdTienda;

            // Eliminamos el detalle del descuento, para agregarlo de nuevo
            DatDetDescuentos::where('IdEncDescuento', $idDescuento)->delete();

            foreach ($codsArticulo as $key => $codArticulo) {
                $articulo =  DB::table('CatArticulos as a')
                    ->select('a.IdArticulo')
                    ->where('a.CodArticulo', $codArticulo)
                    ->value('a.IdArticulo');

                // Validamos que no exista ese producto en ese tipo de descuento
                $existencias = DatEncDescuentos::select('DatEncDescuentos.*', 'DatDetDescuentos.IdArticulo')
                    ->leftjoin('DatDetDescuentos', 'DatDetDescuentos.IdEncDescuento', 'DatEncDescuentos.IdEncDescuento')
                    ->where('TipoDescuento', $tipoDescuento)
                    ->where('DatEncDescuentos.IdEncDescuento', '<>', $idDescuento)
                    ->where('DatDetDescuentos.IdEncDescuento', '<>', null)
                    ->where('DatEncDescuentos.FechaInicio', '<=', $fechaInicio)
                    ->where('DatEncDescuentos.FechaFin', '>=', $fechaFin)
                    ->where('DatEncDescuentos.IdTienda', $idTienda)
                    ->where('DatDetDescuentos.IdArticulo', $articulo)
                    ->get();

                if (count($existencias) != 0) {
                    DB::rollback();
                    return back()->with('msjdelete', 'El producto ya cuenta con ese tipo de descuento en esas fechas.');
                }

                // Agregamos los productos
                $datDescuento = new DatDetDescuentos();
                $datDescuento->IdEncDescuento = $idDescuento;
                $datDescuento->IdArticulo = $articulo;
                $datDescuento->PrecioDescuento = $preciosArticulo[$key];
                $datDescuento->IdListaPrecio = $listaPrecios[$key];
                $datDescuento->save();
            }

            DB::commit();
            return back()->with('msjAdd', 'Descuento actualizado correctamente!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    // ECHO
    public function EliminarDescuento($IdEncDescuento)
    {
        try {
            $NomDescuento = DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
                ->value('NomDescuento');

            DB::beginTransaction();
            DatEncDescuentos::where('IdEncDescuento', $IdEncDescuento)
                ->update([
                    'FechaDesactivar' => date('d-m-Y H:i:s'),
                    'Status' => 1
                ]);
            DB::commit();

            return back()->with('msjdelete', 'Se Elimino: ' . $NomDescuento);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
