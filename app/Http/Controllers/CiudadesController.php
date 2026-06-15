<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Estado;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CiudadesController extends Controller
{
    public function CatCiudades(Request $request)
    {
        $txtFiltro = $request->txtFiltro;
        $idEstado = $request->IdEstado;

        $ciudades = DB::table('CatCiudades')
            ->leftJoin('CatEstados', 'CatCiudades.IdEstado', '=', 'CatEstados.IdEstado')
            ->select(
                'IdCiudad',
                'NomCiudad',
                'CatCiudades.Status',
                'CatEstados.NomEstado',
                'CatEstados.IdEstado'
            )
            ->when($txtFiltro, function ($query, $txtFiltro) {
                return $query->where(function ($q) use ($txtFiltro) {
                    $q->where('NomCiudad', 'like', '%' . $txtFiltro . '%')
                        ->orWhere('NomEstado', 'like', '%' . $txtFiltro . '%');
                });
            })
            ->when($idEstado, function ($query, $idEstado) {
                return $query->where('CatCiudades.IdEstado', $idEstado);
            })
            ->paginate(10)
            ->appends(request()->query());

        $estados = Estado::all();
        return view('Ciudades/CatCiudades', compact('ciudades', 'estados', 'txtFiltro'));
    }

    public function CrearCiudad(Request $request)
    {
        $ciudades = new Ciudad();
        $ciudades->NomCiudad = $request->get('NomCiudad');
        $ciudades->IdEstado = $request->get('IdEstado');
        $ciudades->Status = $request->get('Status');
        $ciudades->save();
        return redirect("CatCiudades");
    }

    public function EditarCiudad(Request $request, $id)
    {
        Ciudad::where('IdCiudad', $id)
            ->update([
                'NomCiudad' => $request->get('NomCiudad'),
                'IdEstado' => $request->get('IdEstado'),
                'Status' => $request->get('Status')
            ]);
        $ciudad = Ciudad::find($id);

        return back()->with('msjupdate', 'Ciudad: ' . $ciudad->NomCiudad . ' Editada Correctamente!!');
    }

    public function Ciudades(Request $request, $IdCiudad)
    {
        if ($request->ajax()) {
            try {
                $ciudades = Ciudad::ciudades($IdCiudad);
                return response()->json($ciudades);
            } catch (\Throwable $th) {
                return response()->json([]);
            }
        }
    }
}
