<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class MovimientosDeArticulos implements FromQuery, WithHeadings, WithStyles
{
    private $query;
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID Movimiento',
            'ID Tienda',
            'Tienda',
            'Código Artículo',
            'Artículo',
            'Cantidad',
            'UOM',
            'Fecha Movimiento',
            'Referencia',
            'ID Tipo Movimiento',
            'Tipo Movimiento',
            'ID Usuario',
            'Usuario',
            'Nómina',
            'Empleado',
            'Referencia ID',
            'ID Caja'
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $sheet->getStyle('A1:Q1')->getFont()->getColor()->setARGB('FFFFFFFF'); // Color de texto blanco
        $sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:Q1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:Q1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1E293B');
        return $sheet;
    }
}
