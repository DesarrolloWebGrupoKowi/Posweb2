<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ConcentradoDeArticulosExport implements FromView
{
    private $concentrado;
    public function __construct($concentrado)
    {
        $this->concentrado = $concentrado;
    }

    public function view(): View
    {
        return view('Reportes.ExportConcentradoDeArticulos', ['data' => $this->concentrado]);
    }
}
