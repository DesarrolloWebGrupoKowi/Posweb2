<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class ReportePaquetes implements FromQuery, WithHeadings, WithStyles
{
    public function __construct(private Builder $query) {}

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'IdEncabezado',
            'IdPaquete',
            'IdPreparado',
            'NomPaquete',
            'NomTienda',
            'FechaVenta',
            'CodArticulo',
            'NomArticulo',
            'NomFamilia',
            'NomGrupo',
            'CantArticulo',
            'PrecioArticulo',
            'IvaArticulo',
            'ImporteArticulo'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $sheet->getStyle('A1:N1')->getFont()->getColor()->setARGB('FFFFFFFF'); // Color de texto blanco
        $sheet->getStyle('A1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:N1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1E293B');
        return $sheet;
    }
}
