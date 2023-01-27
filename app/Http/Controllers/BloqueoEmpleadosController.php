<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BloqueoEmpleado;
use App\Models\Empleado;

class BloqueoEmpleadosController extends Controller
{
    public function BloqueoEmpleados(Request $request){
        $radioFiltro = $request->radioFiltro;
        $filtroBusqueda = $request->filtroBusqueda;

        // cuando entras por primera vez
        $bloqueos = BloqueoEmpleado::with('Empleado')
            ->where('Status', 0)
            ->paginate(15);

        if($radioFiltro == 'numNomina'){
            $bloqueos = BloqueoEmpleado::with('Empleado')
                ->where('Status', 0)
                ->where('NumNomina', $filtroBusqueda)
                ->paginate(15);
        }
        if($radioFiltro == 'nomEmpleado'){
            $numsNomina = Empleado::where('Status', 0)
                ->where('Nombre', 'like', '%' . $filtroBusqueda . '%')
                ->orWhere('Apellidos', 'like', '%' . $filtroBusqueda . '%')
                ->pluck('NumNomina');

            $bloqueos = BloqueoEmpleado::with('Empleado')
                ->where('Status', 0)
                ->whereIn('NumNomina', $numsNomina)
                ->paginate(15);
            
        }

        //return $bloqueos;

        return view('BloqueoEmpleados.BloqueoEmpleados', compact('bloqueos', 'radioFiltro', 'filtroBusqueda'));
    }

    public function AgregarBloqueoEmpleado(Request $request){
        $numNomina = $request->numNomina;
        $motivoBloqueo = $request->motivoBloqueo;

        try {
            
            if(!Empleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()){
                return back()->with('msjdelete', 'El empleado no existe o esta dado de baja!');
            }

            if(BloqueoEmpleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()){
                return back()->with('msjdelete', 'El empleado ya se encuentra bloqueado actualmente!');
            }

            DB::beginTransaction(); // inicio de transacciones
            BloqueoEmpleado::insert([
                'NumNomina' => $numNomina,
                'FechaBloqueo' => date('d-m-Y H:i:s'),
                'MotivoBloqueo' => mb_strtoupper($motivoBloqueo, 'UTF-8'),
                'IdUsuario' => Auth::user()->IdUsuario,
                'Status' => 0
            ]);

        } catch (\Throwable $th) {
            DB::rollback(); // algo salio mal
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        $empleado = Empleado::where('NumNomina', $numNomina)
            ->first();

        $nomEmpleado = $empleado->Nombre . ' ' . $empleado->Apellidos;

        DB::commit(); // todo salio bien
        return back()->with('msjAdd', 'Se bloqueo el empleado: ' . $nomEmpleado);
    }

    public function DesbloquearEmpleado($numNomina){
        try {
            DB::beginTransaction();

            BloqueoEmpleado::where('NumNomina', $numNomina)
                ->update([
                    'Status' => 1,
                    'FechaDesbloqueo' => date('d-m-Y H:i:s')
                ]); 

        } catch (\Throwable $th) {
            DB::rollback(); // algo salio mal
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        $empleado = Empleado::where('NumNomina', $numNomina)
            ->first();

        $nomEmpleado = $empleado->Nombre . ' ' . $empleado->Apellidos;

        DB::commit();   
        return back()->with('msjAdd', 'Se ha desbloqueado al empleado: ' . $nomEmpleado);
    }

    public function BuscarEmpleadoParaBloqueo($numNomina){
        if(!Empleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()){
            return 'bajaOrNotExists';
        }

        if(BloqueoEmpleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()){
            return 'bloqueado';
        }

        $empleado = Empleado::where('NumNomina', $numNomina)
            ->first();

        $nomEmpleado = $empleado->Nombre . ' ' . $empleado->Apellidos;

        return $nomEmpleado;
    }
}
