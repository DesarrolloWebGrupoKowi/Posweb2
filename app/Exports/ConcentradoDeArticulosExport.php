<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ConcentradoDeArticulosExport implements FromView
{
    private $concentrado;
    private $agrupado;
    private $agrupadoArticulo;
    public function __construct($concentrado,  $agrupado, $agrupadoArticulo)
    {
        $this->concentrado = $concentrado;
        $this->agrupado = $agrupado;
        $this->agrupadoArticulo = $agrupadoArticulo;
    }

    public function view(): View
    {
        return view('Reportes.ExportConcentradoDeArticulos', [
            'data' => $this->concentrado,
            'agrupado' => $this->agrupado,
            'agrupadoArticulo' => $this->agrupadoArticulo
        ]);
    }
}
