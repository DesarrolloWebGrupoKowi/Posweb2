<!-- Modal Detalle Ticket -->
<div
    class="modal fade"
    id="ModalDetalleTicket{{ $solicitud->IdEncabezado }}"
    tabindex="-1"
    aria-labelledby="ModalDetalleTicket{{ $solicitud->IdEncabezado }}Label"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-xl"
        style="margin-top: 5vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 16px; overflow: hidden;"
        >
            <!-- Modal Header -->
            <div class="px-4 pt-4 pb-3" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(255, 255, 255, 0.15); width: 40px; height: 40px;">
                        <i class="bi bi-receipt fs-5 text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 text-white" style="font-weight: 600; font-size: 1.1rem;">
                            Ticket #{{ $solicitud->Encabezado->IdTicket }}
                        </h5>
                        <p class="mb-0" style="color: rgba(255,255,255,0.5); font-size: 0.78rem;">
                            Caja {{ $solicitud->Encabezado->NumCaja }} &middot; Folio {{ $solicitud->Encabezado->IdEncabezado }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-4">
                <!-- Resumen -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 2px;">Fecha de Venta</div>
                        <div style="font-weight: 600; color: #0f172a;">
                            {{ \Carbon\Carbon::parse($solicitud->Encabezado->FechaVenta)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 2px;">Importe Total</div>
                        <div style="font-weight: 700; color: #059669; font-size: 1.1rem;">
                            ${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Tabla de artículos -->
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table-custom table mb-0">
                        <thead style="position: sticky; top: 0; z-index: 1; background: #f8fafc;">
                            <tr>
                                <th>Código</th>
                                <th>Artículo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">IVA</th>
                                <th class="text-end">Importe</th>
                                <th>Paquete</th>
                                <th>Pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCantidad = 0;
                                $totalIva = 0;
                                $totalImporte = 0;
                            @endphp
                            @foreach ($solicitud->Detalle as $detalle)
                                <tr>
                                    <td style="font-weight: 500; color: #0f172a;">{{ $detalle->CodArticulo }}</td>
                                    <td>{{ $detalle->NomArticulo }}</td>
                                    <td class="text-center">{{ number_format($detalle->CantArticulo, 3) }}</td>
                                    <td class="text-end">${{ number_format($detalle->PrecioArticulo, 2) }}</td>
                                    <td class="text-end">${{ number_format($detalle->IvaArticulo, 2) }}</td>
                                    <td class="text-end" style="font-weight: 500;">${{ number_format($detalle->ImporteArticulo, 2) }}</td>
                                    <td>{{ $detalle->NomPaquete }}</td>
                                    <td>{{ $detalle->Cliente }}</td>
                                </tr>
                                @php
                                    $totalCantidad += $detalle->CantArticulo;
                                    $totalIva += $detalle->IvaArticulo;
                                    $totalImporte += $detalle->ImporteArticulo;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #f8fafc; font-weight: 700; border-top: 2px solid #e2e8f0;">
                                <td colspan="2" class="text-end">Totales</td>
                                <td class="text-center">{{ number_format($totalCantidad, 3) }}</td>
                                <td></td>
                                <td class="text-end">${{ number_format($totalIva, 2) }}</td>
                                <td class="text-end" style="color: #059669;">${{ number_format($totalImporte, 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Motivo de cancelación -->
                @if (isset($solicitud->MotivoCancelacion) && !empty($solicitud->MotivoCancelacion))
                    <div class="mt-4 p-3" style="background: #fffbeb; border-radius: 8px;">
                        <div style="font-weight: 600; color: #92400e; font-size: 0.8rem; margin-bottom: 4px;">
                            <i class="bi bi-exclamation-triangle me-1" style="color: #f59e0b;"></i>Motivo de Cancelación
                        </div>
                        <p class="mb-0" style="color: #a16207; font-size: 0.85rem; line-height: 1.5;">
                            {{ $solicitud->MotivoCancelacion }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="px-4 pb-4 d-flex justify-content-end">
                <button
                    type="button"
                    class="btn"
                    data-bs-dismiss="modal"
                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 20px; font-size: 0.85rem; font-weight: 500;"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
