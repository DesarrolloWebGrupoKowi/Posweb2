<?php

namespace App\Http\Controllers;

use App\Models\XXKW_HEADERS_IVENTAS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstatusFacturasController extends Controller
{
    public function index(Request $request)
    {
        $tipo_factura = $request->filled('tipo_factura');
        // return
        $pedidos = XXKW_HEADERS_IVENTAS::select(
            'Source_Transaction_Number',
            'Buying_Party_Name',
            'STATUS',
            'ORDER_TYPE',
            'MENSAJE_ERROR',
            'FACTURA',
            'UUID',
            'Transaction_On'
        )
            ->selectSub(function ($query) {
                $query->from('XXKW_LINEAS_IVENTAS')
                    ->whereColumn('XXKW_LINEAS_IVENTAS.Source_Transaction_Identifier', 'XXKW_HEADERS_IVENTAS.Source_Transaction_Identifier')
                    ->selectRaw('SUM(Ordered_Quantity)');
            }, 'cantidad_total')
            ->selectSub(function ($query) {
                $query->from('XXKW_LINEAS_IVENTAS')
                    ->whereColumn('XXKW_LINEAS_IVENTAS.Source_Transaction_Identifier', 'XXKW_HEADERS_IVENTAS.Source_Transaction_Identifier')
                    ->selectRaw('SUM(Ordered_Quantity * ADJUSTMENT_AMOUNT)');
            }, 'venta_total')
            ->when($request->filled('pedido'), function ($query) use ($request) {
                return $query->where('Source_Transaction_Number', $request->pedido);
            })
            ->when(!$request->filled('pedido'), function ($query) use ($request) {
                return $query->whereDate('Transaction_On', $request->fecha);
                // return $query->where('ORDER_TYPE', $request->order_type)
                //     ->whereDate('Transaction_On', $request->fecha);
            })
            ->when($request->filled('tipo_factura'), function ($query) use ($request) {
                if ($request->tipo_factura === 'pos_factura') {
                    return $query->where('FACTURA', 1)->whereNotNull('UUID');
                } elseif ($request->tipo_factura === 'pos_contado') {
                    return $query->whereNull('FACTURA');
                } elseif ($request->tipo_factura === 'rutas_factura') {
                    return $query->where('FACTURA', 1)->whereNull('UUID');
                } elseif ($request->tipo_factura === 'rutas_contado') {
                    return $query->whereNull('FACTURA')->where('Source_Transaction_Identifier', 'like', 'RG%');
                }
                // return $query->whereRaw('1 = 0');
            })
            // Si no hay filtros, forzar resultado vacío
            ->when(!$tipo_factura, function ($query) {
                $query->whereRaw('1 = 0');
            })

            ->orderBy('Source_Transaction_Number')
            ->get();

        return view('EstatusFacturas/index', compact('pedidos'));
    }
}
