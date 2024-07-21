<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClienteCloud;
use App\Models\CustomerCloud;

class ClientesCloudController extends Controller
{
    public function CatClientesCloud(){
        $clientesCloud = ClienteCloud::all();

        return view('ClientesCloud.CatClientesCloud', compact('clientesCloud'));
    }

    public function BuscarCustomer(Request $request){

        $sqlSelect = "select distinct ID_CLIENTE, NOMBRE, TIPO_CLIENTE".
                     " from SERVER.CLOUD_TABLES.dbo.XXKW_CUSTOMERS".
                     " where ID_CLIENTE not in (select IdClienteCloud".
                     " from CatClientesCloud)".
                     " and NOMBRE like '%".$request->txtFiltro."%'".
                     " and LOCATION_STATUS = 'A'";

        $customers = DB::select($sqlSelect);

        return view('ClientesCloud.ifrBuscarCustomer', compact('customers'));
    }

    public function GuardarCustomerCloud(Request $request){
        $idClientes = $request->chkCustomer;

        foreach ($idClientes as $key => $idCliente) {
            $customers = CustomerCloud::where('ID_CLIENTE', $idCliente)
                            ->select('ID_CLIENTE', 'NOMBRE', 'TIPO_CLIENTE')
                            ->distinct('ID_CLIENTE')
                            ->get();

            foreach ($customers as $keyCustomer => $customer) {
                ClienteCloud::insert([
                    'IdClienteCloud' => $customer->ID_CLIENTE,
                    'NomClienteCloud' => $customer->NOMBRE,
                    'TipoCliente' => $customer->TIPO_CLIENTE
                ]);
            }
        }

        return redirect('/CatClientesCloud')->with('msjAdd', 'Se Agrego el Cliente con Exito!');
    }
}
