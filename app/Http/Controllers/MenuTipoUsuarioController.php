<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuTipoUsuario;
use App\Models\TipoUsuario;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class MenuTipoUsuarioController extends Controller
{
    public function DatMenuTipoUsuario(Request $request){
        $tipoUsuarios = DB::table('CatTipoUsuarios')
                            ->where('Status', 0)
                            ->get();
                            

        $filtroIdTipoUsuario = $request->get('IdTipoUsuario');
 
        $menuTipoUsuarios = DB::table('DatMenuTipoUsuario as a')
                                ->leftJoin('CatMenus as b', 'b.IdMenu', 'a.IdMenu')
                                ->select(
                                    'a.IdMenuTipoUsuario as dmtpIdMenuTipoUsuario',
                                    'a.IdTipoUsuario as dmtpIdTipoUsuario',
                                    'a.IdMenu as dmtpIdMenu',
                                    'a.Status as dmtpStatus',
                                    'b.IdMenu as cmpIdMenu',
                                    'b.NomMenu as cmpNomMenu',
                                    'b.IdTipoMenu as cmpIdTipoMenu',
                                    'b.Link as cmpLink',
                                    'b.Status as cmpStatus'
                                )
                                ->where('a.IdTipoUsuario', $filtroIdTipoUsuario)
                                ->get();

            if($filtroIdTipoUsuario == "" || $filtroIdTipoUsuario == 0){
                $menus = [];
            }
            else{
                $select = "select * from CatMenus".
                          " where IdMenu not in(select IdMenu from DatMenuTipoUsuario where IdTipoUsuario = ".$filtroIdTipoUsuario.")";
                $menus = DB::select($select);
            }
            //return $menus;

            $TipoUsuarioFind = TipoUsuario::find($filtroIdTipoUsuario);
        //return $TipoUsuarioFind;
        return view('MenuTipoUsuario.DatMenuTipoUsuario', compact('tipoUsuarios','filtroIdTipoUsuario', 'menuTipoUsuarios', 'menus','TipoUsuarioFind'));
    }

    public function RemoverMenu(Request $request){
        $idTipoUsuario = $request->get('IdTipoUsuario');
        $listas = $request->get('chkRemoverMenu');

        if(empty($listas)){
            return back()->with('msjdelete', 'Debe Seleccionar una Opción!');
        }
        
        foreach ($listas as $lista) {
            $sqlDelete = "delete DatMenuTipoUsuario".
                         " where IdTipoUsuario = ".$idTipoUsuario."".
                         " and IdMenu = ".$lista."";
            //echo $sqlUpdate;
            DB::statement($sqlDelete);
        }
        return back()->with('msjAdd', 'Menu Removido con exito!');
    }

    public function AgregarMenu(Request $request){
        $idTipoUsuario = $request->get('IdTipoUsuario');
        $listas = $request->get('chkAgregarMenu');
        //return $listas;

        if(empty($listas)){
            return back()->with('msjdelete', 'Debe Seleccionar una Opción!');
        }

        foreach ($listas as $lista) {
            //$menu = Menu::find($lista);
            
            DB::table('DatMenuTipoUsuario')
            ->insert([
                'IdTipoUsuario' => $idTipoUsuario,
                'IdMenu' => $lista,
                'Status' => 0
            ]);
        }
        return back()->with('msjAdd', 'Menu Agregado con exito!');
    }
}
