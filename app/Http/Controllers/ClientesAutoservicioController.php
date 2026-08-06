<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesAutoservicioController extends Controller
{
    private $dbCorte = 'CORTE';
    private $dbCloud = 'Cloud_Tables';

    /**
     * Vista principal - Lista de clientes de GCSCTEPK
     */
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', '');
        $subinventario = $request->get('subinventario', '');

        $subInventario = DB::connection($this->dbCloud)
            ->table('XXKW_ORGANIZATIONS')
            ->select('ORGANIZATION_CODE', 'ORGANIZATION_NAME')
            ->where('BUSINESS_UNIT_NAME', 'UO_02_ALIME_KOWI')
            ->whereNotIn('ORGANIZATION_CODE', ['APC', 'ACO', 'APR', 'ARF'])
            ->orderBy('ORGANIZATION_CODE')
            ->get();

        $clientes = DB::connection($this->dbCorte)
            ->table('GCSCTEPK')
            ->select(
                'Cliente',
                'Nombre',
                'Direccion',
                'ID_CLIENTE',
                'NOMBRE_CLIENTE',
                'TIPO_CLIENTE',
                'SHIP_TO',
                'BILL_TO',
                'TERMINOS',
                'PRICE_LIST_ID',
                'ORDER_TYPE'
            )
            ->whereNotNull('SUBINVENTORY_NAME')
            ->when($filtro, function ($query) use ($filtro) {
                $query->where('Nombre', 'like', '%' . $filtro . '%')
                    ->orWhere('Cliente', 'like', '%' . $filtro . '%');
            })
            ->when($subinventario, function ($query) use ($subinventario) {
                $query->where('SUBINVENTORY_NAME', $subinventario);
            })
            ->orderBy('Cliente')
            ->paginate(10)
            ->appends(request()->query());

        return view('ClientesAutoservicio.index', compact('subInventario', 'clientes', 'filtro'));
    }

    /**
     * Paso 1 - Buscar clientes en XXKW_CUSTOMERS
     */
    public function buscarClientes(Request $request)
    {
        $nombre = $request->get('nombre', '');

        $clientes = DB::connection($this->dbCloud)
            ->table('XXKW_CUSTOMERS')
            ->select('ID_CLIENTE', 'NOMBRE', 'TIPO_CLIENTE', 'TERMINOS')
            ->where('NOMBRE', 'like', '%' . $nombre . '%')
            ->whereNotNull('ID_CLIENTE')
            ->groupBy('ID_CLIENTE', 'NOMBRE', 'TIPO_CLIENTE', 'TERMINOS')
            ->orderBy('NOMBRE')
            ->get();

        return response()->json($clientes);
    }

    /**
     * Paso 2 - Buscar direcciones de envío (SHIP_TO)
     */
    public function buscarShipTo(Request $request)
    {
        $nombre = $request->get('nombre', '');

        $direcciones = DB::connection($this->dbCloud)
            ->table('XXKW_CUSTOMERS')
            ->select(
                'SHIP_TO',
                DB::raw("CONCAT('Calle:', CALLE, ' ', COLONIA, ' Numero Ext:', NUMEXT, ' ', 'Codigo Postal:', CODIGO_POSTAL, ' ', CIUDAD, ' ', ESTADO) as direccion")
            )
            ->where('NOMBRE', 'like', '%' . $nombre . '%')
            ->where('CODIGO_ENVIO', 'SHIP_TO')
            ->whereNotNull('SHIP_TO')
            ->groupBy('SHIP_TO', DB::raw("CONCAT('Calle:', CALLE, ' ', COLONIA, ' Numero Ext:', NUMEXT, ' ', 'Codigo Postal:', CODIGO_POSTAL, ' ', CIUDAD, ' ', ESTADO)"))
            ->orderBy('SHIP_TO')
            ->get();

        return response()->json($direcciones);
    }

    /**
     * Paso 3 - Buscar direcciones de facturación (BILL_TO)
     */
    public function buscarBillTo(Request $request)
    {
        $nombre = $request->get('nombre', '');

        $direcciones = DB::connection($this->dbCloud)
            ->table('XXKW_CUSTOMERS')
            ->select(
                'BILL_TO',
                DB::raw("CONCAT('Calle:', CALLE, ' ', COLONIA, ' Numero Ext:', NUMEXT, ' ', 'Codigo Postal:', CODIGO_POSTAL, ' ', CIUDAD, ' ', ESTADO, ' Nom Alt: ', NOMBRE_ALT) as direccion")
            )
            ->where('NOMBRE', 'like', '%' . $nombre . '%')
            ->where('CODIGO_ENVIO', 'BILL_TO')
            ->whereNotNull('BILL_TO')
            ->groupBy('BILL_TO', DB::raw("CONCAT('Calle:', CALLE, ' ', COLONIA, ' Numero Ext:', NUMEXT, ' ', 'Codigo Postal:', CODIGO_POSTAL, ' ', CIUDAD, ' ', ESTADO, ' Nom Alt: ', NOMBRE_ALT)"))
            ->orderBy('BILL_TO')
            ->get();

        return response()->json($direcciones);
    }

    /**
     * Paso 4 - Buscar listas de precio
     */
    public function buscarPrecios(Request $request)
    {
        $nombre = $request->get('nombre', '');

        $precios = DB::connection($this->dbCloud)
            ->table('XXKW_LIST_PRICE')
            ->select('PRICE_LIST_ID', 'NAME', 'DESCRIPTION')
            ->where('NAME', 'like', '%' . $nombre . '%')
            ->whereNotNull('PRICE_LIST_ID')
            ->orderBy('NAME')
            ->get();

        return response()->json($precios);
    }

    /**
     * Paso 5 - Buscar tipo de orden
     */
    public function buscarTipoOrden(Request $request)
    {
        // $nombre = $request->get('nombre', '');
        //SELECT * FROM XXKW_OM_ORDER_TYPES WHERE LANGUAGE = 'E' AND MEANING LIKE '%AUTOSERVICIOS%' AND MEANING NOT LIKE '%DEVOLUCION%'
        $tipoOrden = DB::connection($this->dbCloud)
            ->table('XXKW_OM_ORDER_TYPES')
            ->select('LOOKUP_CODE', 'MEANING')
            ->where('LANGUAGE', 'E')
            ->where('MEANING', 'like', '%AUTOSERVICIOS%')
            ->where('MEANING', 'not like', '%DEVOLUCION%')
            ->orderBy('LOOKUP_CODE')
            ->get();
        return response()->json($tipoOrden);
    }

    /**
     * Guardar configuración
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'cliente' => 'required',
            'id_cliente' => 'required',
            'nombre_cliente' => 'required',
            'tipo_cliente' => 'required',
            'ship_to' => 'required',
            'bill_to' => 'required',
            'terminos' => 'required',
            'price_list_id' => 'required',
            'order_type' => 'required',
        ]);

        DB::connection($this->dbCorte)
            ->table('GCSCTEPK')
            ->where('Cliente', $request->cliente)
            ->update([
                'ID_CLIENTE' => $request->id_cliente,
                'NOMBRE_CLIENTE' => $request->nombre_cliente,
                'TIPO_CLIENTE' => $request->tipo_cliente,
                'SHIP_TO' => $request->ship_to,
                'BILL_TO' => $request->bill_to,
                'TERMINOS' => $request->terminos,
                'PRICE_LIST_ID' => $request->price_list_id,
                'ORDER_TYPE' => $request->order_type,
            ]);

        return back()->with('msjAdd', 'Cliente de Autoservicio configurado exitosamente!');
    }
}
