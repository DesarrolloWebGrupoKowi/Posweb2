<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function CatUsuarios(Request $request){
        $txtFiltro = trim($request->get('txtFiltro'));
        $Activo = $request->get('Activo');
        $totalUsuarios = Usuario::all()->count();
        
        if($Activo == null or $Activo == '0' ){
            $usuarios = DB::table('CatUsuarios')
            ->leftJoin('CatTipoUsuarios','CatUsuarios.IdTipoUsuario','=','CatTipoUsuarios.IdTipoUsuario')
            ->select('IdUsuario','NomUsuario','NumNomina','Correo','CatTipoUsuarios.NomTipoUsuario','CatUsuarios.IdTipoUsuario','CatUsuarios.Status')
            ->where('CatUsuarios.Status','=', '0')
            ->where('NomUsuario','like','%'.$txtFiltro.'%')
            ->whereNotIn('IdUsuario', [Auth::user()->IdUsuario])
            ->paginate(10);
        }
        else{
            $usuarios = DB::table('CatUsuarios')
            ->leftJoin('CatTipoUsuarios','CatUsuarios.IdTipoUsuario','=','CatTipoUsuarios.IdTipoUsuario')
            ->select('IdUsuario','NomUsuario','NumNomina','Correo','CatTipoUsuarios.NomTipoUsuario','CatUsuarios.IdTipoUsuario','CatUsuarios.Status')
            ->where('CatUsuarios.Status','=', $Activo)
            ->where('NomUsuario','like','%'.$txtFiltro.'%')
            ->whereNotIn('IdUsuario', [Auth::user()->IdUsuario])
            ->paginate(10)->withQueryString();
        }

        //return $usuarios;
        $tipoUsuarios = DB::table('CatTipoUsuarios')
                            ->Where('Status','=','0')
                            ->get();


        return view('Usuarios/CatUsuarios', compact('usuarios', 'tipoUsuarios', 'Activo', 'txtFiltro'));
        //return $usuarios;
    }

    public function CrearUsuario(Request $request){
        try {
            DB::beginTransaction();

            $NomUsuario = $request->get('NomUsuario');
            $NumNomina = $request->get('NumNomina');
            $Password = $request->get('Password');
            $Correo = $request->get('Correo');
            $IdTipoUsuario = $request->get('IdTipoUsuario');
            $Status = "0";

            if(!Usuario::where('NomUsuario', $NomUsuario)->exists()){
                $usuario = new Usuario();
                $usuario->NomUsuario = $NomUsuario;
                $usuario->NumNomina = $NumNomina;
                $usuario->Password = bcrypt($Password);
                $usuario->Correo = $Correo;
                $usuario->IdTipoUsuario = $IdTipoUsuario;
                $usuario->Status = $Status;
                $usuario->save();

            }else{
                return back()->with('El nombre de usuario ya existe:' . $NomUsuario);
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return redirect("CatUsuarios")->with('msjAdd', 'Usuario: '.$NomUsuario.' Agregado con Exito!');
    }

    //FUNCTION NO USADA!!
    public function Eliminar(Request $request, $id){
    
        $NomAdmin = $request->get('userAdmin');
        $PassAdmin = $request->get('passAdmin');

        $SqlSelect = "select IdUsuario, NomUsuario, CONVERT(VARCHAR(MAX), DECRYPTBYPASSPHRASE('password', Password)) as Pass from CatUsuarios where NomUsuario = '".$NomAdmin."'".
                      " and IdTipoUsuario = 1 and Status = 0 and CONVERT(VARCHAR(MAX), DECRYPTBYPASSPHRASE('password', Password)) = '".$PassAdmin."' COLLATE Latin1_General_CS_AS;";
        $UsuarioAdmin = DB::select($SqlSelect);

        if(empty($UsuarioAdmin)){
            return redirect("CatUsuarios")->with('msjErrorValida','No Existe tu Usuario mi loco');
        }
        else{
            Usuario::where('IdUsuario', $id)
                    ->Update([
                        'Status' => 1
                    ]);

            $NomUsuarioEliminado = DB::table('CatUsuarios')
                                    ->select('NomUsuario')
                                    ->where('IdUsuario','=', $id)
                                    ->first();

            return redirect('CatUsuarios')->with('msjEliminadoLogico',''.$NomUsuarioEliminado->NomUsuario.' Ha Sido Eliminado!');
        }

    }
//TERMINA FUNCTION NO USADA!

    public function EditarUsuario(Request $request, $id){

        $usuario = Usuario::find($id);
        $NomUsuario = $usuario->NomUsuario;

        $NumNomina = $request->get('NumNomina');
        $Correo = $request->get('Correo');
        $IdTipoUsuario = $request->get('IdTipoUsuario');

        Usuario::where('IdUsuario', $id)
            ->update([
           'NumNomina' => $NumNomina,
           'Correo' => $Correo,
           'IdTipoUsuario' => $IdTipoUsuario
        ]);

        return back()->with('msjupdate', 'Usuario '.$NomUsuario. ' Modificado con Exito! ');
        
    }
    
    public function CambiarContraseña(Request $request, $id){
        $usuario = Usuario::find($id);
        $NomUsuario = $usuario->NomUsuario;
        $Correo = $usuario->Correo;
        
        $Password1 = $request->get('Password1');
        $Password2 = $request->get('Password2');
        
        if($Password1 == $Password2){
            Usuario::where('IdUsuario', $id)
                    ->update([
                        'password' => bcrypt($Password1)
                    ]);

        $asunto = 'Se ha cambiado su Contraseña POSWEB2';
        $mensaje = 'La Contraseña del Usuario '.$NomUsuario.' Fue Modificada, Por el Usuario '. Auth::user()->NomUsuario;

        DB::statement("Execute SP_ENVIAR_MAIL '".$Correo."', '".$asunto."', '".$mensaje."'");

        return redirect("CatUsuarios")->with('msjAdd', 'Contraseña del Usuario '.$NomUsuario.' Modificada con Exito!');
        }
        else{
            return redirect("CatUsuarios")->with('msjErrorValida','Las Nuevas Contraseñas No Coinciden!');
        }
    }

    public function ActivarUsuario(Request $request, $id){
        $PassUsuario = $request->get('passAdmin');

        if(!Hash::check($PassUsuario, auth()->user()->password)){
            return back()->with('msjdelete', 'Contraseña Incorrecta!');
        }

        session()->passwordConfirmed();
        Usuario::where('IdUsuario', $id)
                    ->Update([
                        'Status' => 0
                    ]);

        $usuario = Usuario::find($id);

        return back()->with('msjAdd', $usuario->NomUsuario . ' Ha Sido Activado de Nueva Cuenta!!');
    }

    public function MiPerfil(){
        return view('Usuarios.MiPerfil');
    }

    public function CambiarPassword(Request $request, $id){
        Usuario::where('IdUsuario', $id)
                ->update([
                    'password' => bcrypt($request->editPassword)
                ]);

        return back()->with('msjAdd', 'Contraseña Actualizada!');
    }

    public function EditarPerfil(Request $request, $id){
        Usuario::where('IdUsuario', $id)
                ->update([
                    'Correo' => $request->editCorreo,
                    'NumNomina' => $request->editNumNomina,
                    'IdTipoUsuario' => $request->editIdTipoUsuario
                ]);
                
        return back()->with('msjupdate', 'Usuario Actualizado!');
    }
}
