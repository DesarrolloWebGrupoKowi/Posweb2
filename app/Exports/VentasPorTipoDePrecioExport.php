<?php

namespace App\Exports;

use App\Models\Precio;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class VentasPorTipoDePrecioExport implements FromView
{
    private $concentrado;
    private $totales;
    private $clientes;
    public function __construct($concentrado, $totales, $clientes)
    {
        $this->concentrado = $concentrado;
        $this->totales = $totales;
        $this->clientes = $clientes;
    }

    public function view(): View
    {
        return view('Reportes.ExportVentasPorTipoDePrecio', ['data' => $this->concentrado, 'totales' => $this->totales, 'clientes' => $this->clientes]);
    }
}
