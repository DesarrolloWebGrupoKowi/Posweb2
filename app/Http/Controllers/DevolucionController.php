<?php

namespace App\Http\Controllers;

use App\Models\XXKWCreditHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevolucionController extends Controller
{
    public function index(Request $request)
    {
        $devolucionSeleccionada = null;
        $lineas = collect();
        $estatusOracle = null;
        $estatusAgrupado = null;

        if ($request->filled('folio')) {
            $devolucionSeleccionada = XXKWCreditHeader::with('lineas')
                ->where('Source_Transaction_Identifier', $request->folio)
                ->first();

            if ($devolucionSeleccionada) {
                $lineas = $devolucionSeleccionada->lineas;
                if ($devolucionSeleccionada->STATUS === 'PROCESADO') {
                    // Consultar Oracle automáticamente para obtener estatus actualizados
                    $estatusOracle = $this->consultarEstatusOracle($request->folio);

                    if ($estatusOracle && isset($estatusOracle['lines'])) {
                        $estatusUnicos = collect($estatusOracle['lines'])
                            ->pluck('status')
                            ->filter()
                            ->unique()
                            ->values()
                            ->toArray();

                        $estatusAgrupado = implode(', ', $estatusUnicos);
                    }
                }
            }
        }

        if (!$request->filled('folio')) {
            $query = XXKWCreditHeader::with('lineas')->orderBy('Created_at', 'desc');

            if ($request->filled('orden')) {
                $query->where('OrdenVenta', 'LIKE', '%' . $request->orden . '%');
            }
            if ($request->filled('estatus')) {
                if ($request->estatus === 'PENDIENTE') {
                    $query->whereNull('STATUS');
                } else {
                    $query->where('STATUS', $request->estatus);
                }
            }

            $devoluciones = $query->paginate(10);
        } else {
            $devoluciones = collect();
        }

        return view('Devoluciones.index', compact(
            'devoluciones',
            'devolucionSeleccionada',
            'lineas',
            'estatusOracle',
            'estatusAgrupado'
        ));
    }

    public function refresh(string $folio)
    {
        $devolucion = XXKWCreditHeader::with('lineas')
            ->where('Source_Transaction_Identifier', $folio)
            ->firstOrFail();

        $estatusOracle = null;
        $estatusAgrupado = null;

        if ($devolucion->STATUS === 'PROCESADO') {

            $estatusOracle = $this->consultarEstatusOracle($folio);

            if ($estatusOracle && isset($estatusOracle['lines'])) {

                $estatusUnicos = collect($estatusOracle['lines'])
                    ->pluck('status')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                $estatusAgrupado = implode(', ', $estatusUnicos);
            }
        }

        return response()->json([
            'header' => $devolucion,
            'lineas' => $devolucion->lineas,
            'estatusOracle' => $estatusOracle,
            'estatusAgrupado' => $estatusAgrupado
        ]);
    }

    /**
     * Consulta la API de Oracle para obtener el estatus de la devolución
     */
    private function consultarEstatusOracle(string $folio)
    {
        try {
            $client = new \GuzzleHttp\Client(['timeout' => 15]);
            $response = $client->get('http://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/GetDevolucionOracle', [
                'query' => ['Orden' => $folio]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['dato'])) {
                return $data['dato'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error consultando Oracle: ' . $e->getMessage());
            return null;
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'header.orderNumber' => 'required|string',
            'header.sourceTransactionNumber' => 'required|string',
            'header.buyingPartyId' => 'required',
            'header.buyingPartyNumber' => 'required|string',
            'header.buyingPartyName' => 'required|string',
            'header.transactionalCurrencyCode' => 'required|string',
            'header.businessUnitId' => 'required',
            'header.businessUnitName' => 'required|string',
            'header.requestedFulfillmentOrganizationId' => 'required',
            'header.requestedFulfillmentOrganizationCode' => 'required|string',
            'header.requestedFulfillmentOrganizationName' => 'required|string',
            'header.shipToCustomerId' => 'required',
            'header.billToCustomerAccountId' => 'required',
            'header.billToSiteUseId' => 'required',
            'header.cfdi' => 'required',
            'header.metodoDePago' => 'required',
            'header.tipoDePago' => 'required',
            'lineas' => 'required|array|min:1',
            'lineas.*.fulfillLineId' => 'required',
            'lineas.*.lineNumber' => 'required',
            'lineas.*.productNumber' => 'required|string',
            'lineas.*.orderedUOMCode' => 'required|string',
            'lineas.*.unitSellingPrice' => 'required',
            'lineas.*.fulfillmentSplitReferenceId' => 'nullable',
            'lineas.*.cantidad' => 'required|numeric|gt:0',
        ]);

        try {
            DB::beginTransaction();

            $header = $request->header;

            // Parámetros para el SP del header
            $OrderTypeVenta = $header['transactionTypeCode'];
            $Source_Transaction_Identifier = DB::connection('Cloud_Interface')->select("SELECT dbo.FN_CREDIT_FOLIO(?) AS folio", [$OrderTypeVenta])[0]->folio;

            $BuyingPartyNumber_Id = $header['buyingPartyId'];
            $BuyingPartyNumber = $header['buyingPartyNumber'];
            $BuyingPartyName = $header['buyingPartyName'];
            $TransactionalCurrencyCode = $header['transactionalCurrencyCode'];
            $BusinessUnitId = $header['businessUnitId'];
            $BusinessUnitName = $header['businessUnitName'];
            $RequestedFulfillmentOrganizationId = $header['requestedFulfillmentOrganizationId'];
            $RequestedFulfillmentOrganizationCode = $header['requestedFulfillmentOrganizationCode'];
            $RequestedFulfillmentOrganizationName = $header['requestedFulfillmentOrganizationName'];
            $MetododePago = $header['metodoDePago']; // 'PUE'
            $FormaPago = $header['tipoDePago']; // '02'
            // $UsoCfdi = $header['cfdi']; // 'G02'
            $UsoCfdi = 'G02';
            $SiteId = $header['shipToCustomerId'];
            $CustomerAccountId = $header['billToCustomerAccountId'];
            $SiteUseId = $header['billToSiteUseId'];
            $OrdenVta = $header['sourceTransactionNumber'];
            $CreateBy = Auth::user()->id;

            // Ejecutar SP del header
            DB::connection('Cloud_Interface')->statement(
                'EXEC SP_CREDIT_HEADERS
                @OrderTypeVenta = ?,
                @Source_Transaction_Identifier = ?,
                @BuyingPartyNumber_Id = ?,
                @BuyingPartyNumber = ?,
                @BuyingPartyName = ?,
                @TransactionalCurrencyCode = ?,
                @BusinessUnitId = ?,
                @BusinessUnitName = ?,
                @RequestedFulfillmentOrganizationId = ?,
                @RequestedFulfillmentOrganizationCode = ?,
                @RequestedFulfillmentOrganizationName = ?,
                @MetododePago = ?,
                @FormaPago = ?,
                @UsoCfdi = ?,
                @SiteId = ?,
                @CustomerAccountId = ?,
                @SiteUseId = ?,
                @OrdenVta = ?,
                @CreateBy = ?',
                [
                    $OrderTypeVenta,
                    $Source_Transaction_Identifier,
                    $BuyingPartyNumber_Id,
                    $BuyingPartyNumber,
                    $BuyingPartyName,
                    $TransactionalCurrencyCode,
                    $BusinessUnitId,
                    $BusinessUnitName,
                    $RequestedFulfillmentOrganizationId,
                    $RequestedFulfillmentOrganizationCode,
                    $RequestedFulfillmentOrganizationName,
                    $MetododePago,
                    $FormaPago,
                    $UsoCfdi,
                    $SiteId,
                    $CustomerAccountId,
                    $SiteUseId,
                    $OrdenVta,
                    $CreateBy
                ]
            );

            // Insertar líneas con SP
            $SourceTransactionNumber = $header['sourceTransactionNumber'];

            foreach ($request->lineas as $linea) {
                $ProductNumber = $linea['productNumber'];
                $OrderedUOMCode = $linea['orderedUOMCode'];
                $OrderedQuantity = $linea['cantidad'];
                $UnitSellingPrice = -abs($linea['unitSellingPrice']); // Negativo para devolución
                $OriginalSourceLineNumber = $linea['lineNumber'];
                $FulfillLineId = $linea['fulfillLineId'];
                $FulfillmentSplitReferenceId = $linea['fulfillmentSplitReferenceId'] ?? null;

                // Ejecutar el SP para la línea
                DB::connection('Cloud_Interface')->statement(
                    'EXEC SP_CREDIT_LINES
                    @Source_Transaction_Identifier = ?,
                    @ProductNumber = ?,
                    @OrderedUOMCode = ?,
                    @OrderedQuantity = ?,
                    @SourceTransactionNumber = ?,
                    @OriginalSourceLineNumber = ?,
                    @FulfillLineId = ?,
                    @FulfillmentSplitReferenceId = ?,
                    @UnitSellingPrice = ?,
                    @OrderTypeVenta = ?',
                    [
                        $Source_Transaction_Identifier,
                        $ProductNumber,
                        $OrderedUOMCode,
                        $OrderedQuantity,
                        $SourceTransactionNumber,
                        $OriginalSourceLineNumber,
                        $FulfillLineId,
                        $FulfillmentSplitReferenceId,
                        $UnitSellingPrice,
                        $OrderTypeVenta
                    ]
                );
            }

            DB::connection('Cloud_Interface')->statement("EXEC SP_CREDIT_FOLIO ?", [$OrderTypeVenta]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Devolución creada correctamente',
                'folio' => $Source_Transaction_Identifier,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear devolución: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la devolución: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $folio)
    {
        $devolucion = XXKWCreditHeader::with('lineas')
            ->where('Source_Transaction_Identifier', $folio)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $devolucion,
        ]);
    }
}
