<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\DB;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function username()
    {
        return 'NomUsuario';
    }

    public function Login()
    {
        $fechaHoy = strftime("%d de %B %Y", strtotime(date('Y-m-d')));

        return view('Login.Login', compact('fechaHoy'));
    }

    public function authenticate(Request $request)
    {

        $reglas = [
            'NomUsuario' => ['required'],
            'Password' => ['required']
        ];

        request()->validate($reglas);

        $credenciales = [
            'NomUsuario' => $request->get('NomUsuario'),
            'password' => $request->get('Password'),
            'Status' => 0
        ];

        // Verificar si existe al menos una tienda activa
        $hayTiendaActiva = DB::table('CatTiendas')
            ->where('TiendaActiva', 0)
            ->get();

        // Obtener el usuario por nombre de usuario
        $usuario = DB::table('CatUsuarios')
            ->where('NomUsuario', $request->get('NomUsuario'))
            ->first();

        // Si NO hay tienda activa
        if (count($hayTiendaActiva) == 0) {
            // return 'ndloas';
            if ($usuario && $usuario->IdTipoUsuario == 2) {
                return back()->withErrors([
                    'NomUsuario' => 'Usuario no pertenece a tienda activa.'
                ]);
            }
        } else {
            // Si hay tiendas activas, validar que el usuario pertenezca a una tienda activa
            // return
            $usuarioTienda = DB::table('CatUsuariosTienda as ut')
                ->join('CatUsuarios as cu', 'ut.IdUsuario', '=', 'cu.IdUsuario')
                ->join('CatTiendas as ct', 'ut.IdTienda', '=', 'ct.IdTienda')
                ->where('cu.NomUsuario', $request->get('NomUsuario'))
                ->where('ct.TiendaActiva', 0)
                ->where('cu.Status', 0)
                ->first();

            if (!$usuarioTienda) {
                return back()->withErrors([
                    'NomUsuario' => 'El usuario no pertenece a una tienda activa.'
                ]);
            }
        }

        if (!$usuario) {
            return back()->withErrors([
                'NomUsuario' => 'Credenciales incorrectas.'
            ]);
        }

        // return dd($credenciales);

        try {
            if (Auth::attempt($credenciales)) {
                $request->session()->regenerate();
                return redirect('/Dashboard');
            } else {
                session()->flash('msjdelete', 'Las Credenciales No Coinciden');

                return redirect('Login')->withInput($request->all());
            }
        } catch (\Throwable $th) {
            return redirect('Login')->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function Logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();
        return redirect('/');
    }
}
