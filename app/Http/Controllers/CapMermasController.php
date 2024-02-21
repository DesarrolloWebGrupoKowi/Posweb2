<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TipoMerma;
use App\Models\SubTipoMerma;
use App\Models\MermaTmp;
use App\Models\CapMerma;
use App\Models\CorreoTienda;
use App\Models\DatArticulosTipoMerma;
use App\Models\HistorialMovimientoProducto;
use App\Models\InventarioTienda;
use App\Models\Tienda;

class CapMermasController extends Controller
{
    public function CapMermas(Request $request)
    {
        $tiposMerma = TipoMerma::where('Status', 0)
            ->get();

        $idTipoMerma = $request->idTipoMerma;

        $subTiposMerma = SubTipoMerma::where('Status', 0)
            ->where('IdTipoMerma', $idTipoMerma)
            ->get();

        $articulosTipoMerma = DB::table('CatArticulos as a')
            ->leftJoin('DatArticulosTipoMerma as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('b.IdTipoMerma', $idTipoMerma)
            ->where('b.Status', 0)
            ->get();

        $tmpMermas = DB::table('MermasTmp as a')
            ->leftJoin('CatArticulos as b', 'b.CodArticulo', 'a.CodArticulo')
            ->leftJoin('CatTiposMerma as c', 'c.IdTipoMerma', 'a.IdTipoMerma')
            ->leftJoin('CatSubTiposMerma as d', 'd.IdSubTipoMerma', 'a.IdSubTipoMerma')
            ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->get();

        return view('CapMermas.CapMermas', compact('tiposMerma', 'subTiposMerma', 'idTipoMerma', 'articulosTipoMerma', 'tmpMermas'));
    }

    public function TmpMermas(Request $request, $idTipoMerma)
    {
        try {

            $codigo = $request->codArticulo;

            $articulo = DatArticulosTipoMerma::where('codArticulo', $codigo)->where('IdTipoMerma', $idTipoMerma)->get();
            if (count($articulo) == 0) {
                return back()->with('msjdelete', 'Error: El articulo no se encuentra para mermar.');
            }

            DB::beginTransaction();

            $idTienda = Auth::user()->usuarioTienda->IdTienda;

            MermaTmp::insert([
                'IdTienda' => $idTienda,
                'CodArticulo' => $request->codArticulo,
                'CantArticulo' => $request->cantArticulo,
                'IdTipoMerma' => $idTipoMerma,
                'IdSubTipoMerma' => $request->idSubTipoMerma,
                'Comentario' => strtoupper($request->comentario)
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back();
    }

    public function GuardarMermas()
    {
        try {
            DB::beginTransaction();
            DB::connection('server')->beginTransaction();

            $idTienda = Auth::user()->usuarioTienda->IdTienda;

            $mermasTmp = MermaTmp::where('IdTienda', $idTienda)
                ->get();

            foreach ($mermasTmp as $key => $merma) {
                CapMerma::insert([
                    'IdTienda' => $idTienda,
                    'FechaCaptura' => date('d-m-Y H:i:s'),
                    'CodArticulo' => $merma->CodArticulo,
                    'CantArticulo' => $merma->CantArticulo,
                    'IdTipoMerma' => $merma->IdTipoMerma,
                    'IdSubTipoMerma' => $merma->IdSubTipoMerma,
                    'Comentario' => $merma->Comentario,
                    'IdUsuarioCaptura' => Auth::user()->IdUsuario
                ]);

                //GUARDAR MOVIMIENTOS EN HISTORIAL MOVIMIENTOS
                HistorialMovimientoProducto::insert([
                    'IdTienda' => $idTienda,
                    'CodArticulo' => $merma->CodArticulo,
                    'CantArticulo' => $merma->CantArticulo,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => 'CAPTURA DE MERMAS',
                    'IdMovimiento' => 8,
                    'IdUsuario' => Auth::user()->IdUsuario
                ]);

                //STOCK DEL ARTICULO
                $stock = InventarioTienda::where('IdTienda', $idTienda)
                    ->where('CodArticulo', $merma->CodArticulo)
                    ->sum('StockArticulo');

                //DESCONTAR PRODUCTO MERMADO DEL INVENTARIO WEB
                DB::connection('server')->table('DatInventario')
                    ->where('IdTienda', $idTienda)
                    ->where('CodArticulo', $merma->CodArticulo)
                    ->update([
                        'StockArticulo' => $stock - $merma->CantArticulo
                    ]);

                //DESCONTAR PRODUCTO MERMADO DEL INVENTARIO LOCAL
                InventarioTienda::where('IdTienda', $idTienda)
                    ->where('CodArticulo', $merma->CodArticulo)
                    ->update([
                        'StockArticulo' => $stock - $merma->CantArticulo
                    ]);
            }

            MermaTmp::where('IdTienda', $idTienda)
                ->delete();

            //ENVIAR CORREO DE MERMA REALIZADA
            try {
                $nomTienda = Tienda::where('IdTienda', $idTienda)
                    ->value('NomTienda');

                $correoTienda = CorreoTienda::where('IdTienda', $idTienda)
                    ->first();

                $asunto = "NUEVA MERMA EN " . $nomTienda . "";
                $mensaje = 'LA TIENDA HA CAPTURADO NUEVA(S) MERMA(S). Usuario: ' . Auth::user()->NomUsuario;

                $enviarCorreo = "Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx; cponce@kowi.com.mx; " . $correoTienda->EncargadoCorreo . "; " . $correoTienda->GerenteCorreo . "; ', '" . $asunto . "', '" . $mensaje . "'";
                DB::connection('server')->statement($enviarCorreo);
            } catch (\Throwable $th) {
            }
        } catch (\Throwable $th) {
            DB::rollback();
            DB::connection('server')->rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        DB::connection('server')->commit();
        return redirect('CapMermas')->with('msjAdd', 'Se Mermó el Producto Correctamente!');
    }

    public function EliminarMermaTmp($idMermaTmp)
    {
        try {
            DB::beginTransaction();

            MermaTmp::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->where('IdTmpMerma', $idMermaTmp)
                ->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjdelete', 'Se eliminó la merma correctamente!');
    }

    public function ReporteMermas(Request $request)
    {
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $agrupadoDia = $request->agrupado;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $mermas = TipoMerma::with(['Mermas' => function ($merma) use ($idTienda, $fecha1, $fecha2) {
            $merma->where('IdTienda', $idTienda)
                ->leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'CapMermas.CodArticulo')
                ->whereRaw("cast(CapMermas.FechaCaptura as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ");
        }])
            ->where('Status', 0)
            ->get();

        //return $mermas;

        return view('CapMermas.ReporteMermas', compact('mermas', 'fecha1', 'fecha2'));
    }
}
