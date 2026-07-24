<x-page-container title="Asignación de Menús">
    <x-card-gradient-header
        icon="menu-button-wide-fill"
        title="Asignación de Menús"
        subtitle="Configuración de accesos por tipo de usuario"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/DatMenuTipoUsuario"
            id="formChange"
        >
            <x-form.group>
                <x-form.select
                    name="IdTipoUsuario"
                    label="Tipo de usuario"
                    icon="person-badge"
                    col="col-md-4"
                    placeholder="Seleccione tipo de usuario"
                    :options="$tipoUsuarios->pluck('NomTipoUsuario', 'IdTipoUsuario')->toArray()"
                    :selected="$filtroIdTipoUsuario ?? '0'"
                    onchange="MenuTipoUsuario()"
                />
            </x-form.group>
        </x-form.form>

        @if ($filtroIdTipoUsuario)
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-shield-check me-2"
                                style="color: #64748b;"
                            ></i>
                            Configuración de {{ $TipoUsuarioFind->NomTipoUsuario }}
                        </h5>
                        <p class="section-content-subtitle">
                            Gestione los accesos usando los checkboxes y botones de acción
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Menús Disponibles -->
                    <div class="col-md-6">
                        <div class="card-modern">
                            <div
                                class="card-header border-bottom p-3"
                                style="background: linear-gradient(135deg, #fefce8 0%, #fff7ed 100%);"
                            >
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6
                                            class="fw-bold mb-0"
                                            style="color: #0f172a;"
                                        >
                                            <i
                                                class="bi bi-folder fs-5 me-2"
                                                style="color: #f59e0b;"
                                            ></i>
                                            Menús Disponibles
                                        </h6>
                                        <small class="text-muted ms-4">{{ count($menus) }} menús sin asignar</small>
                                    </div>
                                    <span class="badge bg-warning text-warning rounded-pill bg-opacity-10 px-3 py-2">
                                        <i class="bi bi-collection me-1"></i>{{ count($menus) }}
                                    </span>
                                </div>
                            </div>
                            <div
                                class="card-body p-0"
                                style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                            >
                                <table class="table-hover table-custom mb-0 table">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th
                                                width="50"
                                                class="text-center"
                                            >
                                                <input
                                                    class="form-check-input-modern select-all-checkbox"
                                                    type="checkbox"
                                                    data-group="chkAgregarMenu[]"
                                                >
                                            </th>
                                            <th class="ps-0">Menú</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaDisponibles">
                                        @forelse($menus as $menu)
                                            <tr class="menu-row">
                                                <td class="text-center">
                                                    <input
                                                        class="form-check-input-modern menu-checkbox"
                                                        type="checkbox"
                                                        name="chkAgregarMenu[]"
                                                        value="{{ $menu->IdMenu }}"
                                                        data-menu-name="{{ $menu->NomMenu }}"
                                                    >
                                                </td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="bi bi-link-45deg me-2"
                                                            style="color: #94a3b8;"
                                                        ></i>
                                                        <a
                                                            href="{{ $menu->Link }}"
                                                            target="_blank"
                                                            class="text-decoration-none menu-link"
                                                        >
                                                            {{ $menu->NomMenu }}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $menu->NomTipoMenu }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="py-5 text-center">
                                                        <i class="bi bi-folder-check fs-1 text-muted d-block mb-3"></i>
                                                        <h6 class="text-muted">Sin menús disponibles</h6>
                                                        <small class="text-muted">Todos los menús han sido
                                                            asignados</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer border-top bg-white p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small
                                        class="text-muted"
                                        id="countAgregar"
                                    >
                                        <span class="selected-count">0</span> seleccionados
                                    </small>
                                    <form
                                        id="formAgregar"
                                        action="/AgregarMenu"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        <input
                                            type="hidden"
                                            name="IdTipoUsuario"
                                            value="{{ $filtroIdTipoUsuario }}"
                                        >
                                        <div id="hiddenInputsAgregar"></div>
                                        <button
                                            type="submit"
                                            class="btn-modern btn-warning-modern"
                                            id="btnAgregar"
                                            disabled
                                        >
                                            <i class="bi bi-arrow-right-circle me-2"></i>Agregar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menús Asignados -->
                    <div class="col-md-6">
                        <div class="card-modern">
                            <div
                                class="card-header border-bottom p-3"
                                style="background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);"
                            >
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6
                                            class="fw-bold mb-0"
                                            style="color: #0f172a;"
                                        >
                                            <i
                                                class="bi bi-check-circle fs-5 me-2"
                                                style="color: #10b981;"
                                            ></i>
                                            Menús Asignados
                                        </h6>
                                        <small class="text-muted ms-4">{{ count($menuTipoUsuarios) }} menús
                                            configurados</small>
                                    </div>
                                    <span class="badge bg-success text-success rounded-pill bg-opacity-10 px-3 py-2">
                                        <i class="bi bi-collection-check me-1"></i>{{ count($menuTipoUsuarios) }}
                                    </span>
                                </div>
                            </div>
                            <div
                                class="card-body p-0"
                                style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                            >
                                <table class="table-hover table-custom mb-0 table">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th
                                                width="50"
                                                class="text-center"
                                            >
                                                <input
                                                    class="form-check-input-modern select-all-checkbox"
                                                    type="checkbox"
                                                    data-group="chkRemoverMenu[]"
                                                >
                                            </th>
                                            <th class="ps-0">Menú</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaAsignados">
                                        @forelse($menuTipoUsuarios as $menuTipoUsuario)
                                            <tr class="menu-row">
                                                <td class="text-center">
                                                    <input
                                                        class="form-check-input-modern menu-checkbox"
                                                        type="checkbox"
                                                        name="chkRemoverMenu[]"
                                                        value="{{ $menuTipoUsuario->cmpIdMenu }}"
                                                        data-menu-name="{{ $menuTipoUsuario->cmpNomMenu }}"
                                                    >
                                                </td>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="bi bi-link-45deg me-2"
                                                            style="color: #10b981;"
                                                        ></i>
                                                        <a
                                                            href="{{ $menuTipoUsuario->cmpLink }}"
                                                            target="_blank"
                                                            class="text-decoration-none menu-link"
                                                        >
                                                            {{ $menuTipoUsuario->cmpNomMenu }}
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $menuTipoUsuario->NomTipoMenu }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="py-5 text-center">
                                                        <i
                                                            class="bi bi-shield-exclamation fs-1 text-muted d-block mb-3"></i>
                                                        <h6 class="text-muted">Sin menús asignados</h6>
                                                        <small class="text-muted">No hay menús configurados para este
                                                            usuario</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer border-top bg-white p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small
                                        class="text-muted"
                                        id="countRemover"
                                    >
                                        <span class="selected-count">0</span> seleccionados
                                    </small>
                                    <form
                                        id="formRemover"
                                        action="/RemoverMenu"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        <input
                                            type="hidden"
                                            name="IdTipoUsuario"
                                            value="{{ $filtroIdTipoUsuario }}"
                                        >
                                        <div id="hiddenInputsRemover"></div>
                                        <button
                                            type="submit"
                                            class="btn-modern btn-remover"
                                            id="btnRemover"
                                            disabled
                                        >
                                            <i class="bi bi-arrow-left-circle me-2"></i>Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-person-badge display-1"
                        style="color: #cbd5e1;"
                    ></i>
                </div>
                <h5 style="color: #0f172a;">Seleccione un tipo de usuario</h5>
                <p class="text-muted">Elija un tipo de usuario del filtro para comenzar a configurar sus accesos</p>
            </div>
        @endif
    </x-card-gradient-header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            actualizarEstadoBotones();

            document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    actualizarEstadoBotones();
                    toggleRowHighlight(this);
                });
            });

            document.querySelectorAll('.select-all-checkbox').forEach(selectAll => {
                selectAll.addEventListener('change', function() {
                    const groupName = this.dataset.group;
                    const checkboxes = document.querySelectorAll(`input[name="${groupName}"]`);
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                        toggleRowHighlight(cb);
                    });
                    actualizarEstadoBotones();
                });
            });

            document.getElementById('formAgregar').addEventListener('submit', function(e) {
                e.preventDefault();
                const seleccionados = document.querySelectorAll('input[name="chkAgregarMenu[]"]:checked');
                if (seleccionados.length === 0) {
                    alert('Seleccione al menos un menú para agregar');
                    return;
                }
                const hiddenContainer = document.getElementById('hiddenInputsAgregar');
                hiddenContainer.innerHTML = '';
                seleccionados.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'chkAgregarMenu[]';
                    input.value = cb.value;
                    hiddenContainer.appendChild(input);
                });
                this.submit();
            });

            document.getElementById('formRemover').addEventListener('submit', function(e) {
                e.preventDefault();
                const seleccionados = document.querySelectorAll('input[name="chkRemoverMenu[]"]:checked');
                if (seleccionados.length === 0) {
                    alert('Seleccione al menos un menú para remover');
                    return;
                }
                const hiddenContainer = document.getElementById('hiddenInputsRemover');
                hiddenContainer.innerHTML = '';
                seleccionados.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'chkRemoverMenu[]';
                    input.value = cb.value;
                    hiddenContainer.appendChild(input);
                });
                this.submit();
            });
        });

        function MenuTipoUsuario() {
            const tipoUsuario = document.getElementById('IdTipoUsuario').value;
            if (tipoUsuario === '0') {
                window.location.href = '/DatMenuTipoUsuario';
                return;
            }
            document.getElementById("formChange").submit();
        }

        function actualizarEstadoBotones() {
            const removerSeleccionados = document.querySelectorAll('input[name="chkRemoverMenu[]"]:checked').length;
            const agregarSeleccionados = document.querySelectorAll('input[name="chkAgregarMenu[]"]:checked').length;

            const btnRemover = document.getElementById('btnRemover');
            const btnAgregar = document.getElementById('btnAgregar');
            const countRemover = document.querySelector('#countRemover .selected-count');
            const countAgregar = document.querySelector('#countAgregar .selected-count');

            if (btnRemover) {
                btnRemover.disabled = removerSeleccionados === 0;
                btnRemover.classList.toggle('btn-disabled', removerSeleccionados === 0);
            }

            if (btnAgregar) {
                btnAgregar.disabled = agregarSeleccionados === 0;
                btnAgregar.classList.toggle('btn-disabled', agregarSeleccionados === 0);
            }

            if (countRemover) {
                countRemover.textContent = removerSeleccionados;
                countRemover.style.color = removerSeleccionados > 0 ? '#ef4444' : '#64748b';
                countRemover.style.fontWeight = removerSeleccionados > 0 ? '700' : '400';
            }

            if (countAgregar) {
                countAgregar.textContent = agregarSeleccionados;
                countAgregar.style.color = agregarSeleccionados > 0 ? '#f59e0b' : '#64748b';
                countAgregar.style.fontWeight = agregarSeleccionados > 0 ? '700' : '400';
            }
        }

        function toggleRowHighlight(checkbox) {
            const row = checkbox.closest('tr');
            if (checkbox.checked) {
                row.style.backgroundColor = '#eff6ff';
                row.style.borderLeft = '3px solid #3b82f6';
            } else {
                row.style.backgroundColor = '';
                row.style.borderLeft = '';
            }
        }
    </script>
</x-page-container>
