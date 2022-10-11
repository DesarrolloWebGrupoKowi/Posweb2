<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TipoArticulo;

class TipoArticulosController extends Controller
{
    public function TipoArticulos(Request $request){
        $tiposArticulo = TipoArticulo::where('Status', 0)
            ->get();

        return view('TipoArticulos.TipoArticulos', compact('tiposArticulo'));
    }

    public function AgregarTipoArticulo(Request $request){
        $idTipoArticulo = $request->idTipoArticulo;
        $nomTipoArticulo = $request->nomTipoArticulo;

        try {
            DB::beginTransaction();

            if(!TipoArticulo::where('IdTipoArticulo', $idTipoArticulo)->exists()){
                TipoArticulo::insert([
                    'IdTipoArticulo' => $idTipoArticulo,
                    'NomTipoArticulo' => strtoupper($nomTipoArticulo),
                    'Status' => 0
                ]); 
            }
            else{
                DB::rollback();
                return back()->with('msjdelete', 'La Unidad de Negocio ya existe: (' . $idTipoArticulo . ')');
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se Agrego correctamente el tipo de articulo: ' . $nomTipoArticulo);
    }
}
