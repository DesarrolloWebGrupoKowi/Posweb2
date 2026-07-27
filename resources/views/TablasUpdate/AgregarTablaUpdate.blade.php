<!-- Modal Agregar Tabla Update -->
<div
    class="modal fade"
    id="AgregarTablaUpdate"
    tabindex="-1"
    aria-labelledby="AgregarTablaUpdateLabel"
    aria-hidden="true"
>
    <div class="modal-dialog {{ $tablas->count() == 0 ? '' : 'modal-xl' }}" style="margin-top: 10vh;">
        <div
            class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="AgregarTablaUpdateLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-database"></i>
                        </div>
                        <span>Agregar Tabla Actualizable</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <form
                action="/AgregarTablaUpdate/{{ $idTienda }}"
                method="POST"
            >
                @csrf
                <div class="modal-body p-4">
                    @if ($tablas->count() == 0)
                        <div class="py-5 text-center">
                            <i
                                class="bi bi-inbox fs-1 d-block mb-3"
                                style="color: var(--border-light);"
                            ></i>
                            <p
                                class="fs-6 fw-normal text-secondary m-0"
                                style="line-height: 24px"
                            >
                                No hay tablas por agregar
                            </p>
                        </div>
                    @else
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input-modern"
                                    type="checkbox"
                                    id="seleccionarTodos"
                                >
                                <label
                                    class="form-check-label fw-bold"
                                    for="seleccionarTodos"
                                    style="color: var(--text-primary); font-size: 0.9rem;"
                                >
                                    Seleccionar todos
                                </label>
                            </div>
                        </div>
                        <div
                            class="row"
                            style="max-height: 50vh; overflow-y: auto; padding-right: 8px;"
                        >
                            @foreach ($tablas as $tabla)
                                <div class="col-4 mb-2">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input-modern checkbox-nomTabla"
                                            type="checkbox"
                                            name="nomTablas[]"
                                            id="nomTablas_{{ $loop->index }}"
                                            value="{{ $tabla->NomTabla }}"
                                        >
                                        <label
                                            class="form-check-label menu-link"
                                            for="nomTablas_{{ $loop->index }}"
                                        >
                                            {{ $tabla->NomTabla }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button
                        type="button"
                        class="btn d-flex align-items-center gap-1"
                        data-bs-dismiss="modal"
                        style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    >
                        <i class="fa fa-times"></i>
                        Cerrar
                    </button>
                    @if ($tablas->count() > 0)
                        <button
                            type="submit"
                            class="btn d-flex align-items-center gap-1"
                            style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        >
                            <i class="fa fa-save"></i>
                            Agregar
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        function initSeleccionarTodos() {
            const modal = document.getElementById('AgregarTablaUpdate');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'seleccionarTodos') {
                        const checkboxesNomTablas = modal.querySelectorAll('.checkbox-nomTabla');
                        checkboxesNomTablas.forEach(checkbox => {
                            checkbox.checked = e.target.checked;
                            toggleRowHighlight(checkbox);
                        });
                    }
                });

                modal.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('checkbox-nomTabla')) {
                        toggleRowHighlight(e.target);
                        setTimeout(function() {
                            const seleccionarTodos = modal.querySelector('#seleccionarTodos');
                            const checkboxesNomTablas = modal.querySelectorAll(
                                '.checkbox-nomTabla');
                            if (seleccionarTodos) {
                                const todosMarcados = Array.from(checkboxesNomTablas).every(cb => cb
                                    .checked);
                                seleccionarTodos.checked = todosMarcados;
                            }
                        }, 10);
                    }
                });
            }
        }

        function toggleRowHighlight(checkbox) {
            const row = checkbox.closest('.form-check');
            if (row) {
                if (checkbox.checked) {
                    row.style.backgroundColor = 'var(--tag-blue-bg)';
                    row.style.borderRadius = '6px';
                    row.style.padding = '4px 8px';
                    row.style.transition = 'all 0.2s ease';
                } else {
                    row.style.backgroundColor = '';
                    row.style.padding = '';
                }
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSeleccionarTodos);
        } else {
            initSeleccionarTodos();
        }
    })();
</script>
