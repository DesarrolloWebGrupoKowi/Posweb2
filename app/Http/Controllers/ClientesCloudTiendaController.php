<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\ClienteCloud;
use App\Models\ClienteCloudTienda;
use App\Models\CustomerCloud;
use App\Models\ListaPrecio;
use App\Models\TipoPago;

class ClientesCloudTiendaController extends Controller
{
    public function ClientesCloudTienda(Request $request){
        $tiendas = Tienda::all();

        $idTienda = $request->idTienda;

        $idsClienteCloudTienda = ClienteCloudTienda::where('IdTienda', $idTienda)
            ->pluck('IdClienteCloud');

        $clientesCloud = ClienteCloud::orderBy('NomClienteCloud')
            ->whereNotIn('IdClienteCloud', $idsClienteCloudTienda)
            ->get();

        //return $clientesCloud;

        return view('ClientesCloudTienda.DatClientesCloudTienda', compact('tiendas', 'clientesCloud'));
    }

    public function RelacionClienteCloudTienda(Request $request){
        $idTienda = $request->idTienda;

        $idCliente = $request->idClienteCloud;

        $tienda = DB::table('CatTiendas as a')
                    ->leftJoin('CatCiudades as b', 'b.IdCiudad', 'a.IdCiudad')
                    ->where('a.IdTienda', $idTienda)
                    ->first();

        $customersShipTo = CustomerCloud::where('ID_CLIENTE', $idCliente)
                ->where('LOCATION_STATUS', 'A')
                //->where('NOMBRE_ALT', $tienda->NomCiudad)
                ->where('CODIGO_ENVIO', 'SHIP_TO')
                ->get();

        $customersBillTo = CustomerCloud::where('ID_CLIENTE', $idCliente)
                ->where('LOCATION_STATUS', 'A')
                //->where('NOMBRE_ALT', $tienda->NomCiudad)
                ->where('CODIGO_ENVIO', 'BILL_TO')
                ->get();

        //return $customersBillTo;

        return view('ClientesCloudTienda.RelacionClienteCloudTienda', compact('customersShipTo', 'customersBillTo', 'idCliente', 'idTienda'));
    }

    public function GuardarRelacionClienteCloud(Request $request){

        $idTienda = $request->idTienda;
        $tienda = Tienda::where('IdTienda', $idTienda)
                    ->first();
        $idClienteCloud = $request->idCliente;
        $shipTo = $request->chkShipTo[0];
        $billTo = $request->chkBillTo[0];

        $listasPrecio = ListaPrecio::where('Status', 0)
                        ->get();

        $tiposPago = TipoPago::where('Status', 0)
                    ->get();

        $customer = CustomerCloud::where('BILL_TO', $billTo)
                    ->first();

        return view('ClientesCloudTienda.GuardarRelacionClienteCloud', compact('idTienda', 'idClienteCloud', 'shipTo', 'billTo', 'tienda', 'customer', 'listasPrecio', 'tiposPago'));
    }

    public function GuardarDatClienteCloud(Request $request){
        $idTienda = $request->idTienda;
        $idClienteCloud = $request->idClienteCloud;
        $tipoCliente = $request->tipoCliente;
        $shipTo = $request->shipTo;
        $billTo = $request->billTo;
        $codigoEnvio = $request->codigoEnvio;
        $direccion = $request->direccion;
        $locacion = $request->locacion;
        $pais = $request->pais;
        $ciudad = $request->ciudad;
        $codigoPostal = $request->codigoPostal;
        $idListaPrecio = $request->idListaPrecio;
        $idTipoPago = $request->idTipoPago;
        $idTipoNomina = $request->idTipoNomina;

        $clienteCloudTienda = new ClienteCloudTienda();
        $clienteCloudTienda->IdTienda = $idTienda;
        $clienteCloudTienda->IdClienteCloud = $idClienteCloud;
        $clienteCloudTienda->TipoCliente = $tipoCliente;
        $clienteCloudTienda->Ship_To = $shipTo;
        $clienteCloudTienda->Bill_To = $billTo;
        $clienteCloudTienda->Codigo_Envio = $codigoEnvio;
        $clienteCloudTienda->Direccion = $direccion;
        $clienteCloudTienda->Locacion = $locacion;
        $clienteCloudTienda->Pais = $pais;
        $clienteCloudTienda->Ciudad = $ciudad;
        $clienteCloudTienda->Codigo_Postal = $codigoPostal;
        $clienteCloudTienda->IdListaPrecio = $idListaPrecio;
        $clienteCloudTienda->IdTipoPago = $idTipoPago;
        $clienteCloudTienda->IdTipoNomina = $idTipoNomina;
        $clienteCloudTienda->save();

        return redirect('ClientesCloudTienda')->with('msjAdd', 'Cliente Cloud Agregado Exitosamente!');
    }

    public function VerClientesCloudTienda(Request $request){
        $nomTienda = $request->nomTienda;

        $tiendas = Tienda::with('ClienteCloud')
                ->where('NomTienda', 'like', '%'.$nomTienda.'%')
                ->get();

        //return $tiendas;

        return view('ClientesCloudTienda.VerClienteCloudTienda', compact('tiendas', 'nomTienda'));
    }
}
