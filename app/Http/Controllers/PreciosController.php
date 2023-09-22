<?php

namespace App\Http\Controllers;

use App\Exports\PreciosExport;
use App\Mail\ActualizacionPreciosMail;
use App\Models\Grupo;
use App\Models\HistorialPrecio;
use App\Models\ListaPrecio;
use App\Models\Precio;
use App\Models\PrecioTmp;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mail;

class PreciosController extends Controller
{
    public function Precios(Request $request)
    {
        $listaPrecios = ListaPrecio::where('Status', 0)
            ->get();

        $idListaPrecio = $request->IdListaPrecio;

        $grupos = Grupo::where('Status', 0)
            ->get();

        $idGrupo = $request->IdGrupo;

        $fechaActual = date('Y-m-d');
        $datetime = new DateTime('tomorrow');
        $tomorrow = $datetime->format('Y-m-d');

        $precios = DB::table('CatArticulos as a')
            ->leftJoin('DatPreciosTmp as b', 'a.CodArticulo', 'b.CodArticulo')
            ->where('b.IdlistaPrecio', $idListaPrecio)
            ->where('a.IdGrupo', 'like', '%' . $idGrupo . '%')
            ->where('a.Status', 0)
            ->orderBy('a.CodArticulo')
            ->get();

        //return $precios;

        return view('Precios.Precios', compact('listaPrecios', 'idListaPrecio', 'precios', 'grupos', 'idGrupo', 'tomorrow'));
    }

    public function ActualizarPrecios(Request $request)
    {
        $precios = $request->precios;
        $idListaPrecioHidden = $request->idListaPrecioHidden;
        $radioActualizar = $request->radioActualizar;
        $fechaPara = $request->FechaPara;

        try {
            DB::beginTransaction();

            if ($radioActualizar == 'Ahora') {
                if (HistorialPrecio::all()->isNotEmpty()) {
                    DB::table('HistorialPrecios')
                        ->where('Status', 0)
                        ->update([
                            'VigenciaHasta' => date('d-m-Y'),
                            'Status' => 1,
                        ]);
                }

                foreach ($precios as $codArticulo => $precioArticulo) {
                    PrecioTmp::where('IdListaPrecio', $idListaPrecioHidden)
                        ->where('CodArticulo', '' . $codArticulo . '')
                        ->update([
                            'PrecioArticulo' => $precioArticulo,
                            'FechaPara' => date('d-m-Y'),
                        ]);

                    DB::table('HistorialPrecios')->insert([
                        'IdListaPrecio' => $idListaPrecioHidden,
                        'CodArticulo' => $codArticulo,
                        'PrecioArticulo' => $precioArticulo,
                        'VigenciaDe' => date('d-m-Y'),
                        'VigenciaHasta' => null,
                        'IdUsuario' => Auth::user()->IdUsuario,
                        'FechaCaptura' => date('d-m-Y'),
                        'Status' => 0,
                        'IdDatPrecios' => null,
                    ]);
                }

                // sacar precios que se van a actualizar
                $preciosActualizaados = DB::table('DatPrecios as a')
                    ->select('d.NomListaPrecio', 'a.CodArticulo', 'c.NomArticulo', 'a.PrecioArticulo as PrecioArticuloViejo', 'b.PrecioArticulo as PrecioArticuloNuevo')
                    ->leftJoin('CatArticulos as c', 'c.CodArticulo', 'a.CodArticulo')
                    ->leftJoin('CatListasPrecio as d', 'd.IdListaPrecio', 'a.IdListaPrecio')
                    ->leftJoin(DB::raw('(SELECT CodArticulo, PrecioArticulo FROM DatPreciosTmp WHERE IdListaPrecio = ' . $idListaPrecioHidden . ') as b'),
                        function ($join) {
                            $join->on('a.CodArticulo', '=', 'b.CodArticulo');
                        })
                    ->where('a.IdListaPrecio', $idListaPrecioHidden)
                    ->where('a.PrecioArticulo', '<>', DB::raw('b.PrecioArticulo'))
                    ->orderBy('a.CodArticulo')
                    ->get();

                // actualizar precios
                DB::table('DatPrecios AS A')
                    ->leftJoin(
                        DB::raw('(SELECT CodArticulo, PrecioArticulo FROM DatPreciosTmp
                            WHERE IdListaPrecio = ' . $idListaPrecioHidden . ') AS B'),
                        'A.CodArticulo', '=', 'B.CodArticulo'
                    )
                    ->where('A.IdListaPrecio', '=', $idListaPrecioHidden)
                    ->where('A.PrecioArticulo', '<>', DB::raw('B.PrecioArticulo'))
                    ->update([
                        'A.PrecioArticulo' => DB::raw('B.PrecioArticulo'),
                    ]);
            }
            if ($radioActualizar == 'FechaPara') {
                return 'Function en mantenimiento';
                foreach ($precios as $codArticulo => $precioArticulo) {
                    PrecioTmp::where('IdListaPrecio', $idListaPrecioHidden)
                        ->where('CodArticulo', '' . $codArticulo . '')
                        ->update([
                            'PrecioArticulo' => $precioArticulo,
                        ]);
                }

                DB::statement("update DatPreciosTmp set FechaPara = '" . $fechaPara . "'");

                //ENVIAR CORREO DE ACTUALIZACION DE PRECIOS A LAS TIENDAS
                try {
                    //Envio de Correo de Transferencia de Producto

                    $asunto = 'SE HA REALIZADO UNA NUEVA MODIFICACIÓN DE PRECIOS PROGRAMADA PARA EL DIA: ' . strftime('%d de %B del %Y', strtotime($fechaPara));
                    $mensaje = 'MODIFICACIÓN DE PRECIOS REALIZADA POR: ' . strtoupper(Auth::user()->NomUsuario);

                    $enviarCorreo = "Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx;', '" . $asunto . "', '" . $mensaje . "'";
                    DB::statement($enviarCorreo);

                } catch (\Throwable $th) {

                }

            }

            // enviar correo de actualizacion de precios
            $correos = [
                'sistemas@kowi.com.mx',
                'soporte@kowi.com.mx',
            ];

            Mail::to($correos)
                ->send(new ActualizacionPreciosMail($preciosActualizaados));

            //DB::statement("Execute SP_ACTUALIZAR_PRECIOS '". Auth::user()->IdUsuario."'");

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Precios Actualizados!');
    }

    public function DetallePrecios(Request $request)
    {
        $txtFiltro = $request->get('txtFiltro');
        $precios = Precio::select(
            'DatPrecios.CodArticulo',
            'CatArticulos.NomArticulo',
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 1) Menudeo'),
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 2) Minorista'),
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 3) Detalle'),
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 4) EmpySoc')
        )
            ->leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatPrecios.CodArticulo')
            ->where('CatArticulos.NomArticulo', 'LIKE', '%' . $txtFiltro . '%')
            ->orWhere('CatArticulos.CodArticulo', 'LIKE', '%' . $txtFiltro . '%')
            ->groupBy('DatPrecios.CodArticulo', 'CatArticulos.NomArticulo')
            ->paginate(10)->withQueryString();

        return view('Precios.ListaPrecios', compact('precios', 'txtFiltro'));
    }

    public function ExportExcel(Request $request)
    {
        return Excel::download(new PreciosExport, 'precios.xlsx');
    }
}
