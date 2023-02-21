<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MonederoElectronico;
use App\Models\Grupo;
use App\Models\MovimientoMonederoElectronico;
use App\Models\Empleado;
use App\Models\DatMonederoAcumulado;

class MonederoElectronicoController extends Controller
{
    public function CatMonederoElectronico(Request $request){
        $monederoElectronico = MonederoElectronico::where('Status', 0)
            ->get();

        $grupos = Grupo::all();

        return view('MonederoElectronico.CatMonederoElectronico', compact('monederoElectronico', 'grupos'));
    }

    public function EditarMonederoElectronico(Request $request, $idCatMonedero){
        try {
            DB::beginTransaction();
            MonederoElectronico::where('Status', 0)
                ->update([
                    'FechaEliminacion' => date('d-m-Y H:i:s'),
                    'IdUsuario' => Auth::user()->IdUsuario,
                    'Status' => 1
            ]);

            MonederoElectronico::insert([
                'MaximoAcumulado' => $request->maximoAcumulado,
                'MonederoMultiplo' => $request->multiplo,
                'PesosPorMultiplo' => $request->pesosPorMultiplo,
                'VigenciaMonedero' => $request->vigencia,
                'IdGrupo' => $request->idGrupo,
                'FechaCreacion' => date('d-m-Y H:i:s'),
                'IdUsuario' => Auth::user()->IdUsuario,
                'Status' => 0
            ]);

            DB::commit();

            return back()->with('msjupdate', 'Monedero Electrónico Actualizado!');

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('msjdelete', 'Error'. $th->getMessage());
        }
    }

    public function ReporteMonedero(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $numNomina = $request->numNomina;

        try {
            DB::beginTransaction();

            $empleado = Empleado::where('NumNomina', $numNomina)
                ->first();

            $movimientos = DatMonederoAcumulado::where('NumNomina', $numNomina)
                ->whereRaw("cast(FechaGenerado as date) between '". $fecha1 ."' and '". $fecha2 ."' ")
                ->get();

            $monederoFinal = DatMonederoAcumulado::where('NumNomina', $numNomina)
                ->whereRaw("cast(FechaGenerado as date) between '". $fecha1 ."' and '". $fecha2 ."' ")
                ->sum('Monedero');

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('msjdelete', 'Error: '.$e->getMessage());
        }

        //return $empleado;

        return view('MonederoElectronico.ReporteMonedero', compact('empleado', 'fecha1', 'fecha2', 'numNomina', 'empleado', 'movimientos', 'monederoFinal'));
    }
}
