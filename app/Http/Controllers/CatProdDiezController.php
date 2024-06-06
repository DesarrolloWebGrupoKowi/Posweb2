<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\CatProdDiez;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CatTipoDescuento;
use App\Models\DatDetDescuentos;
use App\Models\DatEncDescuentos;
use App\Models\ListaPrecio;
use App\Models\Plaza;
use App\Models\Tienda;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard\TextValue;

class CatProdDiezController extends Controller
{
    // ECHO
    public function index(Request $request)
    {
        $textValue = $request->textValue;

        $catProducts = CatProdDiez::leftJoin('CatArticulos', 'CatArticulos.CodArticulo', 'CatProdDiez.CodArticulo')
            ->leftJoin('CatListasPrecio', 'CatListasPrecio.IdListaPrecio', 'CatProdDiez.IdListaPrecio')
            ->leftJoin('CatUsuarios', 'CatUsuarios.IdUsuario', 'CatProdDiez.IdUsuario')
            ->where('CatArticulos.NomArticulo', 'like', '%' . $textValue . '%')
            ->orWhere('CatArticulos.CodArticulo', 'like', '%' . $textValue . '%')
            ->get();

        $listaPrecios = ListaPrecio::get();

        return view('CatProdDiez.index', compact('catProducts', 'textValue', 'listaPrecios'));
    }

    public function store(Request $request)
    {
        // Validar que el codigo de producto existe
        $articulo = Articulo::where('CodArticulo', $request->CodArticulo)->first();
        if (empty($articulo)) {
            return back()->with('msjdelete', 'El codigo del producto no existe en la base de datos.');
        }

        // Validar que el peso minimo sea menor al peso maximo
        if ($request->PesoMinimo >= $request->PesoMaximo) {
            return back()->with('msjdelete', 'El peso minimo tiene que ser menor al peso maximo.');
        }

        // Validar que no exista en nuesta tabla de descuentos
        $catProd = CatProdDiez::where('CodArticulo', $request->CodArticulo)->first();
        if (isset($catProd)) {
            return back()->with('msjdelete', 'El producto ya existe en la lista.');
        }

        CatProdDiez::create([
            'CodArticulo' => $request->CodArticulo,
            'Cantidad_Ini' => $request->PesoMinimo,
            'Cantidad_Fin' => $request->PesoMaximo,
            'IdListaPrecio' => $request->IdListaPrecio,
            'IdUsuario' => Auth::user()->IdUsuario,
            'Creacion' => date("Y-m-d H:i:s"),
            'Status' => 0,
        ]);

        return redirect()->route('CatProdDiez.index')->with('msjAdd', 'Producto agregado correctamente.');
    }

    public function destroy(Request $request, $id)
    {
        try {
            CatProdDiez::where('IdCatProdDiez', $id)
                ->delete();
            return back()->with('msjAdd', 'Producto eliminado correctamente.');
        } catch (\Throwable $th) {
            return back()->with('msjdelete', 'Error: ' . $th->getMessage());
        }
    }
}
