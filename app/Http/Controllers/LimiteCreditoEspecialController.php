<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LimiteCredito;
use App\Models\LimiteCreditoEspecial;

class LimiteCreditoEspecialController extends Controller
{
    public function index(Request $request)
    {
        $limitesCredito = LimiteCreditoEspecial::leftJoin('CatEmpleados', 'CatEmpleados.NumNomina', 'CatLimiteCreditoEspecial.NumNomina')
            ->paginate();

        return view('LimiteCreditoEspecial.index', compact('limitesCredito'));
    }

    public function create(Request $request)
    {
        LimiteCreditoEspecial::create([
            'NumNomina' => $request->NumNomina,
            'Limite' => $request->Limite,
            'TotalVentaDiaria' => $request->TotalVentaDiaria
        ]);

        return back()->with('msjAdd', 'El Empleado Fie Agregado con Exito!');
    }

    public function update($id, Request $request)
    {
        LimiteCreditoEspecial::where('IdCatLimiteCreditoEspecial', $id)
            ->update([
                'NumNomina' => $request->NumNomina,
                'Limite' => $request->Limite,
                'TotalVentaDiaria' => $request->TotalVentaDiaria
            ]);

        return back()->with('msjupdate', 'Se Editó Correctamente');
    }

    public function delete($id)
    {
        LimiteCreditoEspecial::where('IdCatLimiteCreditoEspecial', $id)
            ->delete();

        return back()->with('msjupdate', 'Se Elimino Correctamente');
    }
}
