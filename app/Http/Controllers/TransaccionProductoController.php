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
use App\Models\DatCaja;
use App\Models\DatCorteInvTmp;
use App\Models\DatRecepcion;
use App\Models\DatTransferencia;
use App\Models\DatTransferenciaDetalle;
use App\Models\MovimientoProducto;
use App\Models\HistorialMovimientoProducto;

class TransaccionProductoController extends Controller
{
    public function TransaccionProducto(Request $request)
    {
        try {
            DB::beginTransaction();

            $idTienda = Auth::user()->usuarioTienda->IdTienda;

            $nomTienda = Tienda::where('IdTienda', $idTienda)
                ->value('NomTienda');

            $destinosTienda = TransaccionTienda::where('IdTienda', $idTienda)
                ->pluck('IdTiendaDestino');

            $tiendas = Tienda::where('Status', 0)
                ->whereIn('IdTienda', $destinosTienda)
                ->get();
        } catch (\Throwable $th) {
            DB::rollback();
            return 'Error Controlado: ' . $th->getMessage();
        }

        DB::commit();
        return view('TransaccionProducto.TransaccionProducto', compact('nomTienda', 'tiendas'));
    }

    public function BuscarArticuloTransaccion(Request $request)
    {
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

        if (empty($nomArticulo)) {
            return ' - ';
        }
        if ($stockArticulo <= 0) {
            return 1;
        }

        return $nomArticulo . ' - ' . $stockArticulo;
    }

    public function GuardarTransaccion(Request $request)
    {
        $idTiendaDestino = $request->idTiendaDestino;
        $codsArticulo = $request->CodArticulo;
        $idTienda = Auth::user()->usuarioTienda->IdTienda;

        try {
            $almacen = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Almacen');

            $nomDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('NomTienda');

            $correoDestinoTienda = Tienda::where('IdTienda', $idTiendaDestino)
                ->value('Correo');

            $nomOrigenTienda = Tienda::where('IdTienda', $idTienda)
                ->value('NomTienda');

            DB::beginTransaction();

            $idCapRecepcion = DB::table('CapRecepcion')
                ->max('IdCapRecepcion') + 1;

            //Obtener caja
            $idCaja = DatCaja::where('Status', 0)
                ->where('Activa', 0)
                ->where('IdTienda', $idTienda)
                ->value('IdCaja');

            $recepcion = CapRecepcion::create([
                'FechaLlegada' => date('d-m-Y H:i:s'),
                'PackingList' => 'TRANSFERENCIA',
                'IdTiendaOrigen' => Auth::user()->usuarioTienda->IdTienda,
                'IdTiendaDestino' => $idTiendaDestino,
                'idtiporecepcion' => 2,
                'Almacen' => $almacen,
                'IdStatusRecepcion' => 2,
                'IdCajaOrigen' => $idCaja,
            ]);

            // Guardamos la transferencia
            $transferencia = new DatTransferencia();
            $transferencia->IdTransferencia = 0;
            $transferencia->IdCaja = $idCaja;
            $transferencia->IdTiendaOrigen = $idTienda;
            $transferencia->IdTiendaDestino = $idTiendaDestino;
            $transferencia->FechaTransferencia = date('d-m-Y H:i:s');
            $transferencia->IdUsuario = Auth::user()->IdUsuario;
            $transferencia->Subir = 0;
            $transferencia->save();

            $IdCapRecepcionLocal = CapRecepcion::where('IdCapRecepcion', $recepcion->IdCapRecepcion)->value('IdRecepcionLocal');
            $IdTransferencia =  DatTransferencia::where('IdDatTransferencia', $transferencia->IdDatTransferencia)->value('IdTransferencia');

            foreach ($codsArticulo as $keyCodArticulo => $cantArticulo) {
                $keyCodArticulo = trim($keyCodArticulo);
                DatRecepcion::insert([
                    'IdCapRecepcion' => $recepcion->IdCapRecepcion,
                    'IdRecepcionLocal' => $IdCapRecepcionLocal,
                    'CodArticulo' => $keyCodArticulo,
                    'CantEnviada' => $cantArticulo,
                    'IdStatusRecepcion' => 1
                ]);

                DatTransferenciaDetalle::insert([
                    'IdTransferencia' => $IdTransferencia,
                    'CodArticulo' => $keyCodArticulo,
                    'CantidadTrasferencia' => $cantArticulo
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

                $stockArticulo = InventarioTienda::where('IdTienda', $idTienda)
                    ->where('CodArticulo', '' . $keyCodArticulo . '')
                    ->value('StockArticulo');

                if ($stockArticulo < $cantArticulo) {
                    DB::rollback();
                    // DB::rollback();
                    return back()->with('msjdelete', 'No Puede Enviar Más Cantidad del Stock Disponible!');
                }

                InventarioTienda::where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                    ->where('CodArticulo', '' . $keyCodArticulo . '')
                    ->update([
                        'StockArticulo' => $stockArticulo - $cantArticulo
                    ]);

                // $batch = DatCorteInvTmp::select(DB::raw('Max(CAST(Batch AS int)) as batch'))
                //     ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                //     ->value('batch');


                // Ya no se pondra aqui, ahora se hara por los procedimientos almacenados
                // DatCorteInvTmp::insert([
                //     'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                //     'IdCaja' => 1,
                //     'Codigo' => $keyCodArticulo,
                //     'Cantidad' => -$cantArticulo,
                //     'Fecha_Creacion' => date('d-m-Y H:i:s'),
                //     'Batch' => $batch,
                //     'StatusProcesado' => 0,
                //     'IdMovimiento' => 2
                // ]);
            }

            try {
                //Envio de Correo de Transferencia de Producto
                $asunto = 'Se Ha Realizado Una Nueva Transferencia de Producto';
                $mensaje = 'Envia: ' . $nomOrigenTienda . '. Recibe: ' . $nomDestinoTienda . '. Id de Recepción: ' . $recepcion->IdCapRecepcion;

                //$enviarCorreo = "Execute SP_ENVIAR_MAIL 'sistemas@kowi.com.mx; " . $correoDestinoTienda . "', '" . $asunto . "', '" . $mensaje . "'";
                // DB::statement($enviarCorreo);
            } catch (\Throwable $th) {
            }

            DB::commit();
            return back()->with('msjAdd', 'Transferencia Exitosa!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            //return $th;
        }
    }

    public function HistorialTransaccion(Request $request)
    {
        $paginate = $request->input('paginate', 10);
        $fecha1 = $request->input('fecha1');
        $fecha2 = $request->input('fecha2', date('Y-m-d'));

        $transferencias = DatTransferencia::with('Detalle')->select(
            'DatTransferencia.*',
            'a.NomTienda as tiendaOrigen',
            'b.NomTienda as tiendaDestino',
            'c.NomUsuario'
        )
            ->leftjoin('CatTiendas as a', 'a.IdTienda', 'DatTransferencia.IdTiendaOrigen')
            ->leftjoin('CatTiendas as b', 'b.IdTienda', 'DatTransferencia.IdTiendaDestino')
            ->leftjoin('CatUsuarios as c', 'c.IdUsuario', 'DatTransferencia.IdUsuario')
            ->whereRaw("cast(FechaTransferencia as date) between '" . $fecha1 . "' and '" . $fecha2 . "' ")
            ->orderBy('FechaTransferencia', 'DESC')
            ->paginate($paginate)
            ->withQueryString();

        return view('TransaccionProducto.ReporteTransaccionProducto', compact('transferencias', 'fecha1', 'fecha2'));
    }
}
