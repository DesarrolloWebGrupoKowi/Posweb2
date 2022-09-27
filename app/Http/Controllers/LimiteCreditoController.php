<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LimiteCredito;

class LimiteCreditoController extends Controller
{
    public function CatLimiteCredito(Request $request){
        $limitesCredito = LimiteCredito::all();

        //return $limitesCredito;

        return view('LimiteCredito.CatLimiteCredito', compact('limitesCredito'));
    }

    public function EditarLimiteCredito($tipoNomina, Request $request){
        LimiteCredito::where('TipoNomina', $tipoNomina)
                    ->update([
                        'Limite' => $request->limiteCredito,
                        'TotalVentaDiaria' => $request->totalVentasDiaria
                    ]);

        return back()->with('msjupdate', 'Se Editó Correctamente');
    }
}
