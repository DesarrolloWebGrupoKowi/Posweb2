<div class="modal fade"
    id="ModalDetalleTicket{{ $solicitud->IdEncabezado }}"
    tabindex="-1"
    aria-labelledby="modalDetalleTicketLabel-{{ $solicitud->IdEncabezado }}"
    aria-hidden="true">
    <div class="modal-dialog modal-xl"
        style="margin-top: 5vh;">
        <div class="modal-content border-0 shadow"
            style="border-radius: 10px;">

            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 10px 10px 0 0;">
                <h5 class="text-white"
                    id="modalDetalleTicketLabel-{{ $solicitud->IdEncabezado }}">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;">
                            @include('components.icons.list')
                        </div>
                        <span>Detalle del Ticket #{{ $solicitud->Encabezado->IdTicket }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <!-- Información resumen del ticket -->
                <div class="d-flex flex-wrap gap-4 mb-4 pb-3 border-bottom"
                    style="border-color: #e5e7eb !important;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                            style="background-color: rgba(30, 41, 59, 0.1); width: 32px; height: 32px;">
                            <div class="d-flex align-items-center justify-content-center"
                                style="color: #1e293b; width: 16px; height: 16px;">
                                @include('components.icons.hash')
                            </div>
                        </div>
                        <div>
                            <div class="small text-muted">Folio</div>
                            <div class="fw-600">{{ $solicitud->Encabezado->IdEncabezado }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                            style="background-color: rgba(30, 41, 59, 0.1); width: 32px; height: 32px;">
                            <div class="d-flex align-items-center justify-content-center"
                                style="color: #1e293b; width: 16px; height: 16px;">
                                @include('components.icons.calendar')
                            </div>
                        </div>
                        <div>
                            <div class="small text-muted">Fecha</div>
                            <div class="fw-600">
                                {{ \Carbon\Carbon::parse($solicitud->Encabezado->FechaVenta)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                            style="background-color: rgba(30, 41, 59, 0.1); width: 32px; height: 32px;">
                            <div class="d-flex align-items-center justify-content-center"
                                style="color: #1e293b; width: 16px; height: 16px;">
                                @include('components.icons.cash')
                            </div>
                        </div>
                        <div>
                            <div class="small text-muted">Total</div>
                            <div class="fw-600">${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de artículos -->
                <div class="table-responsive content-table-sm"
                    style="max-height: 400px; overflow-y: auto;">
                    <table class="table">
                        <thead class="table-head text-white">
                            <tr>
                                <th class="rounded-start text-start">Código</th>
                                <th class="text-start">Artículo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">IVA</th>
                                <th class="text-end">Importe</th>
                                <th>Paquete</th>
                                <th class="rounded-end">Pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCantidad = 0;
                                $totalIva = 0;
                                $totalImporte = 0;
                            @endphp
                            @foreach ($solicitud->Detalle as $detalle)
                                <tr class="small">
                                    <td class="text-start py-1"><span class="fw-500 small">{{ $detalle->CodArticulo }}</span></td>
                                    <td class="text-start py-1">
                                        <span class="small">
                                        {{ $detalle->NomArticulo }}
                                        </span>
                                    </td>
                                    <td class="text-center small py-1">{{ number_format($detalle->CantArticulo, 3) }}</td>
                                    <td class="text-end small py-1">${{ number_format($detalle->PrecioArticulo, 2) }}</td>
                                    <td class="text-end small py-1">${{ number_format($detalle->IvaArticulo, 2) }}</td>
                                    <td class="text-end small py-1 fw-500">
                                        ${{ number_format($detalle->ImporteArticulo, 2) }}</td>
                                    <td class="small py-1">{{ $detalle->NomPaquete }}</td>
                                    <td class="small py-1">{{ $detalle->Cliente }}</td>
                                </tr>
                                @php
                                    $totalCantidad += $detalle->CantArticulo;
                                    $totalIva += $detalle->IvaArticulo;
                                    $totalImporte += $detalle->ImporteArticulo;
                                @endphp
                            @endforeach

                            <!-- Fila de totales -->
                            <tr class="table-light fw-bold">
                                <td colspan="2"
                                    class="text-end py-0">TOTALES:</td>
                                <td class="text-center py-0">{{ number_format($totalCantidad, 3) }}</td>
                                <td></td>
                                <td class="text-end py-0">${{ number_format($totalIva, 2) }}</td>
                                <td class="text-end py-0">${{ number_format($totalImporte, 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Información adicional si existe -->
                @if (isset($solicitud->MotivoCancelacion) && !empty($solicitud->MotivoCancelacion))
                    <div class="mt-3 p-3"
                        style="background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                        <div class="d-flex align-items-center gap-2">
                            <div style="color: #856404;">
                                @include('components.icons.alert-circle')
                            </div>
                            <span class="small fw-500">Motivo de cancelación:</span>
                            <span class="small">{{ $solicitud->MotivoCancelacion }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0">
                <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 8px 20px;">
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.x')
                        Cerrar
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
