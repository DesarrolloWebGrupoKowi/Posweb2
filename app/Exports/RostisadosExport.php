<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RostisadosExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct(private Collection $reporte) {}

    public function collection()
    {
        return $this->reporte;
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Tienda',
            'Fecha',
            'CodigoBaja',
            'ArticuloBaja',
            'CantidadBaja',
            'CodigoAlta',
            'ArticuloAlta',
            'CantidadAlta',
            'MermaEstandar',
            'MermaReal',
            'Recalentado',
            'Usuario Baja',
            'Usuario Alta',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $sheet->getStyle('A1:N1')->getFont()->getColor()->setARGB('FFFFFFFF');

        $sheet->getStyle('A1:N1')
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1:N1')
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A1:N1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('1E293B');

        return $sheet;
    }
}
