<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

            $usuarioTienda = Auth::user()->usuarioTienda;

            if($usuarioTienda->doesntExist()){
                return back()->with('msjdelete', 'El usuario no tiene tiendas agregadas, vaya al modulo de Usuarios Por Tienda');
            }

            if($usuarioTienda->Todas == 0){
                $tiendas = Tienda::where('Status', 0)
                ->get();
            }

            if(!empty($usuarioTienda->IdTienda)){
                $tiendas = Tienda::where('Status', 0)
                ->where('IdTienda', $usuarioTienda->IdTienda)
                ->get();
            }
            
            if(!empty($usuarioTienda->IdPlaza)){
                $tiendas = Tienda::where('IdPlaza', $usuarioTienda->IdPlaza)
                ->where('Status', 0)
                ->get();
            }

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
                    ->orderBy('EXPIRATION', 'desc');
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
                ->whereNull('a.FechaInterfaz')
                ->whereNull('a.IdUsuarioInterfaz')
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

            $subinventory_Code = Tienda::where('IdTienda', $idTienda)
                ->value('Subinventory_Code');

            $centroCosto = Tienda::where('IdTienda', $idTienda)
                ->value('CentroCosto');

            $mermas = CapMerma::with(['Lotes' => function ($lote) use ($almacen, $organization_Name){
                $lote->leftJoin('server.CLOUD_TABLES.dbo.XXKW_ONHAND_TIENDAS', 'XXKW_ONHAND_TIENDAS.INVENTORY_ITEM_ID', 'XXKW_ITEMS.INVENTORY_ITEM_ID')
                    ->where('XXKW_ONHAND_TIENDAS.SUBINVENTORY_CODE', $almacen)
                    ->where('XXKW_ITEMS.ORGANIZATION_NAME', $organization_Name)
                    ->whereDate('EXPIRATION', '>', date('d-m-Y'))
                    ->orderBy('EXPIRATION', 'desc');
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
                ->whereNull('a.FechaInterfaz')
                ->whereNull('a.IdUsuarioInterfaz')
                ->get();

            //return $mermas;

            $source_Header_Id = DB::select('select next value for CLOUD_INTERFACE..SOURCE_HEADER_ID_SEQ as SOURCE_HEADER_ID_SEQ');
            foreach ($source_Header_Id as $key => $value) {
                $s_Header_Id = $value->SOURCE_HEADER_ID_SEQ;
            }

            $sourceLineId = 0;
            $articulosInterfazados = [];

            foreach ($mermas as $keyCapMerma => $merma) {
                if($merma->Lotes->count() > 0){
                    $totalLote = 0;
                    foreach ($merma->Lotes as $key => $mermaLote) {
                        $totalLote = $totalLote + $mermaLote->TOTAL;
                    }

                    $RestanteMerma = $merma->CantArticulo;

                    foreach ($merma->Lotes as $keyLoteMerma => $loteMerma) {
                        if($totalLote >= $merma->CantArticulo){
                            $sourceLineId = $sourceLineId + 1;
                            $auxLote = $loteMerma->TOTAL;
                            $auxCantMerma = $RestanteMerma;
                            
                            $RestanteMerma = $auxCantMerma - $loteMerma->TOTAL;
                            $consumo = ($auxCantMerma - $loteMerma->TOTAL) >= 0 ? $loteMerma->TOTAL : $auxCantMerma;
                            $restanteLote = $loteMerma->TOTAL - $consumo;

                            TransactionCloudInterface::insert([
                                'ORGANIZATION_NAME' => $organization_Name,
                                'ITEM_NUMBER' => $merma->CodArticulo,
                                'SUBINVENTORY_CODE' => $almacen,
                                'TRANSACTION_QUANTITY' => -$consumo,
                                'TRANSACTION_UOM' => $loteMerma->UOM,
                                'TRANSACTION_DATE' => date('d-m-Y H:i:s'),
                                'TRANSACTION_TYPE_NAME' => 'Miscellaneous issue',
                                'SOURCE_CODE' => $subinventory_Code,
                                'SOURCE_HEADER_ID' => $s_Header_Id,
                                'SOURCE_LINE_ID' => $sourceLineId,
                                'DST_SEGMENT1' => $merma->Libro,
                                'DST_SEGMENT2' => $centroCosto,
                                'DST_SEGMENT3' => $merma->Cuenta,
                                'DST_SEGMENT4' => $merma->SubCuenta,
                                'DST_SEGMENT5'=> $merma->InterCosto,
                                'DST_SEGMENT6' => empty($merma->IdTipoArticulo) ? '00' : $merma->IdTipoArticulo,
                                'DST_SEGMENT7' => $merma->Futuro,
                                'LOT_NUMBER' => $loteMerma->LOT_NUMBER
                            ]);

                            //ESTOS SERAN LOS ARTICULOS INTERFAZADOS
                            array_push($articulosInterfazados, $merma->CodArticulo);
                        }

                        if($RestanteMerma <= 0){
                            break;
                        }
                    }
                }   
            }

            //CUANDO LA MERMA YA SE INTERFAZO
            if(!empty($articulosInterfazados)){
                CapMerma::where('IdTienda', $idTienda)
                    ->whereRaw("CAST(FechaCaptura as date) between '". $fecha1 ."' and '". $fecha2 ."'")
                    ->whereNull('FechaInterfaz')
                    ->whereNull('IdUsuarioInterfaz')
                    ->whereIn('CodArticulo', array_unique($articulosInterfazados))
                    ->update([
                        'IdUsuarioInterfaz' => Auth::user()->IdUsuario,
                        'FechaInterfaz' => date('d-m-Y H:i:s')
                    ]);
            }   

        } catch (\Throwable $th) {
            DB::connection('Cloud_Interface')->rollback();
            DB::connection('server')->rollback();
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::connection('Cloud_Interface')->commit();
        DB::connection('server')->commit();
        DB::commit();
        return back()->with('msjAdd', 'Se Interfazaron los articulos: ' . implode(',', array_unique($articulosInterfazados)) . ' Correctamente!');
    }
}