<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banco;

class BancosController extends Controller
{
    public function CatBancos(Request $request){
        $bancos  = Banco::where('Status', 0)
            ->get();

        return view('Bancos.CatBancos', compact('bancos'));
    }

    public function AgregarBanco(Request $request){
        try {
            DB::beginTransaction();
            $nomBanco = $request->nomBanco;

            Banco::insert([
                'NomBanco' => $nomBanco,
                'Status' => 0
            ]);

            DB::commit();
            
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('CatBancos');
        }
    }
}
