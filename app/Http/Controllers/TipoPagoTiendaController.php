<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tienda;
use App\Models\TipoPago;
use App\Models\TipoPagoTienda;

class TipoPagoTiendaController extends Controller
{
    public function DatTipoPagoTienda(Request $request){

        empty($request->idTienda) ? $idTienda = 1 : $idTienda = $request->idTienda;

        $tiendas = Tienda::all();

        $tiposPagoTienda = Tienda::with('TiposPago')
                        ->where('IdTienda', $idTienda)
                        ->get();

        $datTiposPago = TipoPagoTienda::where('Status', 0)
                        ->select('IdTipoPago')
                        ->where('IdTienda', $idTienda)
                        ->get();

        $tiposPagoFaltantes = TipoPago::where('Status', 0)
                        ->whereNotIn('IdTipoPago', $datTiposPago)
                        ->get();

        //return $tiposPagoFaltantes;

        return view('TipoPagoTienda.DatTipoPagoTienda', compact('tiendas', 'idTienda', 'tiposPagoTienda', 'tiposPagoFaltantes'));
    }

    public function AgregarDatTipoPagoTienda(Request $request){
        $idTienda = $request->idTienda;
        $idTiposPago = $request->chkIdTipoPagoAdd;
        
        if(empty($idTiposPago)){
            return back()->with('msjdelete', 'Debe Seleccionar al Menos un Tipo de Pago a Agregar');
        }

        foreach ($idTiposPago as $key => $idTipoPago) {
            TipoPagoTienda::insert([
                'IdTipoPago' => $idTipoPago,
                'IdTienda' => $idTienda,
                'Status'=> 0
            ]);
        }

        return back()->with('msjAdd', 'Tipo(s) de Pago Agregado(s)');
    }

    public function RemoverDatTipoPagoTienda(Request $request){
        $idTienda = $request->idTienda;
        $idTiposPago = $request->chkIdTipoPagoRemove;

        if(empty($idTiposPago)){
            return back()->with('msjdelete', 'Debe Seleccionar al Menos un Tipo de Pago a Remover');
        }

        foreach ($idTiposPago as $key => $idTipoPago) {
            TipoPagoTienda::where('IdTipoPago', $idTipoPago)
                            ->where('IdTienda', $idTienda)
                            ->delete();
        }

        return back()->with('msjdelete', 'Tipo(s) de Pago Eliminado(s)');
    }
}
