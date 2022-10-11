<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Precio;
use App\Models\ListaPrecio;
use App\Models\Grupo;
use App\Models\PrecioTmp;
use App\Models\HistorialPrecio;
use Carbon\Carbon;
use DateTime;

class PreciosController extends Controller
{
    public function Precios(Request $request)
    {
        $listaPrecios = ListaPrecio::where('Status', 0)
                        ->get();
        //return $precios;

        $idListaPrecio = $request->IdListaPrecio;
        //return $idListaPrecio;

        $grupos = Grupo::where('Status', 0)
                    ->get();
        //return $grupos;

        $idGrupo = $request->IdGrupo;

        $fechaActual = date('Y-m-d');
        $datetime = new DateTime('tomorrow');
        $tomorrow = $datetime->format('Y-m-d');

        if($idGrupo == 0){
            $idGrupo = '';
        }

        $precios = DB::table('CatArticulos as a')
            ->leftJoin('DatPreciosTmp as b', 'a.CodArticulo', 'b.CodArticulo')
            ->where('b.IdlistaPrecio', $idListaPrecio)
            ->where('a.IdGrupo', 'like', '%'.$idGrupo.'%')
            ->where('a.Status', 0)
            ->orderBy('a.CodArticulo')
            ->get();
        //return $precios;

        return view('Precios.Precios', compact('listaPrecios', 'idListaPrecio', 'precios', 'grupos', 'idGrupo', 'tomorrow'));
    }

    public function ActualizarPrecios(Request $request)
    {
        $codigos = $request->codigos;
        $precios = $request->precios;
        $idListaPrecioHidden = $request->idListaPrecioHidden;
        $radioActualizar = $request->radioActualizar;
        $fechaPara = $request->FechaPara;

        try {
            DB::beginTransaction();

            $preciosTmp = PrecioTmp::all();
            $array = json_decode($preciosTmp);
            //print_r($array);

            if($radioActualizar == 'Ahora'){
                PrecioTmp::truncate();

                foreach($array as $obj){ 
                    $idListaPrecio = $obj->IdListaPrecio;
                    $codArticulo = $obj->CodArticulo; 
                    $precioArticulo = $obj->PrecioArticulo;

                    for ($i=0; $i < count($codigos) ; $i++) {
                        ($codigos[$i] == $codArticulo && $idListaPrecioHidden == $idListaPrecio) ?  $precioArticulo = $precios[$i] : $precioArticulo;
                    }
                
                    DB::statement("Execute SP_INSERTAR_PRECIOS_TEMPORAL ".$idListaPrecio.", '".$codArticulo."', ".$precioArticulo.", '".date('Y-m-d')."'");
                }

                $fechaActual = date('Y-m-d');
                if(HistorialPrecio::all()->isNotEmpty()){
                    DB::table('HistorialPrecios')
                        ->update([
                            'VigenciaHasta' => $fechaActual,
                            'Status' => 1
                        ]);
                }
            }
            if($radioActualizar == 'FechaPara'){
                PrecioTmp::truncate();

                foreach($array as $obj){ 
                    $idListaPrecio = $obj->IdListaPrecio;
                    $codArticulo = $obj->CodArticulo; 
                    $precioArticulo = $obj->PrecioArticulo;

                    for ($i=0; $i < count($codigos) ; $i++) {
                        ($codigos[$i] == $codArticulo && $idListaPrecioHidden == $idListaPrecio) ?  $precioArticulo = $precios[$i] : $precioArticulo;
                    }
                
                    DB::statement("Execute SP_INSERTAR_PRECIOS_TEMPORAL ".$idListaPrecio.", '".$codArticulo."', ".$precioArticulo.", '".$fechaPara."'");
                }
            }

            DB::statement("Execute SP_ACTUALIZAR_PRECIOS '".Auth::user()->IdUsuario."'");

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Precios Actualizados!');
    }
}
