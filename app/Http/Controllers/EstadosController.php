<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EstadosController extends Controller
{
    public function CatEstados(Request $request)
    {
        $activo = $request->get('Activo');

        $estados = DB::table('CatEstados')
            ->when($activo != null, function ($query) use ($activo) {
                return $query->where('CatEstados.Status', $activo);
            })
            ->orderBy('NomEstado')
            ->paginate(10)
            ->withQueryString();

        return view('Estados/CatEstados', compact('estados', 'activo'));
    }
    public function CrearEstado(Request $request)
    {
        $Estados = new Estado();
        $Estados->NomEstado = $request->get('NomEstado');
        $Estados->Status = 0;
        $Estados->save();
        return redirect("CatEstados");
    }

    public function EditarEstado(Request $request, $id)
    {
        $Status = $request->get('Status');

        Estado::where('IdEstado', $id)
            ->update([
                'Status' => $Status
            ]);

        $estado = Estado::find($id);
        if ($Status == 1) {
            return redirect('CatEstados')->with('msjdelete', 'Estado ' . $estado->NomEstado . ' Desactivado con Exito!');
        } else {
            return redirect('CatEstados')->with('msjAdd', 'Estado ' . $estado->NomEstado . ' Activado con Exito!');
        }
    }
}
