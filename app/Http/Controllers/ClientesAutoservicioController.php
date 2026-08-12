<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientesAutoservicioController extends Controller
{
    private $dbCorte = 'CORTE';
    private $dbTables = 'Cloud_Tables';

    /**
     * Vista principal - Lista de clientes de GCSCTEPK
     */
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', '');
        $subinventario = $request->get('subinventario', '');

        $subInventario = DB::connection($this->dbTables)
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
                'SUBINVENTORY_NAME',
                'SUBINVENTORY_CLOUD',
                'SUCURSAL',
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


        $sucursalesAsignadas = DB::table('CatUsuariosSucursales')
            ->where('IdUsuario', Auth::user()->IdUsuario)->pluck('IdSucursal');

        $sucursales = collect(
            DB::connection('CORTE')->select("EXEC SP_Sucursales")
        )->whereIn('id_sucursal', $sucursalesAsignadas)->values();

        return view('ClientesAutoservicio.index', compact('subInventario', 'clientes', 'sucursales', 'filtro'));
    }

    public function agregarCliente(Request $request)
    {
        try {
            // Validar campos (incluyendo unicidad de Nombre)
            $request->validate([
                'nombre' => 'required|string|max:255|unique:CORTE.GCSCTEPK,Nombre',
                'direccion' => 'required|string|max:500',
                'subinventario_name' => 'required|string|max:100',
                'subinventario_cloud' => 'required|string|max:100',
                'sucursal' => 'required|string|max:50',
            ], [
                // Mensajes personalizados en español
                'nombre.required' => 'El nombre del cliente es obligatorio',
                'nombre.string' => 'El nombre del cliente debe ser texto',
                'nombre.max' => 'El nombre del cliente no puede exceder los 255 caracteres',
                'nombre.unique' => 'Ya existe un cliente con ese nombre',

                'direccion.required' => 'La dirección es obligatoria',
                'direccion.string' => 'La dirección debe ser texto',
                'direccion.max' => 'La dirección no puede exceder los 500 caracteres',

                'subinventario_name.required' => 'El nombre del subinventario es obligatorio',
                'subinventario_name.string' => 'El nombre del subinventario debe ser texto',
                'subinventario_name.max' => 'El nombre del subinventario no puede exceder los 100 caracteres',

                'subinventario_cloud.required' => 'El subinventario cloud es obligatorio',
                'subinventario_cloud.string' => 'El subinventario cloud debe ser texto',
                'subinventario_cloud.max' => 'El subinventario cloud no puede exceder los 100 caracteres',

                'sucursal.required' => 'La sucursal es obligatoria',
                'sucursal.string' => 'La sucursal debe ser texto',
                'sucursal.max' => 'La sucursal no puede exceder los 50 caracteres',
            ]);

            // Obtener el siguiente ID disponible
            $result = DB::connection('CORTE')
                ->select("SELECT dbo.fn_GetNextAvailableClienteId() as NextId");
            $nextId = $result[0]->NextId ?? 1;

            // Insertar nuevo cliente con SUCURSAL
            DB::connection('CORTE')->insert(
                "INSERT INTO GCSCTEPK (Cliente, Nombre, Direccion, SUBINVENTORY_ID, SUBINVENTORY_NAME, SUBINVENTORY_CLOUD, SUCURSAL)
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $nextId,
                    $request->nombre,
                    $request->direccion,
                    0, // SUBINVENTORY_ID fijo en 0
                    $request->subinventario_name,
                    $request->subinventario_cloud,
                    $request->sucursal
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Cliente agregado correctamente',
                'cliente_id' => $nextId
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            // Verificar si es error de duplicado
            if (str_contains($e->getMessage(), 'UNIQUE') || str_contains($e->getMessage(), 'duplicate')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un cliente con ese nombre'
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al agregar cliente: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editarCliente(Request $request)
    {
        try {
            // Validar campos (excluyendo el cliente actual para la unicidad)
            $request->validate([
                'cliente_id' => 'required|integer|exists:CORTE.GCSCTEPK,Cliente',
                'nombre' => 'required|string|max:255|unique:CORTE.GCSCTEPK,Nombre,' . $request->cliente_id . ',Cliente',
                'direccion' => 'required|string|max:500',
                'subinventario_name' => 'required|string|max:100',
                'subinventario_cloud' => 'required|string|max:100',
                'sucursal' => 'required|string|max:50',
            ], [
                'cliente_id.required' => 'El ID del cliente es obligatorio',
                'cliente_id.exists' => 'El cliente no existe en el sistema',
                'nombre.required' => 'El nombre del cliente es obligatorio',
                'nombre.string' => 'El nombre del cliente debe ser texto',
                'nombre.max' => 'El nombre del cliente no puede exceder los 255 caracteres',
                'nombre.unique' => 'Ya existe un cliente con ese nombre',
                'direccion.required' => 'La dirección es obligatoria',
                'direccion.string' => 'La dirección debe ser texto',
                'direccion.max' => 'La dirección no puede exceder los 500 caracteres',
                'subinventario_name.required' => 'El nombre del subinventario es obligatorio',
                'subinventario_name.string' => 'El nombre del subinventario debe ser texto',
                'subinventario_name.max' => 'El nombre del subinventario no puede exceder los 100 caracteres',
                'subinventario_cloud.required' => 'El subinventario cloud es obligatorio',
                'subinventario_cloud.string' => 'El subinventario cloud debe ser texto',
                'subinventario_cloud.max' => 'El subinventario cloud no puede exceder los 100 caracteres',
                'sucursal.required' => 'La sucursal es obligatoria',
                'sucursal.string' => 'La sucursal debe ser texto',
                'sucursal.max' => 'La sucursal no puede exceder los 50 caracteres',
            ]);

            // Actualizar cliente
            DB::connection('CORTE')->update(
                "UPDATE GCSCTEPK
                 SET Nombre = ?, Direccion = ?, SUBINVENTORY_NAME = ?, SUBINVENTORY_CLOUD = ?, SUCURSAL = ?
                 WHERE Cliente = ?",
                [
                    $request->nombre,
                    $request->direccion,
                    $request->subinventario_name,
                    $request->subinventario_cloud,
                    $request->sucursal,
                    $request->cliente_id
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado correctamente',
                'cliente_id' => $request->cliente_id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'UNIQUE') || str_contains($e->getMessage(), 'duplicate')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un cliente con ese nombre'
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cliente: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Paso 1 - Buscar clientes en XXKW_CUSTOMERS
     */
    public function buscarClientes(Request $request)
    {
        $nombre = $request->get('nombre', '');

        $clientes = DB::connection($this->dbTables)
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

        $direcciones = DB::connection($this->dbTables)
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

        $direcciones = DB::connection($this->dbTables)
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

        $precios = DB::connection($this->dbTables)
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
        $tipoOrden = DB::connection($this->dbTables)
            ->table('XXKW_OM_ORDER_TYPES')
            ->select('LOOKUP_CODE', 'MEANING')
            ->where('LANGUAGE', 'E')
            // ->where('MEANING', 'like', '%AUTOSERVICIOS%')
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
