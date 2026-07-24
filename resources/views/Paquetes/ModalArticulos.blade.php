<!-- Modal Artículos Paquete -->
<div
    class="modal fade"
    id="ModalArticulos{{ $paquete->IdPaquete }}"
    tabindex="-1"
    aria-labelledby="ModalArticulos{{ $paquete->IdPaquete }}Label"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg"
        style="margin-top: 10vh;"
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
                    id="ModalArticulos{{ $paquete->IdPaquete }}Label"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-box"></i>
                        </div>
                        <span>{{ $paquete->NomPaquete }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table-hover table-custom mb-0 table">
                        <thead>
                            <tr>
                                <th class="py-1"><i class="bi bi-upc me-1"></i>Código</th>
                                <th class="py-1"><i class="bi bi-box me-1"></i>Artículo</th>
                                <th class="py-1 text-center"><i class="bi bi-123 me-1"></i>Cantidad</th>
                                <th class="py-1 text-end"><i class="bi bi-currency-dollar me-1"></i>Precio</th>
                                <th class="py-1 text-end"><i class="bi bi-calculator me-1"></i>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalImporte = 0; @endphp
                            @foreach ($paquete->ArticulosPaquete as $aPaquete)
                                <tr>
                                    <td
                                        class="py-1"
                                        style="font-weight: 500; color: #0f172a;"
                                    >{{ $aPaquete->CodArticulo }}</td>
                                    <td class="py-1">{{ $aPaquete->NomArticulo }}</td>
                                    <td class="py-1 text-center">{{ number_format($aPaquete->CantArticulo, 2) }}</td>
                                    <td class="py-1 text-end">${{ number_format($aPaquete->PrecioArticulo, 2) }}</td>
                                    <td
                                        class="py-1 text-end"
                                        style="font-weight: 500;"
                                    >${{ number_format($aPaquete->ImporteArticulo, 2) }}</td>
                                </tr>
                                @php $totalImporte += $aPaquete->ImporteArticulo; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #f8fafc; font-weight: 700; border-top: 2px solid #e2e8f0;">
                                <td
                                    colspan="4"
                                    class="py-1 text-end"
                                >Total</td>
                                <td
                                    class="py-1 text-end"
                                    style="color: #059669;"
                                >${{ number_format($totalImporte, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                    <i class="bi bi-x-lg"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
