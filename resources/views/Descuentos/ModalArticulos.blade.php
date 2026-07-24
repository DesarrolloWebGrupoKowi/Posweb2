<!-- Modal Artículos Descuento -->
<div
    class="modal fade"
    id="ModalArticulos{{ $descuento->IdEncDescuento }}"
    tabindex="-1"
    aria-labelledby="ModalArticulos{{ $descuento->IdEncDescuento }}Label"
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
                    id="ModalArticulos{{ $descuento->IdEncDescuento }}Label"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-tag"></i>
                        </div>
                        <span>{{ $descuento->NomDescuento }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table-hover table-custom mb-0 table">
                        <thead>
                            <tr>
                                <th class="py-2"><i class="bi bi-upc me-1"></i>Código</th>
                                <th class="py-2"><i class="bi bi-box me-1"></i>Artículo</th>
                                <th class="py-2"><i class="bi bi-list-ol me-1"></i>Lista de Precio</th>
                                <th class="py-2 text-center"><i class="bi bi-123 me-1"></i>Cantidad</th>
                                <th class="py-2 text-end"><i class="bi bi-currency-dollar me-1"></i>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($descuento->ArticulosDescuento as $aDescuento)
                                <tr
                                    class="{{ $aDescuento->Status == 1 ? 'opacity-50' : '' }}"
                                    style="{{ $aDescuento->Status == 1 ? 'background: #f8fafc;' : '' }}"
                                >
                                    <td
                                        class="py-1"
                                        style="font-weight: 500; color: #0f172a;"
                                    >
                                        <span
                                            style="{{ $aDescuento->Status == 1 ? 'text-decoration: line-through;' : '' }}"
                                        >
                                            {{ $aDescuento->CodArticulo }}
                                        </span>
                                    </td>
                                    <td class="py-1">
                                        <span
                                            style="{{ $aDescuento->Status == 1 ? 'text-decoration: line-through;' : '' }}"
                                        >
                                            {{ $aDescuento->NomArticulo }}
                                        </span>
                                    </td>
                                    <td class="py-1">
                                        @if ($aDescuento->Status == 1)
                                            <span
                                                class="badge bg-light text-dark border">{{ $aDescuento->NomListaPrecio }}</span>
                                        @else
                                            {{ $aDescuento->NomListaPrecio }}
                                        @endif
                                    </td>
                                    <td class="py-1 text-center">{{ number_format($aDescuento->CantArticulo, 2) }}</td>
                                    <td
                                        class="py-1 text-end"
                                        style="font-weight: 500;"
                                    >
                                        ${{ number_format($aDescuento->PrecioDescuento, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="py-5 text-center">
                                            <div class="empty-state-icon mx-auto mb-3">
                                                <i
                                                    class="bi bi-inbox fs-3"
                                                    style="color: #94a3b8;"
                                                ></i>
                                            </div>
                                            <h6 class="text-muted">Sin artículos registrados</h6>
                                            <small class="text-muted">No hay artículos asignados a este
                                                descuento</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
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
