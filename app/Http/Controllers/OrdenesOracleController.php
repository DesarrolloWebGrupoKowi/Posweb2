<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrdenesOracleController extends Controller
{
    public function index(Request $request)
    {

        return view('OrdenesOracle/index');
    }

    public function buscarTicket(Request $request)
    {
        $request->validate([
            'IdEncabezado' => 'required|string',
            'Source_Transaction_Identifier' => 'required|string'
        ]);

        try {
            $resultados = DB::table('DatCortesTienda as DC')
                ->join('CatArticulos as CA', 'CA.IdArticulo', '=', 'DC.IdArticulo')
                ->select(
                    'CA.CodArticulo as productNumber',
                    DB::raw('SUM(DC.CantArticulo) as quantity')  // Sumar cantidades por producto
                )
                ->where('DC.IdEncabezado', $request->IdEncabezado)
                ->where('DC.Source_Transaction_Identifier', $request->Source_Transaction_Identifier)
                ->where('DC.StatusVenta', 0)
                ->where('CA.Status', 0)
                ->groupBy('CA.CodArticulo')  // Agrupar por código del artículo
                ->get();

            // Log para depuración
            Log::info('Ticket encontrado:', [
                'IdEncabezado' => $request->IdEncabezado,
                'Source_Transaction_Identifier' => $request->Source_Transaction_Identifier,
                'registros' => $resultados->count(),
                'data' => $resultados->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => $resultados
            ]);
        } catch (\Exception $e) {
            Log::error('Error al buscar ticket: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al consultar el ticket: ' . $e->getMessage()
            ], 500);
        }
    }
}
