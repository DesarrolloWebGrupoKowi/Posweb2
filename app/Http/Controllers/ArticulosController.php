<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use Illuminate\Support\Facades\DB;
use App\Models\Familia;
use App\Models\Grupo;
use App\Models\TipoArticulo;

class ArticulosController extends Controller
{
    public function CatArticulos(Request $request){
        $filtroArticulo = $request->txtFiltroArticulo;

        $articulos = DB::table('CatArticulos as a')
            ->leftJoin('CatGrupos as b', 'b.IdGrupo', 'a.IdGrupo')
            ->leftJoin('CatFamilias as c', 'c.IdFamilia', 'a.IdFamilia')
            ->leftJoin('CatTipoArticulos as d', 'd.IdTipoArticulo', 'a.IdTipoArticulo')
            ->select('a.*', 'b.NomGrupo', 'c.NomFamilia', 'd.NomTipoArticulo')
            ->where('a.Status', 0)
            ->where('a.NomArticulo', 'like', '%'.$filtroArticulo.'%')
            ->orderBy('a.CodArticulo')
            ->paginate(20);

        //return $articulos;

        $familias = Familia::all();
        $grupos = Grupo::all();

        return view('Articulos.CatArticulos', compact('articulos', 'filtroArticulo', 'familias', 'grupos'));
    }

    public function EditarArticulo(Request $request, $id){

        Articulo::where('CodArticulo', $id)
                ->update([
                    'Amece' => $request->txtCodAmece,
                    'UOM' => $request->txtUOM,
                    'UOM2' => $request->txtUOM,
                    'Peso' => $request->txtPeso,
                    'Tercero' => $request->txtTercero,
                    'PrecioRecorte' => $request->txtPrecioRecorte,
                    'Factor' => $request->txtFactor,
                    'IdFamilia' => $request->txtIdFamilia,
                    'IdGrupo' => $request->txtIdGrupo,
                    'Iva' => $request->txtIva
                ]);

                $articulo = Articulo::where('CodArticulo', $id)
                                    ->first();
                
                return back()->with('msjupdate', 'Articulo ' . $articulo->NomArticulo . ' Editado!');
    }

    public function EnviarArticulo(Request $request){
        $filtroArticulo = $request->get('filtroArticulo');
        $radioBuscar = $request->radioBuscar;

        if($radioBuscar == 'radioCodigo'){

            $arrayArticulo = Articulo::where('CodArticulo', $filtroArticulo)
    ->select('CodArticulo')
    ->get()
    ->toArray();
            //return $arrayArticulo;

            $xxkw_items = DB::connection('Cloud_Tables')->table('XXKW_ITEMS')
                ->select('ITEM_NUMBER','DESCRIPTION')
                ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
                ->where('ITEM_NUMBER', $filtroArticulo)
                ->whereNotIn('ITEM_NUMBER', $arrayArticulo)
                ->get();
        }
        else{
            $arrayArticulo = Articulo::where('NomArticulo', 'like', '%'. $filtroArticulo . '%')
                ->select('CodArticulo')
                ->get()
                ->toArray();
            //return $arrayArticulo;

            $xxkw_items = DB::connection('Cloud_Tables')->table('XXKW_ITEMS')
                ->select('ITEM_NUMBER','DESCRIPTION')
                ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
                ->where('DESCRIPTION', 'like', '%'.$filtroArticulo.'%')
                ->where('ITEM_NUMBER', 'not like', 'D%')
                ->where('ITEM_NUMBER', 'not like', '%PI%')
                ->where('ITEM_NUMBER', 'not like', '%-B%')
                ->whereNotIn('ITEM_NUMBER', $arrayArticulo)
                ->get();
        }

        return view('Articulos.EnviarArticulo', compact('xxkw_items'));
    }

    public function mostrarArticulo(){

        return view('Articulos.MostrarArticulo');
    }

    public function AgregarArticulo($id){

        $articulo_XXKW_ITEMS = DB::connection('Cloud_Tables')
            ->table('XXKW_ITEMS')
            ->select('ITEM_NUMBER','DESCRIPTION')
            ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
            ->where('ITEM_NUMBER', $id)
            ->get();
        //return $articulo_XXKW_ITEMS;

        $familias = Familia::all();

        $grupos = Grupo::all();

        $tiposArticulo = TipoArticulo::where('Status', 0)
            ->get();
                                                            
        return view('Articulos.MostrarArticulo', compact('articulo_XXKW_ITEMS', 'familias', 'grupos', 'tiposArticulo'));
    }

    public function BuscarArticulo(){
        return view('Articulos.BuscarArticulo');
    }

    public function LigarArticulo(Request $request){

        try {
            DB::beginTransaction();

            $articulo_XXKW_ITEMS = DB::connection('Cloud_Tables')
                                ->table('XXKW_ITEMS')
                                ->select('Inventory_Item_Id')
                                ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
                                ->where('ITEM_NUMBER', $request->banCodArticulo)
                                ->first();

            $maxCodEtiqueta = Articulo::max('CodEtiqueta')+1;

            $articulo = new Articulo();
            $articulo -> CodArticulo = $request->banCodArticulo;
            $articulo -> NomArticulo = $request->banNomArticulo;
            $articulo -> Amece = $request->banCodAmece;
            $articulo -> UOM = $request->banUOM;
            $articulo -> UOM2 = $request->banUOM;
            $articulo -> Peso = $request->banPeso;
            $articulo -> Tercero = $request->banTercero;
            $articulo -> CodEtiqueta = $maxCodEtiqueta;
            $articulo -> PrecioRecorte = $request->banPrecioRecorte;
            $articulo -> Factor = $request->banFactor;
            $articulo -> Inventory_Item_Id = $articulo_XXKW_ITEMS->Inventory_Item_Id;
            $articulo -> IdTipoArticulo = $request->banIdTipoArticulo;
            $articulo -> IdFamilia = $request->banIdFamilia;
            $articulo -> IdGrupo = $request->banIdGrupo;
            $articulo -> Iva = $request->banIva;
            $articulo -> Status = 0;
            $articulo -> save();

            $spArticulo = DB::table('CatArticulos')
                ->select('CodArticulo')
                ->where('CodArticulo', $request->banCodArticulo)
                ->first();

            DB::statement('Execute SP_NUEVOARTICULOYPRECIO '.$spArticulo->CodArticulo.'');

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return redirect('CatArticulos')->with('msjAdd', 'Articulo Agregado!');
    }
}
