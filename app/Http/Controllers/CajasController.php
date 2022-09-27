<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Caja;
use App\Models\Tienda;
use App\Models\DatCaja;

class CajasController extends Controller
{
    public function CatCajas(Request $request){
        $cajas = Caja::all();

        return view('Cajas.CatCajas', compact('cajas'));
    }

    public function CrearCaja(Request $request){
        $caja = new Caja();
        $caja -> NumCaja = $request->NumCaja;
        $caja -> Status = 0;
        $caja -> save();

        return redirect('CatCajas');
    }

    public function CajasTienda(Request $request){
        $tiendas = Tienda::all();

        $idTienda = $request->idTienda;

        $cajasTienda = DB::table('DatCajas as a')
            ->leftJoin('CatTiendas as b', 'b.IdTienda', 'a.IdTienda')
            ->where('a.Status', 0)
            ->where('a.IdTienda', $idTienda)
            ->get();

        $cajas = Caja::where('Status', 0)
            ->get();

        //return $cajasTienda;

        return view('Cajas.CajasTienda', compact('tiendas', 'cajasTienda', 'idTienda', 'cajas')); 
    }

    public function AgregarCajaTienda(Request $request){
        $idTienda = $request->idTiendaCaja;
        $idCaja = $request->idCaja;

        try {
            DB::beginTransaction();

            DatCaja::insert([
                'IdTienda' => $idTienda,
                'IdCaja' => $idCaja,
                'Activa' => 0,
                'Status' => 0
            ]);

            DB::commit();
            return back()->with('msjAdd', 'Se Agrego Caja Activa Correctamente!');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th);
        }
    }
}
