<x-page-container title="Clientes Cloud Por Tienda">
    <x-card-gradient-header
        icon="cloud-check"
        title="Clientes Cloud Por Tienda"
        subtitle="Gestione la relación de clientes cloud con tiendas"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            <a
                href="/VerClientesCloudTienda"
                class="btn-modern btn-outline-modern"
            >
                <i class="bi bi-plus-circle me-2"></i>Agregar Cliente
            </a>
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form id="formBuscar">
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-5"
                    placeholder="Seleccione tienda"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                />
                <x-form.select
                    name="idClienteCloud"
                    label="Cliente Cloud"
                    icon="cloud"
                    col="col-md-5"
                    placeholder="Seleccione cliente cloud"
                    :options="$clientesCloud->pluck('NomClienteCloud', 'IdClienteCloud')->toArray()"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="search"
                    class="flex-grow-1"
                    id="btnBuscar"
                />
            </div>
        </x-form.form>

        <!-- Área de resultados y guardado -->
        <div class="row g-4 p-4">
            <!-- Columna izquierda: Direcciones -->
            <div
                class="col-lg-6"
                id="columnaDirecciones"
            >
                <div class="py-5 text-center">
                    <i
                        class="bi bi-search display-1"
                        style="color: #cbd5e1;"
                    ></i>
                    <h5
                        style="color: #0f172a;"
                        class="mt-3"
                    >Realice una búsqueda</h5>
                    <p class="text-muted">Seleccione tienda y cliente cloud para ver las direcciones disponibles</p>
                </div>
            </div>

            <!-- Columna derecha: Formulario de guardado -->
            <div
                class="col-lg-6"
                id="columnaGuardado"
            >
                <div class="py-5 text-center">
                    <i
                        class="bi bi-arrow-left display-1"
                        style="color: #cbd5e1;"
                    ></i>
                    <h5
                        style="color: #0f172a;"
                        class="mt-3"
                    >Seleccione direcciones</h5>
                    <p class="text-muted">Marque las direcciones de envío y facturación para continuar</p>
                </div>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formBuscar = document.getElementById('formBuscar');
        const columnaDirecciones = document.getElementById('columnaDirecciones');
        const columnaGuardado = document.getElementById('columnaGuardado');

        formBuscar.addEventListener('submit', function(e) {
            e.preventDefault();

            const idTienda = this.querySelector('[name="idTienda"]').value;
            const idClienteCloud = this.querySelector('[name="idClienteCloud"]').value;

            if (!idTienda || !idClienteCloud) {
                alert('Seleccione tienda y cliente cloud');
                return;
            }

            const btn = this.querySelector('#btnBuscar');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Buscando...';
            }

            columnaDirecciones.innerHTML = `
                <div class="py-5 text-center">
                    <div class="spinner-border mb-3" style="color: #3b82f6;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted">Cargando direcciones...</p>
                </div>`;
            columnaGuardado.innerHTML = `
                <div class="py-5 text-center">
                    <i class="bi bi-arrow-left display-1" style="color: #cbd5e1;"></i>
                    <h5 style="color: #0f172a;" class="mt-3">Seleccione direcciones</h5>
                    <p class="text-muted">Marque las direcciones de envío y facturación para continuar</p>
                </div>`;

            fetch(`/RelacionClienteCloudTienda?idTienda=${idTienda}&idClienteCloud=${idClienteCloud}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    columnaDirecciones.innerHTML = extraerBody(html);
                    inicializarFormDirecciones();
                })
                .finally(() => {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-search me-2"></i>Buscar';
                    }
                });
        });

        function extraerBody(html) {
            const match = html.match(/<body[^>]*>([\s\S]*)<\/body>/i);
            return match ? match[1] : html;
        }

        function inicializarFormDirecciones() {
            const form = columnaDirecciones.querySelector('form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const shipCheck = form.querySelector('input[name="chkShipTo[]"]:checked');
                const billCheck = form.querySelector('input[name="chkBillTo[]"]:checked');

                if (!shipCheck || !billCheck) {
                    alert('Debe seleccionar al menos una dirección de envío y una de facturación');
                    return;
                }

                const formData = new FormData(this);
                const params = new URLSearchParams(formData).toString();

                columnaGuardado.innerHTML = `
                    <div class="py-5 text-center">
                        <div class="spinner-border mb-3" style="color: #3b82f6;" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted">Cargando formulario...</p>
                    </div>`;

                fetch('/GuardarRelacionClienteCloud?' + params, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        columnaGuardado.innerHTML = extraerBody(html);
                        inicializarFormGuardado();
                    });
            });
        }

        function inicializarFormGuardado() {
            const form = columnaGuardado.querySelector('form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.style.opacity = '0.6';
                    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Guardando...';
                }

                const formData = new FormData(this);

                fetch('/GuardarDatClienteCloud', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            columnaGuardado.innerHTML = `
                            <div class="py-5 text-center">
                                <i class="bi bi-check-circle display-1" style="color: #10b981;"></i>
                                <h5 style="color: #0f172a;" class="mt-3">Guardado correctamente</h5>
                                <p class="text-muted">La relación se ha guardado con éxito</p>
                            </div>`;
                            columnaDirecciones.innerHTML = `
                            <div class="py-5 text-center">
                                <i class="bi bi-search display-1" style="color: #cbd5e1;"></i>
                                <h5 style="color: #0f172a;" class="mt-3">Realice una nueva búsqueda</h5>
                                <p class="text-muted">Seleccione tienda y cliente cloud para continuar</p>
                            </div>`;
                        }
                    })
                    .catch(() => {
                        if (btn) {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                            btn.innerHTML = '<i class="bi bi-floppy me-2"></i>Guardar';
                        }
                    });
            });
        }
    });
</script>
