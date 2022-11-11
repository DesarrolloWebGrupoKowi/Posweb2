<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\DB;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function username(){
        return 'NomUsuario';
    }

    public function Login(){
        $fechaHoy = strftime("%d de %B %Y", strtotime(date('Y-m-d')));
    
        return view('Login.Login', compact('fechaHoy'));
    }

    public function authenticate(Request $request){

        $reglas = [
            'NomUsuario' => ['required'],
            'Password' => ['required']
        ];

        request()->validate($reglas);

        $credenciales = [
            'NomUsuario' => $request->get('NomUsuario'),
            'password' => $request->get('Password'),
            'Status' => 0,
        ];
        //return dd($credenciales);

        try {
            if(Auth::attempt($credenciales)){
                $request->session()->regenerate();            
                return redirect('/Dashboard');
            }
            else{
                session()->flash('msjdelete','Las Credenciales No Coinciden');
                
                return redirect('Login')->withInput($request->all());
            }
        } catch (\Throwable $th) {
            return redirect('Login')->with('msjdelete', 'Error: '.$th->getMessage());
        }
    }

    public function Logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();
        return redirect('/');
    }
}
