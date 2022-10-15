<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TipoMerma;
use App\Models\SubTipoMerma;
use App\Models\TipoMermaArticulo;
use App\Models\Articulo;

class TiposMermaController extends Controller
{
    public function TiposMerma(Request $request){
        $tiposMerma = TipoMerma::where('Status', 0)
            ->get();

        return view('TiposMerma.TiposMerma', compact('tiposMerma'));
    }

    public function CrearTipoMerma(Request $request){
        $nomTipoMerma = $request->nomTipoMerma;

        try {
            DB::beginTransaction();

            TipoMerma::insert([
                'NomTipoMerma' => strtoupper($nomTipoMerma),
                'Status' => 0
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se agrego correctamente el tipo de merma: ' . $nomTipoMerma);
    }

    public function SubTiposMerma(Request $request){
        $tiposMerma = TipoMerma::where('Status', 0)
            ->get();

        $idTipoMerma = $request->idTipoMerma;

        try {
            DB::beginTransaction();

            $subTiposMerma = DB::table('CatTiposMerma as a')
                ->leftJoin('CatSubTiposMerma as b', 'b.IdTipoMerma', 'a.IdTipoMerma')
                ->where('b.IdTipoMerma', $idTipoMerma)
                ->where('b.Status', 0)
                ->get();

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error:' . $th->getMessage());
        }

        DB::commit();
        return view('TiposMerma.SubTiposMerma', compact('tiposMerma', 'idTipoMerma', 'subTiposMerma'));
    }

    public function CrearSubTipoMerma(Request $request, $idTipoMerma){
        $nomSubTipoMerma = $request->nomSubTipoMerma;

        try {
            DB::beginTransaction();

            SubTipoMerma::insert([
                'IdTipoMerma' => $idTipoMerma,
                'NomSubTipoMerma' => strtoupper($nomSubTipoMerma),
                'Status' => 0
            ]);
                
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' .$th->getMeesage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se Agrego Sub Tipo de Merma: ' . strtoupper($nomSubTipoMerma));
    }

    public function EliminarSubTipoMerma($idSubTipooMerma){
        try {
            DB::beginTransaction();

            SubTipoMerma::where('IdSubTipoMerma', $idSubTipooMerma)
                ->update([
                    'Status' => 1
                ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se eliminó el sub tipo de merma correctamente!');
    }

    public function TiposMermaArticulo(Request $request){
        try {
            DB::beginTransaction();
            $tiposMerma = TipoMerma::where('Status', 0)
                ->get();

            $idTipoMerma = $request->idTipoMerma;

            $articulos = Articulo::where('Status', 0)
                ->orderBy('CodArticulo')
                ->get();

            $tiposMermaArticulo = DB::table('DatArticulosTipoMerma as a')
                ->leftJoin('CatTiposMerma as b', 'b.IdTipoMerma', 'a.IdTipoMerma')
                ->leftJoin('CatArticulos as c', 'c.CodArticulo', 'a.CodArticulo')
                ->where('a.IdTipoMerma', $idTipoMerma)
                ->where('c.Status', 0)
                ->orderBy('c.CodArticulo')
                ->get();

            //return $tiposMermaArticulo;
                
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' .$th->getMessage());
        }

        DB::commit();

        return view('TiposMerma.TiposMermaArticulo', compact('tiposMerma', 'idTipoMerma', 'tiposMermaArticulo', 'articulos'));
    }

    public function AgregarArticuloMerma(Request $request, $idTipoMerma){
        $codArticulo = $request->codArticulo;

        try {
            DB::beginTransaction();
            
            if (!Articulo::where('CodArticulo', $codArticulo)->where('Status', 0)->exists()) {
                DB::rollback();
                return back()->with('msjdelete', 'Articulo Desconocido: (' . $codArticulo .')');
            }
            if(TipoMermaArticulo::where('CodArticulo', $codArticulo)->where('IdTipoMerma', $idTipoMerma)->exists()){
                DB::rollback();
                return back()->with('msjdelete', 'El Articulo ya esta agregado para este tipo de Merma!');
            } 
            else {
                TipoMermaArticulo::insert([
                    'IdTipoMerma' => $idTipoMerma,
                    'CodArticulo' => $codArticulo,
                    'Status' => 0
                ]);
            }
                
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' .$th->getMeesage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se Agrego Correctamente el Articulo!');
    }

    public function EliminarTipoMerma($idTipoMerma){
        try {
            DB::beginTransaction();

            TipoMerma::where('IdTipoMerma', $idTipoMerma)
                ->update([
                    'Status' => 1
                ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se eliminó el tipo de merma correctamente!');
    }

    public function EliminarArticuloTipoMerma($idTipoMerma, $codArticulo){
        try {
            DB::beginTransaction();

            TipoMermaArticulo::where('IdTipoMerma', $idTipoMerma)
                ->where('CodArticulo', $codArticulo)
                ->delete();

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjdelete', 'Se eliminó el articulo correctamente: (' . $codArticulo . ')');
    }
}
