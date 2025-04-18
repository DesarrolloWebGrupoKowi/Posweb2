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
            ->when(isset($fecha1) && !isset($fecha2), function ($q) use ($fecha1) {
                return $q->whereDate('FechaSolicitud', '>=', $fecha1);
            })
            ->when(!isset($fecha1) && isset($fecha2), function ($q) use ($fecha2) {
                return $q->whereDate('FechaSolicitud', '<=', $fecha2);
            })
            ->when(isset($fecha1) && isset($fecha2), function ($q) use ($fecha1, $fecha2) {
                return $q->whereRaw("cast(FechaSolicitud as date) between '" . $fecha1 . "' and '" . $fecha2 . "'");
            })
            // ->whereRaw("cast(FechaSolicitud as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            // ->whereDate('FechaSolicitud', '>=', $fecha)
            // ->whereNull('SolicitudAprobada')
            // ->whereNull('FechaAprobacion')
            // ->whereNull('IdUsuarioAprobacion')
            ->orderBy('FechaSolicitud', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return view('ReporteCancelacionTickets.CancelacionTickets', compact('solicitudesCancelacion', 'fecha1', 'fecha2'));
    }
}
