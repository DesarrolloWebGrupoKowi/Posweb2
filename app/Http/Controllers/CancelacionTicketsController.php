<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tienda;
use App\Models\DatEncabezado;
use App\Models\DatDetalle;
use App\Models\CorteTienda;
use App\Models\CreditoEmpleado;
use App\Models\CorreoTienda;

class CancelacionTicketsController extends Controller
{
    public function CancelacionTickets(Request $request){
        $idTienda = $request->idTienda;
        $fechaVenta = $request->fechaVenta;
        $numTicket = $request->numTicket;

        try {
            DB::beginTransaction();

            $tiendas = Tienda::where('Status', 0)
                ->get();
            
            $tickets = DatEncabezado::with(['detalle' => function ($detalle){
                $detalle->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                    ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido');
            }, 'Tienda'])
                ->where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fechaVenta)
                ->where('IdTicket', $numTicket)
                ->get();

            //return $tickets;

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' .  $th->getMessage());
        }

        DB::commit();
        return view('CancelacionTickets.CancelacionTickets', compact('idTienda', 'fechaVenta', 'numTicket', 'tiendas', 'tickets'));
    }

    public function CancelarTicket(Request $request, $idTienda, $fechaVenta, $numTicket){
        try {
            DB::beginTransaction();

            $motivoCancelacion = $request->motivoCancelacion;

            $idEncabezado = DatEncabezado::where('IdTienda', $idTienda)
                ->whereDate('FechaVenta', $fechaVenta)
                ->where('IdTicket', $numTicket)
                ->value('IdEncabezado');

            DatEncabezado::where('IdEncabezado', $idEncabezado)
                ->update([
                    'StatusVenta' => 1,
                    'MotivoCancel' => $motivoCancelacion,
                    'FechaCancelacion' => date('d-m-Y H:i:s')
                ]);

            CorteTienda::where('IdEncabezado', $idEncabezado)
                ->update([
                    'StatusVenta' => 1
                ]);

            if(CreditoEmpleado::where('IdEncabezado', $idEncabezado)->exists()){
                CreditoEmpleado::where('IdEncabezado', $idEncabezado)
                    ->update([
                        'StatusVenta' => 1
                    ]);
            }

            $nomTienda = Tienda::where('IdTienda', $idTienda)
                ->value('NomTienda');

            $correoTienda = Tienda::where('IdTienda', $idTienda)
                ->value('Correo');

            $correosTienda = CorreoTienda::where('IdTienda', $idTienda)
                ->first();

            //Enviar Correo de Cancelacion de Ticket
            $asunto = ':: POSWEB2 PRUEBA :: NUEVA CANCELACIÓN DE TICKET EN ' . $nomTienda;

            $mensaje = 'Ticket #' . $numTicket . ' del dia: ' . strftime('%d %B %Y', strtotime($fechaVenta)) . '. Motivo: ' . $motivoCancelacion
            . '. Cancelado Por: ' . strtoupper(Auth::user()->NomUsuario);

            $correos = "sistemas@kowi.com.mx; cponce@kowi.com.mx; ". $correoTienda .'; ' . $correosTienda->EncargadoCorreo . '; ' . 
            $correosTienda->GerenteCorreo . '; ' . $correosTienda->SupervisorCorreo . '; ' . $correosTienda->AdministrativaCorreo . '; ' . 
            $correosTienda->FacturistaCorreo . '; ' . Auth::user()->Correo .";";

            $enviarCorreo = "Execute SP_ENVIAR_MAIL '". $correos ."', '".$asunto."', '".$mensaje."'";

            DB::statement($enviarCorreo);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th);
        }

        DB::commit();
        return back()->with('msjAdd', 'Se Elimino Correctamente el Ticket!');
    }
}