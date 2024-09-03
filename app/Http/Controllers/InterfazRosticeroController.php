<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatRosticero;
use App\Models\Tienda;
use App\Models\TransactionCloudInterface;
use App\Models\XXKW_ONHAND_TIENDAS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InterfazRosticeroController extends Controller
{
    public function index(Request $request)
    {
        $idTienda = $request->idTienda;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $usuarioTienda = Auth::user()->usuarioTienda;

        $tiendas = Tienda::where('Status', 0)
            ->when(empty($usuarioTienda), function ($query) {
                return $query->where('IdTienda', -1);
            })
            ->when(!empty($usuarioTienda->IdTienda), function ($query) use ($usuarioTienda) {
                return $query->where('IdTienda', $usuarioTienda->IdTienda);
            })
            ->when(!empty($usuarioTienda->IdPlaza), function ($query) use ($usuarioTienda) {
                return $query->where('IdPlaza', $usuarioTienda->IdPlaza);
            })
            ->get();

        // Este if se agrego para evitar la consulta de abajo, aqui pasara solo cuando cargue la pagina
        if (!$idTienda) {
            $rostisados = DatRosticero::where('IdDatRosticero', -1)->paginate(10)->withQueryString();
            return view('InterfazRosticero.index', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'rostisados'));
        }


        $tienda = Tienda::where('IdTienda', $idTienda)->first(['Almacen', 'Organization_Name', 'Subinventory_Code', 'CentroCosto']);
        $almacen = $tienda->Almacen;
        $organization_Name = $tienda->Organization_Name;
        $subinventory_Code = $tienda->Subinventory_Code;
        $centroCosto = $tienda->CentroCosto;



        $rostisados = DatRosticero::with(['Detalle', 'Lotes' => function ($lote) use ($almacen, $organization_Name) {
            $lote->leftJoin('server.CLOUD_TABLES.dbo.XXKW_ONHAND_TIENDAS', 'XXKW_ONHAND_TIENDAS.INVENTORY_ITEM_ID', 'XXKW_ITEMS.INVENTORY_ITEM_ID')
                // $lote->leftJoin('CLOUD_TABLES.dbo.XXKW_ONHAND_TIENDAS', 'XXKW_ONHAND_TIENDAS.INVENTORY_ITEM_ID', 'XXKW_ITEMS.INVENTORY_ITEM_ID')
                ->where('XXKW_ONHAND_TIENDAS.SUBINVENTORY_CODE', $almacen)
                ->where('XXKW_ITEMS.ORGANIZATION_NAME', $organization_Name)
                ->whereDate('EXPIRATION', '>', date('d-m-Y'))
                ->orderBy('EXPIRATION', 'desc');
        }])
            ->select(
                'DatRosticero.*',
                'a.NomArticulo',
                'b.NomTienda'
            )
            ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'DatRosticero.CodigoVenta')
            ->leftjoin('CatTiendas as b', 'b.IdTienda', 'DatRosticero.IdTienda')
            ->where('DatRosticero.IdTienda', $idTienda)
            ->whereRaw("CAST(DatRosticero.Fecha as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
            ->where(function ($q) {
                $q->whereNull('FechaInterfazBaja');
                $q->orWhereNull('FechaInterfazAlta');
            })
            ->where('Finalizado', 1)
            ->where('DatRosticero.Status', 0)
            ->orderBy('Fecha', 'desc')
            ->paginate(10)->withQueryString();

        return view('InterfazRosticero.index', compact('tiendas', 'idTienda', 'fecha1', 'fecha2', 'rostisados'));
    }

    public function InterfazarBaja($idTienda, $fecha1, $fecha2)
    {
        try {
            DB::connection('Cloud_Interface')->beginTransaction();
            DB::beginTransaction();

            $tienda = Tienda::where('IdTienda', $idTienda)->first(['Almacen', 'Organization_Name', 'Subinventory_Code', 'CentroCosto', 'NombreCorto']);
            $almacen = $tienda->Almacen;
            $organization_Name = $tienda->Organization_Name;
            $subinventory_Code = 'MARINADOS ' . $tienda->NombreCorto . date(' dmY');
            $centroCosto = $tienda->CentroCosto;
            $source_Header_Id = DB::select("SELECT (SELECT * FROM OPENQUERY(SERVER, 'SELECT NEXT VALUE FOR CLOUD_INTERFACE..PRICE_ADJ_IDENTIFIER_SEQ')) as SOURCE_HEADER_ID_SEQ")[0]->SOURCE_HEADER_ID_SEQ;
            $lineasInterfazadas = [];

            $rostisados = DatRosticero::with(['Lotes' => function ($lote) use ($almacen, $organization_Name) {
                $lote->leftJoin('server.CLOUD_TABLES.dbo.XXKW_ONHAND_TIENDAS', 'XXKW_ONHAND_TIENDAS.INVENTORY_ITEM_ID', 'XXKW_ITEMS.INVENTORY_ITEM_ID')
                    // $lote->leftJoin('CLOUD_TABLES.dbo.XXKW_ONHAND_TIENDAS', 'XXKW_ONHAND_TIENDAS.INVENTORY_ITEM_ID', 'XXKW_ITEMS.INVENTORY_ITEM_ID')
                    ->where('XXKW_ONHAND_TIENDAS.SUBINVENTORY_CODE', $almacen)
                    ->where('XXKW_ITEMS.ORGANIZATION_NAME', $organization_Name)
                    ->whereDate('EXPIRATION', '>', date('d-m-Y'))
                    ->orderBy('EXPIRATION', 'desc');
            }])
                ->leftjoin('CatRosticeroArticulos as ra', 'ra.CodigoVenta', 'DatRosticero.CodigoVenta')
                ->where('DatRosticero.IdTienda', $idTienda)
                ->whereRaw("CAST(DatRosticero.Fecha as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->whereNull('FechaInterfazBaja')
                ->where('Finalizado', 1)
                ->where('DatRosticero.Status', 0)
                ->orderBy('Fecha', 'desc')
                ->get();

            foreach ($rostisados as $keyRostisado => $rostisado) {

                // Verificamos que el producto tenga lotes
                if (count($rostisado->Lotes) > 0) {

                    // Cantidad total del producto en el lote
                    $totalLote = 0;
                    foreach ($rostisado->Lotes as $mermaLote) {
                        $totalLote = $totalLote + $mermaLote->TOTAL;
                    }
                    $RestanteMerma = $rostisado->CantidadMatPrima;

                    foreach ($rostisado->Lotes as $loteMerma) {
                        if ($totalLote >= $rostisado->CantArticulo) {
                            $auxCantMerma = $RestanteMerma;
                            $RestanteMerma = $auxCantMerma - $loteMerma->TOTAL;
                            $consumo = ($auxCantMerma - $loteMerma->TOTAL) >= 0 ? $loteMerma->TOTAL : $auxCantMerma;
                            TransactionCloudInterface::insert([
                                'ORGANIZATION_NAME' => $organization_Name,
                                'ITEM_NUMBER' => $rostisado->CodigoMatPrima,
                                'SUBINVENTORY_CODE' => $almacen,
                                'TRANSACTION_QUANTITY' => -$consumo,
                                'TRANSACTION_UOM' => $loteMerma->UOM,
                                'TRANSACTION_DATE' => date('d-m-Y H:i:s'),
                                'TRANSACTION_TYPE_NAME' => 'Miscellaneous issue',
                                'SOURCE_CODE' => $subinventory_Code,
                                'SOURCE_HEADER_ID' => $source_Header_Id,
                                'SOURCE_LINE_ID' => $keyRostisado + 1,
                                'DST_SEGMENT1' => $rostisado->Libro,
                                'DST_SEGMENT2' => $centroCosto,
                                'DST_SEGMENT3' => $rostisado->Cuenta,
                                'DST_SEGMENT4' => $rostisado->SubCuenta,
                                'DST_SEGMENT5' => $rostisado->InterCosto,
                                'DST_SEGMENT6' => $rostisado->UnidadNegocio,
                                'DST_SEGMENT7' => $rostisado->Futuro,
                                'LOT_NUMBER' => $loteMerma->LOT_NUMBER
                            ]);

                            //ESTOS SERAN LOS ARTICULOS INTERFAZADOS
                            array_push($lineasInterfazadas, $rostisado->IdDatRosticero);
                        }

                        if ($RestanteMerma <= 0) {
                            break;
                        }
                    }
                }
            }

            DatRosticero::with('Detalle')
                ->select(
                    'DatRosticero.*',
                    'a.NomArticulo',
                    'b.NomTienda'
                )
                ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'DatRosticero.CodigoVenta')
                ->leftjoin('CatTiendas as b', 'b.IdTienda', 'DatRosticero.IdTienda')
                ->where('DatRosticero.IdTienda', $idTienda)
                ->whereRaw("CAST(DatRosticero.Fecha as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->whereNull('FechaInterfazBaja')
                ->whereIn('IdDatRosticero', array_unique($lineasInterfazadas))
                ->orderBy('Fecha', 'desc')
                ->update([
                    'IdUsuarioInterfazBaja' => Auth::user()->IdUsuario,
                    'FechaInterfazBaja' => date('d-m-Y H:i:s')
                ]);

            DB::connection('Cloud_Interface')->commit();
            DB::commit();
            return back()->with('msjAdd', 'Articulos dados de baja correctamente: ' . implode(',', array_unique($lineasInterfazadas)));
        } catch (\Throwable $th) {
            DB::connection('Cloud_Interface')->rollback();
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }

    public function InterfazarAlta($idTienda, $fecha1, $fecha2)
    {
        try {
            DB::connection('Cloud_Interface')->beginTransaction();
            DB::beginTransaction();

            $tienda = Tienda::where('IdTienda', $idTienda)->first(['Almacen', 'Organization_Name', 'Subinventory_Code', 'CentroCosto', 'NombreCorto']);
            $almacen = $tienda->Almacen;
            $organization_Name = $tienda->Organization_Name;
            $subinventory_Code = 'MARINADOS ' . $tienda->NombreCorto . date(' dmY');
            $centroCosto = $tienda->CentroCosto;
            $source_Header_Id = DB::select("SELECT (SELECT * FROM OPENQUERY(SERVER, 'SELECT NEXT VALUE FOR CLOUD_INTERFACE..PRICE_ADJ_IDENTIFIER_SEQ')) as SOURCE_HEADER_ID_SEQ")[0]->SOURCE_HEADER_ID_SEQ;
            $lote = date('dmY') . 'A';
            $DATE_EXPIRATION = date("d-m-Y", strtotime(date("d-m-Y") . "+ 1 month"));
            $lineasInterfazadas = [];

            $rostisados = DatRosticero::where('DatRosticero.IdTienda', $idTienda)
                ->leftjoin('CatRosticeroArticulos as ra', 'ra.CodigoVenta', 'DatRosticero.CodigoVenta')
                ->whereRaw("CAST(DatRosticero.Fecha as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->whereNull('FechaInterfazAlta')
                ->where('Finalizado', 1)
                ->where('DatRosticero.Status', 0)
                ->orderBy('Fecha', 'desc')
                ->get();

            foreach ($rostisados as $keyRostisado => $rostisado) {
                TransactionCloudInterface::insert([
                    'ORGANIZATION_NAME' => $organization_Name,
                    'ITEM_NUMBER' => $rostisado->CodigoVenta,
                    'SUBINVENTORY_CODE' => $almacen,
                    'TRANSACTION_QUANTITY' => $rostisado->CantidadVenta,
                    'TRANSACTION_UOM' => 'KG',
                    'DATE_EXPIRATION' => $DATE_EXPIRATION,
                    'TRANSACTION_DATE' => date('d-m-Y H:i:s'),
                    'TRANSACTION_TYPE_NAME' => 'Miscellaneous Receipt',
                    'SOURCE_CODE' => $subinventory_Code,
                    'SOURCE_HEADER_ID' => $source_Header_Id,
                    'SOURCE_LINE_ID' => $keyRostisado + 1,
                    'DST_SEGMENT1' => $rostisado->Libro,
                    'DST_SEGMENT2' => $centroCosto,
                    'DST_SEGMENT3' => $rostisado->Cuenta,
                    'DST_SEGMENT4' => $rostisado->SubCuenta,
                    'DST_SEGMENT5' => $rostisado->InterCosto,
                    'DST_SEGMENT6' => $rostisado->UnidadNegocio,
                    'DST_SEGMENT7' => $rostisado->Futuro,
                    'LOT_NUMBER' => $lote
                ]);

                array_push($lineasInterfazadas, $rostisado->IdDatRosticero);
            }

            DatRosticero::with('Detalle')
                ->select(
                    'DatRosticero.*',
                    'a.NomArticulo',
                    'b.NomTienda'
                )
                ->leftjoin('CatArticulos as a', 'a.CodArticulo', 'DatRosticero.CodigoVenta')
                ->leftjoin('CatTiendas as b', 'b.IdTienda', 'DatRosticero.IdTienda')
                ->where('DatRosticero.IdTienda', $idTienda)
                ->whereRaw("CAST(DatRosticero.Fecha as date) between '" . $fecha1 . "' and '" . $fecha2 . "'")
                ->whereNull('FechaInterfazAlta')
                ->whereIn('IdDatRosticero', array_unique($lineasInterfazadas))
                ->orderBy('Fecha', 'desc')
                ->update([
                    'IdUsuarioInterfazAlta' => Auth::user()->IdUsuario,
                    'FechaInterfazAlta' => date('d-m-Y H:i:s')
                ]);

            DB::connection('Cloud_Interface')->commit();
            DB::commit();
            return back()->with('msjAdd', 'Articulos dados de baja correctamente: ' . implode(',', array_unique($lineasInterfazadas)));
        } catch (\Throwable $th) {
            DB::connection('Cloud_Interface')->rollback();
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
