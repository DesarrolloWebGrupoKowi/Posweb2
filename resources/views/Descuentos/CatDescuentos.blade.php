<x-page-container title="Crear Descuento">
    <x-card-gradient-header
        icon="tags"
        title="Crear Descuento"
        subtitle="Configure una nueva promoción o descuento"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <div class="p-4">
            <!-- Encabezado con botón -->
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-gear me-2"
                            style="color: #64748b;"
                        ></i>Configuración de la Promoción
                    </h5>
                    <p class="section-content-subtitle">Complete los datos para crear un nuevo descuento</p>
                </div>
                <a
                    href="/VerDescuentos"
                    class="btn-modern btn-outline-modern"
                >
                    <i class="bi bi-boxes me-2"></i>Ver descuentos
                </a>
            </div>

            <form
                id="formPaquete"
                action="/GuardarDescuento"
                method="POST"
            >
                @csrf

                <div class="row g-4">
                    <!-- Panel principal -->
                    <div class="col-lg-8">
                        <div class="card-modern">
                            <div class="card-body p-4">
                                <!-- Nombre del descuento -->
                                <div class="mb-3">
                                    <label
                                        class="form-label fw-medium mb-2"
                                        style="color: #475569; font-size: 0.85rem;"
                                    >
                                        Nombre del descuento <span style="color: #ef4444;">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text"
                                            style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                        >
                                            <i class="bi bi-tag"></i>
                                        </span>
                                        <input
                                            class="form-control border-start-0"
                                            style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                            type="text"
                                            name="nomDescuento"
                                            id="nomDescuento"
                                            placeholder="Ej: Descuento de verano 2024"
                                            value="{{ old('nomDescuento') }}"
                                            autofocus
                                        >
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Tipo descuento <span style="color: #ef4444;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-gear"></i>
                                            </span>
                                            <select
                                                class="form-select border-start-0"
                                                name="tipoDescuento"
                                                id="tipoDescuento"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                            >
                                                <option value="">Seleccione tipo descuento</option>
                                                @foreach ($tiposdescuentos as $td)
                                                    <option
                                                        value="{{ $td->IdTipoDescuento }}"
                                                        {{ old('tipoDescuento') == $td->IdTipoDescuento ? 'selected' : '' }}
                                                    >
                                                        {{ $td->NomTipoDescuento }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Ámbito de aplicación
                                        </label>
                                        <div
                                            class="rounded-3 p-3"
                                            style="background: #f8fafc;"
                                        >
                                            <small style="color: #64748b;">
                                                <i
                                                    class="bi bi-info-circle me-1"
                                                    style="color: #3b82f6;"
                                                ></i>
                                                <span id="ambitoTexto">Seleccione un tipo de descuento</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div
                                        class="col-md-6"
                                        id="divTienda"
                                        style="display: none;"
                                    >
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Tienda <span style="color: #ef4444;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-shop"></i>
                                            </span>
                                            <select
                                                class="form-select border-start-0"
                                                name="idTienda"
                                                id="idTienda"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                            >
                                                <option value="">Seleccione una tienda</option>
                                                @foreach ($tiendas as $tienda)
                                                    <option
                                                        value="{{ $tienda->IdTienda }}"
                                                        {{ old('idTienda') == $tienda->IdTienda ? 'selected' : '' }}
                                                    >
                                                        {{ $tienda->NomTienda }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div
                                        class="col-md-6"
                                        id="divPlaza"
                                        style="display: none;"
                                    >
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Plaza <span style="color: #ef4444;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-building"></i>
                                            </span>
                                            <select
                                                class="form-select border-start-0"
                                                name="idPlaza"
                                                id="idPlaza"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                            >
                                                <option value="">Seleccione una plaza</option>
                                                @foreach ($plazas as $plaza)
                                                    <option
                                                        value="{{ $plaza->IdPlaza }}"
                                                        {{ old('idPlaza') == $plaza->IdPlaza ? 'selected' : '' }}
                                                    >
                                                        {{ $plaza->NomPlaza }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Fecha inicio <span style="color: #ef4444;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-calendar-check"></i>
                                            </span>
                                            <input
                                                class="form-control border-start-0"
                                                type="date"
                                                name="fechaInicio"
                                                id="fechaInicio"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                value="{{ old('fechaInicio') }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Fecha fin <span style="color: #ef4444;">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"
                                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                            >
                                                <i class="bi bi-calendar-x"></i>
                                            </span>
                                            <input
                                                class="form-control border-start-0"
                                                type="date"
                                                name="fechaFin"
                                                id="fechaFin"
                                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                                value="{{ old('fechaFin') }}"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div
                                    class="d-flex justify-content-end border-top mt-4 gap-2 pt-3"
                                    style="border-color: #e2e8f0 !important;"
                                >
                                    <button
                                        type="reset"
                                        id="btnLimpiar"
                                        class="btn"
                                        style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500;"
                                    >
                                        <i class="bi bi-arrow-repeat me-2"></i>Limpiar
                                    </button>
                                    <button
                                        type="submit"
                                        id="btnGenerarObject"
                                        class="btn-modern btn-agregar"
                                    >
                                        <i class="bi bi-floppy me-2"></i>Generar Descuento
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel lateral -->
                    <div class="col-lg-4">
                        <div class="card-modern">
                            <div
                                class="card-header border-bottom p-3"
                                style="background: #f8fafc;"
                            >
                                <div class="d-flex align-items-center gap-2">
                                    <i
                                        class="bi bi-info-circle"
                                        style="color: #64748b;"
                                    ></i>
                                    <h6
                                        class="fw-bold mb-0"
                                        style="color: #0f172a;"
                                    >Información</h6>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <small
                                        class="fw-bold d-block mb-2"
                                        style="color: #475569;"
                                    >Tipos de descuento</small>
                                    <ul
                                        class="list-unstyled mb-0"
                                        style="font-size: 0.8rem; color: #64748b;"
                                    >
                                        <li class="mb-2"><i class="bi bi-box me-1"></i> <strong>Producto:</strong>
                                            Aplica a productos específicos</li>
                                        <li class="mb-2"><i class="bi bi-shop me-1"></i> <strong>Tienda:</strong>
                                            Aplica a toda una tienda</li>
                                        <li><i class="bi bi-building me-1"></i> <strong>Plaza:</strong> Aplica a todas
                                            las tiendas de una plaza</li>
                                    </ul>
                                </div>

                                <div class="mb-4">
                                    <small
                                        class="fw-bold d-block mb-2"
                                        style="color: #475569;"
                                    >Recomendaciones</small>
                                    <ul
                                        class="list-unstyled mb-0"
                                        style="font-size: 0.8rem; color: #64748b;"
                                    >
                                        <li class="mb-2"><i
                                                class="bi bi-check2 me-1"
                                                style="color: #10b981;"
                                            ></i> Usa nombres descriptivos</li>
                                        <li class="mb-2"><i
                                                class="bi bi-check2 me-1"
                                                style="color: #10b981;"
                                            ></i> Verifica que las fechas sean correctas</li>
                                        <li><i
                                                class="bi bi-check2 me-1"
                                                style="color: #10b981;"
                                            ></i> Puedes agregar artículos después de crear la promoción</li>
                                    </ul>
                                </div>

                                <div
                                    id="resumenContainer"
                                    class="border-top pt-3"
                                    style="display: none; border-color: #e2e8f0 !important;"
                                >
                                    <small
                                        class="fw-bold d-block mb-2"
                                        style="color: #475569;"
                                    >Resumen</small>
                                    <div
                                        id="resumenContent"
                                        class="rounded-3 p-3"
                                        style="background: #f8fafc; font-size: 0.8rem; color: #475569;"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-card-gradient-header>
</x-page-container>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formPaquete');
        const tipoDescuento = document.getElementById('tipoDescuento');
        const divTienda = document.getElementById('divTienda');
        const divPlaza = document.getElementById('divPlaza');
        const selectTienda = document.getElementById('idTienda');
        const selectPlaza = document.getElementById('idPlaza');
        const fechaInicio = document.getElementById('fechaInicio');
        const fechaFin = document.getElementById('fechaFin');
        const nomDescuento = document.getElementById('nomDescuento');
        const ambitoTexto = document.getElementById('ambitoTexto');
        const resumenContainer = document.getElementById('resumenContainer');
        const resumenContent = document.getElementById('resumenContent');

        const textosAmbito = {
            1: '📦 Aplicará a productos específicos',
            2: '🏪 Aplicará a una tienda completa',
            3: '📍 Aplicará a una plaza completa'
        };

        function toggleCamposPorTipo() {
            const tipo = parseInt(tipoDescuento.value);
            divTienda.style.display = 'none';
            divPlaza.style.display = 'none';
            selectTienda.required = false;
            selectPlaza.required = false;

            if (tipo && textosAmbito[tipo]) {
                ambitoTexto.innerHTML = textosAmbito[tipo];
            } else {
                ambitoTexto.innerHTML = 'Seleccione un tipo de descuento';
            }

            if (tipo === 2) {
                divTienda.style.display = 'block';
                selectTienda.required = true;
            } else if (tipo === 3) {
                divPlaza.style.display = 'block';
                selectPlaza.required = true;
            }
            actualizarResumen();
        }

        function actualizarResumen() {
            const tipo = parseInt(tipoDescuento.value);
            const nombre = nomDescuento.value;
            const tienda = selectTienda.options[selectTienda.selectedIndex]?.text;
            const plaza = selectPlaza.options[selectPlaza.selectedIndex]?.text;
            const inicio = fechaInicio.value;
            const fin = fechaFin.value;

            if (nombre && tipo && inicio && fin) {
                resumenContainer.style.display = 'block';
                let html = `<div class="mb-1"><strong>Nombre:</strong> ${nombre}</div>`;
                html +=
                    `<div class="mb-1"><strong>Tipo:</strong> ${tipoDescuento.options[tipoDescuento.selectedIndex]?.text || ''}</div>`;
                if (tipo === 2 && tienda && tienda !== 'Seleccione una tienda') html +=
                    `<div class="mb-1"><strong>Tienda:</strong> ${tienda}</div>`;
                if (tipo === 3 && plaza && plaza !== 'Seleccione una plaza') html +=
                    `<div class="mb-1"><strong>Plaza:</strong> ${plaza}</div>`;
                if (inicio && fin) {
                    const diffDays = Math.ceil((new Date(fin) - new Date(inicio)) / (1000 * 60 * 60 * 24));
                    html += `<div class="mb-1"><strong>Vigencia:</strong> ${diffDays} días</div>`;
                }
                resumenContent.innerHTML = html;
            } else {
                resumenContainer.style.display = 'none';
            }
        }

        function validarFechas() {
            if (fechaInicio.value && fechaFin.value && fechaFin.value < fechaInicio.value) {
                fechaFin.setCustomValidity('La fecha fin debe ser mayor o igual a la fecha inicio');
                return false;
            }
            fechaFin.setCustomValidity('');
            return true;
        }

        tipoDescuento.addEventListener('change', toggleCamposPorTipo);
        nomDescuento.addEventListener('input', actualizarResumen);
        selectTienda.addEventListener('change', actualizarResumen);
        selectPlaza.addEventListener('change', actualizarResumen);
        fechaInicio.addEventListener('change', () => {
            validarFechas();
            actualizarResumen();
        });
        fechaFin.addEventListener('change', () => {
            validarFechas();
            actualizarResumen();
        });

        document.getElementById('btnLimpiar').addEventListener('click', function(e) {
            e.preventDefault();
            form.reset();
            setTimeout(() => {
                toggleCamposPorTipo();
                actualizarResumen();
            }, 100);
        });

        form.addEventListener('submit', function(e) {
            if (!validarFechas()) {
                e.preventDefault();
                return false;
            }
            const tipo = parseInt(tipoDescuento.value);
            if (tipo === 2 && !selectTienda.value) {
                e.preventDefault();
                alert('Debe seleccionar una tienda para este tipo de descuento');
                return false;
            }
            if (tipo === 3 && !selectPlaza.value) {
                e.preventDefault();
                alert('Debe seleccionar una plaza para este tipo de descuento');
                return false;
            }
        });

        toggleCamposPorTipo();
        validarFechas();
    });
</script>
