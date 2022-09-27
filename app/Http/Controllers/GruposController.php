<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;

class GruposController extends Controller
{
    public function CatGrupos(){
        $grupos = Grupo::all();
        return view('Grupos.CatGrupos', compact('grupos'));
    }

    public function CrearGrupo(Request $request){
        $grupo = new Grupo();
        $grupo -> NomGrupo = $request->get('NomGrupo');
        $grupo -> Status = 0;
        $grupo->save();

        return redirect('CatGrupos');
    }
}
