<?php

namespace App\Http\Controllers;

use App\Exports\ArticulosExport;
use App\Models\Articulo;
use App\Models\Familia;
use App\Models\Grupo;
use App\Models\TipoArticulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ArticulosController extends Controller
{
    public function CatArticulos(Request $request)
    {
        $txtFiltro = $request->txtFiltro;

        $articulos = DB::table('CatArticulos as a')
            ->leftJoin('CatGrupos as b', 'b.IdGrupo', 'a.IdGrupo')
            ->leftJoin('CatFamilias as c', 'c.IdFamilia', 'a.IdFamilia')
            ->leftJoin('CatTipoArticulos as d', 'd.IdTipoArticulo', 'a.IdTipoArticulo')
            ->select('a.*', 'b.NomGrupo', 'c.NomFamilia', 'd.NomTipoArticulo')
            ->where('a.Status', 0)
            ->when($txtFiltro, function ($q) use ($txtFiltro) {
                $q->where('a.NomArticulo', 'like', '%' . $txtFiltro . '%');
                $q->orWhere('a.IdArticulo', 'like', '%' . $txtFiltro . '%');
                $q->orWhere('a.CodArticulo', 'like', '%' . $txtFiltro . '%');
            })
            ->orderBy('a.CodArticulo')
            ->paginate(10)
            ->withQueryString();

        //return $articulos;

        $familias = Familia::where('Status', 0)
            ->get();
        $grupos = Grupo::where('Status', 0)
            ->get();
        $tiposArticulo = TipoArticulo::where('Status', 0)
            ->get();

        return view('Articulos.CatArticulos', compact('articulos', 'txtFiltro', 'familias', 'grupos', 'tiposArticulo'));
    }

    public function EditarArticulo(Request $request, $id)
    {

        try {
            DB::beginTransaction();

            Articulo::where('CodArticulo', $id)
                ->update([
                    'Amece' => $request->txtCodAmece,
                    'UOM' => $request->txtUOM,
                    'UOM2' => $request->txtUOM,
                    'Peso' => $request->txtPeso,
                    'Tercero' => $request->txtTercero,
                    'PrecioRecorte' => $request->txtPrecioRecorte,
                    'Factor' => $request->txtFactor,
                    'IdTipoArticulo' => $request->idTipoArticulo,
                    'IdFamilia' => $request->txtIdFamilia,
                    'IdGrupo' => $request->txtIdGrupo,
                    'Iva' => $request->txtIva,
                ]);

            $articulo = Articulo::where('CodArticulo', $id)
                ->first();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjupdate', 'Se ha editado el articulo: ' . $articulo->NomArticulo);
    }

    public function EnviarArticulo(Request $request)
    {
        $filtroArticulo = $request->get('filtroArticulo');
        $radioBuscar = $request->radioBuscar;

        if ($radioBuscar == 'radioCodigo') {

            $arrayArticulo = Articulo::where('CodArticulo', $filtroArticulo)
                ->select('CodArticulo')
                ->get()
                ->toArray();
            //return $arrayArticulo;

            $xxkw_items = DB::connection('Cloud_Tables')->table('XXKW_ITEMS')
                ->select('ITEM_NUMBER', 'DESCRIPTION')
                ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
                ->where('ITEM_NUMBER', $filtroArticulo)
                ->whereNotIn('ITEM_NUMBER', $arrayArticulo)
                ->get();
        } else {
            $arrayArticulo = Articulo::where('NomArticulo', 'like', '%' . $filtroArticulo . '%')
                ->select('CodArticulo')
                ->get()
                ->toArray();
            //return $arrayArticulo;

            $xxkw_items = DB::connection('Cloud_Tables')->table('XXKW_ITEMS')
                ->select('ITEM_NUMBER', 'DESCRIPTION')
                ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
                ->where('DESCRIPTION', 'like', '%' . $filtroArticulo . '%')
                ->where('ITEM_NUMBER', 'not like', 'D%')
                ->where('ITEM_NUMBER', 'not like', '%PI%')
                ->where('ITEM_NUMBER', 'not like', '%-B%')
                ->whereNotIn('ITEM_NUMBER', $arrayArticulo)
                ->get();
        }

        return view('Articulos.EnviarArticulo', compact('xxkw_items'));
    }

    public function mostrarArticulo()
    {

        return view('Articulos.MostrarArticulo');
    }

    public function AgregarArticulo($id)
    {

        $articulo_XXKW_ITEMS = DB::connection('Cloud_Tables')
            ->table('XXKW_ITEMS')
            ->select('ITEM_NUMBER', 'DESCRIPTION')
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

    public function BuscarArticulo(Request $request)
    {

        // return view('Articulos.BuscarArticulo');
        $txtFiltro = $request->txtFiltro;
        $Item_number = $request->Item_number;

        $familias = Familia::all();
        $grupos = Grupo::all();
        $tiposArticulo = TipoArticulo::where('Status', 0)->get();

        $arrayArticulo = Articulo::where('NomArticulo', 'like', '%' . $txtFiltro . '%')
            ->select('CodArticulo')
            ->get()
            ->toArray();

        $articulos = DB::connection('Cloud_Tables')->table('XXKW_ITEMS')
            ->select('ITEM_NUMBER', 'DESCRIPTION')
            ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
            ->where('ITEM_NUMBER', 'not like', 'D%')
            ->where('ITEM_NUMBER', 'not like', '%PI%')
            ->where('ITEM_NUMBER', 'not like', '%-B%')
            ->where(function ($query) use ($txtFiltro) {
                $query->where('ITEM_NUMBER', $txtFiltro)
                    ->orWhere('DESCRIPTION', 'like', '%' . $txtFiltro . '%');
            })
            ->whereNotIn('ITEM_NUMBER', $arrayArticulo)
            ->paginate(10);

        // Si selecciono un articulo, se cargan los datos
        $articulo = DB::connection('Cloud_Tables')
            ->table('XXKW_ITEMS')
            ->select('ITEM_NUMBER', 'DESCRIPTION')
            ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
            ->where('ITEM_NUMBER', $Item_number)
            ->first();

        return view('Articulos.DescargarArticulo', compact('txtFiltro', 'familias', 'grupos', 'tiposArticulo', 'articulos', 'articulo'));
    }

    public function LigarArticulo(Request $request)
    {
        try {
            DB::beginTransaction();

            $articulo_XXKW_ITEMS = DB::connection('Cloud_Tables')
                ->table('XXKW_ITEMS')
                ->select('Inventory_Item_Id')
                ->where('ORGANIZATION_NAME', 'MAESTRO DE ARTICULOS')
                ->where('ITEM_NUMBER', $request->txtCodArticulo)
                ->first();

            $maxCodEtiqueta = Articulo::max('CodEtiqueta') + 1;

            $articulo = new Articulo();
            $articulo->CodArticulo = $request->txtCodArticulo;
            $articulo->NomArticulo = $request->txtNomArticulo;
            $articulo->Amece = $request->banCodtxtCodAmeceAmece;
            $articulo->UOM = $request->txtUOM;
            $articulo->UOM2 = $request->txtUOM;
            $articulo->Peso = $request->txtPeso;
            $articulo->Tercero = $request->txtTercero;
            $articulo->CodEtiqueta = $maxCodEtiqueta;
            $articulo->PrecioRecorte = $request->txtPrecioRecorte;
            $articulo->Factor = $request->txtFactor;
            $articulo->Inventory_Item_Id = $articulo_XXKW_ITEMS->Inventory_Item_Id;
            $articulo->IdTipoArticulo = $request->idTipoArticulo;
            $articulo->IdFamilia = $request->txtIdFamilia;
            $articulo->IdGrupo = $request->txtIdGrupo;
            $articulo->Iva = $request->txtIva;
            $articulo->Status = 0;
            $articulo->save();

            $spArticulo = DB::table('CatArticulos')
                ->select('CodArticulo')
                ->where('CodArticulo', $request->txtCodArticulo)
                ->first();

            DB::statement('Execute SP_NUEVOARTICULOYPRECIO ' . $spArticulo->CodArticulo . '');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return redirect('CatArticulos')->with('msjAdd', 'Articulo Agregado!');
    }

    public function ExportExcel()
    {
        return Excel::download(new ArticulosExport, 'articulos.xlsx');
    }
}
