<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuTipoUsuario;
use App\Models\TipoMenu;
use App\Models\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Condition
{
    public $IdTipoMenu;
}

class MenuPoswebController extends Controller
{
    public function CatMenuPosWeb(Request $request)
    {
        $tipoMenus = TipoMenu::where('Status', 0)
            ->get();

        $filtroMenu = $request->txtFiltroMenu;

        $menusPosweb = DB::table('CatMenus as a')
            ->leftJoin('CatTipoMenu as b', 'b.IdTipoMenu', 'a.IdTipoMenu')
            ->select(
                'a.IdMenu as cmpIdMenu',
                'a.NomMenu as cmpNomMenu',
                'a.IdTipoMenu as cmpIdTipoMenu',
                'a.Link as cmpLink',
                'a.Icono as cmpIcono',
                'a.BgColor as cmpBgColor',
                'a.Status as cmpStatus',
                'b.IdTipoMenu as ctmIdTipoMenu',
                'b.NomTipoMenu as ctmNomTipoMenu',
                'b.Status as ctmStatus'
            )
            ->where('a.NomMenu', 'like', '%' . $filtroMenu . '%')
            ->paginate(10)
            ->withQueryString();
        //return $menusPosweb;
        return view('Menus.CatMenuPosweb', compact('tipoMenus', 'menusPosweb', 'filtroMenu'));
    }

    public function CrearMenuPosweb(Request $request)
    {
        $nomMenu = $request->get('NomMenu');
        $idTipoMenu = $request->get('IdTipoMenu');
        $link = $request->get('Link');
        $icono = $request->get('Icono');
        $bgColor = $request->get('BgColor');

        $MenuPosweb = new Menu();
        $MenuPosweb->NomMenu = $nomMenu;
        $MenuPosweb->IdTipoMenu = $idTipoMenu;
        $MenuPosweb->Link = $link;
        $MenuPosweb->Status = 0;
        $MenuPosweb->Icono = $icono;
        $MenuPosweb->BgColor = $bgColor;
        $MenuPosweb->save();
        return redirect('CatMenuPosweb')->with('msjAdd', 'Menu Posweb Creado Correctamente!!');
    }

    public function EditarMenu(Request $request, $id)
    {

        Menu::where('IdMenu', $id)
            ->update([
                'NomMenu' => $request->get('NomMenu'),
                'IdTipoMenu' => $request->get('IdTipoMenu'),
                'Link' => $request->get('Link'),
                'Icono' => $request->get('Icono'),
                'BgColor' => $request->get('BgColor'),
            ]);

        $menuPosweb = Menu::find($id);

        return back()->with('msjupdate', $menuPosweb->NomMenu . ' Editado Correctamente!');
    }

    public function OrdenarMenus(Request $request)
    {
        $tiposUsuario = TipoUsuario::where('Status', 0)
            ->get();

        $idTipoUsuario = $request->idTipoUsuario;

        $tipoMenus = DB::table('CatMenus as a')
            ->leftJoin('CatTipoMenu as b', 'b.IdTipoMenu', 'a.IdTipoMenu')
            ->leftJoin('DatMenuTipoUsuario as c', 'c.IdMenu', 'a.IdMenu')
            ->select('b.IdTipoMenu')
            ->where('c.IdTipoUsuario', $idTipoUsuario)
            ->distinct('b.IdTipoMenu')
            ->get();

        /*Instanciar clase condition para hacer la validacion
        cuando el Tipo de usuario no tiene Menus asignados*/
        $condition[] = new Condition;
        $condition[0]->IdTipoMenu = "0";

        //return $condition;

        $tipoMenus = count($tipoMenus) == 0 ? $condition : $tipoMenus;

        foreach ($tipoMenus as $key => $tipoMenuItem) {
            $tipoMenu[$key] = $tipoMenuItem->IdTipoMenu;
        }

        //return $tipoMenu;

        $menus = TipoMenu::with(['Ordenar' => function ($query) use ($idTipoUsuario) {
            $query->where('IdTipoUsuario', $idTipoUsuario)
                ->orderBy('Posicion');
        }])
            ->whereIn('IdTipoMenu', $tipoMenu)
            ->get();

        //return $menus;

        return view('Menus.OrdenarMenus', compact('tiposUsuario', 'idTipoUsuario', 'menus'));
    }

    public function EditarPosicionMenu(Request $request)
    {
        $idTipoUsuario = $request->idTipoUsuario;
        $posicion = $request->posicion;

        foreach ($posicion as $key => $posicionMenu) {
            MenuTipoUsuario::where('IdTipoUsuario', $idTipoUsuario)
                ->where('IdMenu', $key)
                ->update([
                    'Posicion' => $posicionMenu,
                ]);
        }

        return back();
    }
}
