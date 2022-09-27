<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Familia;

class FamiliaArticulosController extends Controller
{
    public function CatFamilias(Request $request){
        $familias = Familia::where('NomFamilia', 'like', '%'.$request->txtFiltro.'%')
                            ->orWhere('IdFamilia', $request->txtFiltro)
                            ->orderby('IdFamilia')
                            ->paginate(15);
        
        $txtFiltro = $request->txtFiltro;

        return view('Familias.CatFamilias', compact('familias', 'txtFiltro'));
    }

    public function CrearFamilia(Request $request){
        $familia = new Familia();
        $familia -> NomFamilia = $request->get('NomFamilia');
        $familia -> Status = 0;
        $familia->save();

        return redirect('CatFamilias');
    }
}
