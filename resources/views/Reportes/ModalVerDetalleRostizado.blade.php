@php
    $piezasActivas = $tConcentrado->Detalle->where('Status', 0)->whereNull('CantMermaRecalentado');

    $cantidadTotal = $piezasActivas->sum('Cantidad');
    $totalPiezas = $tConcentrado->Detalle->count();
    $totalVendidas = $piezasActivas->where('Vendida', 1)->count();
    $totalMermaRecalentado = $tConcentrado->Detalle->sum('CantMermaRecalentado');
@endphp
<div
    class="modal fade"
    id="modalDetalle{{ $tConcentrado->IdRosticero }}"
    tabindex="-1"
    aria-labelledby="modalDetalleLabel{{ $tConcentrado->IdRosticero }}"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg"
        style="margin-top: 5vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px; overflow: hidden;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="modalDetalleLabel{{ $tConcentrado->IdRosticero }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-fire"></i>
                        </div>
                        <span>Detalle de Rostisado: {{ $tConcentrado->IdRosticero }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                {{-- Info resumen --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <small style="color: #94a3b8;">Artículo Baja</small>
                        <div style="font-weight: 500;">{{ $tConcentrado->CodigoMatPrima }}</div>
                        <small style="color: #64748b;">{{ $tConcentrado->ArticuloMatPrima }}</small>
                    </div>
                    <div class="col-md-3">
                        <small style="color: #94a3b8;">Artículo Alta</small>
                        <div style="font-weight: 500;">{{ $tConcentrado->CodigoVenta }}</div>
                        <small style="color: #64748b;">{{ $tConcentrado->ArticuloVenta }}</small>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #94a3b8;">Total Piezas</small>
                        <div style="font-weight: 600; font-size: 1.1rem;">{{ $totalPiezas }}</div>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #94a3b8;">Vendidas</small>
                        <div style="font-weight: 600; color: #10b981; font-size: 1.1rem;">{{ $totalVendidas }}</div>
                    </div>
                    <div class="col-md-2">
                        <small style="color: #94a3b8;">Merma Recal.</small>
                        <div style="font-weight: 600; color: #ef4444; font-size: 1.1rem;">
                            {{ number_format($totalMermaRecalentado, 3) }}</div>
                    </div>
                </div>

                {{-- Tabla de detalle --}}
                @if ($tConcentrado->Detalle->count() > 0)
                    <div
                        class="table-responsive"
                        style="max-height: 400px; overflow-y: auto;"
                    >
                        <table class="table-hover table-custom table-sm mb-0 table">
                            <thead style="position: sticky; top: 0; z-index: 2;">
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>Línea</th>
                                    <th><i class="bi bi-upc me-1"></i>Etiqueta</th>
                                    <th class="text-end"><i class="bi bi-weight me-1"></i>Cantidad</th>
                                    <th class="text-end"><i class="bi bi-fire me-1"></i>Merma Recal.</th>
                                    <th><i class="bi bi-clock me-1"></i>Fecha</th>
                                    <th class="text-center"><i class="bi bi-circle me-1"></i>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tConcentrado->Detalle as $detalle)
                                    <tr
                                        class="{{ $detalle->CantMermaRecalentado ? 'table-warning' : '' }}"
                                        style="{{ $detalle->CantMermaRecalentado ? 'background: #fffbeb;' : '' }} {{ $detalle->Status == 1 ? 'text-decoration: line-through #64748b;' : '' }}"
                                    >
                                        <td style="font-weight: 500;">{{ $detalle->Linea }}</td>
                                        <td style="font-size: 0.8rem; color: #64748b;">{{ $detalle->CodigoEtiqueta }}
                                        </td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 500;"
                                        >{{ number_format($detalle->Cantidad, 3) }}</td>
                                        <td class="text-end">
                                            @if ($detalle->CantMermaRecalentado)
                                                <span
                                                    style="color: #ef4444; font-weight: 500;">{{ number_format($detalle->CantMermaRecalentado, 3) }}</span>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td style="font-size: 0.78rem; color: #64748b;">
                                            {{ \Carbon\Carbon::parse($detalle->FechaCreacion)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            @if ($detalle->Status == 1)
                                                <span
                                                    style="background: #fef2f2; color: #ef4444; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 500;"
                                                >Cancelado</span>
                                            @else
                                                <span
                                                    style="background: #f0fdf4; color: #10b981; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 500;"
                                                >Activo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background: #f1f5f9; font-weight: 700;">
                                    <td
                                        colspan="2"
                                        class="text-end"
                                    >Totales:</td>
                                    <td class="text-end">{{ number_format($cantidadTotal, 3) }}</td>
                                    <td class="text-end">{{ number_format($totalMermaRecalentado, 3) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    {{-- Estado vacío --}}
                    <div class="py-5 text-center">
                        <i
                            class="bi bi-inbox"
                            style="font-size: 3rem; color: #94a3b8;"
                        ></i>
                        <h6
                            class="mt-2"
                            style="color: #64748b; font-weight: 600;"
                        >Sin detalle disponible</h6>
                        <p style="color: #94a3b8; font-size: 0.85rem;">Este rostisado no tiene piezas registradas</p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
