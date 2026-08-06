<x-page-container title="Clientes Autoservicio">
    <x-card-gradient-header
        icon="truck"
        title="Clientes Autoservicio"
        subtitle="Configuración de direcciones de envío, facturación y listas de precio"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/ClientesAutoservicio">
            <x-form.group>
                <x-form.text
                    name="filtro"
                    label="Buscar cliente"
                    icon="search"
                    placeholder="Nombre o código del cliente..."
                    col="col-md-4"
                    :autofocus="true"
                />
                <x-form.select
                    name="subinventario"
                    label="Subinventario"
                    icon="building"
                    col="col-md-2"
                    :options="$subInventario->pluck('ORGANIZATION_NAME', 'ORGANIZATION_CODE')->toArray()"
                    placeholder="Todos los almacenes"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear url="/ClientesAutoservicio" />
            </div>
        </x-form.form>

        <!-- Tabla de clientes -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-people me-2"
                            style="color: var(--text-secondary);"
                        ></i>Clientes de Autoservicio
                    </h5>
                    <p class="section-content-subtitle">{{ $clientes->total() }} clientes registrados</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Cliente</th>
                            <th><i class="bi bi-building me-1"></i>Nombre</th>
                            <th><i class="bi bi-geo-alt me-1"></i>Dirección</th>
                            <th><i class="bi bi-person-badge me-1"></i>ID Cliente</th>
                            <th><i class="bi bi-tag me-1"></i>Tipo</th>
                            <th><i class="bi bi-truck me-1"></i>Envío</th>
                            <th><i class="bi bi-receipt me-1"></i>Facturación</th>
                            <th><i class="bi bi-cash me-1"></i>Precio</th>
                            <th><i class="bi bi-cash me-1"></i>Tipo Orden</th>
                            <th class="text-center"><i class="bi bi-check-circle me-1"></i>Estado</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            @php
                                $configurado =
                                    $cliente->ID_CLIENTE &&
                                    $cliente->TIPO_CLIENTE &&
                                    $cliente->TERMINOS &&
                                    $cliente->SHIP_TO &&
                                    $cliente->BILL_TO &&
                                    $cliente->PRICE_LIST_ID;
                                $faltan = [];
                                if (!$cliente->ID_CLIENTE) {
                                    $faltan[] = 'ID Cliente';
                                }
                                if (!$cliente->TIPO_CLIENTE) {
                                    $faltan[] = 'Tipo De Cliente';
                                }
                                if (!$cliente->TERMINOS) {
                                    $faltan[] = 'Terminos';
                                }
                                if (!$cliente->SHIP_TO) {
                                    $faltan[] = 'Envío';
                                }
                                if (!$cliente->BILL_TO) {
                                    $faltan[] = 'Facturación';
                                }
                                if (!$cliente->PRICE_LIST_ID) {
                                    $faltan[] = 'Precio';
                                }
                            @endphp
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">{{ $cliente->Cliente }}</td>
                                <td>{{ $cliente->Nombre }}</td>
                                <td>
                                    <span
                                        class="text-truncate d-inline-block"
                                        style="max-width: 200px;"
                                        title="{{ $cliente->Direccion }}"
                                    >
                                        {{ $cliente->Direccion ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($cliente->ID_CLIENTE)
                                        <span class="">{{ $cliente->ID_CLIENTE }}</span>
                                    @else
                                        <span class="">-</span>
                                    @endif
                                </td>
                                <td>{{ $cliente->TIPO_CLIENTE ?: '-' }}</td>
                                <td>
                                    @if ($cliente->SHIP_TO)
                                        <span class=""><i
                                                class="bi bi-check-circle me-1"></i>{{ $cliente->SHIP_TO }}</span>
                                    @else
                                        <span class="">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($cliente->BILL_TO)
                                        {{-- <span class=""><i class="bi bi-check-circle me-1"></i>Configurado</span> --}}
                                        <span class=""><i
                                                class="bi bi-check-circle me-1"></i>{{ $cliente->BILL_TO }}</span>
                                    @else
                                        <span class="">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($cliente->PRICE_LIST_ID)
                                        <span class="">{{ $cliente->PRICE_LIST_ID }}</span>
                                    @else
                                        <span class="">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($cliente->ORDER_TYPE)
                                        <span class="">{{ $cliente->ORDER_TYPE }}</span>
                                    @else
                                        <span class="">-</span>
                                    @endif
                                </td>
                                <td
                                    class="text-center"
                                    style="padding-top: -4rem;"
                                >
                                    @if ($configurado)
                                        <span
                                            class="tags-green"
                                            style="font-size: 0.75rem; cursor: default;"
                                        >
                                            Completado
                                        </span>
                                    @else
                                        <span
                                            class="tags-yellow"
                                            style="font-size: 0.75rem; cursor: help;"
                                            title="Falta: {{ implode(', ', $faltan) }}"
                                        >
                                            Incompleto
                                            <span style="margin-left: 4px; opacity: 0.8;">({{ count($faltan) }})</span>
                                        </span>
                                    @endif
                                </td>
                                <td
                                    class="text-center"
                                    style="padding-top: .4rem;"
                                >
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Botón Ver -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-animated d-flex align-items-center gap-1"
                                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: 1px solid var(--btn-gray-hover); border-radius: 8px; padding: 6px 12px; font-size: 0.8rem;"
                                            onclick="abrirVerCliente({{ json_encode($cliente) }})"
                                            title="Ver información completa"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <!-- Botón Configurar -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-animated d-flex align-items-center gap-1"
                                            style="background: var(--btn-blue-bg); color: var(--btn-blue-text); border: 1px solid var(--btn-blue-hover); border-radius: 8px; padding: 6px 12px; font-size: 0.8rem;"
                                            onclick="abrirWizard('{{ $cliente->Cliente }}', '{{ $cliente->Nombre }}', '{{ $cliente->NOMBRE_CLIENTE }}', {{ json_encode($cliente) }})"
                                            title="Configurar cliente"
                                        >
                                            <i class="bi bi-sliders"></i> Configurar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="11"
                                    class="py-5 text-center"
                                >
                                    <i
                                        class="bi bi-inbox"
                                        style="font-size: 2.5rem; color: var(--text-muted);"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: var(--text-secondary); font-size: 0.85rem;"
                                    >No se encontraron clientes</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $clientes])
        </div>
    </x-card-gradient-header>

    @include('ClientesAutoservicio.ModalWizard')
    @include('ClientesAutoservicio.ModalVerCliente')

</x-page-container>

<style>
    /* ============================================================ */
    /* ESTILOS DEL WIZARD */
    /* ============================================================ */
    .paso-wizard {
        position: relative;
        cursor: default;
    }

    .paso-circulo {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--bg-subtle);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        border: 2px solid var(--border-light);
    }

    .paso-wizard.active .paso-circulo {
        background: var(--gradient-start);
        color: white;
        border-color: var(--gradient-start);
    }

    .paso-wizard.completado .paso-circulo {
        background: var(--success-color);
        color: white;
        border-color: var(--success-color);
    }

    .paso-wizard small {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .paso-wizard.active small {
        color: var(--text-primary);
        font-weight: 600;
    }

    .paso-wizard.completado small {
        color: var(--success-color);
    }

    /* Radio personalizado en tabla */
    .radio-seleccion {
        cursor: pointer;
    }

    .radio-seleccion:hover {
        background: var(--bg-subtle) !important;
    }

    .radio-seleccion.seleccionado {
        background: var(--btn-blue-bg) !important;
    }

    .radio-seleccion.seleccionado td {
        font-weight: 600;
    }
</style>

<script>
    // ============================================================
    // SISTEMA DE TOASTS DINÁMICOS (REUTILIZANDO TUS ESTILOS)
    // ============================================================
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

    // Función para cerrar toast manualmente
    function cerrarToast(btn) {
        const toast = btn.closest('.toast-alert');
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(120%)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    function validarPaso(paso) {
        const validaciones = {
            1: {
                campo: 'id_cliente',
                mensaje: 'Por favor, selecciona un cliente de la lista.',
                titulo: 'Cliente requerido'
            },
            2: {
                campo: 'ship_to',
                mensaje: 'Por favor, selecciona una dirección de envío (SHIP_TO).',
                titulo: 'Envío requerido'
            },
            3: {
                campo: 'bill_to',
                mensaje: 'Por favor, selecciona una dirección de facturación (BILL_TO).',
                titulo: 'Facturación requerida'
            },
            4: {
                campo: 'price_list_id',
                mensaje: 'Por favor, selecciona una lista de precios.',
                titulo: 'Lista de precios requerida'
            },
            5: {
                campo: 'order_type',
                mensaje: 'Por favor, selecciona un tipo de orden.',
                titulo: 'Tipo de orden requerido'
            }
        };

        const validacion = validaciones[paso];
        if (!validacion) return true;

        // Verificar si el campo está seleccionado o ya existe en datos actuales
        const valorSeleccionado = datosSeleccionados[validacion.campo] ||
            clienteDatosActuales[validacion.campo.toUpperCase()];

        if (!valorSeleccionado) {
            mostrarToast(validacion.mensaje, 'warning', validacion.titulo);
            return false;
        }

        return true;
    }

    // ============================================================
    // WIZARD MEJORADO
    // ============================================================
    let pasoActual = 1;
    let clienteNombre = '';
    let clienteDatosActuales = {}; // Datos actuales del cliente
    let datosSeleccionados = {
        id_cliente: '',
        nombre_cliente: '',
        tipo_cliente: '',
        terminos: '',
        ship_to: '',
        bill_to: '',
        price_list_id: '',
        price_list_name: ''
    };

    function abrirWizard(clienteId, nombreGCSCTEPK, nombreClienteConfigurado, clienteCompleto =
        null) { // Resetear todo primero
        clienteNombre = '';
        clienteDatosActuales = {};
        datosSeleccionados = {
            id_cliente: '',
            nombre_cliente: '',
            tipo_cliente: '',
            terminos: '',
            ship_to: '',
            bill_to: '',
            price_list_id: '',
            price_list_name: ''
        };

        // Si ya tiene NOMBRE_CLIENTE configurado, usar ese (es el nombre real en XXKW_CUSTOMERS)
        if (nombreClienteConfigurado && nombreClienteConfigurado.trim() !== '') {
            clienteNombre = nombreClienteConfigurado;
        } else {
            clienteNombre = nombreGCSCTEPK;
        }

        document.getElementById('inputCliente').value = clienteId;
        document.getElementById('wizardClienteNombre').textContent = nombreGCSCTEPK + ' (' + clienteId + ')';

        // Guardar datos actuales para mostrar "Actualmente configurado"
        if (clienteCompleto) {
            clienteDatosActuales = clienteCompleto;
            datosSeleccionados = {
                id_cliente: clienteCompleto.ID_CLIENTE || '',
                nombre_cliente: clienteCompleto.NOMBRE_CLIENTE || '',
                tipo_cliente: clienteCompleto.TIPO_CLIENTE || '',
                terminos: clienteCompleto.TERMINOS || '',
                ship_to: clienteCompleto.SHIP_TO || '',
                bill_to: clienteCompleto.BILL_TO || '',
                price_list_id: clienteCompleto.PRICE_LIST_ID || '',
                price_list_name: '',
                order_type: clienteCompleto.ORDER_TYPE || '',
                order_type_name: ''
            };
        } else {
            clienteDatosActuales = {};
            datosSeleccionados = {
                id_cliente: '',
                nombre_cliente: '',
                tipo_cliente: '',
                terminos: '',
                ship_to: '',
                bill_to: '',
                price_list_id: '',
                order_type: '',
                order_type_name: ''
            };
        }

        pasoActual = 1;
        irPaso(1);
        cargarPaso1();

        const modal = new bootstrap.Modal(document.getElementById('ModalWizard'));
        modal.show();
    }

    function irPaso(paso) {
        // Si intenta ir a un paso superior, validar con toasts
        if (paso > 1 && paso <= 5) {
            if (!validarPaso(paso - 1)) {
                return;
            }
        }

        pasoActual = paso;

        // Mostrar paso correcto
        document.querySelectorAll('.paso-contenido').forEach(el => el.style.display = 'none');
        document.getElementById('paso' + paso).style.display = 'block';

        // Actualizar timeline
        document.querySelectorAll('.timeline-item').forEach(el => {
            const p = parseInt(el.dataset.paso);
            el.classList.remove('active', 'completado');
            if (p === paso) el.classList.add('active');
            else if (p < paso) el.classList.add('completado');

            if (p > paso) {
                el.style.opacity = '0.4';
                el.style.cursor = 'not-allowed';
                el.style.pointerEvents = 'none';
            } else {
                el.style.opacity = '1';
                el.style.cursor = 'pointer';
                el.style.pointerEvents = 'auto';
            }
        });

        // Botones
        const btnAnterior = document.getElementById('btnAnterior');
        const btnSiguiente = document.getElementById('btnSiguiente');
        const btnGuardar = document.getElementById('btnGuardarWizard');

        btnAnterior.classList.toggle('d-none', paso === 1);

        if (paso === 6) {
            btnSiguiente.classList.add('d-none');
            btnGuardar.classList.remove('d-none');
        } else {
            btnSiguiente.classList.remove('d-none');
            btnGuardar.classList.add('d-none');
        }

        // Mostrar valor actual si existe
        if (paso === 1 && clienteDatosActuales.ID_CLIENTE) {
            document.getElementById('clienteActual').style.display = 'block';
            const nombreCliente = clienteDatosActuales.NOMBRE_CLIENTE || '-';
            document.getElementById('clienteActualTexto').innerHTML =
                `<strong>${nombreCliente}</strong><br><span style="font-size: 0.72rem; color: var(--text-secondary);">ID: ${clienteDatosActuales.ID_CLIENTE} | Tipo: ${clienteDatosActuales.TIPO_CLIENTE || '-'} | Términos: ${clienteDatosActuales.TERMINOS || '-'}</span>`;
        }
        if (paso === 2 && clienteDatosActuales.SHIP_TO) {
            document.getElementById('shipToActual').style.display = 'block';
            // Buscar la dirección en los datos cargados
            const shipToDireccion = document.querySelector('#listaShipTo .radio-seleccion.seleccionado td:last-child')
                ?.textContent || '';
            document.getElementById('shipToActualTexto').innerHTML =
                `<strong>${clienteDatosActuales.SHIP_TO}</strong>` +
                (shipToDireccion ?
                    `<br><span style="font-size: 0.72rem; color: var(--text-secondary);">${shipToDireccion}</span>` : ''
                );
        }
        if (paso === 3 && clienteDatosActuales.BILL_TO) {
            document.getElementById('billToActual').style.display = 'block';
            // Buscar la dirección en los datos cargados
            const billToDireccion = document.querySelector('#listaBillTo .radio-seleccion.seleccionado td:last-child')
                ?.textContent || '';
            document.getElementById('billToActualTexto').innerHTML =
                `<strong>${clienteDatosActuales.BILL_TO}</strong>` +
                (billToDireccion ?
                    `<br><span style="font-size: 0.72rem; color: var(--text-secondary);">${billToDireccion}</span>` : ''
                );
        }
        if (paso === 4 && clienteDatosActuales.PRICE_LIST_ID) {
            document.getElementById('precioActual').style.display = 'block';
            const priceName = datosSeleccionados.price_list_name || clienteDatosActuales.PRICE_LIST_ID || '-';
            document.getElementById('precioActualTexto').innerHTML =
                `<strong>${priceName}</strong><br><span style="font-size: 0.72rem; color: var(--text-secondary);">ID: ${clienteDatosActuales.PRICE_LIST_ID}</span>`;
        }
        if (paso === 5 && clienteDatosActuales.ORDER_TYPE) {
            document.getElementById('tipoOrdenActual').style.display = 'block';
            const orderName = datosSeleccionados.order_type_name || '';
            document.getElementById('tipoOrdenActualTexto').innerHTML =
                `<strong>${orderName || clienteDatosActuales.ORDER_TYPE}</strong>` +
                (orderName ?
                    `<br><span style="font-size: 0.72rem; color: var(--text-secondary);">Código: ${clienteDatosActuales.ORDER_TYPE}</span>` :
                    '');
        }
    }

    // ============================================================
    // PASO 1: CLIENTES
    // ============================================================
    function cargarPaso1(nombreBusqueda) {
        if (!nombreBusqueda) {
            nombreBusqueda = document.getElementById('buscarCliente').value || clienteNombre;
        }
        document.getElementById('buscarCliente').value = nombreBusqueda;

        const lista = document.getElementById('listaClientes');
        lista.innerHTML =
            '<div class="text-center py-4"><span class="spinner-border spinner-border-sm" style="color: var(--text-muted);"></span> Buscando clientes...</div>';

        fetch(`/api/autoservicio/buscar-clientes?nombre=${encodeURIComponent(nombreBusqueda)}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    lista.innerHTML =
                        '<div class="text-center py-4"><i class="bi bi-search" style="font-size: 2rem; color: var(--text-muted);"></i><p style="color: var(--text-muted);">No se encontraron clientes con: <span>' +
                        nombreBusqueda + '</span></p></div>';
                    return;
                }

                let html =
                    '<table class="table table-sm mb-0"><thead><tr><th></th><th>ID Cliente</th><th>Nombre</th><th>Tipo</th><th>Términos</th></tr></thead><tbody>';
                data.forEach((c) => {
                    // ✅ Marcar como seleccionado si coincide con el ID_CLIENTE actual
                    const esActual = clienteDatosActuales.ID_CLIENTE && c.ID_CLIENTE ===
                        clienteDatosActuales.ID_CLIENTE;
                    if (esActual) {
                        datosSeleccionados.id_cliente = c.ID_CLIENTE;
                        datosSeleccionados.nombre_cliente = c.NOMBRE;
                        datosSeleccionados.tipo_cliente = c.TIPO_CLIENTE || '';
                        datosSeleccionados.terminos = c.TERMINOS || '';
                    }

                    html += `
                    <tr class="radio-seleccion ${esActual ? 'seleccionado' : ''}" style="cursor: pointer;">
                        <td><input type="radio" name="clienteRadio" class="form-check-input" ${esActual ? 'checked' : ''}></td>
                        <td style="font-weight: 500;">${c.ID_CLIENTE}</td>
                        <td>${c.NOMBRE}</td>
                        <td>${c.TIPO_CLIENTE || '-'}</td>
                        <td>${c.TERMINOS || '-'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                lista.innerHTML = html;
            })
            .catch(() => {
                lista.innerHTML = '<div class="text-center py-4 text-danger">Error al cargar clientes</div>';
            });
    }

    // ============================================================
    // PASO 2: SHIP_TO
    // ============================================================
    function cargarPaso2(nombreBusqueda) {
        if (!nombreBusqueda) {
            nombreBusqueda = document.getElementById('buscarShipTo').value || clienteNombre;
        }
        document.getElementById('buscarShipTo').value = nombreBusqueda;

        const lista = document.getElementById('listaShipTo');
        const filtroContainer = document.getElementById('filtroShipToContainer');
        const filtroInput = document.getElementById('filtroShipTo');

        lista.innerHTML =
            '<div class="text-center py-4"><span class="spinner-border spinner-border-sm" style="color: var(--text-muted);"></span> Buscando direcciones...</div>';

        fetch(`/api/autoservicio/buscar-shipto?nombre=${encodeURIComponent(nombreBusqueda)}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    lista.innerHTML =
                        '<div class="text-center py-4"><i class="bi bi-search" style="font-size: 2rem; color: var(--text-muted);"></i><p style="color: var(--text-muted);">No se encontraron direcciones con: <strong>' +
                        nombreBusqueda + '</strong></p></div>';
                    filtroContainer.style.display = 'none';
                    return;
                }

                filtroContainer.style.display = '';
                filtroInput.value = '';
                document.getElementById('contadorShipTo').textContent = data.length + ' resultados';

                let html =
                    '<table class="table table-sm mb-0"><thead><tr><th></th><th>SHIP_TO</th><th>Dirección</th></tr></thead><tbody>';
                data.forEach((d) => {
                    // ✅ Marcar como seleccionado si coincide con el SHIP_TO actual
                    const esActual = clienteDatosActuales.SHIP_TO && d.SHIP_TO === clienteDatosActuales
                        .SHIP_TO;
                    if (esActual) {
                        datosSeleccionados.ship_to = d.SHIP_TO;
                    }

                    html += `
                    <tr class="radio-seleccion ${esActual ? 'seleccionado' : ''}" style="cursor: pointer;">
                        <td><input type="radio" name="shipRadio" class="form-check-input" ${esActual ? 'checked' : ''}></td>
                        <td style="font-weight: 500;">${d.SHIP_TO}</td>
                        <td>${d.direccion || '-'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                lista.innerHTML = html;
                // Actualizar "Actualmente configurado" después de cargar
                setTimeout(() => actualizarConfiguradoPaso2(), 100);
            })
            .catch(() => {
                lista.innerHTML = '<div class="text-center py-4 text-danger">Error al cargar direcciones</div>';
            });
    }

    // ============================================================
    // PASO 3: BILL_TO
    // ============================================================
    function cargarPaso3(nombreBusqueda) {
        if (!nombreBusqueda) {
            nombreBusqueda = document.getElementById('buscarBillTo').value || clienteNombre;
        }
        document.getElementById('buscarBillTo').value = nombreBusqueda;

        const lista = document.getElementById('listaBillTo');
        const filtroContainer = document.getElementById('filtroBillToContainer');
        const filtroInput = document.getElementById('filtroBillTo');

        lista.innerHTML =
            '<div class="text-center py-4"><span class="spinner-border spinner-border-sm" style="color: var(--text-muted);"></span> Buscando direcciones...</div>';

        fetch(`/api/autoservicio/buscar-billto?nombre=${encodeURIComponent(nombreBusqueda)}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    lista.innerHTML =
                        '<div class="text-center py-4"><i class="bi bi-search" style="font-size: 2rem; color: var(--text-muted);"></i><p style="color: var(--text-muted);">No se encontraron direcciones con: <strong>' +
                        nombreBusqueda + '</strong></p></div>';
                    filtroContainer.style.display = 'none';
                    return;
                }

                filtroContainer.style.display = '';
                filtroInput.value = '';
                document.getElementById('contadorBillTo').textContent = data.length + ' resultados';

                let html =
                    '<table class="table table-sm mb-0"><thead><tr><th></th><th>BILL_TO</th><th>Dirección</th></tr></thead><tbody>';
                data.forEach((d) => {
                    // ✅ Marcar como seleccionado si coincide con el BILL_TO actual
                    const esActual = clienteDatosActuales.BILL_TO && d.BILL_TO === clienteDatosActuales
                        .BILL_TO;
                    if (esActual) {
                        datosSeleccionados.bill_to = d.BILL_TO;
                    }

                    html += `
                    <tr class="radio-seleccion ${esActual ? 'seleccionado' : ''}" style="cursor: pointer;">
                        <td><input type="radio" name="billRadio" class="form-check-input" ${esActual ? 'checked' : ''}></td>
                        <td style="font-weight: 500;">${d.BILL_TO}</td>
                        <td>${d.direccion || '-'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                lista.innerHTML = html;
                // Actualizar "Actualmente configurado" después de cargar
                setTimeout(() => actualizarConfiguradoPaso3(), 100);
            })
            .catch(() => {
                lista.innerHTML = '<div class="text-center py-4 text-danger">Error al cargar direcciones</div>';
            });
    }

    // ============================================================
    // PASO 4: PRECIOS
    // ============================================================
    function cargarPaso4(nombreBusqueda) {
        if (!nombreBusqueda) {
            nombreBusqueda = document.getElementById('buscarPrecios').value || clienteNombre;
        }
        document.getElementById('buscarPrecios').value = nombreBusqueda;

        const lista = document.getElementById('listaPrecios');
        lista.innerHTML =
            '<div class="text-center py-4"><span class="spinner-border spinner-border-sm" style="color: var(--text-muted);"></span> Buscando listas de precio...</div>';

        fetch(`/api/autoservicio/buscar-precios?nombre=${encodeURIComponent(nombreBusqueda)}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    lista.innerHTML =
                        '<div class="text-center py-4"><i class="bi bi-search" style="font-size: 2rem; color: var(--text-muted);"></i><p style="color: var(--text-muted);">No se encontraron listas con: <strong>' +
                        nombreBusqueda + '</strong></p></div>';
                    return;
                }

                let html =
                    '<table class="table table-sm mb-0"><thead><tr><th></th><th>ID</th><th>Nombre</th><th>Descripción</th></tr></thead><tbody>';
                data.forEach((p) => {
                    // ✅ Marcar como seleccionado si coincide con el PRICE_LIST_ID actual
                    const esActual = clienteDatosActuales.PRICE_LIST_ID && p.PRICE_LIST_ID ===
                        clienteDatosActuales.PRICE_LIST_ID;
                    if (esActual) {
                        datosSeleccionados.price_list_id = p.PRICE_LIST_ID;
                        datosSeleccionados.price_list_name = p.NAME || '';
                    }

                    html += `
                    <tr class="radio-seleccion ${esActual ? 'seleccionado' : ''}" style="cursor: pointer;">
                        <td><input type="radio" name="precioRadio" class="form-check-input" ${esActual ? 'checked' : ''}></td>
                        <td style="font-weight: 500;">${p.PRICE_LIST_ID}</td>
                        <td>${p.NAME || '-'}</td>
                        <td>${p.DESCRIPTION || '-'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                lista.innerHTML = html;
                // Actualizar "Actualmente configurado" después de cargar
                setTimeout(() => actualizarConfiguradoPaso4(), 100);

            })
            .catch(() => {
                lista.innerHTML = '<div class="text-center py-4 text-danger">Error al cargar listas</div>';
            });
    }

    // ============================================================
    // PASO 5: TIPO DE ORDEN
    // ============================================================
    function cargarPaso5() {
        const lista = document.getElementById('listaTipoOrden');
        const filtroInput = document.getElementById('buscarTipoOrden');
        const contador = document.getElementById('contadorTipoOrden');

        lista.innerHTML =
            '<div class="text-center py-4"><span class="spinner-border spinner-border-sm" style="color: var(--text-muted);"></span> Cargando tipos de orden...</div>';

        fetch(`/api/autoservicio/buscar-tipo-orden`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    lista.innerHTML =
                        '<div class="text-center py-4"><i class="bi bi-search" style="font-size: 2rem; color: var(--text-muted);"></i><p style="color: var(--text-muted);">No se encontraron tipos de orden</p></div>';
                    contador.textContent = '';
                    return;
                }

                // Limpiar filtro
                filtroInput.value = '';
                contador.textContent = data.length + ' resultados';

                let html =
                    '<table class="table table-sm mb-0"><thead><tr><th></th><th>Código</th><th>Descripción</th></tr></thead><tbody>';
                data.forEach((t) => {
                    const esActual = clienteDatosActuales.ORDER_TYPE && t.LOOKUP_CODE ===
                        clienteDatosActuales.ORDER_TYPE;
                    if (esActual) {
                        datosSeleccionados.order_type = t.LOOKUP_CODE;
                        datosSeleccionados.order_type_name = t.MEANING || '';
                    }

                    html += `
                    <tr class="radio-seleccion ${esActual ? 'seleccionado' : ''}" style="cursor: pointer;">
                        <td><input type="radio" name="tipoOrdenRadio" class="form-check-input" ${esActual ? 'checked' : ''}></td>
                        <td style="font-weight: 500;">${t.LOOKUP_CODE}</td>
                        <td>${t.MEANING || '-'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                lista.innerHTML = html;
                // Actualizar "Actualmente configurado" después de cargar
                setTimeout(() => actualizarConfiguradoPaso5(), 100);
            })
            .catch(() => {
                lista.innerHTML = '<div class="text-center py-4 text-danger">Error al cargar tipos de orden</div>';
            });
    }

    // ============================================================
    // PASO 6: RESUMEN
    // ============================================================
    function cargarResumen() {
        // Cliente
        document.getElementById('resumenIdCliente').textContent = datosSeleccionados.id_cliente || clienteDatosActuales
            .ID_CLIENTE || '-';

        const nombreCliente = datosSeleccionados.nombre_cliente || clienteDatosActuales.NOMBRE_CLIENTE || '-';
        const nombreClienteElement = document.getElementById('resumenNombreCliente');
        nombreClienteElement.textContent = nombreCliente;
        nombreClienteElement.setAttribute('title', nombreCliente);

        const tipoCliente = datosSeleccionados.tipo_cliente || clienteDatosActuales.TIPO_CLIENTE || '-';
        document.getElementById('resumenTipoCliente').textContent = tipoCliente;

        document.getElementById('resumenTerminos').textContent = datosSeleccionados.terminos || clienteDatosActuales
            .TERMINOS || '-';

        // Direcciones
        document.getElementById('resumenShipTo').textContent = datosSeleccionados.ship_to || clienteDatosActuales
            .SHIP_TO || '-';
        document.getElementById('resumenBillTo').textContent = datosSeleccionados.bill_to || clienteDatosActuales
            .BILL_TO || '-';

        // Precios
        document.getElementById('resumenPriceListId').textContent = datosSeleccionados.price_list_id ||
            clienteDatosActuales.PRICE_LIST_ID || '-';
        document.getElementById('resumenPriceListName').textContent = datosSeleccionados.price_list_name || '-';

        // Tipo de Orden
        const orderType = datosSeleccionados.order_type || clienteDatosActuales.ORDER_TYPE || '-';
        const orderTypeName = datosSeleccionados.order_type_name || '';
        document.getElementById('resumenOrderTypeCodigo').textContent = orderType;

        const descElement = document.getElementById('resumenOrderTypeDescripcion');
        descElement.textContent = orderTypeName || '-';
        descElement.setAttribute('title', orderTypeName || '');
    }

    // ============================================================
    // SELECCIÓN POR DELEGACIÓN DE EVENTOS
    // ============================================================

    // Paso 1 - Seleccionar cliente
    document.getElementById('listaClientes').addEventListener('click', function(e) {
        const fila = e.target.closest('.radio-seleccion');
        if (!fila) return;

        const celdas = fila.querySelectorAll('td');
        datosSeleccionados.id_cliente = celdas[1].textContent.trim();
        datosSeleccionados.nombre_cliente = celdas[2].textContent.trim();
        datosSeleccionados.tipo_cliente = celdas[3].textContent.trim();
        datosSeleccionados.terminos = celdas[4].textContent.trim();

        document.querySelectorAll('#listaClientes .radio-seleccion').forEach(el => el.classList.remove(
            'seleccionado'));
        fila.classList.add('seleccionado');
        fila.querySelector('input[type="radio"]').checked = true;
    });

    // Paso 2 - Seleccionar SHIP_TO
    document.getElementById('listaShipTo').addEventListener('click', function(e) {
        const fila = e.target.closest('.radio-seleccion');
        if (!fila) return;

        const celdas = fila.querySelectorAll('td');
        datosSeleccionados.ship_to = celdas[1].textContent.trim();

        document.querySelectorAll('#listaShipTo .radio-seleccion').forEach(el => el.classList.remove(
            'seleccionado'));
        fila.classList.add('seleccionado');
        fila.querySelector('input[type="radio"]').checked = true;
    });

    // Paso 3 - Seleccionar BILL_TO
    document.getElementById('listaBillTo').addEventListener('click', function(e) {
        const fila = e.target.closest('.radio-seleccion');
        if (!fila) return;

        const celdas = fila.querySelectorAll('td');
        datosSeleccionados.bill_to = celdas[1].textContent.trim();

        document.querySelectorAll('#listaBillTo .radio-seleccion').forEach(el => el.classList.remove(
            'seleccionado'));
        fila.classList.add('seleccionado');
        fila.querySelector('input[type="radio"]').checked = true;
    });

    // Paso 4 - Seleccionar Precio
    document.getElementById('listaPrecios').addEventListener('click', function(e) {
        const fila = e.target.closest('.radio-seleccion');
        if (!fila) return;

        const celdas = fila.querySelectorAll('td');
        datosSeleccionados.price_list_id = celdas[1].textContent.trim();
        datosSeleccionados.price_list_name = celdas[2].textContent.trim();

        document.querySelectorAll('#listaPrecios .radio-seleccion').forEach(el => el.classList.remove(
            'seleccionado'));
        fila.classList.add('seleccionado');
        fila.querySelector('input[type="radio"]').checked = true;
    });

    // Paso 5 - Seleccionar Tipo de Orden
    document.getElementById('listaTipoOrden').addEventListener('click', function(e) {
        const fila = e.target.closest('.radio-seleccion');
        if (!fila) return;

        const celdas = fila.querySelectorAll('td');
        datosSeleccionados.order_type = celdas[1].textContent.trim();
        datosSeleccionados.order_type_name = celdas[2].textContent.trim();

        document.querySelectorAll('#listaTipoOrden .radio-seleccion').forEach(el => el.classList.remove(
            'seleccionado'));
        fila.classList.add('seleccionado');
        fila.querySelector('input[type="radio"]').checked = true;
    });

    // ============================================================
    // ACTUALIZAR "ACTUALMENTE CONFIGURADO" DESPUÉS DE CARGAR
    // ============================================================
    function actualizarConfiguradoPaso2() {
        if (clienteDatosActuales.SHIP_TO) {
            document.getElementById('shipToActual').style.display = 'block';
            const filaSeleccionada = document.querySelector('#listaShipTo .radio-seleccion.seleccionado');
            const direccion = filaSeleccionada ? filaSeleccionada.querySelectorAll('td')[2]?.textContent.trim() : '';
            document.getElementById('shipToActualTexto').innerHTML =
                `<strong>${clienteDatosActuales.SHIP_TO}</strong>` +
                (direccion ? `<br><span style="font-size: 0.72rem; color: var(--text-secondary);">${direccion}</span>` :
                    '');
        }
    }

    function actualizarConfiguradoPaso3() {
        if (clienteDatosActuales.BILL_TO) {
            document.getElementById('billToActual').style.display = 'block';
            const filaSeleccionada = document.querySelector('#listaBillTo .radio-seleccion.seleccionado');
            const direccion = filaSeleccionada ? filaSeleccionada.querySelectorAll('td')[2]?.textContent.trim() : '';
            document.getElementById('billToActualTexto').innerHTML =
                `<strong>${clienteDatosActuales.BILL_TO}</strong>` +
                (direccion ? `<br><span style="font-size: 0.72rem; color: var(--text-secondary);">${direccion}</span>` :
                    '');
        }
    }

    function actualizarConfiguradoPaso4() {
        if (clienteDatosActuales.PRICE_LIST_ID) {
            document.getElementById('precioActual').style.display = 'block';
            const filaSeleccionada = document.querySelector('#listaPrecios .radio-seleccion.seleccionado');
            const nombre = filaSeleccionada ? filaSeleccionada.querySelectorAll('td')[2]?.textContent.trim() : '';
            document.getElementById('precioActualTexto').innerHTML =
                `<strong>${nombre || clienteDatosActuales.PRICE_LIST_ID}</strong><br><span style="font-size: 0.72rem; color: var(--text-secondary);">ID: ${clienteDatosActuales.PRICE_LIST_ID}</span>`;
        }
    }

    function actualizarConfiguradoPaso5() {
        if (clienteDatosActuales.ORDER_TYPE) {
            document.getElementById('tipoOrdenActual').style.display = 'block';
            const filaSeleccionada = document.querySelector('#listaTipoOrden .radio-seleccion.seleccionado');
            const descripcion = filaSeleccionada ? filaSeleccionada.querySelectorAll('td')[2]?.textContent.trim() : '';
            document.getElementById('tipoOrdenActualTexto').innerHTML =
                `<strong>${descripcion || clienteDatosActuales.ORDER_TYPE}</strong>` +
                (descripcion ?
                    `<br><span style="font-size: 0.72rem; color: var(--text-secondary);">Código: ${clienteDatosActuales.ORDER_TYPE}</span>` :
                    '');
        }
    }

    // ============================================================
    // NAVEGACIÓN
    // ============================================================

    document.getElementById('btnSiguiente').addEventListener('click', function() {
        // if (pasoActual === 1 && !datosSeleccionados.id_cliente && !clienteDatosActuales.ID_CLIENTE) {
        //     alert('Selecciona un cliente o busca uno nuevo');
        //     return;
        // }
        // if (pasoActual === 2 && !datosSeleccionados.ship_to && !clienteDatosActuales.SHIP_TO) {
        //     alert('Selecciona una dirección de envío');
        //     return;
        // }
        // if (pasoActual === 3 && !datosSeleccionados.bill_to && !clienteDatosActuales.BILL_TO) {
        //     alert('Selecciona una dirección de facturación');
        //     return;
        // }
        // if (pasoActual === 4 && !datosSeleccionados.price_list_id && !clienteDatosActuales.PRICE_LIST_ID) {
        //     alert('Selecciona una lista de precios');
        //     return;
        // }
        // Validar el paso actual
        if (!validarPaso(pasoActual)) {
            return; // No avanzar si la validación falla
        }


        const siguiente = pasoActual + 1;
        irPaso(siguiente);
        if (siguiente === 2) cargarPaso2();
        if (siguiente === 3) cargarPaso3();
        if (siguiente === 4) cargarPaso4();
        if (siguiente === 5) cargarPaso5();
        if (siguiente === 6) cargarResumen();
    });

    document.getElementById('btnAnterior').addEventListener('click', function() {
        irPaso(pasoActual - 1);
    });

    document.getElementById('btnGuardarWizard').addEventListener('click', function() {
        const form = document.getElementById('formWizard');
        const campos = ['id_cliente', 'nombre_cliente', 'tipo_cliente', 'terminos', 'ship_to', 'bill_to',
            'price_list_id', 'order_type'
        ];

        form.querySelectorAll('input[type="hidden"].dinamico').forEach(el => el.remove());

        campos.forEach(campo => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = campo;
            // Si no se seleccionó nada nuevo, usar el valor actual
            input.value = datosSeleccionados[campo] || clienteDatosActuales[campo] || '';
            input.classList.add('dinamico');
            form.appendChild(input);
        });

        form.submit();
    });

    // Limpiar al cerrar
    document.getElementById('ModalWizard').addEventListener('hidden.bs.modal', function() {
        pasoActual = 1;
        clienteNombre = '';
        clienteDatosActuales = {};
        datosSeleccionados = {
            id_cliente: '',
            nombre_cliente: '',
            tipo_cliente: '',
            terminos: '',
            ship_to: '',
            bill_to: '',
            price_list_id: '',
            price_list_name: '',
            order_type: '',
            order_type_name: ''
        };
        irPaso(1);

        // Limpiar los inputs de búsqueda
        document.getElementById('buscarCliente').value = '';
        document.getElementById('buscarShipTo').value = '';
        document.getElementById('buscarBillTo').value = '';
        document.getElementById('buscarPrecios').value = '';
        document.getElementById('listaTipoOrden').value = '';

        // Ocultar valores actuales
        document.getElementById('clienteActual').style.display = 'none';
        document.getElementById('shipToActual').style.display = 'none';
        document.getElementById('billToActual').style.display = 'none';
        document.getElementById('precioActual').style.display = 'none';
        document.getElementById('tipoOrdenActual').style.display = 'none';

        // Limpiar listas
        document.getElementById('listaClientes').innerHTML = '';
        document.getElementById('listaShipTo').innerHTML = '';
        document.getElementById('listaBillTo').innerHTML = '';
        document.getElementById('listaPrecios').innerHTML = '';
        document.getElementById('listaTipoOrden').innerHTML = '';
    });

    // ============================================================
    // MODAL VER CLIENTE
    // ============================================================
    function abrirVerCliente(cliente) {
        document.getElementById('verClienteCodigo').textContent = cliente.Cliente + ' - ' + cliente.Nombre;
        document.getElementById('verCliente').textContent = cliente.Cliente;
        document.getElementById('verNombre').textContent = cliente.Nombre;
        document.getElementById('verDireccion').textContent = cliente.Direccion || 'No registrada';

        // ID Cliente
        const idCliente = document.getElementById('verIdCliente');
        if (cliente.ID_CLIENTE) {
            idCliente.textContent = cliente.ID_CLIENTE;
            // idCliente.className = 'tags-green';
        } else {
            idCliente.textContent = 'Sin configurar';
            // idCliente.className = 'tags-red';
        }

        document.getElementById('verNombreCliente').textContent = cliente.NOMBRE_CLIENTE || '-';

        // Tipo Cliente
        const tipoCliente = document.getElementById('verTipoCliente');
        if (cliente.TIPO_CLIENTE) {
            tipoCliente.textContent = cliente.TIPO_CLIENTE;
            tipoCliente.className = 'tags-blue';
        } else {
            tipoCliente.textContent = 'Sin tipo';
            tipoCliente.className = 'tags-yellow';
        }

        document.getElementById('verTerminos').textContent = cliente.TERMINOS || '-';
        document.getElementById('verShipTo').textContent = cliente.SHIP_TO || 'No configurado';
        document.getElementById('verBillTo').textContent = cliente.BILL_TO || 'No configurado';
        document.getElementById('verPriceList').textContent = cliente.PRICE_LIST_ID || 'No asignada';

        // Estados
        const estadosDiv = document.getElementById('verEstados');
        let estadosHTML = '';

        estadosHTML += cliente.ID_CLIENTE ?
            '<span class="tags-green"><i class="bi bi-check-circle me-1"></i>ID Cliente</span>' :
            '<span class="tags-red"><i class="bi bi-x-circle me-1"></i>ID Cliente</span>';

        estadosHTML += cliente.TIPO_CLIENTE ?
            '<span class="tags-green"><i class="bi bi-check-circle me-1"></i>Tipo Cliente</span>' :
            '<span class="tags-yellow"><i class="bi bi-exclamation-circle me-1"></i>Tipo Cliente</span>';

        estadosHTML += cliente.SHIP_TO ?
            '<span class="tags-green"><i class="bi bi-check-circle me-1"></i>Dirección Envío</span>' :
            '<span class="tags-yellow"><i class="bi bi-exclamation-circle me-1"></i>Dirección Envío</span>';

        estadosHTML += cliente.BILL_TO ?
            '<span class="tags-green"><i class="bi bi-check-circle me-1"></i>Dirección Facturación</span>' :
            '<span class="tags-yellow"><i class="bi bi-exclamation-circle me-1"></i>Dirección Facturación</span>';

        estadosHTML += cliente.TERMINOS ?
            '<span class="tags-green"><i class="bi bi-check-circle me-1"></i>Términos</span>' :
            '<span class="tags-yellow"><i class="bi bi-exclamation-circle me-1"></i>Términos</span>';

        estadosHTML += cliente.PRICE_LIST_ID ?
            '<span class="tags-green"><i class="bi bi-check-circle me-1"></i>Lista Precio</span>' :
            '<span class="tags-red"><i class="bi bi-x-circle me-1"></i>Lista Precio</span>';

        estadosDiv.innerHTML = estadosHTML;

        const modal = new bootstrap.Modal(document.getElementById('ModalVerCliente'));
        modal.show();
    }

    // ============================================================
    // FILTRO EN FRONTEND (GENÉRICO)
    // ============================================================
    function filtrarLista(idLista, idFiltro, idContador) {
        const filtro = document.getElementById(idFiltro).value.toLowerCase().trim();
        const tabla = document.getElementById(idLista);
        const filas = tabla.querySelectorAll('tbody tr');
        let visibles = 0;
        const total = filas.length;

        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            if (filtro === '' || textoFila.includes(filtro)) {
                fila.style.display = '';
                visibles++;
            } else {
                fila.style.display = 'none';
            }
        });

        const contador = document.getElementById(idContador);
        if (contador) {
            if (filtro !== '') {
                contador.textContent = `${total} resultados · Mostrando ${visibles}`;
            } else {
                contador.textContent = `${total} resultados`;
            }
        }
    }
</script>
