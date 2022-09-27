<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MovimientoProducto;

class MovimientosProductoController extends Controller
{
    public function CatMovimientosProducto(Request $request){
        $movimientosProducto = MovimientoProducto::where('Status', 0)
            ->get();

        return view('MovimientosProducto.CatMovimientosProducto', compact('movimientosProducto'));
    }

    public function AgregarMovimiento(Request $request){
        try {
            DB::beginTransaction();

            MovimientoProducto::insert([
                'NomMovimiento'=> $request->nomMovimiento,
                'Status' => 0
            ]);

            DB::commit();

            return back()->with('msjAdd', 'Se Agrego Nuevo Movimiento: '. $request->nomMovimiento);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('msjdelete', 'Error'. $th->getMessage());
        }
    }
}
