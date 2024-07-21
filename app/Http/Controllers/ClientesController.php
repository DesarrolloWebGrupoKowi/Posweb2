<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClienteCloud;
use App\Models\CustomerCloud;

class ClientesController extends Controller
{
    public function CatClientes(Request $request)
    {
        $paginate = $request->paginate ? $request->paginate : 10;
        $txtFiltro = $request->get('txtFiltro');
        $clientes = Cliente::where('RFC', 'LIKE', $txtFiltro)
            ->orWhere('NomCliente', 'LIKE', '%' . $txtFiltro . '%')
            ->paginate($paginate)
            ->withQueryString();

        return view('Clientes.CatClientes', compact('clientes', 'txtFiltro'));
    }
}
