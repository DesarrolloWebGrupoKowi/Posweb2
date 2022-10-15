<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\CapMerma;
use App\Models\OnHandTiendaCloudTable;
use App\Models\ItemCloudTable;
use App\Models\TransactionCloudInterface;

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

            $almacen = Tienda::where('IdTienda', $idTienda)
                ->value('Almacen');

            $organization_Name = Tienda::where('IdTienda', $idTienda)
                ->value('Organization_Name');

            $mermas = CapMerma::with(['Lotes' => function ($lote) use ($almacen, $organization_Name){
                $lote->leftJoin('server.CLOUD_TABLES.dbo.XXKW_ONHAND_TIENDAS', 'XXKW_ONHAND_TIENDAS.INVENTORY_ITEM_ID', 'XXKW_ITEMS.INVENTORY_ITEM_ID')
                    ->where('XXKW_ONHAND_TIENDAS.SUBINVENTORY_CODE', $almacen)
                    ->where('XXKW_ITEMS.ORGANIZATION_NAME', $organization_Name)
                    ->whereDate('EXPIRATION', '>', date('d-m-Y'))
                    ->orderBy('EXPIRATION');
            }])
                ->from('CapMermas as a')
                ->leftJoin('CatTiposMerma as b', 'b.IdTipoMerma', 'a.IdTipoMerma')
                ->leftJoin('CatCuentasMerma as c', 'c.IdTipoMerma', 'a.IdTipoMerma')
                ->leftJoin('CatTiendas as d', 'd.IdTienda', 'a.IdTienda')
                ->leftJoin('CatArticulos as e', 'e.CodArticulo', 'a.CodArticulo')
                ->select('d.Almacen', 'a.CodArticulo', 'e.NomArticulo', DB::raw('sum(a.CantArticulo) as CantArticulo'), 'b.NomTipoMerma',
                        'c.Libro', 'd.CentroCosto', 'c.Cuenta', 'c.SubCuenta', 'c.InterCosto', 'e.IdTipoArticulo', 'c.Futuro')
                ->where('a.IdTienda', $idTienda)
                ->whereRaw("CAST(a.FechaCaptura as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->groupBy('a.CodArticulo', 'b.NomTipoMerma', 'c.Libro', 'c.Cuenta', 'c.SubCuenta', 'c.InterCosto', 'c.Futuro',
                        'd.Almacen','e.IdTipoArticulo', 'd.CentroCosto', 'e.NomArticulo')
                ->get();

            $lotesDisponibles = null;
            foreach ($mermas as $keyCapMerma => $merma) {
                if($merma->Lotes->count() > 0){
                    $totalLote = 0;

                    foreach ($merma->Lotes as $key => $mermaLote) {
                        $totalLote = $totalLote + $mermaLote->TOTAL;
                    }

                    $RestanteMerma = $merma->CantArticulo;
    
                    foreach ($merma->Lotes as $keyLoteMerma => $loteMerma) {
                        if($totalLote >= $merma->CantArticulo){
                            $auxLote = $loteMerma->TOTAL;
                            $auxCantMerma = $RestanteMerma;
    
                            $RestanteMerma = $auxCantMerma - $loteMerma->TOTAL;
                            $consumo = ($auxCantMerma - $loteMerma->TOTAL) >= 0 ? $loteMerma->TOTAL : $auxCantMerma;
                            $restanteLote = $loteMerma->TOTAL - $consumo;
                            $lotesDisponibles = $lotesDisponibles + 1;
                        }
                        if($RestanteMerma <= 0){
                            break;
                        }
                    }
                }   
            }

            //return $lotesDisponibles;

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return view('InterfazMermas.InterfazMermas', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'mermas', 'lotesDisponibles'));
    }

    public function InterfazarMermas($idTienda, $fecha1, $fecha2){
        try {
            DB::connection('Cloud_Interface')->beginTransaction();
            DB::connection('server')->beginTransaction();
            DB::beginTransaction();

            $almacen = Tienda::where('IdTienda', $idTienda)
                ->value('Almacen');

            $organization_Name = Tienda::where('IdTienda', $idTienda)
                ->value('Organization_Name');

            $mermas = CapMerma::with(['Lotes' => function ($lote) use ($almacen, $organization_Name){
                $lote->leftJoin('server.CLOUD_TABLES.dbo.XXKW_ONHAND_TIENDAS', 'XXKW_ONHAND_TIENDAS.INVENTORY_ITEM_ID', 'XXKW_ITEMS.INVENTORY_ITEM_ID')
                    ->where('XXKW_ONHAND_TIENDAS.SUBINVENTORY_CODE', $almacen)
                    ->where('XXKW_ITEMS.ORGANIZATION_NAME', $organization_Name)
                    ->whereDate('EXPIRATION', '>', date('d-m-Y'))
                    ->orderBy('EXPIRATION');
            }])
                ->from('CapMermas as a')
                ->leftJoin('CatTiposMerma as b', 'b.IdTipoMerma', 'a.IdTipoMerma')
                ->leftJoin('CatCuentasMerma as c', 'c.IdTipoMerma', 'a.IdTipoMerma')
                ->leftJoin('CatTiendas as d', 'd.IdTienda', 'a.IdTienda')
                ->leftJoin('CatArticulos as e', 'e.CodArticulo', 'a.CodArticulo')
                ->select('d.Almacen', 'a.CodArticulo', 'e.NomArticulo', DB::raw('sum(a.CantArticulo) as CantArticulo'), 'b.NomTipoMerma',
                        'c.Libro', 'd.CentroCosto', 'c.Cuenta', 'c.SubCuenta', 'c.InterCosto', 'e.IdTipoArticulo', 'c.Futuro')
                ->where('a.IdTienda', $idTienda)
                ->whereRaw("CAST(a.FechaCaptura as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                ->groupBy('a.CodArticulo', 'b.NomTipoMerma', 'c.Libro', 'c.Cuenta', 'c.SubCuenta', 'c.InterCosto', 'c.Futuro',
                        'd.Almacen','e.IdTipoArticulo', 'd.CentroCosto', 'e.NomArticulo')
                ->get();

            //return $mermas;

            foreach ($mermas as $keyCapMerma => $merma) {
                if($merma->Lotes->count() > 0){
                    $totalLote = 0;
                    foreach ($merma->Lotes as $key => $mermaLote) {
                        $totalLote = $totalLote + $mermaLote->TOTAL;
                        echo 'lotes: ' . $mermaLote->LOT_NUMBER . ' - ' . $mermaLote->TOTAL . '<br>'; 
                    }

                    echo 'Total Lotes: ' . $totalLote;
                    echo '<br><br>';
                    $RestanteMerma = $merma->CantArticulo;

                    foreach ($merma->Lotes as $keyLoteMerma => $loteMerma) {
                        if($totalLote >= $merma->CantArticulo){
                            $auxLote = $loteMerma->TOTAL;
                            $auxCantMerma = $RestanteMerma;
                            echo 'Articulo: ' . $merma->CodArticulo . '<br>';
                            echo 'lote: ' . $loteMerma->LOT_NUMBER . '<br>'; 
                            echo 'Merma: ' . $auxCantMerma . '<br>';
                            echo 'inv lote: ' . $loteMerma->TOTAL . '<br>'; 

                            $RestanteMerma = $auxCantMerma - $loteMerma->TOTAL;
                            $consumo = ($auxCantMerma - $loteMerma->TOTAL) >= 0 ? $loteMerma->TOTAL : $auxCantMerma;
                            echo 'Consumo: ' . $consumo;
                            echo '<br>';
                            $restanteLote = $loteMerma->TOTAL - $consumo;
                            echo 'Restante lote :' . $restanteLote;
                            echo '<br>';
                            echo '<br>';
                        }
                        if($RestanteMerma <= 0){
                            break;
                        }
                    }
                }   
            }

            return 'fin:)';

        } catch (\Throwable $th) {
            DB::connection('Cloud_Interface')->rollback();
            DB::connection('server')->rollback();
            DB::rollback();
            return $th;
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::connection('Cloud_Interface')->commit();
        DB::connection('server')->commit();
        DB::commit();
        return back()->with('msjAdd', 'Mermas Interfazadas!');
    }
}
