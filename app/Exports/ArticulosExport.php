<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ArticulosExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $data = DB::table('CatArticulos as a')
            ->leftJoin('CatGrupos as b', 'b.IdGrupo', 'a.IdGrupo')
            ->leftJoin('CatFamilias as c', 'c.IdFamilia', 'a.IdFamilia')
            ->leftJoin('CatTipoArticulos as d', 'd.IdTipoArticulo', 'a.IdTipoArticulo')
            ->select('a.*', 'b.NomGrupo', 'c.NomFamilia', 'd.NomTipoArticulo')
            ->where('a.Status', 0)
            ->orderBy('a.CodArticulo')
            ->get();

        return view('Articulos.Export', ['data' => $data]);
    }
}
