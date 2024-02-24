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
        $txtFiltro = $request->get('txtFiltro');
        $clientes = Cliente::where('RFC', 'LIKE', $txtFiltro)
            ->orWhere('NomCliente', 'LIKE', '%' . $txtFiltro . '%')
            ->paginate(10);

        return view('Clientes.CatClientes', compact('clientes', 'txtFiltro'));
    }
}
