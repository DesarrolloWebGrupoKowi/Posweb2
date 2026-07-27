<div
    class="modal fade"
    id="ModalConfirmarInterfazCreditos"
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
                    style="width: 64px; height: 64px; background: var(--btn-amber-bg);"
                >
                    <i
                        class="bi bi-cash-stack"
                        style="font-size: 1.5rem; color: var(--btn-amber-text);"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: var(--text-primary);"
                >Interfazar Créditos</h5>

                <!-- Mensaje -->
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 24px;">¿Estás seguro de
                    interfazar los créditos?</p>

                <!-- Botones -->
                <div class="d-flex gap-2">
                    <button
                        type="button"
                        class="btn flex-grow-1"
                        data-bs-dismiss="modal"
                        style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                    >
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <form
                        action="InterfazarCreditos/{{ $fecha1 }}/{{ $fecha2 }}/{{ empty($idTipoNomina) ? 0 : $idTipoNomina }}/{{ empty($numNomina) ? 0 : $numNomina }}"
                        method="POST"
                        class="flex-grow-1"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="btn w-100"
                            id="btnExportar"
                            style="background: var(--warning-color); color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-check-circle me-1"></i> Interfazar Créditos
                        </button>
                        <button
                            id="btnCargandoDatos"
                            hidden
                            class="btn w-100"
                            type="button"
                            disabled
                            style="background: var(--warning-color); color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem; opacity: 0.7;"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Interfazando créditos...
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnExportar').addEventListener('click', function() {
        document.getElementById('btnExportar').hidden = true;
        document.getElementById('btnCargandoDatos').hidden = false;
    });
</script>
