<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\DB;

class TipoUsuariosController extends Controller
{
    public function CatTipoUsuarios(Request $request){
        $filtroActivo = $request->filtroActivo;

        $tipoUsuarios = DB::table('CatTipoUsuarios')
                        ->Where('Status', $filtroActivo == '' ? $filtroActivo = 0 : $filtroActivo)
                        ->get();
                        //return $tipoUsuarios;
        return view('TipoUsuarios.CatTipoUsuarios',compact('tipoUsuarios', 'filtroActivo'));
    }

    public function CrearTipoUsuario(Request $request){
        $TipoUsuario = new TipoUsuario();
        $TipoUsuario -> NomTipoUsuario = $request->get('NomTipoUsuario');
        $TipoUsuario -> Status = 0;
        $TipoUsuario->save();
        return redirect("CatTipoUsuarios")->with('msjAdd', 'Tipo de Usuario: '.$TipoUsuario->NomTipoUsuario.' Agregado con Exito!');
    }

    public function EditarTipoUsuario(Request $request, $id){
       TipoUsuario::where('IdTipoUsuario', $id)
                    ->update([
                        'NomTipoUsuario' => $request->NomTipoUsuario
                    ]);
        $tipoUsuario = TipoUsuario::find($id);

        return back()->with('msjupdate', 'Tipo de Usuario '.$tipoUsuario->NomTipoUsuario. ' Editado Con Exito!');
    }

    public function EliminarTipoUsuario($id){
        TipoUsuario::where('IdTipoUsuario', $id)
                    ->update([
                        'Status' => 1
                    ]);
        $tipoUsuario = TipoUsuario::find($id);

        return back()->with('msjdelete', 'Tipo de Usuario '.$tipoUsuario->NomTipoUsuario. ' Eliminado Con Exito!');
    }
}
