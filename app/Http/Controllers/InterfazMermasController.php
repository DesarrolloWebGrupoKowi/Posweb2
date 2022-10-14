<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\CapMerma;
use App\Models\OnHandTiendaCloudTable;
use App\Models\ItemCloudTable;

class InterfazMermasController extends Controller
{
    public function InterfazMermas(Request $request){
        try {
            DB::beginTransaction();

            $tiendas = Tienda::where('Status', 0)
                ->get();

            $idTienda = $request->idTienda;
            $fecha1 = $request->fecha1;
            $fecha2 = $request->fecha2;

            $mermas = DB::table('CapMermas as a')
                ->leftJoin('CatTiposMerma as b', 'b.IdTipoMerma', 'a.IdTipoMerma')
                ->leftJoin('CatCuentasMerma as c', 'c.IdTipoMerma', 'a.IdTipoMerma')
                ->leftJoin('CatTiendas as d', 'd.IdTienda', 'a.IdTienda')
                ->leftJoin('CatArticulos as e', 'e.CodArticulo', 'a.CodArticulo')
                ->leftJoin()
                ->select('d.Almacen', 'a.CodArticulo', 'e.NomArticulo', DB::raw('sum(a.CantArticulo) as CantArticulo'), 'b.NomTipoMerma',
                        'c.Libro', 'd.CentroCosto', 'c.Cuenta', 'c.SubCuenta', 'c.InterCosto', 'e.IdTipoArticulo', 'c.Futuro')
                ->where('a.IdTienda', $idTienda)
                ->whereRaw("CAST(a.FechaCaptura as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->groupBy('a.CodArticulo', 'b.NomTipoMerma', 'c.Libro', 'c.Cuenta', 'c.SubCuenta', 'c.InterCosto', 'c.Futuro',
                    'd.Almacen','e.IdTipoArticulo', 'd.CentroCosto', 'e.NomArticulo')
                ->get();

            $item = ItemCloudTable::where('ITEM_NUMBER', '101211')
                ->get();
                
            return $item;

            //return $mermas;

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();

        return view('InterfazMermas.InterfazMermas', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'mermas'));
    }
}
