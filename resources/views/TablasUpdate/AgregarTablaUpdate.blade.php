<!-- Modal Agregar Tabla Update-->
<div class="modal fade" id="AgregarTablaUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog {{ $tablas->count() == 0 ? '' : 'modal-xl' }}">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tabla Actualizable</h5>
            </div>
            <form action="/AgregarTablaUpdate/{{ $idTienda }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <div class="row">
                                @if ($tablas->count() == 0)
                                    <div class="container">
                                        <div class="d-flex justify-content-center">
                                            {{-- <h4 style="text-align: center"></h4> --}}
                                            <p class="fs-6 text-center fw-normal text-secondary m-0"
                                                style="line-height: 24px">
                                                No hay tablas por agregar
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="seleccionarTodos">
                                            <label class="form-check-label fw-bold" for="seleccionarTodos">
                                                Seleccionar todos
                                            </label>
                                        </div>
                                    </div>
                                    @foreach ($tablas as $tabla)
                                        <div class="col-4">
                                            <input class="form-check-input checkbox-nomTabla" type="checkbox" name="nomTablas[]"
                                                id="nomTablas_{{ $loop->index }}" value="{{ $tabla->NomTabla }}">
                                            <label class="form-check-label" for="nomTablas_{{ $loop->index }}">
                                                {{ $tabla->NomTabla }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    @if ($tablas->count() > 0)
                        <button class="btn btn-sm btn-warning">
                            <i class="fa fa-save"></i> Agregar
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Funcionalidad para seleccionar/deseleccionar todos los checkboxes de nomTablas
    (function() {
        function initSeleccionarTodos() {
            const modal = document.getElementById('AgregarTablaUpdate');
            if (modal) {
                // Event delegation para el checkbox "seleccionar todos"
                modal.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'seleccionarTodos') {
                        const checkboxesNomTablas = modal.querySelectorAll('.checkbox-nomTabla');
                        checkboxesNomTablas.forEach(checkbox => {
                            checkbox.checked = e.target.checked;
                        });
                    }
                });

                // Event delegation para los checkboxes individuales
                modal.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('checkbox-nomTabla')) {
                        setTimeout(function() {
                            const seleccionarTodos = modal.querySelector('#seleccionarTodos');
                            const checkboxesNomTablas = modal.querySelectorAll('.checkbox-nomTabla');
                            if (seleccionarTodos) {
                                const todosMarcados = Array.from(checkboxesNomTablas).every(cb => cb.checked);
                                seleccionarTodos.checked = todosMarcados;
                            }
                        }, 10);
                    }
                });
            }
        }

        // Intentar inicializar cuando el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSeleccionarTodos);
        } else {
            initSeleccionarTodos();
        }
    })();
</script>
