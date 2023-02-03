<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\CancelacionTicketMail;
use App\Models\Tienda;
use App\Models\DatEncabezado;
use App\Models\DatDetalle;
use App\Models\CorteTienda;
use App\Models\CreditoEmpleado;
use App\Models\CorreoTienda;
use App\Models\InventarioTienda;
use App\Models\Articulo;
use App\Models\HistorialMovimientoProducto;
use App\Models\DatCaja;
use App\Models\SolicitudCancelacionTicket;

class CancelacionTicketsController extends Controller
{
    public function CancelacionTickets(Request $request){
        $solicitudesCancelacion = SolicitudCancelacionTicket::with(['Tienda' => function ($query) {
            $query->select('IdTienda', 'NomTienda');
        }, 'Encabezado' => function ($query) {
            $query->leftJoin('DatCajas', 'DatCajas.IdDatCajas', 'DatEncabezado.IdDatCaja')
                ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
                ->select('DatEncabezado.IdEncabezado', 'CatCajas.NumCaja', 'DatEncabezado.IdDatCaja', 'DatEncabezado.IdTicket', 
                    'DatEncabezado.FechaVenta', 'DatEncabezado.ImporteVenta');
        }, 'Detalle' => function ($query) {
            $query->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                ->select('DatDetalle.IdEncabezado', 'DatDetalle.IdArticulo', 'CatArticulos.CodArticulo', 'CatArticulos.NomArticulo', 
                    'DatDetalle.CantArticulo', 'DatDetalle.PrecioArticulo', 'DatDetalle.IvaArticulo', 
                    'DatDetalle.SubTotalArticulo', 'DatDetalle.ImporteArticulo', 'DatDetalle.IdPaquete', 
                    'DatDetalle.IdPedido', 'CatPaquetes.NomPaquete', 'DatEncPedido.Cliente');
        }])
            ->whereNull('SolicitudAprobada')
            ->whereNull('FechaAprobacion')
            ->whereNull('IdUsuarioAprobacion')
            ->get();

        //return $solicitudesCancelacion;

        return view('CancelacionTickets.CancelacionTickets', compact('solicitudesCancelacion'));
    }

    public function CancelarTicket(Request $request, $idEncabezado){
        try {
            DB::beginTransaction();

            $motivoCancelacion = $request->motivoCancelacion;

            $solicitudCancelacion = SolicitudCancelacionTicket::with(['Tienda' => function ($query) {
                $query->select('IdTienda', 'NomTienda');
            }, 'Encabezado' => function ($query) {
                $query->leftJoin('DatCajas', 'DatCajas.IdDatCajas', 'DatEncabezado.IdDatCaja')
                    ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
                    ->select('DatEncabezado.IdEncabezado', 'CatCajas.NumCaja', 'DatEncabezado.IdDatCaja', 'DatEncabezado.IdTicket', 
                        'DatEncabezado.FechaVenta', 'DatEncabezado.ImporteVenta');
            }, 'Detalle' => function ($query) {
                $query->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                    ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                    ->select('DatDetalle.IdEncabezado', 'DatDetalle.IdArticulo', 'CatArticulos.CodArticulo', 'CatArticulos.NomArticulo', 
                        'DatDetalle.CantArticulo', 'DatDetalle.PrecioArticulo', 'DatDetalle.IvaArticulo', 
                        'DatDetalle.SubTotalArticulo', 'DatDetalle.ImporteArticulo', 'DatDetalle.IdPaquete', 
                        'DatDetalle.IdPedido', 'CatPaquetes.NomPaquete', 'DatEncPedido.Cliente');
            }])
                ->whereNull('SolicitudAprobada')
                ->whereNull('FechaAprobacion')
                ->whereNull('IdUsuarioAprobacion')
                ->where('IdEncabezado', $idEncabezado)
                ->first();

            // enviar correo de aprobacion de solicitud de cancelacion de ticket
            $correos = [
                'soporte@kowi.com.mx',
                'sistemas@kowi.com.mx'
            ];

            Mail::to($correos)
                ->send(new CancelacionTicketMail($solicitudCancelacion));
            
            return 'menito';

            DatEncabezado::where('IdEncabezado', $idEncabezado)
                ->update([
                    'StatusVenta' => 1,
                    'IdUsuarioCancelacion' => Auth::user()->IdUsuario,
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

                // revisar si la venta genero monedero electronico, para cancelarlo **

            }

            //DEVOLVER INVENTARIO DEL TICKET CANCELADO
            $detalleVenta = DatDetalle::where('IdEncabezado', $idEncabezado)
                ->get();

            foreach ($detalleVenta as $key => $detalle) {
                $codArticulo = Articulo::where('IdArticulo', $detalle->IdArticulo)
                    ->value('CodArticulo');

                $stock = InventarioTienda::where('IdTienda', $idTienda)
                    ->where('CodArticulo', $codArticulo)
                    ->value('StockArticulo');

                InventarioTienda::where('IdTienda', $idTienda)
                    ->where('CodArticulo', $codArticulo)
                    ->update([
                        'StockArticulo' => $stock + $detalle->CantArticulo
                    ]);

            }

            //INSERTAR EN HISTORIAL MOVIMIENTOS PRODUCTO
            HistorialMovimientoProducto::insert([
                'IdTienda' => $idTienda,
                'FechaMovimiento' => date('d-m-Y H:i:s'),
                'Referencia' => $idEncabezado,
                'IdMovimiento' => 12,
                'IdUsuario' => Auth::user()->IdUsuario
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se Canceló Correctamente el Ticket!');
    }

    public function SolicitudCancelacionTicket(Request $request){
        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        $idTicket = $request->idTicket;

        $ticket = DatEncabezado::with(['detalle' => function ($join) {
            $join->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete');
        }, 'TipoPago'])
            ->where('IdTicket', $idTicket)
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', date('d-m-Y'))
            ->first();

        $ticketEncontrado = (empty($ticket)) ? 'no' : 'si'; 

        $ticketConSolicitud = 'no';
        if(!empty($ticket->IdEncabezado)){
            if(SolicitudCancelacionTicket::where('IdEncabezado', $ticket->IdEncabezado)->exists()){
                $ticketConSolicitud = 'si';
            }
        }

        //return $ticketConSolicitud;

        return view('CancelacionTickets.SolicitudCancelacionTicket', compact('idTicket', 'ticket', 'ticketEncontrado', 'ticketConSolicitud'));
    }
    
    public function SolicitarCancelacion($idEncabezado, Request $request){
        try {
            DB::connection('server')->getPDO(); // revisar conexion al server

            DB::beginTransaction();
            DB::connection('server')->beginTransaction();

            SolicitudCancelacionTicket::insert([
                'FechaSolicitud' => date('d-m-Y H:i:s'),
                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                'IdEncabezado' => $idEncabezado,
                'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                'MotivoCancelacion' => mb_strtoupper($request->motivoCancelacion, 'UTF-8'),
                'Status' => 0
            ]);

            DB::connection('server')->table('SolicitudCancelacionTicket')
                ->insert([
                    'FechaSolicitud' => date('d-m-Y H:i:s'),
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'IdEncabezado' => $idEncabezado,
                    'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                    'MotivoCancelacion' => mb_strtoupper($request->motivoCancelacion, 'UTF-8'),
                    'Status' => 0
                ]);

        } catch (\Throwable $th) {
            DB::rollback();
            DB::connection('server')->rollback();

            try {
                DB::beginTransaction();

                SolicitudCancelacionTicket::insert([
                    'FechaSolicitud' => date('d-m-Y H:i:s'),
                    'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                    'IdEncabezado' => $idEncabezado,
                    'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                    'MotivoCancelacion' => mb_strtoupper($request->motivoCancelacion, 'UTF-8'),
                    'Status' => 0
                ]);

            } catch (\Throwable $th) {
                DB::rollback();
                return back()->with('msjdelete', 'Error: ' . $th->getMessage());
            }

            DB::commit();
            return redirect('SolicitudCancelacionTicket')->with('msjAdd', 'Solicitud de cancelación de ticket, guardada!');
        }

        DB::commit();
        DB::connection('server')->commit();

        return back()->with('msjAdd', 'La solicitud de cancelación de tickete, se realizó correctamente');
    }
}