<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListaPrecio;
use App\Models\Articulo;

class ListasPrecioController extends Controller
{
    public function CatListasPrecio(Request $request){
        $filtroListaPrecio= $request->txtListaPrecio;
        $selectListaPrecio = ListaPrecio::where('Status', 0)
                            ->get();
        $listasPrecio = ListaPrecio::where('NomListaPrecio', 'like', '%'. $filtroListaPrecio .'%')
                                    ->where('Status', 0)
                                    ->get();

        return view('ListasPrecio.CatListasPrecio', compact('listasPrecio', 'filtroListaPrecio', 'selectListaPrecio'));
    }

    public function CrearListaPrecio(Request $request){

            $listaPrecio = new ListaPrecio();
            $listaPrecio -> NomListaPrecio = $request->get('NomListaPrecio');
            $listaPrecio -> PesoMinimo = $request->get('PesoMinimo');
            $listaPrecio -> PesoMaximo = $request->get('PesoMaximo');
            $listaPrecio -> PorcentajeIva = $request->get('PorcentajeIva');
            $listaPrecio -> Status = 0;
            $listaPrecio->save();

            $idListaPrecio = ListaPrecio::max('IdListaPrecio');

        if($request->checkExistente == 'on'){

            $insertExistenteTmp = "insert into DatPreciosTmp".
                               " select ".$idListaPrecio.", CodArticulo, PrecioArticulo, FechaPara". 
                               " from DatPreciosTmp".
                               " where IdListaPrecio = ".$request->selectListaPrecio."";
            DB::statement($insertExistenteTmp);

            $insertExistente = "insert into DatPrecios".
                               " select ".$idListaPrecio.", CodArticulo, PrecioArticulo". 
                               " from DatPrecios".
                               " where IdListaPrecio = ".$request->selectListaPrecio."";
            DB::statement($insertExistente);
        }
        else{
            $insertListaPrecioTmp = "insert into DatPreciosTmp".
                                    " select ".$idListaPrecio.", CodArticulo, 0, CAST(GETDATE() as date)".
                                    " from CatArticulos";
            DB::statement($insertListaPrecioTmp);

            $insertListaPrecio = "insert into DatPrecios".
                                 " select ".$idListaPrecio.", CodArticulo, 0".
                                 " from CatArticulos";
            DB::statement($insertListaPrecio);
        }

        return redirect('CatListasPrecio')->with('msjAdd', 'Se Creo Correctamente la Lista de Precios: '.$request->get('NomListaPrecio'));
    }

    public function EditarListaPrecio(Request $request, $id){
        ListaPrecio::where('IdListaPrecio', $id)
                    ->update([
                        'PesoMinimo' => $request->get('PesoMinimo'),
                        'PesoMaximo' => $request->get('PesoMaximo'),
                        'PorcentajeIva' => $request->get('PorcentajeIva')
                    ]);
                    
        $lista = ListaPrecio::find($id);
        return back()->with('msjupdate', 'Lista de Precios: '.$lista->NomListaPrecio.' Editada Correctamente!');
    }
}
