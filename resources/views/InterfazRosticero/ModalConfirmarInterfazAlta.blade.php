<div
    class="modal fade"
    id="ModalConfirmarInterfazAlta"
    tabindex="-1"
>
    <div class="modal-dialog modal-dialog-centered">
        <div
            class="modal-content"
            style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <div class="p-4 text-center">
                <!-- Icono -->
                <div
                    class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px; background: #eff6ff;"
                >
                    <i
                        class="bi bi-box-arrow-up"
                        style="font-size: 1.5rem; color: #3b82f6;"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: #0f172a;"
                >Interfazar Altas</h5>

                <!-- Mensaje -->
                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 24px;">¿Estás seguro de dar alta a los
                    productos?</p>

                <!-- Botones -->
                <div class="d-flex gap-2">
                    <button
                        type="button"
                        class="btn flex-grow-1"
                        data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                    >
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <form
                        action="/InterfazarRosticeroAlta/{{ $idTienda }}/{{ $fecha1 }}/{{ $fecha2 }}"
                        method="POST"
                        class="flex-grow-1"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="btn w-100"
                            style="background: #3b82f6; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-check-circle me-1"></i> Interfazar Altas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
