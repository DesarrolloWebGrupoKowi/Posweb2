<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudFactura;
use App\Models\CustomerCloud;
use App\Models\MovimientoClienteCloud;
use App\Models\NotificacionClienteCloud;
use App\Models\CorteTienda;
use App\Models\ConstanciaSituacionFiscal;
use App\Models\Tienda;

class LigarClientesController extends Controller
{
    public function ClientesNuevos(Request $request)
    {
        // $sqlSelect =" select a.*, f.IdTicket, c.NomTipoPago, e.NomBanco, d.NumTarjeta, d.Pago, h.NomCFDI, g.NomTienda, j.NomConstancia".
        //             " from SolicitudFactura as a".
        //             " left join DatCortesTienda as b on b.IdSolicitudFactura=a.IdSolicitudFactura".
        //             " left join CatTipoPago as c on c.IdTipoPago=b.IdTipoPago".
        //             " left join DatTipoPago as d on d.IdTipoPago=b.IdTipoPago".
        //             " left join CatBancos as e on e.IdBanco=d.IdBanco".
        //             " left join DatEncabezado as f on f.IdEncabezado=d.IdEncabezado".
        //             " left join CatTiendas as g on g.IdTienda=a.IdTienda".
        //             " left join CatUsoCFDI as h on h.UsoCFDI=a.UsoCFDI".
        //             " left join ConstanciaSituacionFiscal as j on j.IdSolicitudFactura=a.IdSolicitudFactura".
        //             " where a.Bill_To is null".
        //             " and a.IdClienteCloud is null".
        //             " and d.IdEncabezado=a.IdEncabezado".
        //             // " and a.Status=0".
        //             " group by a.IdSolicitudFactura, a.Id, a.RFC, a.FechaSolicitud, a.IdEncabezado,".
        //             " a.IdTienda, a.IdClienteCloud, a.TipoPersona, a.NomCliente, a.Calle, ".
        //             " a.NumExt, a.NumInt, a.Colonia, a.Ciudad, a.Municipio, a.Estado,".
        //             " a.Pais, a.CodigoPostal, a.Email, a.Telefono, a.IdUsuarioSolicitud, a.IdUsuarioCliente, a.Fecha_Cliente,".
        //             " a.Bill_To, a.UsoCFDI, a.IdCaja, a.Editar, a.Bajar, a.Status, a.MetodoPago, c.NomTipoPago, d.NumTarjeta, e.NomBanco, d.NumTarjeta, f.IdTicket, d.Pago, h.NomCFDI, g.NomTienda, j.NomConstancia, a.Subir,a.IdTipoPago";

        // $clientesPorLigar = DB::select($sqlSelect);

        // //return $clientesPorLigar;

        // $movimientos = MovimientoClienteCloud::with(['Notificaciones' => function ($notificacion) {
        //     $notificacion->where('DatNotificacionesClienteCloud.Status', 0)
        //         ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'DatNotificacionesClienteCloud.IdTienda');
        // }])
        //         ->where('Status', 0)
        //         ->get();

        // $notificaciones = NotificacionClienteCloud::where('Status', 0)
        //                 ->count();

        //return $movimientos;

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
        $searchQuery = $request->query('search') ? $request->query('search') : '';
        $searchQuery = $request->search ? $request->search : '';

        $ids = [];
        foreach ($tiendas as $tienda) {
            array_push($ids, $tienda->IdTienda);
        }

        $solicitudes = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
            ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
            ->where('NomCliente', 'LIKE', '%' . $searchQuery . '%')
            ->where('SolicitudFactura.Status', '0')
            ->whereNotNull('SolicitudFactura.Editar')
            ->whereIn('SolicitudFactura.IdTienda', $ids)
            ->where('SolicitudFactura.IdTienda', 'LIKE', $idTienda)
            ->get();

        return view('LigarClientes.ClientesNuevos', compact('solicitudes', 'tiendas', 'idTienda'));
    }

    public function VerConstanciaCliente(Request $request, $idSolicitudFactura)
    {
        $constancia = ConstanciaSituacionFiscal::where('IdSolicitudFactura', $idSolicitudFactura)
            ->first();

        $base64Pdf = $constancia->Constancia1 .
            $constancia->Constancia2 .
            $constancia->Constancia3 .
            $constancia->Constancia4 .
            $constancia->Constancia5 .
            $constancia->Constancia6 .
            $constancia->Constancia7 .
            $constancia->Constancia8 .
            $constancia->Constancia9 .
            $constancia->Constancia10;

        $pdfDecoded = base64_decode($base64Pdf);

        $pdf2 = fopen('c:\\ConstanciasPosWeb2\\' . $constancia->NomConstancia . '', 'w');

        fwrite($pdf2, $pdfDecoded);

        fclose($pdf2);

        $path = 'c:\\ConstanciasPosWeb2\\' . $constancia->NomConstancia . '';

        return response()->file($path);
    }

    public function LigarCliente(Request $request)
    {
        // $idSolicitudFactura = $request->idSolicitudFactura;
        // $bill_To = $request->bill_To;

        // $tipoPago = CorteTienda::where('IdSolicitudFactura', $idSolicitudFactura)
        //     ->select('IdTipoPago')
        //     ->distinct('IdTipoPago')
        //     ->first();

        // $solicitudFactura = DB::table('SolicitudFactura as a')
        //     ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
        //     ->leftJoin('DatTipoPago as c', 'c.IdEncabezado', 'a.IdEncabezado')
        //     ->leftJoin('CatTipoPago as d', 'd.IdTipoPago', 'c.IdTipoPago')
        //     ->leftJoin('CatBancos as e', 'e.IdBanco', 'c.IdBanco')
        //     ->select('a.*', 'b.NomTienda', 'd.NomTipoPago', 'e.NomBanco', 'c.NumTarjeta')
        //     ->where('a.IdSolicitudFactura', $idSolicitudFactura)
        //     ->where('c.IdTipoPago', $tipoPago->IdTipoPago)
        //     ->first();

        // //return $solicitudFactura;

        // $clientesOracle = CustomerCloud::with('Correo')
        //     ->where('RFC', $solicitudFactura->RFC)
        //     ->where('CODIGO_ENVIO', 'BILL_TO')
        //     ->get();

        // $clienteAlta = $clientesOracle->count() == 0 ? 1 : 0;

        // $cOracle = CustomerCloud::where('BILL_TO', $bill_To)
        //     ->first();

        // $ligarCliente = empty($cOracle) ? 1 : 0;
        try {
            $solicitud = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco')
                ->with('ConstanciaSituacionFiscal')
                ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
                ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
                ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
                ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
                ->where('IdSolicitudFactura', $request->idSolicitudFactura)
                ->whereNotNull('Editar')
                ->first();

            $clienteSolicitud = Cliente::with([
                'CorreoCliente' => function ($query) {
                    $query->select('IdClienteCloud', 'Email');
                    $query->groupBy('IdClienteCloud', 'Email');
                }
            ])
                ->where('RFC', $solicitud->RFC)
                // ->where('Bill_To', $solicitud->Bill_To)
                ->get();

            $clientes = Cliente::where('RFC', $solicitud->RFC)
                ->get();

            return view('LigarClientes.LigarCliente', compact('solicitud', 'clienteSolicitud', 'clientes'));
        } catch (\Throwable $th) {
            return redirect('ClientesNuevos')->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        // return view('LigarClientes.LigarCliente', compact('solicitudFactura', 'clientesOracle', 'clienteAlta', 'ligarCliente', 'cOracle'));
    }

    public function GuardarLigueCliente($idSolicitudFactura, $bill_To)
    {
        $clienteOracle = CustomerCloud::where('BILL_TO', $bill_To)
            ->first();

        SolicitudFactura::where('IdSolicitudFactura', $idSolicitudFactura)
            ->update([
                'IdClienteCloud' => $clienteOracle->ID_CLIENTE,
                'BILL_TO' => $clienteOracle->BILL_TO,
                'Fecha_Cliente' => date('d-m-Y H:i:s'),
                'IdUsuarioCliente' => Auth::user()->IdUsuario
            ]);

        return redirect('ClientesNuevos')->with('msjAdd', 'Solicitud de Factura Ligada con Exito!');
    }

    public function GuardarCheckClienteEditado(Request $request)
    {

        $idsNotificacion = $request->chkIdNotificacion;

        if (empty($idsNotificacion)) {
            return back()->with('msjdelete', 'Debe Seleccionar una Casilla, de un Cliente Previamente Modificado!');
        }

        foreach ($idsNotificacion as $key => $idNotificacion) {
            NotificacionClienteCloud::where('IdDatNotificacionesClienteCloud', $idNotificacion)
                ->update([
                    'Status' => 1
                ]);
        }

        return back()->with('msjAdd', 'Listo, Vaya a Ligar Cliente(s)!');
    }

    public function Cancelar($id, Request $request)
    {
        try {
            SolicitudFactura::where('Id', $id)->update([
                'Status' => 1,
                'IdUsuarioCancelacion' => Auth::user()->IdUsuario,
                'FechaCancelacion' => date('d-m-Y H:i:s')
            ]);
            return redirect('ClientesNuevos')->with('msjAdd', 'Solicitud de factura cancelada correctamente');
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function Finalizar($id, Request $request)
    {
        try {
            SolicitudFactura::where('Id', $id)->update([
                'Editar' => null,
            ]);

            return redirect('ClientesNuevos')->with('msjAdd', 'Solicitud de factura finalizada correctamente');
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
