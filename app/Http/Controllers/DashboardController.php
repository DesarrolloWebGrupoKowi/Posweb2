<?php

namespace App\Http\Controllers;

use App\Models\DatCaja;
use App\Models\TipoMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Condition
{
    public $IdTipoMenu;
}

class DashboardController extends Controller
{
    public function Dashboard()
    {
        $idCaja =  DatCaja::where('status', 0)->where('activa', 0)->value('IdCaja');

        $tipoMenus = DB::table('CatMenus as a')
            ->leftJoin('CatTipoMenu as b', 'b.IdTipoMenu', 'a.IdTipoMenu')
            ->leftJoin('DatMenuTipoUsuario as c', 'c.IdMenu', 'a.IdMenu')
            ->select('b.IdTipoMenu')
            ->where('c.IdTipoUsuario', Auth::user()->IdTipoUsuario)
            ->distinct('b.IdTipoMenu')
            ->get();

        //Instanciar clase condition para hacer la validacion cuando el Tipo de usuario no tiene Menus asignados
        $condition[] = new Condition;
        $condition[0]->IdTipoMenu = "0";

        //return $condition;

        $tipoMenus = count($tipoMenus) == 0 ? $condition : $tipoMenus;

        foreach ($tipoMenus as $key => $tipoMenuItem) {
            if ($idCaja == 1)
                $tipoMenu[$key] = $tipoMenuItem->IdTipoMenu;
            else
            if ($tipoMenuItem->IdTipoMenu != 5)
                $tipoMenu[$key] = $tipoMenuItem->IdTipoMenu;
        }

        // return $tipoMenu;

        $menus = TipoMenu::with('DetalleMenu')
            ->whereIn('IdTipoMenu', $tipoMenu)
            ->orderBy('Posicion')
            ->get();

        //return $menus;

        return view('Dashboard.DashboardMaterial', compact('menus', 'idCaja'));
    }
}
