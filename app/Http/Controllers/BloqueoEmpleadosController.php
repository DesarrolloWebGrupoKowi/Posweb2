<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BloqueoEmpleado;
use App\Models\Empleado;

class BloqueoEmpleadosController extends Controller
{
    public function BloqueoEmpleados(Request $request)
    {
        $numNomina = $request->numNomina;
        $nomEmpleado = $request->nomEmpleado;

        $bloqueos = BloqueoEmpleado::with('Empleado')
            ->where('DatBloqueoEmpleado.Status', 0)
            ->when($numNomina, function ($query) use ($numNomina) {
                // Validar si es numérico para usar where o like
                if (is_numeric($numNomina)) {
                    $query->where('DatBloqueoEmpleado.NumNomina', $numNomina);
                } else {
                    // Convertir a string para like
                    $query->whereRaw('CAST(DatBloqueoEmpleado.NumNomina AS VARCHAR) LIKE ?', ['%' . $numNomina . '%']);
                }
            })
            ->when($nomEmpleado, function ($query) use ($nomEmpleado) {
                $query->leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'DatBloqueoEmpleado.NumNomina')
                    ->where(function ($q) use ($nomEmpleado) {
                        $q->where('CatEmpleados.Nombre', 'like', '%' . $nomEmpleado . '%')
                            ->orWhere('CatEmpleados.Apellidos', 'like', '%' . $nomEmpleado . '%');
                    });
            })
            ->select('DatBloqueoEmpleado.*')
            ->orderBy('FechaBloqueo', 'desc')
            ->paginate(10);

        return view('BloqueoEmpleados.BloqueoEmpleados', compact('bloqueos', 'numNomina', 'nomEmpleado'));
    }

    public function AgregarBloqueoEmpleado(Request $request)
    {
        $numNomina = $request->numNomina;
        $motivoBloqueo = $request->motivoBloqueo;

        try {

            if (!Empleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()) {
                return back()->with('msjdelete', 'El empleado no existe o esta dado de baja!');
            }

            if (BloqueoEmpleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()) {
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

    public function DesbloquearEmpleado(int $numNomina)
    {
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

    public function BuscarEmpleadoParaBloqueo(int $numNomina)
    {
        if (!Empleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()) {
            return 'bajaOrNotExists';
        }

        if (BloqueoEmpleado::where('NumNomina', $numNomina)->where('Status', 0)->exists()) {
            return 'bloqueado';
        }

        $empleado = Empleado::where('NumNomina', $numNomina)
            ->first();

        $nomEmpleado = $empleado->Nombre . ' ' . $empleado->Apellidos;

        return $nomEmpleado;
    }
}
