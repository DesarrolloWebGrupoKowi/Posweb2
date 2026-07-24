<!-- Modal Reactivar Artículo -->
<div class="modal fade" id="ModalReactivarArticulo" tabindex="-1" aria-labelledby="ModalReactivarArticuloLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="p-4 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f0fdf4;">
                    <i class="bi bi-arrow-repeat" style="font-size: 1.5rem; color: #10b981;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: #0f172a;" id="ModalReactivarArticuloLabel">Artículo Existente</h5>
                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 4px;">El artículo está desactivado.</p>
                <p style="color: #94a3b8; font-size: 0.78rem; margin-bottom: 24px;">¿Desea reactivarlo?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn flex-grow-1" data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="button" id="btnReactivarArticulo" class="btn flex-grow-1"
                        style="background: #10b981; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;">
                        <i class="bi bi-check-lg me-1"></i> Reactivar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
