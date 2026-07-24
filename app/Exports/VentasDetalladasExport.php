<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class VentasDetalladasExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private Builder|QueryBuilder $query;
    private string $fechaCorte;

    public function __construct(Builder|QueryBuilder $query, $fechaCorte = null)
    {
        $this->query = $query;
        $this->fechaCorte = $fechaCorte ?? now()->format('Y-m-d');
    }

    public function query()
    {
        return $this->query;
    }

    public function title(): string
    {
        return 'Ventas ' . Carbon::parse($this->fechaCorte)->format('d-m-Y');
    }

    public function map($row): array
    {
        $fechaVenta = $row->FechaVenta ? Carbon::parse($row->FechaVenta) : null;

        return [
            // Datos del Ticket
            $row->IdTicket,
            $row->IdEncabezado,
            $row->Linea,

            // Fechas
            $fechaVenta ? $fechaVenta->format('d/m/Y') : '',
            $fechaVenta ? $fechaVenta->format('H:i:s') : '',
            $fechaVenta ? $this->getTurno($fechaVenta->format('H:i:s')) : '',

            // Tienda
            $row->IdTienda,
            $row->NomTienda,

            // Vendedor
            $row->IdUsuario,
            $row->NomUsuario,
            $row->NombreEmpleado,

            // Artículo
            $row->IdArticulo,
            $row->CodArticulo,
            $row->NomArticulo,
            $row->NomFamilia,
            $row->NomGrupo,
            (float) $row->CantArticulo,
            (float) $row->PrecioArticulo,
            (float) $row->PrecioLista,
            (float) $row->ImporteArticulo,
            (float) $row->IvaArticulo,
            (float) $row->SubTotalArticulo,

            // Totales del ticket
            (float) $row->SubTotal,
            (float) $row->Iva,
            (float) $row->ImporteVenta,

            // Comprador
            $row->NumNomina,
            trim($row->NombreEmpleadoComprador . ' ' . ($row->ApellidosEmpleadoComprador ?? '')),

            // Pago y precios
            $row->NomTipoPago,
            $row->IdListaPrecio,
            $row->Recorte ? 'Sí' : 'No',

            // Descuentos y paquetes
            $row->NomPaquete ?? '',
            $row->NomDescuento ?? '',
            $row->IdEncDescuento ?? '',

            // Facturación
            $row->SolicitudFE ?? '',
            $row->IdSolicitudFactura ?? '',
            $row->UUID ?? '',
            $row->NomCliente ?? '',

            // Status
            $row->StatusVenta == '0' ? 'Activa' : 'Cancelada',
        ];
    }

    public function headings(): array
    {
        return [
            // Datos del Ticket
            'ID Ticket',
            'Encabezado',
            'Línea',

            // Fechas
            'Fecha Venta',
            'Hora Venta',
            'Turno',

            // Tienda
            'ID Tienda',
            'Tienda',

            // Vendedor
            'ID Vendedor',
            'Usuario Vendedor',
            'Nombre Vendedor',

            // Artículo
            'ID Artículo',
            'Código Artículo',
            'Artículo',
            'Familia',
            'Grupo',
            'Cantidad',
            'Precio Unitario',
            'Precio Lista',
            'Importe Artículo',
            'IVA Artículo',
            'SubTotal Artículo',

            // Totales Ticket
            'SubTotal Ticket',
            'IVA Ticket',
            'Importe Total',

            // Comprador
            'Nómina Comprador',
            'Empleado Comprador',

            // Pago y precios
            'Tipo Pago',
            'Lista Precios',
            'Es Recorte',

            // Descuentos y paquetes
            'Paquete',
            'Descuento',
            'ID Descuento',

            // Facturación
            'Solicitud FE',
            'ID Solicitud Factura',
            'UUID',
            'Cliente',

            // Status
            'Estatus Venta',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID Ticket
            'B' => 15,  // Encabezado
            'C' => 8,   // Línea
            'D' => 14,  // Fecha Venta
            'E' => 12,  // Hora Venta
            'F' => 12,  // Turno
            'G' => 10,  // ID Tienda
            'H' => 35,  // Tienda
            'I' => 12,  // ID Vendedor
            'J' => 20,  // Usuario Vendedor
            'K' => 35,  // Nombre Vendedor
            'L' => 12,  // ID Artículo
            'M' => 16,  // Código Artículo
            'N' => 35,  // Artículo
            'O' => 18,  // Familia
            'P' => 18,  // Grupo
            'Q' => 12,  // Cantidad
            'R' => 16,  // Precio Unitario
            'S' => 16,  // Precio Lista
            'T' => 16,  // Importe Artículo
            'U' => 14,  // IVA Artículo
            'V' => 16,  // SubTotal Artículo
            'W' => 16,  // SubTotal Ticket
            'X' => 14,  // IVA Ticket
            'Y' => 16,  // Importe Total
            'Z' => 15,  // Nómina Comprador
            'AA' => 35, // Empleado Comprador
            'AB' => 14, // Tipo Pago
            'AC' => 14, // Lista Precios
            'AD' => 12, // Es Recorte
            'AE' => 20, // Paquete
            'AF' => 20, // Descuento
            'AG' => 15, // ID Descuento
            'AH' => 14, // Solicitud FE
            'AI' => 18, // ID Solicitud Factura
            'AJ' => 40, // UUID
            'AK' => 30, // Cliente
            'AL' => 14, // Estatus Venta
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'AL';
        $highestRow = $sheet->getHighestRow();

        // Estilo de encabezados
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'], // Azul oscuro
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Alto de fila de encabezados
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Color de fondo alternado para filas
        for ($row = 2; $row <= $highestRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('EFF6FF'); // Azul muy claro
            }
        }

        // Formato de moneda para columnas de importes
        $sheet->getStyle("R2:Y{$highestRow}")->getNumberFormat()
            ->setFormatCode('$#,##0.00');

        // Formato de cantidad
        $sheet->getStyle("Q2:Q{$highestRow}")->getNumberFormat()
            ->setFormatCode('#,##0.0000');

        // Alineación derecha para números
        $sheet->getStyle("Q2:Y{$highestRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Alineación central para IDs y códigos
        $sheet->getStyle("A2:C{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("G2:G{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("I2:I{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("L2:M{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("Z2:Z{$highestRow}")->getAlignment()->setHorizontal('center');

        // Colorear estatus
        $conditionalStyleCancelada = [
            'font' => ['color' => ['rgb' => 'DC2626'], 'bold' => true],
        ];
        $conditionalStyleActiva = [
            'font' => ['color' => ['rgb' => '059669']],
        ];

        // Aplicar formato condicional al estatus
        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("AL{$row}")->getValue();
            if ($cellValue === 'Cancelada') {
                $sheet->getStyle("AL{$row}")->applyFromArray($conditionalStyleCancelada);
            } else {
                $sheet->getStyle("AL{$row}")->applyFromArray($conditionalStyleActiva);
            }
        }

        // Auto-filtro en todas las columnas
        $sheet->setAutoFilter("A1:{$lastColumn}1");

        // Congelar panel (encabezados y primeras 3 columnas)
        $sheet->freezePane('D2');

        // Bordes suaves para toda la tabla
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DBEAFE'],
                ],
            ],
        ];
        $sheet->getStyle("A1:{$lastColumn}{$highestRow}")->applyFromArray($styleArray);

        // Agregar fila de resumen al final
        $lastDataRow = $highestRow + 1;
        $summaryRow = $highestRow + 2;

        $sheet->getStyle("A{$summaryRow}:{$lastColumn}{$summaryRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DBEAFE'],
            ],
        ]);

        $sheet->setCellValue("A{$summaryRow}", 'RESUMEN');
        $sheet->mergeCells("A{$summaryRow}:P{$summaryRow}");

        $sheet->setCellValue("Q{$summaryRow}", "=SUBTOTAL(9,Q2:Q{$highestRow})");
        $sheet->setCellValue("T{$summaryRow}", "=SUBTOTAL(9,T2:T{$highestRow})");
        $sheet->setCellValue("V{$summaryRow}", "=SUBTOTAL(9,V2:V{$highestRow})");
        $sheet->setCellValue("Y{$summaryRow}", "=SUBTOTAL(9,Y2:Y{$highestRow})");

        // Formato moneda para totales
        $sheet->getStyle("T{$summaryRow}")->getNumberFormat()->setFormatCode('$#,##0.00');
        $sheet->getStyle("V{$summaryRow}")->getNumberFormat()->setFormatCode('$#,##0.00');
        $sheet->getStyle("Y{$summaryRow}")->getNumberFormat()->setFormatCode('$#,##0.00');

        $sheet->getStyle("Q{$summaryRow}")->getNumberFormat()->setFormatCode('#,##0.0000');

        return $sheet;
    }

    /**
     * Determina el turno basado en la hora
     */
    private function getTurno(string $hora)
    {
        $horaInt = (int) substr($hora, 0, 2);

        if ($horaInt >= 6 && $horaInt < 14) {
            return 'Matutino';
        } elseif ($horaInt >= 14 && $horaInt < 22) {
            return 'Vespertino';
        } else {
            return 'Nocturno';
        }
    }
}
