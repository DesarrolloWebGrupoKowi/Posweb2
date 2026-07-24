<div
    class="modal fade"
    id="ModalLotes{{ $merma->CodArticulo }}"
    tabindex="-1"
    aria-labelledby="ModalLotes{{ $merma->CodArticulo }}"
    aria-hidden="true"
>
    <div
        class="modal-dialog"
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
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i
                                class="bi bi-database"
                                style="color: white; font-size: 0.9rem;"
                            ></i>
                        </div>
                        <span>Lotes Disponibles</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 pb-0">
                <!-- Info del artículo -->
                <div
                    class="d-flex align-items-center mb-3 gap-2 p-2"
                    style="background: #f8fafc; border-radius: 8px;"
                >
                    <i
                        class="bi bi-box"
                        style="color: #64748b;"
                    ></i>
                    <span style="color: #0f172a; font-weight: 600; font-size: 0.85rem;">
                        {{ $merma->NomArticulo }}
                    </span>
                    <span
                        class="tags-blue"
                        style="font-size: 0.7rem;"
                    >
                        {{ number_format($merma->CantArticulo, 3) }} kg
                    </span>
                </div>

                <!-- Tabla de lotes -->
                <div class="table-responsive">
                    <table class="table-hover table-custom table-sm table">
                        <thead>
                            <tr>
                                <th
                                    class="py-1"
                                    style="font-size: 0.75rem;"
                                ><i class="bi bi-upc me-1"></i>Lote</th>
                                <th
                                    class="py-1"
                                    style="font-size: 0.75rem;"
                                ><i class="bi bi-calendar me-1"></i>Caducidad</th>
                                <th
                                    class="py-1 text-end"
                                    style="font-size: 0.75rem;"
                                ><i class="bi bi-box me-1"></i>Inventario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalLote = 0;
                            @endphp
                            @foreach ($merma->Lotes as $lote)
                                <tr>
                                    <td style="font-size: 0.8rem;">
                                        <span
                                            class="fw-semibold"
                                            style="color: #0f172a;"
                                        >{{ $lote->LOT_NUMBER }}</span>
                                    </td>
                                    <td style="font-size: 0.8rem;">
                                        <span style="color: #475569;">
                                            {{ strftime('%d %B %Y', strtotime($lote->EXPIRATION)) }}
                                        </span>
                                    </td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500; font-size: 0.8rem;"
                                    >{{ number_format($lote->TOTAL, 3) }} kg</td>
                                </tr>
                                @php
                                    $totalLote = $totalLote + $lote->TOTAL;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #f8fafc;">
                                <td></td>
                                <td
                                    class="fw-bold py-1 text-end"
                                    style="color: #0f172a; font-size: 0.8rem;"
                                >Total:</td>
                                <td
                                    class="fw-bold py-1 text-end"
                                    style="color: #0f172a; font-size: 0.8rem;"
                                >{{ number_format($totalLote, 3) }} kg</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Alerta inventario insuficiente -->
                @if ($merma->CantArticulo >= $totalLote)
                    <div
                        class="d-flex align-items-center justify-content-center mt-3 gap-2 p-2"
                        style="background: #fef2f2; border-radius: 8px;"
                    >
                        <i
                            class="bi bi-exclamation-triangle"
                            style="color: #ef4444; font-size: 0.8rem;"
                        ></i>
                        <span style="color: #dc2626; font-weight: 600; font-size: 0.78rem;">
                            Inventario Insuficiente
                        </span>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 6px 14px; font-size: 0.8rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                >
                    <i
                        class="bi bi-x"
                        style="font-size: 1.2rem;"
                    ></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
