<x-page-container title="Consulta de Órdenes Oracle">
    <!-- Contenedor de Toast Alerts -->
    <div
        class="toast-alert-container"
        id="toastContainer"
    ></div>
    <x-card-gradient-header
        icon="clipboard-check"
        title="Órdenes Oracle"
        subtitle="Consulta y seguimiento de órdenes de venta"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Formulario de búsqueda -->
        <x-form.form id="form-busqueda">
            <x-form.group>
                <x-form.text
                    name="orden"
                    label="Número de orden"
                    icon="receipt"
                    placeholder="Ingrese el número de orden..."
                    col="col-md-4"
                    :autofocus="true"
                />
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <button
                    type="button"
                    id="btn-buscar"
                    class="btn-gradient flex-grow-1"
                    onclick="buscarOrden()"
                >
                    <span id="btn-texto">
                        <i class="bi bi-search"></i> Buscar orden
                    </span>
                    <span
                        id="btn-cargando"
                        class="d-none"
                    >
                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Buscando...
                    </span>
                </button>
                <button
                    type="button"
                    class="btn-light-ghost"
                    onclick="limpiarBusqueda()"
                >
                    <i class="bi bi-x-circle"></i> Limpiar
                </button>
            </div>
        </x-form.form>

        <!-- Resultados -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Datos de la orden
                    </h5>
                    <p
                        class="section-content-subtitle"
                        id="subtitulo-resultados"
                    >Información detallada de la orden consultada</p>
                </div>
            </div>

            <!-- Contenedor de resultados -->
            <div
                id="resultados-contenedor"
                class="d-none"
            >
                <!-- Aquí se carga dinámicamente -->
            </div>

            <!-- Estado vacío inicial -->
            <div
                id="estado-inicial"
                class="py-5 text-center"
            >
                <div
                    class="d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 64px; height: 64px; background-color: #f1f5f9; border-radius: 50%;"
                >
                    <i
                        class="bi bi-inbox"
                        style="font-size: 28px; color: #94a3b8;"
                    ></i>
                </div>
                <h6
                    class="fw-semibold mb-1"
                    style="color: #475569;"
                >Consulta de órdenes</h6>
                <p
                    class="text-muted mb-0"
                    style="font-size: 0.85rem;"
                >Ingrese un número de orden y presione buscar para ver los resultados</p>
            </div>
        </div>
    </x-card-gradient-header>

    <style>
        /* Toast Alert - Posición fija arriba a la derecha */
        .toast-alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 420px;
            width: 100%;
        }

        .toast-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.06);
            animation: slideInRight 0.3s ease-out;
            border: 1px solid #e2e8f0;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-alert.removing {
            animation: slideOutRight 0.3s ease-in forwards;
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .toast-alert .toast-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .toast-alert.toast-danger .toast-icon {
            background: #fef2f2;
            color: #dc2626;
        }

        .toast-alert.toast-warning .toast-icon {
            background: #fffbeb;
            color: #f59e0b;
        }

        .toast-alert.toast-success .toast-icon {
            background: #f0fdf4;
            color: #10b981;
        }

        .toast-alert .toast-content {
            flex: 1;
            min-width: 0;
        }

        .toast-alert .toast-content strong {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .toast-alert .toast-content span {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.4;
        }

        .toast-alert .toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.15s;
            font-size: 1rem;
            line-height: 1;
        }

        .toast-alert .toast-close:hover {
            background: #f1f5f9;
            color: #475569;
        }
    </style>

    <script>
        let datoGlobal = null;

        // Función para establecer devolución total
        function devolucionTotal() {
            const inputs = document.querySelectorAll('.input-devolucion');

            inputs.forEach(input => {
                const maxValue = parseFloat(input.dataset.max);
                input.value = maxValue.toFixed(3);

                // Disparar evento change para actualizar totales
                const event = new Event('change', {
                    bubbles: true
                });
                input.dispatchEvent(event);

                // Efecto visual de actualización
                input.style.backgroundColor = '#fef2f2';
                input.style.borderColor = '#fecaca';
                input.style.transition = 'all 0.3s ease';

                setTimeout(() => {
                    input.style.backgroundColor = '';
                    input.style.borderColor = '#e2e8f0';
                }, 500);
            });

            // Actualizar estado del botón de procesar
            actualizarTotalDevolucion();

            // Mostrar notificación
            const totalProductos = inputs.length;
            if (totalProductos > 0) {
                // Puedes usar alert simple o tu sistema de notificaciones
                console.log(`Devolución total aplicada a ${totalProductos} productos`);
            }
        }
        // Abrir modal de devolución
        function abrirModalDevolucion() {
            const modal = new bootstrap.Modal(document.getElementById('modalDevolucion'));
            modal.show();
            actualizarTotalDevolucion();
        }

        function copiarFolio() {
            const folioInput = document.getElementById('folio-devolucion');
            folioInput.select();
            folioInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(folioInput.value).then(() => {
                const btn = document.querySelector('#folio-devolucion + button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg"></i> Copiado';
                btn.style.background = '#f0fdf4';
                btn.style.color = '#059669';
                btn.style.borderColor = '#a7f3d0';

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.background = '#eff6ff';
                    btn.style.color = '#2563eb';
                    btn.style.borderColor = '#bfdbfe';
                }, 2000);
            }).catch(() => {
                // Fallback para navegadores que no soportan clipboard
                alert('Folio: ' + folioInput.value);
            });
        }

        // Eventos para inputs de cantidad
        $(document).on('input', '.input-devolucion', function() {
            const max = parseFloat($(this).data('max'));
            let val = parseFloat($(this).val()) || 0;

            if (val > max) $(this).val(max.toFixed(2));
            if (val < 0) $(this).val('0.00');

            actualizarTotalDevolucion();
        });

        // Botón "Max"
        $(document).on('click', '.btn-max-devolucion', function() {
            const $input = $(this).closest('tr').find('.input-devolucion');
            const max = $input.data('max');
            $input.val(max).trigger('input');
        });

        // Actualizar total del modal
        function actualizarTotalDevolucion() {
            let totalImporte = 0;
            let totalKilos = 0;
            let lineasConCantidad = 0;

            $('.input-devolucion').each(function() {
                const cantidad = parseFloat($(this).val()) || 0;
                const precio = parseFloat($(this).data('price')) || 0;
                if (cantidad > 0) {
                    totalImporte += cantidad * precio;
                    totalKilos += cantidad;
                    lineasConCantidad++;
                }
            });

            $('#devolucion-total-modal').text(
                '$' + totalImporte.toLocaleString('en-US', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                })
            );

            $('#devolucion-total-kilos').text(
                totalKilos.toLocaleString('en-US', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                }) + ' kg'
            );

            $('#btn-procesar-devolucion').prop('disabled', lineasConCantidad === 0);
        }

        // Procesar devolución
        function procesarDevolucion() {
            if (!datoGlobal) {
                console.error('No hay datos de orden disponibles');
                return;
            }

            const lineas = [];
            let debugInfo = []; // Para debug

            // Recorrer inputs para obtener líneas con cantidad > 0
            $('.input-devolucion').each(function() {
                const val = $(this).val();
                const cantidad = parseFloat(val) || 0;

                debugInfo.push({
                    lineId: $(this).data('line'),
                    val: val,
                    cantidad: cantidad
                });

                if (cantidad > 0) {
                    const lineId = $(this).data('line');
                    const lineaOriginal = datoGlobal.lines.find(line => line.fulfillLineId == lineId);

                    if (lineaOriginal) {
                        lineas.push({
                            fulfillLineId: lineaOriginal.fulfillLineId,
                            lineNumber: lineaOriginal.lineNumber,
                            productId: lineaOriginal.productId,
                            productNumber: lineaOriginal.productNumber,
                            productDescription: lineaOriginal.productDescription,
                            orderedUOMCode: lineaOriginal.orderedUOMCode,
                            orderedUOM: lineaOriginal.orderedUOM,
                            unitSellingPrice: lineaOriginal.unitSellingPrice,
                            extendedAmount: lineaOriginal.extendedAmount,
                            status: lineaOriginal.status,
                            fulfillmentSplitReferenceId: lineaOriginal.fulfillmentSplitReferenceId,
                            cantidad: cantidad
                        });
                    }
                }
            });

            if (lineas.length === 0) {
                alert('Seleccione al menos un producto con cantidad a devolver');
                return;
            }

            // Armar objeto de devolución
            const devolucion = {
                header: {
                    orderNumber: datoGlobal.orderNumber,
                    sourceTransactionNumber: datoGlobal.sourceTransactionNumber,
                    businessUnitId: datoGlobal.businessUnitId,
                    businessUnitName: datoGlobal.businessUnitName,
                    creationDate: datoGlobal.creationDate,
                    buyingPartyId: datoGlobal.buyingPartyId,
                    buyingPartyNumber: datoGlobal.buyingPartyNumber,
                    buyingPartyName: datoGlobal.buyingPartyName,
                    transactionTypeCode: datoGlobal.transactionTypeCode,
                    transactionType: datoGlobal.transactionType,
                    requestedFulfillmentOrganizationId: datoGlobal.lines?.[0]?.requestedFulfillmentOrganizationId,
                    requestedFulfillmentOrganizationCode: datoGlobal.lines?.[0]?.requestedFulfillmentOrganizationCode,
                    requestedFulfillmentOrganizationName: datoGlobal.lines?.[0]?.requestedFulfillmentOrganizationName,
                    transactionalCurrencyCode: datoGlobal.transactionalCurrencyCode,
                    transactionalCurrencyName: datoGlobal.transactionalCurrencyName,
                    shipToCustomerId: datoGlobal.shipToCustomer?.[0]?.siteId || '',
                    billToCustomerAccountId: datoGlobal.billToCustomer?.[0]?.customerAccountId || '',
                    billToSiteUseId: datoGlobal.billToCustomer?.[0]?.siteUseId || '',
                },
                lineas: lineas
            };

            console.log('Devolución a enviar:', devolucion);

            $.ajax({
                url: '/api/devoluciones',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(devolucion),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);

                    // Cerrar modal de devolución
                    bootstrap.Modal.getInstance(document.getElementById('modalDevolucion')).hide();

                    // Crear modal de éxito
                    const modalHtml = `
                            <div class="modal fade" id="modalExitoDevolucion" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                                        <div class="text-center p-4">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width: 64px; height: 64px; background: #f0fdf4;">
                                                <i class="bi bi-check-circle" style="font-size: 2rem; color: #10b981;"></i>
                                            </div>
                                            <h5 class="fw-bold mb-2" style="color: #0f172a;">¡Devolución creada!</h5>
                                            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 16px;">La devolución se ha creado correctamente</p>

                                            <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                                                <span style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;">Folio</span>
                                                <input type="text"
                                                    id="folio-devolucion"
                                                    value="${response.folio}"
                                                    readonly
                                                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-weight: 700; color: #0f172a; font-size: 1rem; text-align: center; width: 180px;"
                                                    onclick="this.select()">
                                                <button type="button"
                                                        onclick="copiarFolio()"
                                                        class="btn btn-sm d-flex align-items-center gap-1"
                                                        style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 8px; padding: 6px 12px; font-weight: 500; font-size: 0.8rem;">
                                                    <i class="bi bi-clipboard"></i> Copiar
                                                </button>
                                            </div>

                                            <button type="button"
                                                    class="btn w-100"
                                                    data-bs-dismiss="modal"
                                                    style="background: #0f172a; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;">
                                                Aceptar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                    // Remover modal anterior si existe
                    $('#modalExitoDevolucion').remove();

                    // Agregar y mostrar
                    $('body').append(modalHtml);
                    const modalExito = new bootstrap.Modal(document.getElementById('modalExitoDevolucion'));
                    modalExito.show();

                    // Remover del DOM al cerrar
                    document.getElementById('modalExitoDevolucion').addEventListener('hidden.bs.modal',
                        function() {
                            this.remove();
                        });
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Error al crear la devolución';
                    alert(error);
                }
            });
        }

        function buscarOrden() {
            const orden = $('#orden').val().trim();
            const $btnTexto = $('#btn-texto');
            const $btnCargando = $('#btn-cargando');
            const $resultadosContenedor = $('#resultados-contenedor');
            const $estadoInicial = $('#estado-inicial');
            const $btnBuscar = $('#btn-buscar');
            const $inputOrden = $('#orden');

            if (!orden) {
                mostrarToast('warning', 'Campo requerido', 'El número de orden es requerido');
                $inputOrden.addClass('is-invalid');
                $inputOrden.focus();
                return;
            }
            $inputOrden.removeClass('is-invalid');
            $resultadosContenedor.addClass('d-none').empty();

            $btnTexto.addClass('d-none');
            $btnCargando.removeClass('d-none');
            $btnBuscar.prop('disabled', true);

            // Determinar URL según prefijo de la orden
            let url;
            const ordenUpper = orden.toUpperCase();

            if (ordenUpper.startsWith('DEV')) {
                url = 'https://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/GetDevolucionOracle';
            } else {
                // Si no coincide con ningún prefijo conocido, usar GetSalesOracle por defecto
                url = 'https://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/GetSalesOracle';
            }

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    Orden: ordenUpper
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    // if (!response.ok || !response.dato) {
                    //     $errorTexto.text(response.message || 'No se encontraron resultados para la orden: ' +
                    //         orden);
                    //     $errorMensaje.removeClass('d-none');
                    //     return;
                    // }
                    if (!response.dato) {
                        mostrarToast('danger', 'Sin resultados', response.message ||
                            'No se encontraron resultados para la orden: ' + orden);
                        return;
                    }

                    $estadoInicial.addClass('d-none');
                    mostrarResultados(response.dato, orden);
                    datoGlobal = response.dato;
                },
                error: function(xhr, status, error) {
                    let titulo = 'Error';
                    let mensaje = 'Error al consultar el servicio';

                    if (xhr.status === 404) {
                        titulo = 'No encontrado';
                        mensaje = 'No se encontraron resultados para la orden: ' + orden;
                    } else if (xhr.status === 500) {
                        titulo = 'Error del servidor';
                        mensaje = 'Error interno del servidor. Intente nuevamente.';
                    } else if (xhr.status === 0) {
                        titulo = 'Sin conexión';
                        mensaje = 'No se pudo conectar con el servidor. Verifique su conexión.';
                    } else if (status === 'timeout') {
                        titulo = 'Tiempo agotado';
                        mensaje = 'La solicitud tardó demasiado. Intente nuevamente.';
                    }

                    mostrarToast('danger', titulo, mensaje);
                },
                complete: function() {
                    $btnTexto.removeClass('d-none');
                    $btnCargando.addClass('d-none');
                    $btnBuscar.prop('disabled', false);
                },
                timeout: 30000
            });
        }

        function mostrarResultados(dato, orden) {
            const $resultadosContenedor = $('#resultados-contenedor');
            const $subtituloResultados = $('#subtitulo-resultados');

            const esDevolucion = orden.toUpperCase().startsWith('DEV');

            $subtituloResultados.html('Datos obtenidos para la orden <strong>#' + orden + '</strong>');

            // Formatear fecha
            const fecha = new Date(dato.creationDate).toLocaleDateString('es-MX', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Calcular total de la orden
            const total = dato.lines.reduce((sum, line) => sum + parseFloat(line.extendedAmount), 0).toFixed(2);
            const totalProductos = dato.lines.length;

            let html = `
                <!-- SECCIÓN 1: KPIs -->
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-xl-3 col-md-6 col-12">
                            <div
                                class="kpi-card"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #dbeafe 0%,
                                        #bfdbfe 100%
                                    );
                                    border-radius: 12px;
                                    padding: 20px;
                                    position: relative;
                                    overflow: hidden;
                                "
                            >
                                <div
                                    style="
                                        position: absolute;
                                        top: -20px;
                                        right: -20px;
                                        width: 100px;
                                        height: 100px;
                                        background: rgba(59, 130, 246, 0.08);
                                        border-radius: 50%;
                                    "
                                ></div>
                                <div style="position: relative; z-index: 1">
                                    <div
                                        class="d-flex justify-content-between align-items-start mb-2"
                                    >
                                        <span
                                            style="
                                                color: #1d4ed8;
                                                font-weight: 600;
                                                font-size: 0.8rem;
                                                text-transform: uppercase;
                                            "
                                            >Total Orden</span
                                        >
                                        <i
                                            class="bi bi-cash-stack"
                                            style="
                                                color: #3b82f6;
                                                font-size: 1.3rem;
                                                opacity: 0.7;
                                            "
                                        ></i>
                                    </div>
                                    <h3
                                        class="mb-1"
                                        style="
                                            font-weight: 700;
                                            color: #0f172a;
                                            font-size: 1.5rem;
                                        "
                                    >
                                        $${parseFloat(total).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                                    </h3>
                                    <span style="color: #94a3b8; font-size: 0.78rem">
                                        Moneda: ${dato.transactionalCurrencyCode}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 col-12">
                            <div
                                class="kpi-card"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #f0fdf4 0%,
                                        #dcfce7 100%
                                    );
                                    border-radius: 12px;
                                    padding: 20px;
                                    position: relative;
                                    overflow: hidden;
                                "
                            >
                                <div
                                    style="
                                        position: absolute;
                                        top: -20px;
                                        right: -20px;
                                        width: 100px;
                                        height: 100px;
                                        background: rgba(16, 185, 129, 0.08);
                                        border-radius: 50%;
                                    "
                                ></div>
                                <div style="position: relative; z-index: 1">
                                    <div
                                        class="d-flex justify-content-between align-items-start mb-2"
                                    >
                                        <span
                                            style="
                                                color: #059669;
                                                font-weight: 600;
                                                font-size: 0.8rem;
                                                text-transform: uppercase;
                                            "
                                            >Productos</span
                                        >
                                        <i
                                            class="bi bi-box-seam"
                                            style="
                                                color: #10b981;
                                                font-size: 1.3rem;
                                                opacity: 0.7;
                                            "
                                        ></i>
                                    </div>
                                    <h3
                                        class="mb-1"
                                        style="
                                            font-weight: 700;
                                            color: #0f172a;
                                            font-size: 1.5rem;
                                        "
                                    >
                                        ${totalProductos}
                                    </h3>
                                    <span style="color: #94a3b8; font-size: 0.78rem">
                                        Ítems en orden
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 col-12">
                            <div
                                class="kpi-card"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #fefce8 0%,
                                        #fef3c7 100%
                                    );
                                    border-radius: 12px;
                                    padding: 20px;
                                    position: relative;
                                    overflow: hidden;
                                "
                            >
                                <div
                                    style="
                                        position: absolute;
                                        top: -20px;
                                        right: -20px;
                                        width: 100px;
                                        height: 100px;
                                        background: rgba(245, 158, 11, 0.08);
                                        border-radius: 50%;
                                    "
                                ></div>
                                <div style="position: relative; z-index: 1">
                                    <div
                                        class="d-flex justify-content-between align-items-start mb-2"
                                    >
                                        <span
                                            style="
                                                color: #d97706;
                                                font-weight: 600;
                                                font-size: 0.8rem;
                                                text-transform: uppercase;
                                            "
                                            >Moneda</span
                                        >
                                        <i
                                            class="bi bi-currency-dollar"
                                            style="
                                                color: #f59e0b;
                                                font-size: 1.3rem;
                                                opacity: 0.7;
                                            "
                                        ></i>
                                    </div>
                                    <h3
                                        class="mb-1"
                                        style="
                                            font-weight: 700;
                                            color: #0f172a;
                                            font-size: 1.5rem;
                                        "
                                    >
                                        ${dato.transactionalCurrencyCode}
                                    </h3>
                                    <span style="color: #94a3b8; font-size: 0.78rem">
                                        ${dato.transactionalCurrencyName}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 col-12">
                            <div
                                class="kpi-card"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #f5f3ff 0%,
                                        #ede9fe 100%
                                    );
                                    border-radius: 12px;
                                    padding: 20px;
                                    position: relative;
                                    overflow: hidden;
                                "
                            >
                                <div
                                    style="
                                        position: absolute;
                                        top: -20px;
                                        right: -20px;
                                        width: 100px;
                                        height: 100px;
                                        background: rgba(139, 92, 246, 0.08);
                                        border-radius: 50%;
                                    "
                                ></div>
                                <div style="position: relative; z-index: 1">
                                    <div
                                        class="d-flex justify-content-between align-items-start mb-2"
                                    >
                                        <span
                                            style="
                                                color: #7c3aed;
                                                font-weight: 600;
                                                font-size: 0.8rem;
                                                text-transform: uppercase;
                                            "
                                            >Almacén</span
                                        >
                                        <i
                                            class="bi bi-building"
                                            style="
                                                color: #8b5cf6;
                                                font-size: 1.3rem;
                                                opacity: 0.7;
                                            "
                                        ></i>
                                    </div>
                                    <h3
                                        class="mb-1"
                                        style="
                                            font-weight: 700;
                                            color: #0f172a;
                                            font-size: 1.5rem;
                                        "
                                    >
                                        ${dato.lines[0].requestedFulfillmentOrganizationCode}
                                    </h3>
                                    <span style="color: #94a3b8; font-size: 0.78rem">
                                        ${dato.lines[0].requestedFulfillmentOrganizationName}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: Información General y Cliente -->
                <div class="px-4 pb-4">
                    <div class="row g-4">
                        <!-- Información General -->
                        <div class="col-lg-8">
                            <div class="rounded p-4 shadow-sm" style="background: white; border-radius: 12px;">
                                <h5 class="mb-4" style="font-weight: 600; color: #0f172a; font-size: 1rem;">
                                    <i class="bi bi-info-circle me-2" style="color: #3b82f6;"></i>Información General
                                </h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                                Número de Orden
                                            </label>
                                            <p style="font-weight: 500;">
                                                #${dato.orderNumber}
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                                Transacción Origen
                                            </label>
                                            <p style="color: #334155; font-size: 0.9rem; font-weight: 500;">
                                                ${dato.sourceTransactionNumber}
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                                Fecha de Creación
                                            </label>
                                            <p class="mb-0" style="color: #475569; font-size: 0.9rem;">
                                                <i class="bi bi-clock me-1" style="color: #94a3b8; font-size: 0.8rem;"></i>
                                                <span style="font-weight: 500">${fecha}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                                Unidad de Negocio
                                            </label>
                                            <p class="mb-0" style="color: #475569; font-size: 0.9rem;">
                                                <span style="font-weight: 500">${dato.businessUnitName}</span>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                                Tipo de Transacción
                                            </label>
                                            <p class="mb-0" style="color: #475569; font-size: 0.9rem;">
                                                <span style="font-weight: 500">${dato.transactionType}</span>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                                Almacén
                                            </label>
                                            <p class="mb-0" style="color: #334155; font-size: 0.9rem;">
                                                <span style="font-weight: 500">${dato.lines[0].requestedFulfillmentOrganizationName}</span>
                                            </p>
                                        </div>
                                        <div class="mb-0">
                                            <label style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                                                Moneda
                                            </label>
                                            <p class="mb-0">
                                                <span class="badge bg-light text-dark border" style="font-weight: 500; font-size: 0.8rem; padding: 4px 10px;">
                                                    ${dato.transactionalCurrencyCode}
                                                    <span style="color: #64748b; font-weight: 400;">- ${dato.transactionalCurrencyName}</span>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos del Cliente -->
                        <div class="col-lg-4">
                            <div class="rounded p-4 shadow-sm h-100" style="background: white; border-radius: 12px;">
                                <h5 class="mb-4" style="font-weight: 600; color: #0f172a; font-size: 1rem;">
                                    <i class="bi bi-person me-2" style="color: #8b5cf6;"></i>Datos del Cliente
                                </h5>
                                <div class="text-center mb-4">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 56px; height: 56px; background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                                        <span class="text-white fs-5 fw-bold">${dato.buyingPartyName.charAt(0)}</span>
                                    </div>
                                    <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 0.95rem;">
                                        ${dato.buyingPartyName}
                                    </h6>
                                    <p style="color: #64748b; font-size: 0.8rem; margin-bottom: 0;">
                                        <span style="color: #94a3b8;">Cliente</span>
                                        <span style="font-weight: 500" style="color: #475569;">#${dato.buyingPartyNumber}</span>
                                    </p>
                                </div>
                                <div class="border-top pt-3" style="border-color: #f1f5f9 !important;">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-fingerprint me-2" style="color: #cbd5e1; font-size: 0.85rem;"></i>
                                        <small style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">ID Cliente</small>
                                    </div>
                                    <div class="ms-4">
                                        <span class="small font-monospace fw-medium" style="color: #475569; font-size: 0.8rem; background: #f8fafc; padding: 3px 8px; border-radius: 4px;">
                                            ${dato.buyingPartyId}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: Direcciones -->
                <div class="px-4 pb-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="rounded p-4 shadow-sm h-100" style="background: white; border-radius: 12px;">
                                <h5 class="mb-4" style="font-weight: 600; color: #0f172a; font-size: 1rem;">
                                    <i class="bi bi-truck me-2" style="color: #f59e0b;"></i>Dirección de Envío
                                </h5>
                                ${dato.shipToCustomer && dato.shipToCustomer.length > 0 ? `
                                                                                                                                                                                                <div class="d-flex">
                                                                                                                                                                                                    <div class="me-3 mt-1">
                                                                                                                                                                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                                                                                                                                                            style="width: 36px; height: 36px; background: #fffbeb;">
                                                                                                                                                                                                            <i class="bi bi-geo-alt" style="color: #f59e0b; font-size: 1.1rem;"></i>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <div>
                                                                                                                                                                                                        <p class="mb-2" style="color: #475569; font-size: 0.9rem;">
                                                                                                                                                                                                            <span class="fw-semibold" style="color: #0f172a;">${dato.shipToCustomer[0].address1}</span>
                                                                                                                                                                                                            ${dato.shipToCustomer[0].address2 ? `<span style="color: #64748b;"> · ${dato.shipToCustomer[0].address2}</span>` : ''}
                                                                                                                                                                                                            <span style="color: #64748b;"> · No. ${dato.shipToCustomer[0].address3}</span>
                                                                                                                                                                                                            <span style="color: #64748b;"> · ${dato.shipToCustomer[0].city}, ${dato.shipToCustomer[0].state}</span>
                                                                                                                                                                                                        </p>
                                                                                                                                                                                                        <p class="mb-0">
                                                                                                                                                                                                            <span class="badge" style="background: #f8fafc; color: #475569; font-weight: 500; font-size: 0.8rem; padding: 5px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                                                                                                                                                                                                                <span style="color: #94a3b8; font-weight: 500;">C.P.</span> ${dato.shipToCustomer[0].postalCode}
                                                                                                                                                                                                            </span>
                                                                                                                                                                                                        </p>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            ` : `
                                                                                                                                                                                                <div class="text-center py-4">
                                                                                                                                                                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                                                                                                                                                                        style="width: 56px; height: 56px; background: #f8fafc;">
                                                                                                                                                                                                        <i class="bi bi-inbox" style="font-size: 1.5rem; color: #cbd5e1;"></i>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <p class="mb-0" style="color: #94a3b8; font-size: 0.85rem; font-weight: 500;">
                                                                                                                                                                                                        Sin dirección de envío
                                                                                                                                                                                                    </p>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            `}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded p-4 shadow-sm h-100" style="background: white; border-radius: 12px;">
                                <h5 class="mb-4" style="font-weight: 600; color: #0f172a; font-size: 1rem;">
                                    <i class="bi bi-receipt me-2" style="color: #10b981;"></i>Dirección de Facturación
                                </h5>
                                ${dato.billToCustomer && dato.billToCustomer.length > 0 ? `
                                                                                                                                                                                                <div class="d-flex">
                                                                                                                                                                                                    <div class="me-3 mt-1">
                                                                                                                                                                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                                                                                                                                                            style="width: 36px; height: 36px; background: #ecfdf5;">
                                                                                                                                                                                                            <i class="bi bi-geo-alt" style="color: #10b981; font-size: 1.1rem;"></i>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <div>
                                                                                                                                                                                                        <p class="mb-2" style="color: #475569; font-size: 0.9rem;">
                                                                                                                                                                                                            <span class="fw-semibold" style="color: #0f172a;">${dato.billToCustomer[0].address1}</span>
                                                                                                                                                                                                            ${dato.billToCustomer[0].address2 ? `<span style="color: #64748b;"> · ${dato.billToCustomer[0].address2}</span>` : ''}
                                                                                                                                                                                                            <span style="color: #64748b;"> · No. ${dato.billToCustomer[0].address3}</span>
                                                                                                                                                                                                            <span style="color: #64748b;"> · ${dato.billToCustomer[0].city}, ${dato.billToCustomer[0].state}</span>
                                                                                                                                                                                                        </p>
                                                                                                                                                                                                        <p class="mb-0">
                                                                                                                                                                                                            <span class="badge" style="background: #f8fafc; color: #475569; font-weight: 500; font-size: 0.8rem; padding: 5px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                                                                                                                                                                                                                <span style="color: #94a3b8; font-weight: 500;">C.P.</span> ${dato.billToCustomer[0].postalCode}
                                                                                                                                                                                                            </span>
                                                                                                                                                                                                        </p>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            ` : `
                                                                                                                                                                                                <div class="text-center py-4">
                                                                                                                                                                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                                                                                                                                                                        style="width: 56px; height: 56px; background: #f8fafc;">
                                                                                                                                                                                                        <i class="bi bi-inbox" style="font-size: 1.5rem; color: #cbd5e1;"></i>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                    <p class="mb-0" style="color: #94a3b8; font-size: 0.85rem; font-weight: 500;">
                                                                                                                                                                                                        Sin dirección de facturación
                                                                                                                                                                                                    </p>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            `}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: Tabla de Productos -->
                <div class="px-4 pb-4">
                    <div class="rounded p-4 shadow-sm" style="background: white; border-radius: 12px;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0" style="font-weight: 600; color: #0f172a; font-size: 1rem;">
                                <i class="bi bi-box-seam me-2" style="color: #ef4444;"></i>Productos
                                <span style="color: #64748b; font-weight: 500;">(${totalProductos})</span>
                            </h5>
                            <div class="d-flex align-items-center">
                                <span style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; margin-right: 8px;">Total</span>
                                <span class="fw-bold" style="color: #0f172a; font-size: 1.15rem; letter-spacing: -0.2px;">
                                    $${parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                </span>
                                ${!esDevolucion ? `
                                                    <button type="button"
                                                            class="btn btn-sm d-flex align-items-center gap-2 ms-4"
                                                            style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; padding: 8px 16px; font-weight: 600; font-size: 0.85rem;"
                                                            onclick="abrirModalDevolucion()"
                                                            onmouseover="this.style.background='#fee2e2'; this.style.transform='translateY(-1px)'"
                                                            onmouseout="this.style.background='#fef2f2'; this.style.transform='translateY(0)'">
                                                        <i class="bi bi-arrow-return-left"></i> Crear Devolución
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm d-flex align-items-center gap-2 ms-4"
                                                            style="background: #f0fdf4; color: #059669; border: 1px solid #bbf7d0; border-radius: 8px; padding: 8px 16px; font-weight: 600; font-size: 0.85rem;"
                                                            onclick="window.open('/Devoluciones?orden=${dato.sourceTransactionNumber}', '_blank')"
                                                            onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                                                            onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'">
                                                        <i class="bi bi-list-check"></i> Ver Devoluciones
                                                    </button>
                                                ` : ''}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-custom">
                                <thead style="position: sticky; top: 0; z-index: 2;">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 90px;">ID Producto</th>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th class="text-end">Cantidad</th>
                                        ${!esDevolucion ? `
                                                            <th class="text-end">Disp. Devolución</th>
                                                            <th class="text-end">Devuelto</th>
                                                        ` : ''}
                                        <th style="width: 70px;">UOM</th>
                                        <th class="text-end">Precio Unit.</th>
                                        <th class="text-end">Importe</th>
                                        <th class="text-center" style="width: 100px;">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${dato.lines.map(line => {
                                        const cantidad = parseFloat(line.orderedQuantity);
                                        const dispDevolucion = parseFloat(line.orderedReturn);
                                        const diferencia = cantidad - dispDevolucion;
                                        const tieneDiferencia = diferencia > 0;

                                        return `
                                                            <tr>
                                                                <td>
                                                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-weight: 600; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;">
                                                                        ${line.lineNumber}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="font-monospace" style="color: #64748b; font-size: 0.78rem; font-weight: 500;">
                                                                        ${line.productId}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="fw-semibold" style="color: #0f172a; font-size: 0.85rem;">
                                                                        ${line.productNumber}
                                                                    </span>
                                                                </td>
                                                                <td class="text-truncate" style="max-width: 180px;" title="${line.productDescription}">
                                                                    <span style="color: #334155; font-size: 0.85rem; font-weight: 500;">
                                                                        ${line.productDescription}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span style="color: #475569; font-weight: 500; font-size: 0.85rem;">
                                                                        ${cantidad.toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                                    </span>
                                                                </td>
                                                                ${!esDevolucion ? `
                                                    <td class="text-end">
                                                        <span style="color: ${dispDevolucion > 0 ? '#0f172a' : '#94a3b8'};
                                                                    font-weight: ${dispDevolucion > 0 ? 600 : 400};
                                                                    font-size: 0.85rem;">
                                                            ${dispDevolucion.toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="fw-semibold" style="font-size: 0.85rem;
                                                            color: ${tieneDiferencia ? '#ef4444' : '#10b981'};">
                                                            ${tieneDiferencia ? '-' : ''}${Math.abs(diferencia).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                        </span>
                                                    </td>
                                                ` : ''}
                                                                <td>
                                                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;">
                                                                        ${line.orderedUOMCode}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span style="color: #475569; font-weight: 500; font-size: 0.85rem;">
                                                                        $${parseFloat(line.unitSellingPrice).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span class="fw-semibold" style="color: #0f172a; font-size: 0.85rem;">
                                                                        $${parseFloat(line.extendedAmount).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge rounded-pill"
                                                                        style="background: ${line.status === 'Closed' ? '#f1f5f9' : '#f0fdf4'};
                                                                                color: ${line.status === 'Closed' ? '#64748b' : '#059669'};
                                                                                font-weight: 500;
                                                                                font-size: 0.75rem;
                                                                                padding: 4px 10px;">
                                                                        <i class="bi bi-${line.status === 'Closed' ? 'check-circle' : 'arrow-repeat'} me-1" style="font-size: 0.7rem;"></i>
                                                                        ${line.status}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        `;
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal de Devolución -->
                <div class="modal fade" id="modalDevolucion" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">

                            <!-- Header -->
                            <div class="p-4" style="background: linear-gradient(135deg, #fef2f2 0%, #fff5f5 100%); border-bottom: 1px solid #fecaca;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-0">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                                style="width: 36px; height: 36px; background: #fef2f2; border: 2px solid #fecaca;">
                                                <i class="bi bi-arrow-return-left" style="color: #dc2626; font-size: 1rem;"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0" style="color: #0f172a; font-size: 1.1rem;">Crear Devolución</h5>
                                                <p class="mb-0" style="color: #64748b; font-size: 0.8rem;">
                                                    Orden <span class="fw-semibold" style="color: #0f172a;" id="devolucion-orden">#${dato.orderNumber}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            style="width: 32px; height: 32px; border-radius: 8px; background-color: #fee2e2; opacity: 1;"
                                            onmouseover="this.style.backgroundColor='#fecaca'"
                                            onmouseout="this.style.backgroundColor='#fee2e2'">
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-4">
                                <!-- Alerta informativa -->
                                <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-3"
                                    style="background: #fffbeb; border: 1px solid #fde68a;">
                                    <i class="bi bi-info-circle fs-5" style="color: #f59e0b;"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold" style="color: #92400e; font-size: 0.85rem;">Productos disponibles para devolución</p>
                                        <p class="mb-0" style="color: #a16207; font-size: 0.8rem;">Solo se muestran los productos con cantidad disponible para devolver</p>
                                    </div>
                                </div>

                                <!-- Botón Devolución Total -->
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button"
                                            id="btn-devolucion-total"
                                            class="btn d-flex align-items-center gap-2"
                                            style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; padding: 8px 16px; font-weight: 600; font-size: 0.85rem;"
                                            onclick="devolucionTotal()"
                                            onmouseover="this.style.background='#fee2e2'; this.style.transform='translateY(-1px)'"
                                            onmouseout="this.style.background='#fef2f2'; this.style.transform='translateY(0)'">
                                        <i class="bi bi-box-arrow-in-down"></i> Devolución Total
                                    </button>
                                </div>

                                <!-- Tabla de productos a devolver -->
                                <div class="table-responsive"
                                    style="max-height: 50vh; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                                    <table class="table table-hover table-custom">
                                        <thead style="position: sticky; top: 0; z-index: 2;">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Código</th>
                                                <th>Producto</th>
                                                <th class="text-end">Cantidad</th>
                                                <th class="text-end">Disp. Devolución</th>
                                                <th style="width: 80px;">UOM</th>
                                                <th class="text-end">Precio Unit.</th>
                                                <th class="text-end" style="width: 140px;">Cant. a Devolver</th>
                                            </tr>
                                        </thead>
                                        <tbody id="devolucion-tbody">
                                            ${dato.lines
                                                .filter(line => parseFloat(line.orderedReturn) > 0)
                                                .map(line => `
                                                            <tr data-line-id="${line.fulfillLineId}">
                                                                <td>
                                                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-weight: 600; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;">
                                                                        ${line.lineNumber}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="fw-semibold" style="color: #0f172a; font-size: 0.85rem;">
                                                                        ${line.productNumber}
                                                                    </span>
                                                                </td>
                                                                <td class="text-truncate" style="max-width: 200px;" title="${line.productDescription}">
                                                                    <span style="color: #334155; font-size: 0.85rem; font-weight: 500;">
                                                                        ${line.productDescription}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span style="color: #64748b; font-size: 0.85rem;">
                                                                        ${parseFloat(line.orderedQuantity).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span class="fw-semibold" style="color: #0f172a; font-size: 0.85rem;">
                                                                        ${parseFloat(line.orderedReturn).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;">
                                                                        ${line.orderedUOMCode}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span style="color: #475569; font-size: 0.85rem;">
                                                                        $${parseFloat(line.unitSellingPrice).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="number"
                                                                            class="form-control form-control-sm input-devolucion"
                                                                            value="0.000"
                                                                            min="0"
                                                                            max="${parseFloat(line.orderedReturn).toFixed(3)}"
                                                                            step="0.001"
                                                                            data-max="${parseFloat(line.orderedReturn).toFixed(3)}"
                                                                            data-price="${line.unitSellingPrice}"
                                                                            data-line="${line.fulfillLineId}"
                                                                            style="width: 110px; text-align: right; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem; padding: 6px 10px; font-weight: 500;">
                                                                        <button type="button"
                                                                                class="btn btn-sm btn-max-devolucion"
                                                                                style="background: #f1f5f9; color: #64748b; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem; white-space: nowrap;"
                                                                                title="Devolver todo">
                                                                            Max
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Estado vacío si no hay productos -->
                                <div id="devolucion-empty" class="text-center py-5 d-none">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 64px; height: 64px; background: #f8fafc;">
                                        <i class="bi bi-check-circle" style="font-size: 1.8rem; color: #10b981;"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1" style="color: #475569;">Sin productos pendientes</h6>
                                    <p class="mb-0" style="color: #94a3b8; font-size: 0.85rem;">Todos los productos de esta orden ya fueron devueltos</p>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="p-4 d-flex justify-content-between align-items-center"
                                style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                                <div class="d-flex align-items-center gap-4">
                                    <div>
                                        <span style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Total Kilos</span>
                                        <span id="devolucion-total-kilos" class="fw-bold ms-2" style="color: #0f172a; font-size: 1.1rem;">0.00 kg</span>
                                    </div>
                                    <div style="width: 1px; height: 30px; background: #e2e8f0;"></div>
                                    <div>
                                        <span style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Total Importe</span>
                                        <span id="devolucion-total-modal" class="fw-bold ms-2" style="color: #0f172a; font-size: 1.1rem;">$0.00</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                            class="btn"
                                            data-bs-dismiss="modal"
                                            style="background: white; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 20px; font-weight: 500; font-size: 0.85rem;">
                                        Cancelar
                                    </button>
                                    <button type="button"
                                            id="btn-procesar-devolucion"
                                            class="btn d-flex align-items-center gap-2"
                                            style="background: #dc2626; color: white; border-radius: 8px; padding: 8px 24px; font-weight: 600; font-size: 0.85rem;"
                                            onclick="procesarDevolucion()"
                                            disabled>
                                        <i class="bi bi-check-lg"></i> Crear Devolución
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $resultadosContenedor.html(html).removeClass('d-none');

            $('html, body').animate({
                scrollTop: $resultadosContenedor.offset().top - 100
            }, 500);
        }

        function limpiarBusqueda() {
            $('#orden').val('').removeClass('is-invalid').focus();
            $('#error-mensaje').addClass('d-none');
            $('#resultados-contenedor').addClass('d-none').empty();
            $('#estado-inicial').removeClass('d-none');
            $('#subtitulo-resultados').text('Información detallada de la orden consultada');
        }

        function mostrarToast(tipo, titulo, mensaje) {
            const container = document.getElementById('toastContainer');

            const icons = {
                danger: 'bi-exclamation-triangle-fill',
                warning: 'bi-exclamation-circle-fill',
                success: 'bi-check-circle-fill'
            };

            const toast = document.createElement('div');
            toast.className = `toast-alert toast-${tipo}`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bi ${icons[tipo] || icons.danger}"></i>
                </div>
                <div class="toast-content">
                    <strong>${titulo}</strong>
                    <span>${mensaje}</span>
                </div>
                <button type="button" class="toast-close" onclick="cerrarToast(this)">
                    <i class="bi bi-x"></i>
                </button>
            `;

            container.appendChild(toast);

            // Auto-cerrar después de 6 segundos
            setTimeout(() => {
                cerrarToast(toast.querySelector('.toast-close'));
            }, 6000);
        }

        function cerrarToast(elemento) {
            const toast = elemento.closest('.toast-alert');
            if (toast) {
                toast.classList.add('removing');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        $(document).ready(function() {
            $('#orden').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    buscarOrden();
                }
            });

            $('#orden').on('input', function() {
                $(this).removeClass('is-invalid');
                $('#error-mensaje').addClass('d-none');
            });
        });
    </script>
</x-page-container>
