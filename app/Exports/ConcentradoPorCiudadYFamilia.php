<?php

namespace App\Exports;

use App\Models\Precio;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ConcentradoPorCiudadYFamilia implements FromView
{
    private $concentrado;
    private $totales;
    private $kilos;
    public function __construct($concentrado, $totales, $kilos)
    {
        $this->concentrado = $concentrado;
        $this->totales = $totales;
        $this->kilos = $kilos;
    }

    public function view(): View
    {
        return view('Reportes.ExportConcentradoPorCiudadYFamilia', ['data' => $this->concentrado, 'totales' => $this->totales, 'kilos' => $this->kilos]);
    }
}
