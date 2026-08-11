<!-- Modal Asignar Sucursales -->
<div
    class="modal fade"
    id="ModalAsignarSucursales"
    tabindex="-1"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg"
        style="margin-top: 10vh;"
    >
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
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i
                                class="bi bi-building"
                                style="color: white; font-size: 1rem;"
                            ></i>
                        </div>
                        <span>Asignar Sucursales - <span id="modalUsuarioNombre">Usuario</span></span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <input
                    type="hidden"
                    id="asignarIdUsuario"
                >

                <!-- Buscador de sucursales -->
                <div class="mb-3">
                    <div class="input-group">
                        <span
                            class="input-group-text"
                            style="background: var(--bg-light); border: 1px solid var(--border-input); border-radius: 8px 0 0 8px;"
                        >
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            id="buscarSucursal"
                            class="form-control border-start-0"
                            style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Buscar sucursal..."
                            onkeyup="filtrarSucursales()"
                        >
                    </div>
                </div>

                <!-- Lista de sucursales -->
                <div
                    class="row"
                    id="listaSucursales"
                    style="max-height: 400px; overflow-y: auto;"
                >
                    <div class="col-12 py-4 text-center">
                        <span
                            class="spinner-border spinner-border-sm"
                            style="color: var(--text-muted);"
                        ></span>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 8px;">Cargando sucursales...
                        </p>
                    </div>
                </div>

                <!-- Resumen -->
                <div
                    class="mt-3 p-2"
                    style="background: var(--bg-subtle); border-radius: 8px; border: 1px solid var(--border-light);"
                >
                    <span style="font-size: 0.85rem; color: var(--text-secondary);">
                        <i
                            class="bi bi-check2-circle me-1"
                            style="color: var(--success-color);"
                        ></i>
                        <span id="contadorSeleccionadas">0</span> sucursales seleccionadas
                    </span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500;"
                >
                    <i class="bi bi-x"></i> Cancelar
                </button>
                <button
                    type="button"
                    id="btnGuardarSucursales"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500;"
                >
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarToast(mensaje, tipo = 'success', titulo = '') {
        // Mapeo de tipos a clases e iconos
        const config = {
            success: {
                clase: 'toast-success',
                icono: 'bi-check-circle-fill',
                tituloDefault: '¡Éxito!'
            },
            warning: {
                clase: 'toast-warning',
                icono: 'bi-exclamation-triangle-fill',
                tituloDefault: 'Atención'
            },
            danger: {
                clase: 'toast-danger',
                icono: 'bi-exclamation-triangle-fill',
                tituloDefault: 'Error'
            },
            info: {
                clase: 'toast-success',
                icono: 'bi-info-circle-fill',
                tituloDefault: 'Información'
            }
        };

        const cfg = config[tipo] || config.success;
        const tituloFinal = titulo || cfg.tituloDefault;

        // Crear el toast
        const toastHTML = `
            <div class="toast-alert ${cfg.clase} show" role="alert">
                <div class="toast-icon">
                    <i class="bi ${cfg.icono}"></i>
                </div>
                <div class="toast-content">
                    <strong>${tituloFinal}</strong>
                    <span>${mensaje}</span>
                </div>
                <button type="button" class="toast-close" onclick="cerrarToast(this)">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;

        // Buscar o crear contenedor
        let container = document.querySelector('.alerts-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'alerts-toast-container';
            document.body.appendChild(container);
        }

        // Agregar toast al contenedor
        container.insertAdjacentHTML('beforeend', toastHTML);

        // Auto-eliminar después de 5 segundos
        const toast = container.lastElementChild;
        setTimeout(() => {
            cerrarToast(toast.querySelector('.toast-close'));
        }, 5000);
    }

    // ============================================================
    // ABRIR MODAL ASIGNAR SUCURSALES
    // ============================================================
    let todasLasSucursales = [];

    function abrirAsignarSucursales(idUsuario, nombreUsuario) {
        // Limpiar backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Configurar modal
        document.getElementById('asignarIdUsuario').value = idUsuario;
        document.getElementById('modalUsuarioNombre').textContent = nombreUsuario;

        // Cargar sucursales
        cargarSucursales(idUsuario);

        // Mostrar modal
        const modalElement = document.getElementById('ModalAsignarSucursales');
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        modal.show();
    }

    // ============================================================
    // CARGAR SUCURSALES DEL USUARIO
    // ============================================================
    function cargarSucursales(idUsuario) {
        const lista = document.getElementById('listaSucursales');
        lista.innerHTML = `
            <div class="col-12 text-center py-4">
                <span class="spinner-border spinner-border-sm" style="color: var(--text-muted);"></span>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 8px;">Cargando sucursales...</p>
            </div>
        `;

        fetch(`/api/usuarios-sucursales/${idUsuario}`)
            .then(response => response.json())
            .then(data => {
                todasLasSucursales = data.todas;
                const asignadas = data.asignadas || [];

                let html = '';
                data.todas.forEach(sucursal => {
                    const checked = asignadas.includes(sucursal.id_sucursal) ? 'checked' : '';
                    html += `
                        <div class="col-md-6 col-lg-4 mb-2 sucursal-item">
                            <div class="form-check p-2" style="border: 1px solid var(--border-light); border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">
                                <input class="form-check-input sucursal-checkbox" type="checkbox" value="${sucursal.id_sucursal}" id="suc_${sucursal.id_sucursal}" ${checked} onchange="actualizarContador()">
                                <label class="form-check-label w-100" style="font-size: 0.85rem; cursor: pointer;" for="suc_${sucursal.id_sucursal}">
                                    ${sucursal.Sucursal}
                                </label>
                            </div>
                        </div>
                    `;
                });

                lista.innerHTML = html;
                actualizarContador();

                // Limpiar busqueda
                document.getElementById('buscarSucursal').value = '';
            })
            .catch(error => {
                lista.innerHTML = `
                    <div class="col-12 text-center py-4 text-danger">
                        <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                        <p style="font-size: 0.85rem; margin-top: 8px;">Error al cargar sucursales</p>
                    </div>
                `;
            });
    }

    // ============================================================
    // FILTRAR SUCURSALES
    // ============================================================
    function filtrarSucursales() {
        const filtro = document.getElementById('buscarSucursal').value.toLowerCase().trim();
        const items = document.querySelectorAll('.sucursal-item');

        items.forEach(item => {
            const texto = item.textContent.toLowerCase();
            item.style.display = filtro === '' || texto.includes(filtro) ? '' : 'none';
        });
    }

    // ============================================================
    // ACTUALIZAR CONTADOR
    // ============================================================
    function actualizarContador() {
        const seleccionadas = document.querySelectorAll('.sucursal-checkbox:checked').length;
        document.getElementById('contadorSeleccionadas').textContent = seleccionadas;
    }

    // ============================================================
    // GUARDAR ASIGNACIÓN
    // ============================================================
    document.getElementById('btnGuardarSucursales').addEventListener('click', function() {
        const idUsuario = document.getElementById('asignarIdUsuario').value;
        const checkboxes = document.querySelectorAll('.sucursal-checkbox:checked');
        const sucursales = Array.from(checkboxes).map(cb => parseInt(cb.value));

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Guardando...';

        fetch('/UsuariosPorSucursal/asignar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                        'content') || ''
                },
                body: JSON.stringify({
                    IdUsuario: idUsuario,
                    sucursales: sucursales
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarToast(`Sucursales asignadas correctamente (${data.total} seleccionadas)`,
                        'success');

                    const modalElement = document.getElementById('ModalAsignarSucursales');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();

                    setTimeout(() => location.reload(), 1000);
                } else {
                    mostrarToast(data.message || 'Error al asignar sucursales', 'danger');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-save"></i> Guardar';
                }
            })
            .catch(error => {
                mostrarToast('Error de conexión: ' + error.message, 'danger');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-save"></i> Guardar';
            });
    });

    // ============================================================
    // LIMPIAR AL CERRAR EL MODAL
    // ============================================================

    document.getElementById('ModalAsignarSucursales').addEventListener('hidden.bs.modal', function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        const btn = document.getElementById('btnGuardarSucursales');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save"></i> Guardar';
    });
</script>
