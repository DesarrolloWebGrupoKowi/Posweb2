<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Articulo;
use App\Models\Tienda;
use App\Models\TransaccionTienda;
use App\Models\InventarioTienda;
use App\Models\CapRecepcion;
use App\Models\DatRecepcion;
use App\Models\MovimientoProducto;
use App\Models\HistorialMovimientoProducto;

class TransaccionProductoController extends Controller
{
    public function TransaccionProducto(Request $request){
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $nomTienda = Tienda::where('IdTienda', $idTienda)
            ->value('NomTienda');

        $destinosTienda = TransaccionTienda::where('IdTienda', $idTienda)
            ->pluck('IdTiendaDestino');

        $tiendas = Tienda::where('Status', 0)
            ->whereIn('IdTienda', $destinosTienda)
            ->get();

        return view('TransaccionProducto.TransaccionProducto', compact('nomTienda', 'tiendas'));
    }

    public function BuscarArticuloTransaccion(Request $request){
        $codArticulo = $request->codArticulo;

        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        $nomArticulo = DB::table('CatArticulos as a')
            ->leftJoin('DatInventario as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('b.IdTienda', $idTienda)
            ->where('a.CodArticulo', $codArticulo)
            ->where('a.Status', 0)
            ->value('a.NomArticulo');

        $stockArticulo = DB::table('CatArticulos as a')
            ->leftJoin('DatInventario as b', 'b.CodArticulo', 'a.CodArticulo')
            ->where('b.IdTienda', $idTienda)
            ->where('a.CodArticulo', $codArticulo)
            ->where('a.Status', 0)
            ->value('b.StockArticulo');

        if(empty($nomArticulo)){
            return ' - ';
        }
        if($stockArticulo <= 0){
            return 1;
        }

        return $nomArticulo . ' - ' . $stockArticulo;
    }

    public function GuardarTransaccion(Request $request){
        $idTiendaDestino = $request->idTiendaDestino;
        $codsArticulo = $request->CodArticulo;
        
        try {
            $almacen = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Almacen');

            $nomDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('NomTienda');

            $correoDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Correo');

            $nomOrigenTienda = Tienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->value('NomTienda');
        
            DB::beginTransaction();
            DB::connection('server')->beginTransaction();

            $capRecepcion = new CapRecepcion();
            $capRecepcion->FechaLlegada = date('d-m-Y H:i:s');
            $capRecepcion->PackingList = 'TRANSFERENCIA';
            $capRecepcion->IdTiendaOrigen = Auth::user()->usuarioTienda->IdTienda;
            $capRecepcion->Almacen = $almacen;
            $capRecepcion->IdStatusRecepcion = 1;
            $capRecepcion->IdUsuario = Auth::user()->IdUsuario;
            $capRecepcion->save();

            foreach ($codsArticulo as $keyCodArticulo => $cantArticulo) {
                DatRecepcion::insert([
                    'IdCapRecepcion' => $capRecepcion->IdCapRecepcion,
                    'CodArticulo' => $keyCodArticulo,
                    'CantEnviada' => $cantArticulo,
                    'IdStatusRecepcion' => 1
                ]);

                HistorialMovimientoProducto::insert([
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'CodArticulo' => $keyCodArticulo,
                    'CantArticulo' => -$cantArticulo,
                    'FechaMovimiento' => date('d-m-Y H:i:s'),
                    'Referencia' => $nomOrigenTienda,
                    'IdMovimiento' => 2,
                    'IdUsuario' => Auth::user()->IdUsuario
                ]);

                $stockArticulo = InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('CodArticulo', ''.$keyCodArticulo.'')
                    ->value('StockArticulo');

                if($stockArticulo < $cantArticulo){
                    DB::rollback();
                    DB::connection('server')->rollback();
                    return back()->with('msjdelete', 'No Puede Enviar Más Cantidad del Stock Disponible!');
                }

                InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('CodArticulo', ''.$keyCodArticulo.'')
                    ->update([
                        'StockArticulo' => $stockArticulo - $cantArticulo
                    ]);
            }

            try {
                //Envio de Correo de Transferencia de Producto
                $asunto = 'Se Ha Realizado Una Nueva Transferencia de Producto';
                $mensaje = 'Envia: ' . $nomOrigenTienda . '. Recibe: ' . $nomDestinoTienda . '. Id de Recepción: ' . $capRecepcion->IdCapRecepcion;

                $enviarCorreo = "Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx; ". $correoDestinoTienda ."', '".$asunto."', '".$mensaje."'";
                DB::statement($enviarCorreo);
            } catch (\Throwable $th) {
                
            }
                
            DB::commit();
            DB::connection('server')->commit();
            return back()->with('msjAdd', 'Transferencia Exitosa!');

        } catch (\Throwable $th) {
            DB::rollback();
            DB::connection('server')->rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            //return $th;
        }
    }
}
