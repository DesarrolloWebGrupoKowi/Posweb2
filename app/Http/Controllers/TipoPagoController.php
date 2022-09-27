<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoPago;

class TipoPagoController extends Controller
{
    public function CatTipoPago(){
        $tiposPago = TipoPago::all();

        return view('TipoPago.CatTipoPago', compact('tiposPago'));
    }

    public function AgregarTipoPago(Request $request){
        $tipoPago = new TipoPago();
        $tipoPago -> NomTipoPago = $request->NomTipoPago;
        $tipoPago -> ClaveSat = $request->ClaveSat;
        $tipoPago -> Status = 0;
        $tipoPago -> save();

        return back();
    }
}
