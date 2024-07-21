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
    public function DatMenuTipoUsuario(Request $request)
    {
        $tipoUsuarios = DB::table('CatTipoUsuarios')
            ->where('Status', 0)
            ->get();


        $filtroIdTipoUsuario = $request->get('IdTipoUsuario');

        $menuTipoUsuarios = DB::table('DatMenuTipoUsuario as a')
            ->leftJoin('CatMenus as b', 'b.IdMenu', 'a.IdMenu')
            ->leftJoin('CatTipoMenu as c', 'c.IdTipoMenu', 'b.IdTipoMenu')
            ->select(
                'a.IdMenuTipoUsuario as dmtpIdMenuTipoUsuario',
                'a.IdTipoUsuario as dmtpIdTipoUsuario',
                'a.IdMenu as dmtpIdMenu',
                'a.Status as dmtpStatus',
                'b.IdMenu as cmpIdMenu',
                'b.NomMenu as cmpNomMenu',
                'b.IdTipoMenu as cmpIdTipoMenu',
                'b.Link as cmpLink',
                'b.Status as cmpStatus',
                'c.NomTipoMenu as NomTipoMenu'
            )
            ->where('a.IdTipoUsuario', $filtroIdTipoUsuario)
            ->orderBy('c.NomTipoMenu')
            ->orderBy('cmpNomMenu')
            ->get();

        if ($filtroIdTipoUsuario == "" || $filtroIdTipoUsuario == 0) {
            $menus = [];
        } else {
            $select = "select * from CatMenus as cm " .
                "left join CatTipoMenu as ct on cm.IdTipoMenu = ct.IdTipoMenu" .
                " where cm.IdMenu not in(select IdMenu from DatMenuTipoUsuario where IdTipoUsuario = " . $filtroIdTipoUsuario . ")" .
                " order by NomTipoMenu, NomMenu";
            $menus = DB::select($select);
        }
        // return $menus;

        $TipoUsuarioFind = TipoUsuario::find($filtroIdTipoUsuario);

        return view('MenuTipoUsuario.DatMenuTipoUsuario', compact('tipoUsuarios', 'filtroIdTipoUsuario', 'menuTipoUsuarios', 'menus', 'TipoUsuarioFind'));
    }

    public function RemoverMenu(Request $request)
    {
        $idTipoUsuario = $request->get('IdTipoUsuario');
        $listas = $request->get('chkRemoverMenu');

        if (empty($listas)) {
            return back()->with('msjdelete', 'Debe Seleccionar una Opción!');
        }

        foreach ($listas as $lista) {
            $sqlDelete = "delete DatMenuTipoUsuario" .
                " where IdTipoUsuario = " . $idTipoUsuario . "" .
                " and IdMenu = " . $lista . "";
            //echo $sqlUpdate;
            DB::statement($sqlDelete);
        }
        return back()->with('msjAdd', 'Menu Removido con exito!');
    }

    public function AgregarMenu(Request $request)
    {
        $idTipoUsuario = $request->get('IdTipoUsuario');
        $listas = $request->get('chkAgregarMenu');
        //return $listas;

        if (empty($listas)) {
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
