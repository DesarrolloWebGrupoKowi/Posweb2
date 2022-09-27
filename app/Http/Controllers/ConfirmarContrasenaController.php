<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class ConfirmarContrasenaController extends Controller
{
    public function ConfirmarContrasena(){
        return view('Login.ConfirmarContrasena');
    }

    public function ConfirmContrasena(Request $request, $id){
        if(!Hash::check($request->passAdmin, auth()->user()->password)){
            return back()->with('msjdelete','Contraseña Incorrecta!');
        }
        
        session()->passwordConfirmed();
        Usuario::where('IdUsuario', $id)
                    ->Update([
                        'Status' => 1
                    ]);
        
        $usuario = Usuario::find($id);
        return back()->with('msjupdate','Usuario: ' . $usuario->NomUsuario . ' Eliminado Con Exito!');

    }
}
