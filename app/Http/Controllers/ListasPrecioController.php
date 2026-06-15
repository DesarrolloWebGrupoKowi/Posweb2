<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListaPrecio;
use App\Models\Articulo;

class ListasPrecioController extends Controller
{
    public function CatListasPrecio(Request $request)
    {
        $txtFiltro = $request->txtFiltro;
        $iva = $request->Iva;

        if ($iva !== null && $iva !== '') {
            $iva = $iva / 100;
        }

        $rangoPeso = $request->rangoPeso;

        // Consulta base para el select (sin paginación)
        $selectListaPrecio = ListaPrecio::where('Status', 0)->get();

        // Consulta con filtros
        $listasPrecio = ListaPrecio::where('Status', 0)
            ->when($txtFiltro, function ($query, $txtFiltro) {
                return $query->where('NomListaPrecio', 'like', '%' . $txtFiltro . '%');
            })
            ->when($iva !== null && $iva !== '', function ($query) use ($iva) {
                return $query->where('PorcentajeIva', $iva);
            })
            ->when($rangoPeso, function ($query, $rangoPeso) {
                switch ($rangoPeso) {
                    case '0-10':
                        return $query->where('PesoMaximo', '<=', 10);
                    case '10-50':
                        return $query->where('PesoMinimo', '>=', 10)
                            ->where('PesoMaximo', '<=', 50);
                    case '50-100':
                        return $query->where('PesoMinimo', '>=', 50)
                            ->where('PesoMaximo', '<=', 100);
                    case '100+':
                        return $query->where('PesoMinimo', '>=', 100);
                }
            })
            ->paginate(10)
            ->appends(request()->query());

        return view('ListasPrecio.CatListasPrecio', compact('listasPrecio', 'txtFiltro', 'selectListaPrecio'));
    }

    public function CrearListaPrecio(Request $request)
    {

        $listaPrecio = new ListaPrecio();
        $listaPrecio->NomListaPrecio = $request->get('NomListaPrecio');
        $listaPrecio->PesoMinimo = $request->get('PesoMinimo');
        $listaPrecio->PesoMaximo = $request->get('PesoMaximo');
        $listaPrecio->PorcentajeIva = $request->get('PorcentajeIva');
        $listaPrecio->Status = 0;
        $listaPrecio->save();

        $idListaPrecio = ListaPrecio::max('IdListaPrecio');

        if ($request->checkExistente == 'on') {

            $insertExistenteTmp = "insert into DatPreciosTmp" .
                " select " . $idListaPrecio . ", CodArticulo, PrecioArticulo, FechaPara" .
                " from DatPreciosTmp" .
                " where IdListaPrecio = " . $request->selectListaPrecio . "";
            DB::statement($insertExistenteTmp);

            $insertExistente = "insert into DatPrecios" .
                " select " . $idListaPrecio . ", CodArticulo, PrecioArticulo" .
                " from DatPrecios" .
                " where IdListaPrecio = " . $request->selectListaPrecio . "";
            DB::statement($insertExistente);
        } else {
            $insertListaPrecioTmp = "insert into DatPreciosTmp" .
                " select " . $idListaPrecio . ", CodArticulo, 0, CAST(GETDATE() as date)" .
                " from CatArticulos";
            DB::statement($insertListaPrecioTmp);

            $insertListaPrecio = "insert into DatPrecios" .
                " select " . $idListaPrecio . ", CodArticulo, 0" .
                " from CatArticulos";
            DB::statement($insertListaPrecio);
        }

        return redirect('CatListasPrecio')->with('msjAdd', 'Se Creo Correctamente la Lista de Precios: ' . $request->get('NomListaPrecio'));
    }

    public function EditarListaPrecio(Request $request, $id)
    {
        ListaPrecio::where('IdListaPrecio', $id)
            ->update([
                'PesoMinimo' => $request->get('PesoMinimo'),
                'PesoMaximo' => $request->get('PesoMaximo'),
                'PorcentajeIva' => $request->get('PorcentajeIva')
            ]);

        $lista = ListaPrecio::find($id);
        return back()->with('msjupdate', 'Lista de Precios: ' . $lista->NomListaPrecio . ' Editada Correctamente!');
    }
}
