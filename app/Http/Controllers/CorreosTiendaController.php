<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CorreoTienda;
use App\Models\Tienda;

class CorreosTiendaController extends Controller
{
    public function CorreosTienda(Request $request){
        $tiendas = Tienda::where('Status', 0)
            ->get();

        $idTienda = $request->idTienda;

        $correos = CorreoTienda::where('IdTienda', $idTienda)
            ->where('Status', 0)
            ->get();

        //return $idTienda;

        return view('CorreosTienda.CorreosTienda', compact('tiendas', 'idTienda', 'correos'));
    }

    public function GuardarCorreosTienda(Request $request, $idTienda){
        $gerenteCorreo = $request->gerenteCoreo;
        $encargadoCorreo = $request->encargadoCorreo;
        $facturistaCorreo = $request->facturistaCorreo;

        try {
            DB::beginTransaction();

            CorreoTienda::insert([
                'IdTienda' => $idTienda,
                'GerenteCorreo' => $gerenteCorreo,
                'EncargadoCorreo' => $encargadoCorreo,
                'FacturistaCorreo' => $facturistaCorreo
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se Agregaron los Correos Correctamente!');
    }
}
