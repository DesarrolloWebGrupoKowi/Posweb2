<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TicketsCanceladosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
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

    public function map($row): array
    {
        // Formatear fechas
        $fechaVenta = $row->FechaVenta ? Carbon::parse($row->FechaVenta) : null;
        $fechaCancelacion = $row->FechaCancelacion ? Carbon::parse($row->FechaCancelacion) : null;

        // Calcular tiempo entre venta y cancelación
        $tiempoCancelacion = '';
        if ($fechaVenta && $fechaCancelacion) {
            $diferencia = $fechaVenta->diff($fechaCancelacion);
            $tiempoCancelacion = sprintf(
                '%02d:%02d:%02d',
                $diferencia->h + ($diferencia->days * 24),
                $diferencia->i,
                $diferencia->s
            );
        }

        return [
            // Datos del Ticket
            $row->IdTicket,
            $row->IdEncabezado,

            // Fechas
            $fechaVenta ? $fechaVenta->format('d/m/Y') : '',
            $fechaVenta ? $fechaVenta->format('H:i:s') : '',
            $fechaCancelacion ? $fechaCancelacion->format('d/m/Y') : '',
            $fechaCancelacion ? $fechaCancelacion->format('H:i:s') : '',
            $tiempoCancelacion,

            // Tienda
            $row->IdTienda,
            $row->NomTienda,

            // Artículo
            $row->IdArticulo,
            $row->CodArticulo,
            $row->NomArticulo,
            $row->NomFamilia,
            $row->NomGrupo,
            (float) $row->CantArticulo,
            (float) $row->PrecioArticulo,
            (float) $row->ImporteArticulo,
            (float) $row->IvaArticulo,
            (float) $row->SubTotalArticulo,

            // Totales del ticket (se repiten por artículo)
            (float) $row->SubTotal,
            (float) $row->Iva,
            (float) $row->ImporteVenta,

            // Cancelación
            $row->IdUsuarioCancelacion,
            trim($row->NombreUsuarioCancelacion . ' ' . $row->ApellidoUsuarioCancelacion),
            $row->MotivoCancel,

            // Comprador
            $row->NumNomina,
            trim($row->NombreEmpleadoComprador . ' ' . $row->ApellidosEmpleadoComprador),

            // Vendedor
            $row->IdUsuario,
            $row->NomUsuario,
            $row->NombreEmpleado,

            // Pago
            $row->NomTipoPago,
            $row->IdListaPrecio,

            // Facturación
            $row->IdSolicitudFactura ? 'Sí' : 'No',
            $row->SolicitudFE ?? '',
            $row->UUID ?? '',
            $row->NomCliente ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            // Datos del Ticket
            'ID Ticket',
            'Encabezado',

            // Fechas
            'Fecha Venta',
            'Hora Venta',
            'Fecha Cancelación',
            'Hora Cancelación',
            'Tiempo Transcurrido',

            // Tienda
            'ID Tienda',
            'Tienda',

            // Artículo
            'ID Artículo',
            'Código Artículo',
            'Artículo',
            'Familia',
            'Grupo',
            'Cantidad',
            'Precio Unitario',
            'Importe Artículo',
            'IVA Artículo',
            'SubTotal Artículo',

            // Totales Ticket
            'SubTotal Ticket',
            'IVA Ticket',
            'Importe Total',

            // Cancelación
            'ID Usuario Canceló',
            'Usuario Canceló',
            'Motivo Cancelación',

            // Comprador
            'Nómina Comprador',
            'Empleado Comprador',

            // Vendedor
            'ID Vendedor',
            'Usuario Vendedor',
            'Nombre Vendedor',

            // Pago
            'Tipo Pago',
            'Lista Precios',

            // Facturación
            'Tiene Factura',
            'Solicitud FE',
            'UUID',
            'Cliente',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // ID Ticket
            'B' => 16,  // Encabezado
            'C' => 14,  // Fecha Venta
            'D' => 12,  // Hora Venta
            'E' => 14,  // Fecha Cancelación
            'F' => 12,  // Hora Cancelación
            'G' => 15,  // Tiempo Transcurrido
            'H' => 12,  // ID Tienda
            'I' => 35,  // Tienda
            'J' => 12,  // ID Artículo
            'K' => 16,  // Código Artículo
            'L' => 35,  // Artículo
            'M' => 18,  // Familia
            'N' => 18,  // Grupo
            'O' => 12,  // Cantidad
            'P' => 16,  // Precio Unitario
            'Q' => 16,  // Importe Artículo
            'R' => 14,  // IVA Artículo
            'S' => 16,  // SubTotal Artículo
            'T' => 16,  // SubTotal Ticket
            'U' => 14,  // IVA Ticket
            'V' => 16,  // Importe Total
            'W' => 15,  // ID Usuario Canceló
            'X' => 30,  // Usuario Canceló
            'Y' => 25,  // Motivo Cancelación
            'Z' => 18,  // Nómina Comprador
            'AA' => 30, // Empleado Comprador
            'AB' => 12, // ID Vendedor
            'AC' => 20, // Usuario Vendedor
            'AD' => 30, // Nombre Vendedor
            'AE' => 15, // Tipo Pago
            'AF' => 14, // Lista Precios
            'AG' => 14, // Tiene Factura
            'AH' => 15, // Solicitud FE
            'AI' => 40, // UUID
            'AJ' => 30, // Cliente
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'AJ';

        // Estilo de encabezados
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '991B1B'], // Rojo oscuro para cancelados
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Alto de fila de encabezados
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Color de fondo alternado para filas (efecto zebra)
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEF2F2'); // Rojo muy claro
            }
        }

        // Alineación de columnas numéricas
        $sheet->getStyle("O2:V{$highestRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Formato de moneda para columnas de importes
        $sheet->getStyle("P2:V{$highestRow}")->getNumberFormat()
            ->setFormatCode('$#,##0.00');

        // Formato de cantidad
        $sheet->getStyle("O2:O{$highestRow}")->getNumberFormat()
            ->setFormatCode('#,##0.0000');

        // Alineación central para IDs
        $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("H2:H{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("J2:J{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("W2:W{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("Z2:Z{$highestRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("AB2:AB{$highestRow}")->getAlignment()->setHorizontal('center');

        // Auto-filtro en todas las columnas
        $sheet->setAutoFilter("A1:{$lastColumn}1");

        // Congelar panel (fila de encabezados y primeras 2 columnas)
        $sheet->freezePane('C2');

        // Bordes suaves para toda la tabla
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB'],
                ],
            ],
        ];
        $sheet->getStyle("A1:{$lastColumn}{$highestRow}")->applyFromArray($styleArray);

        return $sheet;
    }
}
