<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListaPrecioTienda;
use App\Models\ListaPrecio;
use Illuminate\Support\Facades\DB;

class ListasPrecioTiendaController extends Controller
{
    public function CatListaPrecioTienda(Request $request){
        
        $idTienda = $request->get('filtroIdTienda');

        $tiendas = DB::table('CatTiendas')
                        ->where('Status', 0)
                        ->get();

        $listasPrecioTienda = DB::table('DatListaPrecioTienda as a')
                                ->leftJoin('CatListasPrecio as b', 'b.IdListaPrecio', 'a.IdListaPrecio')
                                ->where('IdTienda', $idTienda)
                                ->get();

        if(empty($idTienda)){
            $listasPrecio = [];
        }
        else{
            $sqlListasPrecio = "select IdListaPrecio, NomListaPrecio from CatListasPrecio".
                               " where IdListaPrecio not in(select IdListaPrecio from DatListaPrecioTienda where IdTienda = ".$idTienda.")";
            $listasPrecio = DB::select($sqlListasPrecio);
        }
        
        return view('ListaPrecioTienda.CatListaPrecioTienda', compact('idTienda', 'tiendas', 'listasPrecio', 'listasPrecioTienda'));
    }

    public function AgregarLista(Request $request){
        $checksAgregar = $request->get('chkAgregar');
        $checksRemover = $request->get('chkRemover');
        
        if(empty($checksAgregar)){
            return back()->with('msjdelete', 'Debe Seleccionar Almenos 1 Lista de Precios!');
        }

        if($checksAgregar != [] && $checksRemover != []){
            return back()->with('msjdelete', 'No mi compa!!!!');
        }

        foreach ($checksAgregar as $checkAgregar) {
            $listaPrecioTienda = new ListaPrecioTienda();
            $listaPrecioTienda -> IdTienda = $request->get('filtroIdTienda');
            $listaPrecioTienda -> IdListaPrecio = $checkAgregar;
            $listaPrecioTienda -> Status = 0;
            $listaPrecioTienda->save();
        }
        return back()->with('msjAdd', 'Lista de Precio Agregada con Exito!');

    }

    public function RemoverLista(Request $request){
        $checksRemover = $request->get('chkRemover');
        $checksAgregar = $request->get('chkAgregar');
        
        if($checksAgregar != [] && $checksRemover != []){
            return back()->with('msjdelete', 'No mi compa!!!!');
        }

        if(empty($checksRemover)){
            return back()->with('msjdelete', 'Debe Seleccionar Almenos una Lista de Precio!');
        }

        foreach ($checksRemover as $checkRemover) {
            $sqlEliminar = "delete DatListaPrecioTienda".
                       " where IdTienda = ".$request->get('filtroIdTienda')."".
                       " and IdListaPrecio = ".$checkRemover."";
            DB::statement($sqlEliminar);
        }
        return back()->with('msjAdd', 'Lista de Precio Removida con Exito!');
    }

    public function CrearListaPrecioTienda(Request $request){
        
        $listasPrecio = $request->get('chkListaPrecio');
        $idTienda = $request->get('IdTienda');

        foreach ($listasPrecio as $listaPrecio) {
            $listaPrecioTienda = new ListaPrecioTienda();
            $listaPrecioTienda -> IdTienda = $idTienda;
            $listaPrecioTienda -> IdListaPrecio = $listaPrecio;
            $listaPrecioTienda -> Status = 0;
            $listaPrecioTienda->save();
        }

        return redirect('CatListaPrecioTienda');
    }

}
