<?php

namespace App\Http\Controllers;

use App\Models\XXKW_HEADERS_IVENTAS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterfazController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los tipos de orden para el filtro
        $orderTypes = DB::connection('Cloud_Interface')
            ->table('ORDER_TYPE_VW')
            ->select('ORDER_TYPE', 'DESCRIPCION')
            ->orderBy('ORDER_TYPE')
            ->get();

        // Solo consultar si hay filtros aplicados
        $pedidos = collect();
        $filtrosAplicados = false;

        // Solo validar si hay algún filtro activo (se hizo clic en Filtrar)
        $hasAnyFilter = $request->filled('pedido') ||
            $request->filled('order_type') ||
            $request->filled('fecha');

        if ($hasAnyFilter) {
            if ($request->filled('pedido')) {
                // Si viene pedido, no requiere más filtros
                $request->validate([
                    'pedido' => 'nullable|string',
                ]);
                $filtrosAplicados = true;
            } else {
                // Si no, order_type y fecha son obligatorios
                $request->validate([
                    'order_type' => 'required|string',
                    'fecha' => 'required|date',
                ], [
                    'order_type.required' => 'El tipo de orden es obligatorio o busca por pedido.',
                    'fecha.required' => 'La fecha es obligatoria o busca por pedido.',
                ]);
                $filtrosAplicados = true;
            }
        }

        if ($filtrosAplicados) {
            $pedidos = XXKW_HEADERS_IVENTAS::query()
                ->select(
                    'Source_Transaction_Number',
                    'Buying_Party_Name',
                    'STATUS',
                    'ORDER_TYPE',
                    'MENSAJE_ERROR',
                    'FACTURA',
                    'UUID',
                    'Transaction_On'
                )
                ->when($request->filled('pedido'), function ($query) use ($request) {
                    return $query->where('Source_Transaction_Number', 'LIKE', '%' . $request->pedido . '%');
                })
                ->when(!$request->filled('pedido'), function ($query) use ($request) {
                    return $query->where('ORDER_TYPE', $request->order_type)
                        ->whereDate('Transaction_On', $request->fecha);
                })
                ->orderBy('FACTURA')
                ->orderBy('Source_Transaction_Number')
                ->get();
        }

        return view('Interfaz.index', compact('pedidos', 'orderTypes', 'filtrosAplicados'));
    }
}
