<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatEncabezado;
use Illuminate\Support\Facades\Auth;

class VentaPorTipoPagoController extends Controller
{
    public function VentaPorTipoPago(){
        return DatEncabezado::with('TipoPago')
                ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
                ->get();
    }
}
