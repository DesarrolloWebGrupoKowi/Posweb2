<!-- Modal Agregar Cliente Cloud -->
<div
    class="modal fade"
    id="ModalAgregar"
    tabindex="-1"
    aria-labelledby="ModalAgregarLabel"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg"
        style="margin-top: 5vh;"
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
                    id="ModalAgregarLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-cloud"></i>
                        </div>
                        <span>Agregar Cliente Cloud</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <!-- Buscador -->
                <div class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-search"></i>
                                </span>
                                <input
                                    type="text"
                                    id="txtBuscarCustomer"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    placeholder="Buscar cliente por nombre..."
                                    tabindex="1"
                                >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button
                                type="button"
                                id="btnBuscarCustomer"
                                class="btn d-flex align-items-center w-100 gap-2"
                                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500;"
                            >
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resultados -->
                <form
                    action="/GuardarCustomerCloud"
                    method="GET"
                    id="formGuardarCustomers"
                >
                    <div
                        class="rounded"
                        style="border: 1px solid #e2e8f0; max-height: 400px; overflow-y: auto;"
                    >
                        <table class="table-hover table-custom mb-0 table table">
                            <thead style="position: sticky; top: 0; z-index: 1; background: #f8fafc;">
                                <tr>
                                    <th style="width: 20%;"><i class="fa fa-hashtag me-1"></i>Id Cliente</th>
                                    <th style="width: 65%;"><i class="fa fa-font me-1"></i>Nombre</th>
                                    <th
                                        class="text-center"
                                        style="width: 15%;"
                                    ><i class="fa fa-check-square me-1"></i>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyCustomers">
                                <tr>
                                    <td
                                        colspan="3"
                                        class="text-muted py-4 text-center"
                                    >
                                        <i
                                            class="fa fa-search"
                                            style="font-size: 2rem; color: #94a3b8;"
                                        ></i>
                                        <p class="mb-0 mt-2">Realiza una búsqueda para encontrar clientes</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
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
                    Cerrar
                </button>
                <button
                    type="button"
                    id="btnGuardarCustomers"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'"
                >
                    <i class="fa fa-save"></i>
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buscar clientes
        document.getElementById('btnBuscarCustomer').addEventListener('click', function() {
            buscarClientes();
        });

        // Buscar con Enter
        document.getElementById('txtBuscarCustomer').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarClientes();
            }
        });

        // Guardar seleccionados
        document.getElementById('btnGuardarCustomers').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll(
                '#tbodyCustomers input[type="checkbox"]:checked');

            if (checkboxes.length === 0) {
                alert('Selecciona al menos un cliente');
                return;
            }

            // Construir URL con los IDs seleccionados
            const ids = Array.from(checkboxes).map(cb => cb.value);
            const url = '/GuardarCustomerCloud?' + ids.map(id => 'chkCustomer[]=' + id).join('&');

            window.location.href = url;
        });

        function buscarClientes() {
            const filtro = document.getElementById('txtBuscarCustomer').value;
            const tbody = document.getElementById('tbodyCustomers');

            if (!filtro.trim()) {
                alert('Ingresa un texto para buscar');
                return;
            }

            // Mostrar loading
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center py-4">
                        <div class="spinner-border text-secondary" role="status" style="width: 1.5rem; height: 1.5rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 mb-0 text-muted" style="font-size: 0.85rem;">Buscando clientes...</p>
                    </td>
                </tr>
            `;

            // Petición AJAX
            fetch(`/BuscarCustomer?txtFiltro=${encodeURIComponent(filtro)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="fa fa-inbox" style="font-size: 2rem; color: #94a3b8;"></i>
                                <p class="mt-2 mb-0">No se encontraron coincidencias</p>
                            </td>
                        </tr>
                    `;
                    } else {
                        tbody.innerHTML = data.map(customer => `
                        <tr>
                            <td style="font-weight: 600; color: #0f172a;">${customer.ID_CLIENTE}</td>
                            <td>${customer.NOMBRE}</td>
                            <td class="text-center">
                                <input class="form-check-input" type="checkbox" name="chkCustomer[]" value="${customer.ID_CLIENTE}">
                            </td>
                        </tr>
                    `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-4 text-danger">
                            <i class="fa fa-exclamation-triangle" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Error al buscar clientes. Intenta de nuevo.</p>
                        </td>
                    </tr>
                `;
                });
        }
    });
</script>
