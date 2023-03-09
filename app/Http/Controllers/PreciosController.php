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

    public function ActualizarPrecios(Request $request){
        $codigos = $request->codigos;
        $precios = $request->precios;
        $idListaPrecioHidden = $request->idListaPrecioHidden;
        $radioActualizar = $request->radioActualizar;
        $fechaPara = $request->FechaPara;

        return $precios;

        try {
            DB::beginTransaction();

            if($radioActualizar == 'Ahora'){
                foreach ($precios as $codArticulo => $precioArticulo) {
                    PrecioTmp::where('IdListaPrecio', $idListaPrecioHidden)
                        ->where('CodArticulo', ''. $codArticulo .'')
                        ->update([
                            'PrecioArticulo' => $precioArticulo,
                            'FechaPara' => date('d-m-Y')
                        ]);
                }

                $fechaActual = date('Y-m-d');

                if(HistorialPrecio::all()->isNotEmpty()){
                    DB::table('HistorialPrecios')
                        ->where('Status', 0)
                        ->update([
                            'VigenciaHasta' => $fechaActual,
                            'Status' => 1
                        ]);
                }

                //ENVIAR CORREO DE ACTUALIZACION DE PRECIOS A LAS TIENDAS
                try {
                    //Envio de Correo de Transferencia de Producto
                    $asunto = 'SE HA REALIZADO UNA NUEVA MODIFICACIÓN DE PRECIOS, ACTUALIZAR BASCULAS!';
                    $mensaje = 'FAVOR DE ACTUALIZAR BASCULAS. ACTUALIZACIÓN REALIZADA POR: '. strtoupper(Auth::user()->NomUsuario);
    
                    $enviarCorreo = "Execute SP_ENVIAR_MAIL 'soporte@kowi.com.mx;', '".$asunto."', '".$mensaje."'";
                    DB::statement($enviarCorreo);

                } catch (\Throwable $th) {
                    
                }
            }
            if($radioActualizar == 'FechaPara'){
                foreach ($precios as $codArticulo => $precioArticulo) {
                    PrecioTmp::where('IdListaPrecio', $idListaPrecioHidden)
                        ->where('CodArticulo', ''. $codArticulo .'')
                        ->update([
                           'PrecioArticulo' => $precioArticulo,
                        ]);
                }

                DB::statement("update DatPreciosTmp set FechaPara = '". $fechaPara ."'");

                //ENVIAR CORREO DE ACTUALIZACION DE PRECIOS A LAS TIENDAS
                try {
                    //Envio de Correo de Transferencia de Producto
                    
                    $asunto = 'SE HA REALIZADO UNA NUEVA MODIFICACIÓN DE PRECIOS PROGRAMADA PARA EL DIA: '. strftime('%d de %B del %Y', strtotime($fechaPara));
                    $mensaje = 'MODIFICACIÓN DE PRECIOS REALIZADA POR: '. strtoupper(Auth::user()->NomUsuario);
    
                    $enviarCorreo = "Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx;', '".$asunto."', '".$mensaje."'";
                    DB::statement($enviarCorreo);

                } catch (\Throwable $th) {
                    
                }

            }

            DB::statement("Execute SP_ACTUALIZAR_PRECIOS '". Auth::user()->IdUsuario."'");

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Precios Actualizados!');
    }
}
