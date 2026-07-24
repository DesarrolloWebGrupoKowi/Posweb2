<?php

namespace App\Http\Controllers;

use App\Models\XXKW_HEADERS_IVENTAS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstatusFacturasController extends Controller
{
    public function index(Request $request)
    {
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
            ->where('FACTURA', 1)
            ->whereNotNull('UUID')
            ->orderBy('Source_Transaction_Number')
            ->get();
        // }

        return view('EstatusFacturas/index', compact('pedidos'));
    }
}
