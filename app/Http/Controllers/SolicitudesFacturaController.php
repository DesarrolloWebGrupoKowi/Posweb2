<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\Cliente;
use App\Models\DatEncabezado;
use App\Models\Ciudad;
use App\Models\Estado;
use App\Models\SolicitudFactura;
use App\Models\CorteTienda;
use App\Models\UsoCFDI;
use App\Models\TipoPago;
use App\Models\NotificacionClienteCloud;
use App\Models\ConstanciaSituacionFiscal;

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

        $solicitudes = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'CatTipoPago.NomTipoPago')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago', 'CatTipoPago.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->where('NomCliente', 'LIKE', '%' . $searchQuery . '%')
            ->whereNotNull('Editar')
            ->whereIn('SolicitudFactura.IdTienda', $ids)
            ->where('SolicitudFactura.IdTienda', 'LIKE', $idTienda)
            ->paginate(10);

        return view('SolicitudesFactura.SolicitudesFactura', compact('solicitudes', 'tiendas', 'idTienda'));
    }

    public function VerSolicitud($id, Request $request)
    {
        $solicitud = SolicitudFactura::select('SolicitudFactura.*', 'CatTiendas.NomTienda', 'CatTipoPago.NomTipoPago')
            ->leftJoin('CatTiendas', 'CatTiendas.IdTienda', 'SolicitudFactura.IdTienda')
            ->leftJoin('CatTipoPago', 'CatTipoPago.IdTipoPago', 'SolicitudFactura.IdTipoPago')
            ->where('Id', $id)
            ->whereNotNull('Editar')
            ->first();

        $clientes = Cliente::where('RFC', $solicitud->RFC)
            ->get();

        return view('SolicitudesFactura.SolicitudFactura', compact('solicitud', 'clientes'));
    }

    public function Relacionar($id, $billTo, Request $request)
    {
        $cliente = Cliente::where('Bill_To', $billTo)->first();

        SolicitudFactura::where('Id', $id)->update([
            'Bill_To' => $cliente->Bill_To,
            'IdClienteCloud' => $cliente->IdClienteCloud,
            'IdUsuarioCliente' => Auth::user()->IdUsuario,
            'Fecha_Cliente' => date('d-m-Y H:i:s'),
            'Descargar' => 0
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
}
