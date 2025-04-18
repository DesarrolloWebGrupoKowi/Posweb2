<?php

namespace App\Http\Controllers;

use App\Mail\CancelacionTicketMail;
use App\Models\Articulo;
use App\Models\Caja;
use App\Models\CorteTienda;
use App\Models\CreditoEmpleado;
use App\Models\DatCaja;
use App\Models\DatCorteInvTmp;
use App\Models\DatDetalle;
use App\Models\DatEncabezado;
use App\Models\DatMonederoElectronico;
use App\Models\HistorialMovimientoProducto;
use App\Models\InventarioTienda;
use App\Models\SolicitudCancelacionTicket;
use App\Models\Tienda;
use App\Models\VentaCreditoEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class CancelacionTicketsController extends Controller
{
    public function CancelacionTickets(Request $request)
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if (!$usuarioTienda) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        if ($usuarioTienda->Todas == 0) {
            $tiendas = Tienda::where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas = Tienda::where('Status', 0)
                ->where('IdTienda', $usuarioTienda->IdTienda)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)
                ->where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }

        $idTienda = $request->idTienda;

        $ids = [];
        foreach ($tiendas as $tienda) {
            array_push($ids, $tienda->IdTienda);
        }

        $solicitudesCancelacion = SolicitudCancelacionTicket::with([
            'Tienda' => function ($query) {
                $query->select('IdTienda', 'NomTienda');
            },
            'Encabezado' => function ($query) {
                $query->leftJoin('DatCajas', 'DatCajas.IdDatCajas', 'DatEncabezado.IdDatCaja')
                    ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
                    ->select(
                        'DatEncabezado.IdEncabezado',
                        'CatCajas.NumCaja',
                        'DatEncabezado.IdDatCaja',
                        'DatEncabezado.IdTicket',
                        'DatEncabezado.FechaVenta',
                        'DatEncabezado.ImporteVenta'
                    );
            },
            'Detalle' => function ($query) {
                $query->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                    ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                    ->select(
                        'DatDetalle.IdEncabezado',
                        'DatDetalle.IdArticulo',
                        'CatArticulos.CodArticulo',
                        'CatArticulos.NomArticulo',
                        'DatDetalle.CantArticulo',
                        'DatDetalle.PrecioArticulo',
                        'DatDetalle.IvaArticulo',
                        'DatDetalle.SubTotalArticulo',
                        'DatDetalle.ImporteArticulo',
                        'DatDetalle.IdPaquete',
                        'DatDetalle.IdPedido',
                        'CatPaquetes.NomPaquete',
                        'DatEncPedido.Cliente'
                    );
            },
        ])
            ->whereIn('IdTienda', $ids)
            ->where('IdTienda', 'LIKE', $idTienda)
            ->whereNull('SolicitudAprobada')
            ->whereNull('FechaAprobacion')
            ->whereNull('IdUsuarioAprobacion')
            ->paginate(10);

        //        return $solicitudesCancelacion;

        return view('CancelacionTickets.CancelacionTickets', compact('solicitudesCancelacion', 'tiendas', 'idTienda'));
    }

    public function CancelarTicket(Request $request, $idEncabezado)
    {
        try {

            DB::beginTransaction();

            $motivoCancelacion = $request->motivoCancelacion;

            // todo el detail del ticket a cancelar, se usa para el envio del correo
            $solicitudCancelacion = SolicitudCancelacionTicket::with([
                'Tienda' => function ($query) {
                    $query->select('IdTienda', 'NomTienda');
                },
                'Encabezado' => function ($query) {
                    $query->leftJoin('DatCajas', 'DatCajas.IdDatCajas', 'DatEncabezado.IdDatCaja')
                        ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
                        ->select(
                            'DatEncabezado.IdEncabezado',
                            'CatCajas.NumCaja',
                            'DatEncabezado.IdDatCaja',
                            'DatEncabezado.IdTicket',
                            'DatEncabezado.FechaVenta',
                            'DatEncabezado.ImporteVenta'
                        );
                },
                'Detalle' => function ($query) {
                    $query->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                        ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                        ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                        ->select(
                            'DatDetalle.IdEncabezado',
                            'DatDetalle.IdArticulo',
                            'CatArticulos.CodArticulo',
                            'CatArticulos.NomArticulo',
                            'DatDetalle.CantArticulo',
                            'DatDetalle.PrecioArticulo',
                            'DatDetalle.IvaArticulo',
                            'DatDetalle.SubTotalArticulo',
                            'DatDetalle.ImporteArticulo',
                            'DatDetalle.IdPaquete',
                            'DatDetalle.IdPedido',
                            'CatPaquetes.NomPaquete',
                            'DatEncPedido.Cliente'
                        );
                },
            ])
                ->whereNull('SolicitudAprobada')
                ->whereNull('FechaAprobacion')
                ->whereNull('IdUsuarioAprobacion')
                ->where('IdEncabezado', $idEncabezado)
                ->first();

            $idTienda = $solicitudCancelacion->IdTienda;
            $idCaja = $solicitudCancelacion->IdCaja;

            // Actualizamos la solicitud de ticked
            SolicitudCancelacionTicket::where('IdEncabezado', $idEncabezado)
                ->update([
                    'SolicitudAprobada' => 0,
                    'FechaAprobacion' => date('d-m-Y H:i:s'),
                    'IdUsuarioAprobacion' => Auth::user()->IdUsuario,
                    'descargar' => 0,
                ]);

            // enviar correo de aprobacion de solicitud de cancelacion de ticket
            // -> bajar function antes del commit para comprobar que todo salio bien
            $correos = [
                'soporte@kowi.com.mx',
                'sistemas@kowi.com.mx',
            ];

            Mail::to($correos)
                ->send(new CancelacionTicketMail($solicitudCancelacion));

            //return 'menito';

            DatEncabezado::where('IdEncabezado', $idEncabezado)
                ->update([
                    'StatusVenta' => 1,
                    'IdUsuarioCancelacion' => Auth::user()->IdUsuario,
                    'MotivoCancel' => $motivoCancelacion,
                    'FechaCancelacion' => date('d-m-Y H:i:s'),
                ]);

            CorteTienda::where('IdEncabezado', $idEncabezado)
                ->update([
                    'StatusVenta' => 1,
                ]);

            if (VentaCreditoEmpleado::where('IdEncabezado', $idEncabezado)->exists()) {
                // cancelar el credito del empleado del concentrado de creditos (DatConcenVenta) **
                VentaCreditoEmpleado::where('IdEncabezado', $idEncabezado)
                    ->delete();
            }

            if (CreditoEmpleado::where('IdEncabezado', $idEncabezado)->exists()) {
                CreditoEmpleado::where('IdEncabezado', $idEncabezado)
                    ->update([
                        'StatusVenta' => 1,
                    ]);

                // revisar si la venta genero monedero electronico, para cancelarlo **
                $monederoGenerado = DatMonederoElectronico::where('IdEncabezado', $idEncabezado)
                    ->sum('Monedero');

                $fechaExpiracion = DatMonederoElectronico::where('IdEncabezado', $idEncabezado)
                    ->value('FechaExpiracion');

                DatMonederoElectronico::insert([]);
            }

            // devolver inventario del ticket cancelado
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
                        'StockArticulo' => $stock + $detalle->CantArticulo,
                    ]);
            }

            // insertar en el historial de movimientos de producto, con referencia del IdEncabezado
            DatCorteInvTmp::insert([
                'IdTienda' => $idTienda,
                'IdCaja' => $idCaja,
                'Codigo' => $codArticulo,
                'Cantidad' => $detalle->CantArticulo,
                'Fecha_Creacion' => date('d-m-Y H:i:s'),
                'StatusProcesado' => 1,
                'IdMovimiento' => 12,
                'Referencia' => $idEncabezado
            ]);

            HistorialMovimientoProducto::insert([
                'IdTienda' => $idTienda,
                'CodArticulo' => $codArticulo,
                'CantArticulo' => $detalle->CantArticulo,
                'FechaMovimiento' => date('d-m-Y H:i:s'),
                'Referencia' => 'Cancelacion ' . $idEncabezado,
                'IdMovimiento' => 12,
                'IdUsuario' => Auth::user()->IdUsuario,
                'ReferenciaId' => $idEncabezado,
                'IDCAJA' => $idCaja
            ]);
        } catch (\Throwable $th) {
            DB::rollback(); // hubo algun error
            return back()->with('msjdelete', 'Error: ' . $th->getMessage()); // me devuelvo con el mensaje de error
        }

        DB::commit(); // todo salio bien
        return back()->with('msjAdd', 'Se Canceló Correctamente el Ticket!'); // me dvuelvo con el mensaje de éxito
    }

    public function CancelarCancelarTicket(Request $request, $idEncabezado)
    {
        try {

            DB::beginTransaction();

            // todo el detail del ticket a cancelar, se usa para el envio del correo
            $solicitudCancelacion = SolicitudCancelacionTicket::with([
                'Tienda' => function ($query) {
                    $query->select('IdTienda', 'NomTienda');
                },
                'Encabezado' => function ($query) {
                    $query->leftJoin('DatCajas', 'DatCajas.IdDatCajas', 'DatEncabezado.IdDatCaja')
                        ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
                        ->select(
                            'DatEncabezado.IdEncabezado',
                            'CatCajas.NumCaja',
                            'DatEncabezado.IdDatCaja',
                            'DatEncabezado.IdTicket',
                            'DatEncabezado.FechaVenta',
                            'DatEncabezado.ImporteVenta'
                        );
                },
                'Detalle' => function ($query) {
                    $query->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                        ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                        ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                        ->select(
                            'DatDetalle.IdEncabezado',
                            'DatDetalle.IdArticulo',
                            'CatArticulos.CodArticulo',
                            'CatArticulos.NomArticulo',
                            'DatDetalle.CantArticulo',
                            'DatDetalle.PrecioArticulo',
                            'DatDetalle.IvaArticulo',
                            'DatDetalle.SubTotalArticulo',
                            'DatDetalle.ImporteArticulo',
                            'DatDetalle.IdPaquete',
                            'DatDetalle.IdPedido',
                            'CatPaquetes.NomPaquete',
                            'DatEncPedido.Cliente'
                        );
                },
            ])
                ->whereNull('SolicitudAprobada')
                ->whereNull('FechaAprobacion')
                ->whereNull('IdUsuarioAprobacion')
                ->where('IdEncabezado', $idEncabezado)
                ->first();

            // Actualizamos la solicitud de ticked
            SolicitudCancelacionTicket::where('IdEncabezado', $idEncabezado)
                ->update([
                    'SolicitudAprobada' => 1,
                    'FechaAprobacion' => date('d-m-Y H:i:s'),
                    'IdUsuarioAprobacion' => Auth::user()->IdUsuario,
                    'descargar' => 0,
                ]);

            // enviar correo de aprobacion de solicitud de cancelacion de ticket
            // -> bajar function antes del commit para comprobar que todo salio bien
            $correos = [
                'soporte@kowi.com.mx',
                'sistemas@kowi.com.mx',
            ];

            Mail::to($correos)
                ->send(new CancelacionTicketMail($solicitudCancelacion));
        } catch (\Throwable $th) {
            DB::rollback(); // hubo algun error
            return back()->with('msjdelete', 'Error: ' . $th->getMessage()); // me devuelvo con el mensaje de error
        }

        DB::commit(); // todo salio bien
        return back()->with('msjAdd', 'Se Canceló Correctamente el Ticket!'); // me dvuelvo con el mensaje de éxito
    }

    public function SolicitudCancelacionTicket(Request $request)
    {
        $idTienda = Auth::user()->usuarioTienda->IdTienda;
        $idTicket = $request->idTicket;

        $ticket = DatEncabezado::with([
            'detalle' => function ($join) {
                $join->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                    ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete');
            },
            'TipoPago',
            'SolicitudCancelacionTicket',
        ])
            ->where('IdTicket', $idTicket)
            ->where('IdTienda', $idTienda)
            ->whereDate('FechaVenta', date('d-m-Y'))
            ->first();

        $ticketEncontrado = (empty($ticket)) ? 'no' : 'si';

        $ticketConSolicitud = 'no';
        if (!empty($ticket->IdEncabezado)) {
            if (SolicitudCancelacionTicket::where('IdEncabezado', $ticket->IdEncabezado)->exists()) {
                $ticketConSolicitud = 'si';
            }
        }

        //return $ticketConSolicitud;

        return view('CancelacionTickets.SolicitudCancelacionTicket', compact('idTicket', 'ticket', 'ticketEncontrado', 'ticketConSolicitud'));
    }

    public function SolicitarCancelacion($idEncabezado, Request $request)
    {
        // try {
        //     DB::connection('server')->getPDO(); // revisar conexion al server ?

        //     DB::beginTransaction();
        //     DB::connection('server')->beginTransaction();

        //     SolicitudCancelacionTicket::insert([
        //         'FechaSolicitud' => date('d-m-Y H:i:s'),
        //         'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
        //         'IdEncabezado' => $idEncabezado,
        //         'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
        //         'MotivoCancelacion' => mb_strtoupper($request->motivoCancelacion, 'UTF-8'),
        //         'Status' => 0,
        //     ]);

        //     DB::connection('server')->table('SolicitudCancelacionTicket')
        //         ->insert([
        //             'FechaSolicitud' => date('d-m-Y H:i:s'),
        //             'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
        //             'IdEncabezado' => $idEncabezado,
        //             'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
        //             'MotivoCancelacion' => mb_strtoupper($request->motivoCancelacion, 'UTF-8'),
        //             'Status' => 0,
        //         ]);

        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     DB::connection('server')->rollback();



        //     DB::commit();
        //     return redirect('SolicitudCancelacionTicket')->with('msjAdd', 'Solicitud de cancelación de ticket, guardada!');
        // }

        $idCaja = DatCaja::where('Activa', 0)
            ->where('Status', 0)
            ->value('IdCaja');

        try {
            DB::beginTransaction();

            SolicitudCancelacionTicket::insert([
                'FechaSolicitud' => date('d-m-Y H:i:s'),
                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                'IdCaja' => $idCaja,
                'IdEncabezado' => $idEncabezado,
                'IdUsuarioSolicitud' => Auth::user()->IdUsuario,
                'MotivoCancelacion' => mb_strtoupper($request->motivoCancelacion, 'UTF-8'),
                'Status' => 0,
                'subir' => 0,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();

        return back()->with('msjAdd', 'La solicitud de cancelación de tickete, se realizó correctamente');
    }

    public function HistorialCancelacionTickets(Request $request)
    {
        $usuarioTienda = Auth::user()->usuarioTienda;

        if ($usuarioTienda->doesntExist()) {
            return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
        }

        if ($usuarioTienda->Todas == 0) {
            $tiendas = Tienda::where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdTienda)) {
            $tiendas = Tienda::where('Status', 0)
                ->where('IdTienda', $usuarioTienda->IdTienda)
                ->orderBy('IdTienda')
                ->get();
        }
        if (!empty($usuarioTienda->IdPlaza)) {
            $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)
                ->where('Status', 0)
                ->orderBy('IdTienda')
                ->get();
        }

        $idTienda = $request->idTienda;

        $ids = [];
        foreach ($tiendas as $tienda) {
            array_push($ids, $tienda->IdTienda);
        }

        $solicitudesCancelacion = SolicitudCancelacionTicket::with([
            'Tienda' => function ($query) {
                $query->select('IdTienda', 'NomTienda');
            },
            'Encabezado' => function ($query) {
                $query->leftJoin('DatCajas', 'DatCajas.IdDatCajas', 'DatEncabezado.IdDatCaja')
                    ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
                    ->select(
                        'DatEncabezado.IdEncabezado',
                        'CatCajas.NumCaja',
                        'DatEncabezado.IdDatCaja',
                        'DatEncabezado.IdTicket',
                        'DatEncabezado.FechaVenta',
                        'DatEncabezado.ImporteVenta'
                    );
            },
            'Detalle' => function ($query) {
                $query->leftJoin('CatArticulos', 'CatArticulos.IdArticulo', 'DatDetalle.IdArticulo')
                    ->leftJoin('CatPaquetes', 'CatPaquetes.IdPaquete', 'DatDetalle.IdPaquete')
                    ->leftJoin('DatEncPedido', 'DatEncPedido.IdPedido', 'DatDetalle.IdPedido')
                    ->select(
                        'DatDetalle.IdEncabezado',
                        'DatDetalle.IdArticulo',
                        'CatArticulos.CodArticulo',
                        'CatArticulos.NomArticulo',
                        'DatDetalle.CantArticulo',
                        'DatDetalle.PrecioArticulo',
                        'DatDetalle.IvaArticulo',
                        'DatDetalle.SubTotalArticulo',
                        'DatDetalle.ImporteArticulo',
                        'DatDetalle.IdPaquete',
                        'DatDetalle.IdPedido',
                        'CatPaquetes.NomPaquete',
                        'DatEncPedido.Cliente'
                    );
            },
        ])
            ->whereIn('IdTienda', $ids)
            ->where('IdTienda', 'LIKE', $idTienda)
            ->whereNotNull('SolicitudAprobada')
            ->orderBy('FechaSolicitud', 'DESC')
            ->paginate(10);

        //        return $solicitudesCancelacion;

        return view('CancelacionTickets.HistorialCancelacionTickets', compact('solicitudesCancelacion', 'tiendas', 'idTienda'));
    }
}
