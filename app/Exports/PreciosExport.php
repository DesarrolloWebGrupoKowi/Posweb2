<?php

namespace App\Exports;

use App\Models\Precio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PreciosExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $data = Precio::select(
            'DatPrecios.CodArticulo',
            'CatArticulos.NomArticulo',
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 1) Menudeo'),
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 2) Minorista'),
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 3) Detalle'),
            DB::raw('(SELECT PrecioArticulo from DatPrecios as dp where CodArticulo = DatPrecios.CodArticulo and IdListaPrecio = 4) EmpySoc')
        )
            ->leftjoin('CatArticulos', 'CatArticulos.CodArticulo', 'DatPrecios.CodArticulo')
            ->groupBy('DatPrecios.CodArticulo', 'CatArticulos.NomArticulo')
            ->get();

        return view('Precios.Export', ['data' => $data]);
    }
}
