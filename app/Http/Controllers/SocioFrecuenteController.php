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

        try {
            $numNomina = $request->numNomina; // request del front

            // validar que exista el socio/frecuente, ademas de que no sea un empleado y que no este dado de alta.
            $socioFrecuente = Empleado43::where('Num_Nomina', $numNomina)
                ->whereNotIn('Num_Nomina', Empleado::where('NumNomina', $numNomina)
                    ->select('NumNomina')
                    ->where('Status', 0)
                    ->get()
                )
                ->whereNotIn('Num_Nomina', FrecuenteSocio::where('FolioViejo', $numNomina)
                    ->select('FolioViejo')
                    ->where('Status', 0)
                    ->get()
                )  
                ->where('Status', 1)
                ->first();

            $tiposCliente = TipoCliente::where('Status', 0)
                ->get();

        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        return view('Posweb.LigarSocioFrecuente', compact('numNomina', 'socioFrecuente', 'tiposCliente'));
    }

    public function GuardarSocioFrecuente(Request $request, $folioViejo){
        try {
            
            DB::beginTransaction();
            DB::connection('server')->beginTransaction();

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

            // limpiar la tabla local para insertar de nuevo
            DB::table('CatFrecuentesSocios')->truncate();

            // insert into select de la tabla web a la local
            $frecuentesSocios = FrecuenteSocio::all();

            foreach ($frecuentesSocios as $key => $frecuenteSocio) {
                DB::table('CatFrecuentesSocios')->insert([
                    'IdFrecuenteSocio' => $frecuenteSocio->IdFrecuenteSocio,
                    'IdTipoCliente' => $frecuenteSocio->IdTipoCliente,
                    'FolioViejo' => $frecuenteSocio->FolioViejo,
                    'FechaAlta' => strftime('%d %B %Y', strtotime($frecuenteSocio->FechaAlta)),
                    'Nombre' => $frecuenteSocio->Nombre,
                    'Sexo' => $frecuenteSocio->Sexo,
                    'FechaNacimiento' => $frecuenteSocio->FechaNacimiento,
                    'Direccion' => $frecuenteSocio->Direccion,
                    'Colonia' => $frecuenteSocio->Colonia,
                    'Telefono' => $frecuenteSocio->Telefono,
                    'Correo' => $frecuenteSocio->Correo,
                    'IdTienda' => $frecuenteSocio->IdTienda,
                    'Ciudad' => $frecuenteSocio->Ciudad,
                    'IdUsuario' => $frecuenteSocio->IdUsuario,
                    'Status' => 0
                ]);
            }
            

        } catch (\Throwable $th) {
            DB::rollback();
            DB::connection('server')->rollback();
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }

        DB::commit();
        DB::connection('server')->commit();
        return redirect('LigarSocioFrecuente')->with('msjAdd', 'Se ligo correctamente el cliente!');
    }
}
