<?php

namespace App\Exports;

use App\Models\Precio;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class GrupoYTipoPrecio implements FromView
{
    private $concentrado;
    public function __construct($concentrado)
    {
        $this->concentrado = $concentrado;
    }

    public function view(): View
    {
        return view('Reportes.ExportGrupoYTipoPrecio', ['data' => $this->concentrado]);
    }
}
