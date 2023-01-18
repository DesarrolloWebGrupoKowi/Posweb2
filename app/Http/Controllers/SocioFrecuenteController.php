<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\Empleado43;
use App\Models\TipoCliente;
use App\Models\FrecuenteSocio;

class SocioFrecuenteController extends Controller
{
    public function LigarSocioFrecuente(Request $request){
        $numNomina = $request->numNomina;

        $socioFrecuente = Empleado43::where('Num_Nomina', $numNomina)
            ->whereNotIn('Num_Nomina', Empleado::where('NumNomina', $numNomina)
                ->select('NumNomina')
                ->where('Status', 0)
                ->get())  
            ->where('Status', 1)
            ->first();

        $tiposCliente = TipoCliente::where('Status', 0)
            ->get();

        //return $socioFrecuente;

        return view('Posweb.LigarSocioFrecuente', compact('numNomina', 'socioFrecuente', 'tiposCliente'));
    }

    public function GuardarSocioFrecuente(Request $request, $folioViejo){
        try {
            
            DB::beginTransaction();

            $idFrecuenteSocio = FrecuenteSocio::max('IdFrecuenteSocio')+1;

            FrecuenteSocio::insert([
                'IdFrecuenteSocio' => $idFrecuenteSocio,
                'IdTipoCliente' => $request->tipoCliente,
                'FolioViejo' => $folioViejo,
                'FechaAlta' => date('d-m-Y H:i:s'),
                'Nombre' => mb_strtoupper($request->nombre, 'UTF-8'),
                'Sexo' => $request->sexo,
                'FechaNacimiento' => $request->fechaNacimiento,
                'Direccion' => mb_strtoupper($request->direccion, 'UTF-8'),
                'Colonia' => mb_strtoupper($request->colonia, 'UTF-8'),
                'Telefono' => $request->telefono,
                'Correo' => $request->correo,
                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                'Ciudad' => mb_strtoupper($request->ciudad, 'UTF-8'),
                'IdUsuario' => Auth::user()->IdUsuario,
                'Status' => 0
            ]);

            DB::table('CatFrecuentesSocios')->insert([
                'IdFrecuenteSocio' => $idFrecuenteSocio,
                'IdTipoCliente' => $request->tipoCliente,
                'FolioViejo' => $folioViejo,
                'FechaAlta' => date('d-m-Y H:i:s'),
                'Nombre' => mb_strtoupper($request->nombre, 'UTF-8'),
                'Sexo' => $request->sexo,
                'FechaNacimiento' => $request->fechaNacimiento,
                'Direccion' => mb_strtoupper($request->direccion, 'UTF-8'),
                'Colonia' => mb_strtoupper($request->colonia, 'UTF-8'),
                'Telefono' => $request->telefono,
                'Correo' => $request->correo,
                'IdTienda' => Auth::user()->usuarioTienda->IdTienda,
                'Ciudad' => mb_strtoupper($request->ciudad, 'UTF-8'),
                'IdUsuario' => Auth::user()->IdUsuario,
                'Status' => 0
            ]);
            

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        return bacK()->with('msjAdd', 'Se ligo correctamente el cliente!');
    }
}
