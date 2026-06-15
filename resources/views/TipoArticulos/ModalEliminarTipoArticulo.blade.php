<!-- Modal Eliminar Tipo Artículo -->
<div
    class="modal fade"
    id="ModalEliminarTipoArticulo{{ $tipoArticulo->IdCatTipoArticulo }}"
    tabindex="-1"
    aria-labelledby="ModalEliminarTipoArticuloLabel{{ $tipoArticulo->IdCatTipoArticulo }}"
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
                style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalEliminarTipoArticuloLabel{{ $tipoArticulo->IdCatTipoArticulo }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-trash"></i>
                        </div>
                        <span>Eliminar Tipo de Artículo</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EliminarTipoArticulo/{{ $tipoArticulo->IdCatTipoArticulo }}"
                    method="POST"
                >
                    @csrf

                    <!-- Mensaje de confirmación -->
                    <div class="mb-4 text-center">
                        <div
                            class="d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 48px; height: 48px; background-color: #fef2f2; border-radius: 50%;"
                        >
                            <i
                                class="fa fa-exclamation-triangle"
                                style="color: #ef4444; font-size: 1.3rem;"
                            ></i>
                        </div>
                        <p style="font-weight: 500; color: #475569; margin-bottom: 0.25rem; font-size: 0.9rem;">
                            ¿Seguro que desea eliminar este tipo de artículo?
                        </p>
                        <p style="font-weight: 600; color: #dc2626; margin-bottom: 0.25rem; font-size: 0.95rem;">
                            {{ $tipoArticulo->IdTipoArticulo }} - {{ $tipoArticulo->NomTipoArticulo }}
                        </p>
                    </div>

                    <!-- Línea divisoria -->
                    <hr style="border-color: #e2e8f0; margin: 1.25rem 0;">

                    <!-- Información adicional -->
                    <div
                        class="d-flex align-items-center gap-3 rounded p-3"
                        style="background: #fef2f2; border-left: 3px solid #ef4444;"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: #fee2e2; width: 36px; height: 36px;"
                        >
                            <i
                                class="fa fa-info-circle"
                                style="color: #ef4444;"
                            ></i>
                        </div>
                        <div>
                            <small style="color: #991b1b; font-size: 0.78rem;">
                                Esta acción eliminará permanentemente el tipo de artículo.
                            </small>
                        </div>
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
                    <i class="fa fa-times"></i>
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="fa fa-trash"></i>
                    Eliminar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
