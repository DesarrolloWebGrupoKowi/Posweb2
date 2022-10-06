<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        //return $correos;

        return view('CorreosTienda.CorreosTienda', compact('tiendas', 'idTienda', 'correos'));
    }

    public function GuardarCorreosTienda(Request $request, $idTienda){
        $gerenteCorreo = $request->gerenteCorreo;
        $encargadoCorreo = $request->encargadoCorreo;
        $facturistaCorreo = $request->facturistaCorreo;
        $supervisorCorreo = $request->supervisorCorreo;
        $administrativaCorreo = $request->administrativaCorreo;
        $almacenistaCorreo = $request->almacenistaCorreo;
        $recepcionCorreo = $request->recepcionCorreo;

        try {
            DB::beginTransaction();

            CorreoTienda::insert([
                'IdTienda' => $idTienda,
                'GerenteCorreo' => $gerenteCorreo,
                'EncargadoCorreo' => $encargadoCorreo,
                'SupervisorCorreo' => $supervisorCorreo,
                'AdministrativaCorreo' => $administrativaCorreo,
                'AlmacenistaCorreo' => $almacenistaCorreo,
                'RecepcionCorreo' => $recepcionCorreo,
                'FacturistaCorreo' => $facturistaCorreo,
                'Status' => 0
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se Agregaron los Correos Correctamente!');
    }

    public function EditarCorreosTienda(Request $request, $idTienda){
        $gerenteCorreo = $request->gerenteCorreo;
        $encargadoCorreo = $request->encargadoCorreo;
        $facturistaCorreo = $request->facturistaCorreo;
        $supervisorCorreo = $request->supervisorCorreo;
        $administrativaCorreo = $request->administrativaCorreo;
        $almacenistaCorreo = $request->almacenistaCorreo;
        $recepcionCorreo = $request->recepcionCorreo;

        try {
            DB::beginTransaction();

            CorreoTienda::where('IdTienda', $idTienda)
                ->update([
                    'IdTienda' => $idTienda,
                    'GerenteCorreo' => $gerenteCorreo,
                    'EncargadoCorreo' => $encargadoCorreo,
                    'SupervisorCorreo' => $supervisorCorreo,
                    'AdministrativaCorreo' => $administrativaCorreo,
                    'AlmacenistaCorreo' => $almacenistaCorreo,
                    'RecepcionCorreo' => $recepcionCorreo,
                    'FacturistaCorreo' => $facturistaCorreo,
                ]);
                
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'Se editaron correctamente los correos!');
    }
}
