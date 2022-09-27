<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoMenu;
use Illuminate\Support\Facades\DB;

class TipoMenuController extends Controller
{
    public function CatTipoMenu(Request $request){

        $activo = $request->get('activo');

        if($activo == 0 || empty($activo)){
            $tipoMenus = DB::table('CatTipoMenu')
                        ->where('Status', 0)
                        ->get();
        }
        else{
            $tipoMenus = DB::table('CatTipoMenu')
                        ->where('Status', 1)
                        ->get();
        }
                
        return view('TipoMenu.CatTipoMenu', compact('tipoMenus', 'activo'));
    }
    public function CrearTipoMenu(Request $request){
        $NomTipoMenu = $request->get('NomTipoMenu');
        $TipoMenu = new TipoMenu();
        $TipoMenu -> NomTipoMenu = $NomTipoMenu;
        $TipoMenu -> Status = 0;
        $TipoMenu->save();
        return redirect('CatTipoMenu')->with('msjAdd','Tipo de Menu Agregado Correctamente!!');
    }

    public function EditarTipoMenu(Request $request, $id){
        TipoMenu::where('IdTipoMenu', $id)
                    ->update([
                        'NomTipoMenu' => $request->get('NomTipoMenu'),
                        'Status' => $request->get('Status')
                    ]);
                    
        $tipoMenu = TipoMenu::find($id);

        return back()->with('msjupdate','Tipo de Menú: ' . $tipoMenu->NomTipoMenu . ' Editado Correctamente!!');
    }
}
