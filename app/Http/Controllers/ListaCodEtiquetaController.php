<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ListaCodEtiquetaController extends Controller
{
    public function ListaCodEtiquetas(Request $request)
    {
        $txtFiltro = $request->txtFiltro;

        $dlpt = DB::table('DatListaPrecioTienda')
            ->where('IdTienda', Auth::user()->usuarioTienda->IdTienda)
            ->get();

        foreach ($dlpt as $key => $val) {
            $id[] = $val->IdListaPrecio;
        }

        $listaCodEtiquetas = Articulo::with(['PrecioArticulo' => function ($query) use ($id) {
            $query->whereIn('DatPrecios.IdListaPrecio', $id)
                ->orderBy('IdListaPrecio');
        }])
            ->where('Status', 0)
            ->where('NomArticulo', 'like', '%' . $txtFiltro . '%')
            ->orderBy('CodArticulo')
            ->paginate(10);

        //return $listaCodEtiquetas;

        return view('CodEtiquetas.ListaCodEtiquetas', compact('listaCodEtiquetas', 'txtFiltro'));
    }

    public function GenerarPDF()
    {
        $codEtiquetas = DB::table('CatArticulos as a')
            ->leftJoin('DatPrecios as b', 'b.CodArticulo', 'a.CodArticulo')
            ->select('a.CodArticulo', 'a.NomArticulo', 'a.CodEtiqueta', 'b.PrecioArticulo')
            ->where('b.IdListaPrecio', 1)
            ->where('a.Status', 0)
            ->get()
            ->toArray();
        //return $codEtiquetas;

        $usuarioTienda = Auth::user()::with('tiendaUsuario')
            ->where('IdUsuario', Auth::user()->IdUsuario)
            ->first();

        foreach ($usuarioTienda->tiendaUsuario as $key => $tiendaUsuario) {
            $nomTienda = $tiendaUsuario->NomTienda;
        }

        $info = [
            'titulo' => 'Listado de Codigos de Etiqueta',
            'fecha' => strftime("%d %B %Y", strtotime(date('Y-m-d'))),
            'codEtiquetas' => $codEtiquetas,
            'nomTienda' => $nomTienda,
        ];

        view()->share('codEtiquetas', $info);
        $pdf = PDF::loadView('CodEtiquetas.GenerarPDFCodEtiqueta', $info);
        return $pdf->stream('CodEtiquetas.pdf');
    }
}
