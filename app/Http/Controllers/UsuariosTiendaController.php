<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioTienda;
use App\Models\Usuario;
use App\Models\Tienda;
use App\Models\Plaza;
use Illuminate\Support\Facades\DB;

class UsuariosTiendaController extends Controller
{
    public function CatUsuariosTienda(Request $request)
    {
        $txtFiltro = $request->get('txtFiltro', '');
        $paginate = $request->get('paginate', 10);
        $usuariosTienda = DB::table('CatUsuariosTienda')
            ->leftJoin('CatUsuarios', 'CatUsuariosTienda.IdUsuario', '=', 'CatUsuarios.IdUsuario')
            ->leftJoin('CatTiendas', 'CatUsuariosTienda.IdTienda', '=', 'CatTiendas.IdTienda')
            ->leftJoin('CatPlazas', 'CatUsuariosTienda.IdPlaza', '=', 'CatPlazas.IdPlaza')
            ->select(
                'CatUsuariosTienda.IdUsuarioTienda',
                'CatUsuarios.NomUsuario',
                'CatTiendas.NomTienda',
                'CatPlazas.NomPlaza',
                'CatUsuariosTienda.IdTienda',
                'CatUsuariosTienda.IdPlaza',
                'CatUsuariosTienda.Todas'
            )
            ->where([
                ['CatUsuarios.NomUsuario', 'like', '%' . $txtFiltro . '%'],
                ['CatUsuarios.Status', '=', 0],
                ['CatUsuariosTienda.Status', '=', 0]
            ])
            ->paginate($paginate)
            ->withQueryString();

        $sqlSelect = "select * from CatUsuarios" .
            " where IdUsuario not in (select a.IdUsuario from CatUsuariosTienda as a" .
            " left join CatUsuarios as b on b.IdUsuario=a.IdUsuario" .
            " where a.Status = 0" .
            " and b.Status = 0)" .
            " and status = 0";

        $usuarios = DB::select($sqlSelect);


        $tiendas = Tienda::all();

        $plazas = DB::table('CatPlazas')
            ->where('Status', 0)
            ->get();
        //return $usuariosTienda;
        return view('UsuariosTienda.CatUsuariosTienda', compact('usuariosTienda', 'usuarios', 'tiendas', 'plazas', 'txtFiltro'));
    }

    public function CrearUsuarioTienda(Request $request)
    {
        $IdUsuario = $request->get('IdUsuario');
        $radio = $request->get('radio');

        $usuariosTienda = new UsuarioTienda();
        if ($radio == 'todas') {
            $usuariosTienda->IdUsuario = $IdUsuario;
            $usuariosTienda->IdTienda =  null;
            $usuariosTienda->IdPlaza = null;
            $usuariosTienda->Todas = 0;
            $usuariosTienda->Status = 0;
            $usuariosTienda->save();
        }
        if ($radio == 'plaza') {
            $usuariosTienda->IdUsuario = $IdUsuario;
            $usuariosTienda->IdTienda =  null;
            $usuariosTienda->IdPlaza = $request->get('IdPlaza');
            $usuariosTienda->Todas = 1;
            $usuariosTienda->Status = 0;
            $usuariosTienda->save();
        }
        if ($radio == 'tienda') {
            $usuariosTienda->IdUsuario = $IdUsuario;
            $usuariosTienda->IdTienda =  $request->get('IdTienda');
            $usuariosTienda->IdPlaza = null;
            $usuariosTienda->Todas = 1;
            $usuariosTienda->Status = 0;
            $usuariosTienda->save();
        }

        $usuario = Usuario::find($IdUsuario);

        return redirect('CatUsuariosTienda')->with('msjAdd', 'Usuario ' . $usuario->NomUsuario . ' Agregado Con Exito!');
    }
    public function EditarUsuarioTienda(Request $request, $id)
    {
        $IdPlaza = $request->get('IdPlaza');
        $IdTienda = $request->get('IdTienda');
        $radioEdit = $request->get('radioEdit');
        //return $radioEdit;

        if ($radioEdit == 'todas') {
            UsuarioTienda::where('IdUsuarioTienda', $id)
                ->update([
                    'IdPlaza' => null,
                    'IdTienda' => null,
                    'Todas' => 0
                ]);
        }
        if ($radioEdit == 'plaza') {
            UsuarioTienda::where('IdUsuarioTienda', $id)
                ->update([
                    'IdPlaza' => $IdPlaza,
                    'IdTienda' => null,
                    'Todas' => 1
                ]);
        }
        if ($radioEdit == 'tienda') {
            UsuarioTienda::where('IdUsuarioTienda', $id)
                ->update([
                    'IdPlaza' => null,
                    'IdTienda' => $IdTienda,
                    'Todas' => 1
                ]);
        }

        $usuario = DB::table('CatUsuariosTienda')
            ->leftJoin('CatUsuarios', 'CatUsuariosTienda.IdUsuario', '=', 'CatUsuarios.IdUsuario')
            ->where('CatUsuariosTienda.IdUsuarioTienda', '=', $id)
            ->first();

        return back()->with('msjupdate', 'Usuario ' . $usuario->NomUsuario . ' Modificado con Exito!');
    }
    public function EliminarUsuarioTienda($id)
    {

        $usuario = DB::table('CatUsuariosTienda')
            ->leftJoin('CatUsuarios', 'CatUsuariosTienda.IdUsuario', '=', 'CatUsuarios.IdUsuario')
            ->where('CatUsuariosTienda.IdUsuarioTienda', '=', $id)
            ->first();

        UsuarioTienda::where('IdUsuarioTienda', $id)
            ->update([
                'Status' => 1
            ]);
        return back()->with('msjdelete', 'Usuario ' . $usuario->NomUsuario . ' Eliminado Con Exito!');
    }
}
