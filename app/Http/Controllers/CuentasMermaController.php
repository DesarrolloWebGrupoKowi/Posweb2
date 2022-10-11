<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\TipoMerma;
use App\Models\CuentaMerma;

class CuentasMermaController extends Controller
{
    public function CuentasMerma(Request $request){
        $idTipoMerma = $request->idTipoMerma;

        $tiposMerma = TipoMerma::where('Status', 0)
            ->get();

        $cuentasMerma = DB::table('CatCuentasMerma as a')
            ->leftJoin('CatTiposMerma as b', 'b.IdTipoMerma', 'a.IdTipoMerma')
            ->where('a.IdTipoMerma', $idTipoMerma)
            ->get();

        //return $cuentasMerma;

        return view('CuentasMerma.CuentasMerma', compact('tiposMerma', 'idTipoMerma', 'cuentasMerma'));
    }

    public function AgregarCuentaMerma(Request $request, $idTipoMerma){
        try {
            DB::beginTransaction();

            CuentaMerma::insert([
                'IdTipoMerma' => $idTipoMerma,
                'Libro' => $request->libro,
                'Cuenta' => $request->cuenta,
                'SubCuenta' => $request->subCuenta,
                'InterCosto' => $request->intercosto,
                'Futuro' => $request->futuro,
                'Status' => 0
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return back()->with('msjAdd', 'se Agrego correctamente la cuenta merma');
    }
}
