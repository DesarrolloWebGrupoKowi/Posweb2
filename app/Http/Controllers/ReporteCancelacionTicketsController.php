<?php

namespace App\Http\Controllers;

use App\Mail\CancelacionTicketMail;
use App\Models\Articulo;
use App\Models\CorteTienda;
use App\Models\CreditoEmpleado;
use App\Models\DatDetalle;
use App\Models\DatEncabezado;
use App\Models\HistorialMovimientoProducto;
use App\Models\InventarioTienda;
use App\Models\SolicitudCancelacionTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class ReporteCancelacionTicketsController extends Controller
{
    public function SolicitudesCancelacion(Request $request)
    {
        $fecha1 = $request->txtFecha1;
        $fecha2 = $request->txtFecha2;
        empty($fecha1) ? $fecha1 = date('Y-m-d') : $fecha1 = $fecha1;
        empty($fecha2) ? $fecha2 = date('Y-m-d') : $fecha2 = $fecha2;

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
            // ->whereDate('FechaSolicitud', '>=', $fecha)
            ->whereRaw("cast(FechaSolicitud as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            // ->whereNull('SolicitudAprobada')
            // ->whereNull('FechaAprobacion')
            // ->whereNull('IdUsuarioAprobacion')
            ->orderBy('FechaSolicitud', 'DESC')
            ->get();

        $importes = SolicitudCancelacionTicket::select('IdEncabezado')
            ->with([
                'Encabezado' => function ($query) {
                    $query->leftJoin('DatCajas', 'DatCajas.IdDatCajas', 'DatEncabezado.IdDatCaja')
                        ->leftJoin('CatCajas', 'CatCajas.IdCaja', 'DatCajas.IdCaja')
                        ->select(
                            'DatEncabezado.IdEncabezado',
                            'DatEncabezado.ImporteVenta'
                        );
                }
            ])
            // ->whereDate('FechaSolicitud', '>=', $fecha)
            ->whereRaw("cast(FechaSolicitud as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->where('SolicitudAprobada', '0')
            ->groupBy('IdEncabezado')
            ->get();

        $total = 0;
        foreach ($importes as $importe) {
            $total += $importe->encabezado->ImporteVenta;
        }

        return view('ReporteCancelacionTickets.CancelacionTickets', compact('solicitudesCancelacion', 'total', 'fecha1', 'fecha2'));
    }
}
