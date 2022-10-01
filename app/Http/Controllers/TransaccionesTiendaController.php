<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TransaccionTienda;
use App\Models\Tienda;

class TransaccionesTiendaController extends Controller
{
    public function TransaccionesTienda(Request $request){
        $tiendas = Tienda::where('Status', 0)
            ->get();

        $idTienda = $request->idTienda;

        $destinosTienda = TransaccionTienda::where('IdTienda', $idTienda)
            ->pluck('IdTiendaDestino');

        $tiendasAgregadas = Tienda::whereIn('IdTienda', $destinosTienda)
            ->whereNotIn('IdTienda', [$idTienda])
            ->where('Status', 0)
            ->get();


        $tiendasPorAgregar = Tienda::whereNotIn('IdTienda', $destinosTienda)
            ->whereNotIn('IdTienda', [$idTienda])
            ->where('Status', 0)
            ->orderBy('IdCiudad')
            ->get();

        //return $tiendasAgregadas;

        return view('TransaccionesTienda.TransaccionesTienda', compact('tiendas', 'idTienda', 'tiendasAgregadas', 'tiendasPorAgregar'));
    }

    public function AgregarTransaccionTienda($idTienda, Request $request){
        $idsTienda = $request->chkAgregar;

        if(empty($idsTienda)){
            return back()->with('msjdelete', 'Seleccione Tiendas Destino!');
        }

        try {
            DB::beginTransaction();

            foreach ($idsTienda as $key => $idTiendaDestino) {
                TransaccionTienda::insert([
                    'IdTienda' => $idTienda,
                    'IdTiendaDestino' => $idTiendaDestino,
                    'Status' => 0
                ]);
            }

            DB::commit();

            return back()->with('msjAdd', 'Destinos Agregados Correctamente!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function EliminarTransaccionTienda($idTienda, Request $request){
        $idsTienda = $request->chkEliminar;

        if(empty($idsTienda)){
            return back()->with('msjdelete', 'Seleccione Destinos a Eliminar!');
        }

        try {
            DB::beginTransaction();

            foreach ($idsTienda as $key => $idTiendaDestino) {
                TransaccionTienda::where('IdTienda', $idTienda)
                    ->where('IdTiendaDestino', $idTiendaDestino)
                    ->delete();
            }

            DB::commit();

            return back()->with('msjdelete', 'Se Eliminaron Tienda(s) Destino!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
