<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoservicioFacturacionController extends Controller
{
    private $dbPackingList = 'PACKINGLIST';
    private $dbCloud = 'Cloud_Interface';
    private $dbTables = 'Cloud_Tables';

    public function index(Request $request)
    {
        // Datos para rellenar los combos
        $uso_cfdi = DB::connection($this->dbTables)->table('XXKW_FLEX_VALUES')->select('FLEX_VALUE', 'DESCRIPTION')
            ->where('VALUE_CATEGORY', 'XXKW_USO CFDI')->get();
        $metodo_pago = DB::connection($this->dbTables)->table('XXKW_FLEX_VALUES')->select('FLEX_VALUE', 'DESCRIPTION')
            ->where('VALUE_CATEGORY', 'XXKW_METODO DE PAGO')->get();
        $forma_pago =  DB::connection($this->dbTables)->table('XXKW_FLEX_VALUES')->select('FLEX_VALUE', 'DESCRIPTION')
            ->where('VALUE_CATEGORY', 'XXKW_FORMA_DE_PAGO')->get();

        // Datos del packing
        $packList = $request->get('packlist', '');
        $header = null;
        $ship_to = null;
        $bill_to = null;
        $lineas = [];
        $packingorder = null;
        $packingorderlines = [];
        $cfdiNombre = null;
        $metodoNombre = null;
        $formaNombre = null;

        if (!empty($packList)) {
            try {
                // Ejecutar SP del header
                $header = DB::connection($this->dbPackingList)->select("EXEC SP_AUTOSERVICIO_CTE ?", [$packList]);

                if (!empty($header)) {
                    $header = $header[0];

                    // Dirección de envío
                    $ship_to = DB::connection($this->dbTables)
                        ->table('XXKW_CUSTOMERS')
                        ->select(DB::raw("concat('Calle:', CALLE,' ',COLONIA,' Numero Ext:', NUMEXT,' ','Codigo Postal:',CODIGO_POSTAL,' ',CIUDAD,' ',ESTADO) as direccion"))
                        ->where('SHIP_TO', $header->SHIP_TO)
                        ->first();

                    // Dirección de facturación
                    $bill_to = DB::connection($this->dbTables)
                        ->table('XXKW_CUSTOMERS')
                        ->select(DB::raw("concat('Calle:', CALLE,' ',COLONIA,' Numero Ext:', NUMEXT,' ','Codigo Postal:',CODIGO_POSTAL,' ',CIUDAD,' ',ESTADO) as direccion"))
                        ->where('BILL_TO', $header->BILL_TO)
                        ->first();
                    // Ejecutar SP de líneas
                    $lineas = DB::connection($this->dbPackingList)
                        ->select("EXEC SP_AUTOSERVICIO_PACKINGLIST ?", [$packList]);

                    // Relacion entre packinglist y header
                    $packingorder = DB::connection($this->dbCloud)
                        ->table('XXKW_AUTOSERVICIO_PACKINGORDER')
                        ->leftJoin(
                            'XXKW_AUTOSERVICIO_HEADERS',
                            'XXKW_AUTOSERVICIO_PACKINGORDER.SOURCE_TRANSACTION_IDENTIFIER',
                            '=',
                            'XXKW_AUTOSERVICIO_HEADERS.SOURCE_TRANSACTION_IDENTIFIER'
                        )
                        ->where('XXKW_AUTOSERVICIO_PACKINGORDER.PACKINGLIST', $packList)
                        ->select(
                            'XXKW_AUTOSERVICIO_HEADERS.*'
                        )
                        ->first();

                    if ($packingorder) {
                        // Relacion entre packinglist y lineas
                        $packingorderlines = DB::connection($this->dbCloud)
                            ->table('XXKW_AUTOSERVICIO_LINES')
                            ->where('Source_Transaction_Identifier', $packingorder->Source_Transaction_Identifier)
                            ->get();

                        $cfdiNombre = $uso_cfdi->firstWhere('FLEX_VALUE', $packingorder->UCFDI)->DESCRIPTION ?? null;
                        $metodoNombre = $metodo_pago->firstWhere('FLEX_VALUE', $packingorder->METODO_PAGO)->DESCRIPTION ?? null;
                        $formaNombre = $forma_pago->firstWhere('FLEX_VALUE', $packingorder->FORMA_PAGO)->DESCRIPTION ?? null;
                    }
                }
            } catch (\Exception $e) {
                return back()->with('msjdelete', 'Error al consultar: ' . $e->getMessage());
            }
        }

        return view('AutoservicioFacturacion.index', compact(
            'uso_cfdi',
            'metodo_pago',
            'forma_pago',
            'header',
            'lineas',
            'packList',
            'ship_to',
            'bill_to',
            'packingorder',
            'packingorderlines',
            'cfdiNombre',
            'metodoNombre',
            'formaNombre'
        ));
    }

    public function enviar(Request $request)
    {
        // Validación de campos requeridos
        $request->validate([
            'packlist' => 'required',
            'uso_cfdi' => 'required',
            'metodo_pago' => 'required',
            'forma_pago' => 'required',
        ], [
            'packlist.required' => 'El campo packlist es obligatorio',
            'uso_cfdi.required' => 'El campo uso_cfdi es obligatorio',
            'metodo_pago.required' => 'El campo metodo_pago es obligatorio',
            'forma_pago.required' => 'El campo forma_pago es obligatorio',
        ]);

        $packList = $request->get('packlist');
        $header = json_decode($request->get('header_data'), true);
        $lineas = $request->get('lineas', []);
        $usoCfdi = $request->get('uso_cfdi');
        $metodoPago = $request->get('metodo_pago');
        $formaPago = $request->get('forma_pago');

        try {
            DB::beginTransaction();

            // ============================================================
            // VALIDAR HEADER
            // ============================================================
            $orderType = $header['ORDER_TYPE'] ?? null;
            $buyingPartyNumberId = $header['ID_CLIENTE'] ?? null;
            $buyingPartyName = $header['NOMBRE_CLIENTE'] ?? $header['Destino'] ?? null;
            $buyingPartyType = $header['TIPO_CLIENTE'] ?? null;
            $businessUnitName = $header['BusinessUnitName'] ?? 'UO_02_ALIME_KOWI';
            $siteId = $header['SHIP_TO'] ?? null;
            $accountSiteId = $header['BILL_TO'] ?? null;
            // $customerPONumber = $header['Destino'] ?? '';
            $customerPONumber = $header['cliente'] ?? '';
            $paymentTerm = $header['TERMINOS'] ?? '30 DIAS';

            $erroresHeader = [];

            if (empty($orderType)) {
                $erroresHeader[] = 'ORDER_TYPE (Tipo de Orden) no puede estar vacío';
            }
            if (empty($buyingPartyNumberId)) {
                $erroresHeader[] = 'ID_CLIENTE no puede estar vacío';
            }
            if (empty($buyingPartyName)) {
                $erroresHeader[] = 'NOMBRE_CLIENTE no puede estar vacío';
            }
            if (empty($buyingPartyType)) {
                $erroresHeader[] = 'TIPO_CLIENTE no puede estar vacío';
            }
            if (empty($siteId)) {
                $erroresHeader[] = 'SHIP_TO (Dirección de Envío) no puede estar vacío';
            }
            if (empty($accountSiteId)) {
                $erroresHeader[] = 'BILL_TO (Dirección de Facturación) no puede estar vacío';
            }
            if (empty($paymentTerm)) {
                $erroresHeader[] = 'TERMINOS no puede estar vacío';
            }

            if (!empty($erroresHeader)) {
                throw new \Exception('Errores en el Header:<br>• ' . implode('<br>• ', $erroresHeader));
            }

            // ============================================================
            // VALIDAR LÍNEAS
            // ============================================================
            if (empty($lineas)) {
                throw new \Exception('No hay líneas para enviar. El pedido debe tener al menos una línea.');
            }
            // Ordenar líneas por código (en el controlador, no en la vista)
            usort($lineas, function ($a, $b) {
                $codigoA = $a['CODIGO'] ?? $a['codigo'] ?? '';
                $codigoB = $b['CODIGO'] ?? $b['codigo'] ?? '';
                return strcmp($codigoA, $codigoB);
            });

            $erroresLineas = [];
            $lineasValidas = [];

            foreach ($lineas as $index => $linea) {
                $numLinea = $index + 1;
                $codigo = $linea['CODIGO'] ?? $linea['codigo'] ?? null;
                $uom = $linea['UOM'] ?? $linea['uom'] ?? null;
                $cantidad = $linea['CANTIDAD'] ?? $linea['cantidad'] ?? 0;
                $precio = $linea['PRECIO'] ?? $linea['precio'] ?? 0;
                $nombreProd = $linea['NOMBREPROD'] ?? $linea['nombreprod'] ?? '';

                // Limpiar y convertir valores
                $cantidad = floatval(str_replace(',', '', $cantidad));
                $precio = floatval(str_replace(['$', ','], '', $precio));

                // Validar código
                if (empty($codigo)) {
                    $erroresLineas[] = "Línea {$numLinea}: El código del artículo está vacío";
                    continue;
                }

                // Validar UOM
                if (empty($uom) || $uom === '-') {
                    $erroresLineas[] = "Línea {$numLinea} ({$codigo}): La unidad de medida (UOM) no puede estar vacía";
                    continue;
                }

                // Validar nombre del producto
                if (empty($nombreProd) || $nombreProd === 'Sin nombre') {
                    $erroresLineas[] = "Línea {$numLinea} ({$codigo}): El nombre del producto está vacío";
                    continue;
                }

                // Validar cantidad
                if ($cantidad <= 0) {
                    $erroresLineas[] = "Línea {$numLinea} ({$codigo}): La cantidad debe ser mayor a 0 (valor actual: {$cantidad})";
                    continue;
                }

                // Validar precio
                if ($precio <= 0) {
                    $erroresLineas[] = "Línea {$numLinea} ({$codigo}): El precio debe ser mayor a 0 (valor actual: {$precio})";
                    continue;
                }

                // Línea válida
                $lineasValidas[] = [
                    'codigo' => $codigo,
                    'uom' => $uom,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'nombreProd' => $nombreProd,
                ];
            }

            // Si hay errores en líneas, lanzar excepción
            if (!empty($erroresLineas)) {
                throw new \Exception('Errores en las Líneas:<br>• ' . implode('<br>• ', $erroresLineas));
            }

            // Si no hay líneas válidas después de filtrar
            if (empty($lineasValidas)) {
                throw new \Exception('No hay líneas válidas para enviar. Todas las líneas tienen errores.');
            }

            // ============================================================
            // GENERAR FOLIO
            // ============================================================
            $sourceTransactionIdentifier = DB::connection($this->dbCloud)
                ->select("SELECT dbo.FN_AUTOSERVICIO_FOLIO(?) as folio", [$orderType])[0]->folio;

            // ============================================================
            // INSERTAR HEADER
            // ============================================================
            DB::connection($this->dbCloud)->statement("EXEC SP_AUTOSERVICIO_HEADERS
                @OrderTypeVenta=?,
                @Source_Transaction_Identifier=?,
                @BuyingPartyNumber_Id=?,
                @BuyingPartyName=?,
                @BuyingPartyType=?,
                @TransactionalCurrencyCode=?,
                @BusinessUnitName=?,
                @MetododePago=?,
                @FormaPago=?,
                @UsoCfdi=?,
                @SiteId=?,
                @Account_Site_Identifier=?,
                @OrderType=?,
                @CreateBy=?,
                @CustomerPONumber=?,
                @Pakinglist=?
            ", [
                $orderType,
                $sourceTransactionIdentifier,
                $buyingPartyNumberId,
                $buyingPartyName,
                $buyingPartyType,
                'MXN',
                $businessUnitName,
                $metodoPago,
                $formaPago,
                $usoCfdi,
                $siteId,
                $accountSiteId,
                $orderType,
                Auth::user()->IdUsuario ?? 11,
                $customerPONumber,
                $packList
            ]);

            // ============================================================
            // INSERTAR LÍNEAS
            // ============================================================
            $lineaIndex = 0;
            foreach ($lineasValidas as $linea) {
                DB::connection($this->dbCloud)->statement("EXEC SP_AUTOSERVICIO_LINES
                    @Source_Transaction_Identifier=?,
                    @ProductNumber=?,
                    @OrderedUOMCode=?,
                    @OrderedQuantity=?,
                    @Adjusmet_Amount=?,
                    @Business_Unit_Name=?,
                    @Payment_Term=?
                ", [
                    $sourceTransactionIdentifier,
                    $linea['codigo'],
                    $linea['uom'],
                    $linea['cantidad'],
                    $linea['precio'],
                    $businessUnitName,
                    $paymentTerm
                ]);

                $lineaIndex++;
            }

            // ============================================================
            // INCREMENTAR FOLIO
            // ============================================================
            DB::connection($this->dbCloud)->statement("EXEC SP_AUTOSERVICIO_FOLIO @ORDERTYPE=?", [$orderType]);

            DB::commit();

            return back()->with('msjAdd', 'PackList ' . $packList . ' enviado a interfaz. Folio: ' . $sourceTransactionIdentifier . '. Líneas procesadas: ' . $lineaIndex);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en enviar(): ' . $e->getMessage(), [
                'packlist' => $packList,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('msjdelete', 'Error al enviar: ' . $e->getMessage());
        }
    }

    public function reporte(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));
        $orden = $request->get('orden', ''); // Nuevo: filtro por tipo de orden
        $estatus = $request->get('estatus', '');
        $cliente = $request->get('cliente', '');

        $headers = DB::connection($this->dbCloud)
            ->table('XXKW_AUTOSERVICIO_HEADERS')
            ->select(
                'Source_Transaction_Identifier',
                'Source_Transaction_Number',
                'Buying_Party_Name',
                'Buying_Party_Type',
                'Transaction_On',
                'Requesting_Business_Unit',
                'Batch_Name',
                'STATUS',
                'ORDER_TYPE',
                'MENSAJE_ERROR',
                'CustomerPONumber',
                'UCFDI',
                'METODO_PAGO',
                'FORMA_PAGO'
            )
            ->whereDate('Transaction_On', $fecha) // Obligatorio: filtrar por fecha
            ->when($orden, function ($query) use ($orden) {
                $query->where('ORDER_TYPE', $orden); // Filtrar por tipo de orden
            })
            ->when($estatus, function ($query) use ($estatus) {
                if ($estatus === 'NULL') {
                    $query->whereNull('STATUS');
                } else {
                    $query->where('STATUS', $estatus);
                }
            })
            ->when($cliente, function ($query) use ($cliente) {
                $query->where(function ($q) use ($cliente) {
                    $q->where('Buying_Party_Name', 'like', '%' . $cliente . '%')
                        ->orWhere('CustomerPONumber', 'like', '%' . $cliente . '%');
                });
            })
            ->orderBy('Transaction_On', 'DESC')
            ->orderBy('Source_Transaction_Identifier', 'DESC')
            ->paginate(20);

        // Calcular totales
        foreach ($headers as $header) {
            $header->total_lineas = DB::connection($this->dbCloud)
                ->table('XXKW_AUTOSERVICIO_LINES')
                ->where('Source_Transaction_Identifier', $header->Source_Transaction_Identifier)
                ->count();

            $header->total_kilos = DB::connection($this->dbCloud)
                ->table('XXKW_AUTOSERVICIO_LINES')
                ->where('Source_Transaction_Identifier', $header->Source_Transaction_Identifier)
                ->where('Ordered_UOM', 'KILOGRAMO')
                ->sum('Ordered_Quantity');

            $header->total_piezas = DB::connection($this->dbCloud)
                ->table('XXKW_AUTOSERVICIO_LINES')
                ->where('Source_Transaction_Identifier', $header->Source_Transaction_Identifier)
                ->where('Ordered_UOM', 'PIEZA.')
                ->sum('Ordered_Quantity');

            $header->total_importe = DB::connection($this->dbCloud)
                ->table('XXKW_AUTOSERVICIO_LINES')
                ->where('Source_Transaction_Identifier', $header->Source_Transaction_Identifier)
                ->sum(DB::raw('Ordered_Quantity * ADJUSTMENT_AMOUNT'));
        }

        // Obtener tipos de orden para el filtro
        $tiposOrden = DB::connection($this->dbCloud)
            ->table('XXKW_AUTOSERVICIO_HEADERS')
            ->select('ORDER_TYPE')
            ->distinct()
            ->whereNotNull('ORDER_TYPE')
            ->orderBy('ORDER_TYPE')
            ->pluck('ORDER_TYPE');

        return view('AutoservicioFacturacion.reporte', compact('headers', 'fecha', 'orden', 'estatus', 'cliente', 'tiposOrden'));
    }

    public function detalleLineas(string $folio)
    {
        $lineas = DB::connection($this->dbCloud)
            ->table('XXKW_AUTOSERVICIO_LINES')
            ->where('Source_Transaction_Identifier', $folio)
            ->orderBy('Source_Transaction_Line_Number')
            ->get();

        return response()->json($lineas);
    }
}
