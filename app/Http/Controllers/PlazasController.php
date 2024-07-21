<?php

namespace App\Http\Controllers;

use App\Models\Plaza;
use App\Models\Ciudad;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PlazasController extends Controller
{
    public function CatPlazas(Request $request)
    {
        $ciudades = Ciudad::all();
        $activo = $request->get('activo');

        $plazas = DB::table('CatPlazas')
            ->leftJoin('CatCiudades', 'CatPlazas.IdCiudad', '=', 'CatCiudades.IdCiudad')
            ->leftJoin('CatEstados', 'CatCiudades.IdEstado', '=', 'CatEstados.IdEstado')
            ->select(
                'CatPlazas.*',
                'CatCiudades.IdCiudad as ccIdCiudad',
                'CatCiudades.NomCiudad as ccNomCiudad',
                'CatEstados.IdEstado as ceIdEstado',
                'CatEstados.NomEstado as ceNomEstado'
            )
            ->when($activo != null, function ($query) use ($activo) {
                return $query->where('CatPlazas.Status', $activo);
            })
            ->get();

        return view('Plazas.CatPlazas', compact('plazas', 'ciudades', 'activo'));
    }

    public function CrearPlaza(Request $request)
    {
        $Plazas = new Plaza();
        $Plazas->NomPlaza = $request->get('NomPlaza');
        $Plazas->IdCiudad = $request->get('IdCiudad');
        $Plazas->Status = 0;
        $Plazas->save();
        return redirect('CatPlazas')->with('msjAdd', 'Plaza Creada Correctamente!');
    }

    public function EditarPlaza(Request $request, $id)
    {
        $plaza = Plaza::find($id);
        $NomPlaza = $request->get('NomPlaza');
        $IdCiudad = $request->get('IdCiudad');
        $Status = $request->get('Status');

        Plaza::where('IdPlaza', $id)
            ->update([
                'NomPlaza' => $NomPlaza,
                'IdCiudad' => $IdCiudad,
                'Status' => $Status
            ]);
        return redirect('CatPlazas')->with('msjupdate', $plaza->NomPlaza . ' Editada Correctamente!');
    }
}
