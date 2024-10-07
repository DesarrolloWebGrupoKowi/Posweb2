<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tienda;
use App\Models\Cliente;
use App\Models\SolicitudFactura;
use App\Models\CorteTienda;

class SolicitudesFacturaController extends Controller
{
    public function VerSolicitudes(Request $request)
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
        $searchQuery = $request->query('search') ? $request->query('search') : '';
        $searchQuery = $request->search ? $request->search : '';

        $ids = [];
        foreach ($tiendas as $tienda) {
            array_push($ids, $tienda->IdTienda);
        }

        $solicitudes = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco', 'DatEncabezado.IdTicket', 'DatEncabezado.SubTotal', 'DatEncabezado.ImporteVenta')
            ->whereHas('DetalleTicket', function ($query) {
                $query->whereColumn('DatCortesTienda.IdTipoPago', 'SolicitudFactura.IdTipoPago');
            })
            ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'SolicitudFactura.IdEncabezado')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
            ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
            ->where('NomCliente', 'LIKE', '%' . $searchQuery . '%')
            ->where(function ($query) {
                $query->whereNull('SolicitudFactura.Editar')
                    ->orWhere('SolicitudFactura.Status', '1');
            })
            ->whereIn('SolicitudFactura.IdTienda', $ids)
            ->where('SolicitudFactura.IdTienda', 'LIKE', $idTienda)
            ->orderBy('SolicitudFactura.FechaSolicitud', 'desc')
            ->paginate(10)
            ->onEachSide(1);

        $solicitudesPendientes = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco', 'DatEncabezado.IdTicket')
            ->leftJoin('DatEncabezado', 'DatEncabezado.IdEncabezado', 'SolicitudFactura.IdEncabezado')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
            ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
            ->where('SolicitudFactura.Status', '0')
            ->whereNotNull('SolicitudFactura.Editar')
            ->whereIn('SolicitudFactura.IdTienda', $ids)
            ->orderBy('SolicitudFactura.FechaSolicitud', 'desc')
            ->get();

        return view('SolicitudesFactura.SolicitudesFactura', compact('solicitudes', 'solicitudesPendientes', 'tiendas', 'idTienda'));
    }

    public function VerSolicitud($id, Request $request)
    {
        $solicitud = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'ct.NomTipoPago', 'dt.NumTarjeta', 'cb.NomBanco')
            ->with('ConstanciaSituacionFiscal')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago as ct', 'ct.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->leftJoin('DatTipoPago as dt', [['dt.IdEncabezado', 'SolicitudFactura.IdEncabezado'], ['dt.IdTipoPago', 'SolicitudFactura.IdTipoPago']])
            ->leftJoin('CatBancos as cb', 'cb.IdBanco', 'dt.IdBanco')
            ->where('Id', $id)
            // ->whereNotNull('Editar')/*  */
            ->first();

        // $clienteSolicitud = Cliente::with([
        //     'CorreoCliente' => function ($query) {
        //         $query->select('IdClienteCloud', 'Email');
        //         $query->groupBy('IdClienteCloud', 'Email');
        //     }
        // ])
        //     ->where('RFC', $solicitud->RFC)
        //     // ->where('Bill_To', $solicitud->Bill_To)
        //     ->get();

        // $clientes = Cliente::where('RFC', $solicitud->RFC)
        //     ->get();

        return view('SolicitudesFactura.SolicitudFactura', compact('solicitud'));
    }

    public function Relacionar($id, $billTo, Request $request)
    {
        $cliente = Cliente::where('Bill_To', $billTo)->first();

        SolicitudFactura::where('Id', $id)->update([
            'Bill_To' => $cliente->Bill_To,
            'IdClienteCloud' => $cliente->IdClienteCloud,
            'IdUsuarioCliente' => Auth::user()->IdUsuario,
            'Fecha_Cliente' => date('d-m-Y H:i:s')
        ]);

        $idSolid = SolicitudFactura::where('Id', $id)->value('IdSolicitudFactura');

        CorteTienda::where('IdSolicitudFactura', $idSolid)->update([
            'Bill_To' => $cliente->Bill_To
        ]);

        return back()->with('msjAdd', 'Cliente relacionado correctamente');
    }

    public function Finalizar($id, Request $request)
    {
        SolicitudFactura::where('Id', $id)->update([
            'Editar' => null,
        ]);

        return redirect('SolicitudesFactura')->with('msjAdd', 'Solicitud de factura finalizada correctamente');
    }

    public function Cancelar($id, Request $request)
    {
        try {
            SolicitudFactura::where('Id', $id)->update([
                'Status' => 1,
                'IdUsuarioCancelacion' => Auth::user()->IdUsuario,
                'FechaCancelacion' => date('d-m-Y H:i:s')
            ]);
            return redirect('SolicitudesFactura')->with('msjAdd', 'Solicitud de factura cancelada correctamente');
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
