@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Crear Descuento')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y BOTONES -->
        <x-layout.section-card>
            <div class="d-flex justify-content-sm-between align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title
                    :titulo="'Crear Descuento'"
                    :options="[['name' => 'Catálogo de descuentos', 'value' => '/VerDescuentos']]"
                />
                <div class="d-flex gap-2">
                    <x-filters.buttons.back-button />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>
        </x-layout.section-card>

        <!-- SECCIÓN 2: FORMULARIO DE CREACIÓN MEJORADO -->
        <div class="row g-4">
            <!-- Panel principal del formulario -->
            <div class="col-12 col-lg-8">
                <div
                    class="card border-0 shadow-sm"
                    style="border-radius: 8px; overflow: hidden;"
                >
                    <div
                        class="card d-flex flex-column border-0 p-4 pb-3"
                        style="border-radius: 10px; min-height: 0;"
                    >
                        <div class="d-flex align-items-center gap-2">
                            <div
                                class="rounded-circle p-2"
                                style="background-color: rgba(56, 70, 94, 0.1);"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#38465E"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M12 4v16M8 8V4h8v4" />
                                    <rect
                                        x="4"
                                        y="8"
                                        width="16"
                                        height="12"
                                        rx="2"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h5 class="mb-0 text-gray-800">CONFIGURACIÓN DE LA PROMOCIÓN</h5>
                                <h6
                                    class="fw-semibold text-muted m-0"
                                    style="font-size: 0.9rem;"
                                >
                                    Configuración básica de la promoción
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-0">
                        <form
                            id="formPaquete"
                            action="/GuardarDescuento"
                            method="POST"
                        >
                            @csrf

                            <!-- Campos principales -->
                            <div class="row g-4">
                                <div class="col-12">
                                    <label
                                        class="text-secondary fw-500 mb-1"
                                        style="color: #38465E;"
                                    >
                                        Nombre del descuento <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-light border-end-0"
                                            style="border-radius: 4px 0 0 4px;"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="#38465E"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"
                                                />
                                            </svg>
                                        </span>
                                        <input
                                            class="form-control border-start-0 ps-0"
                                            style="border-radius: 0 4px 4px 0;"
                                            type="text"
                                            name="nomDescuento"
                                            id="nomDescuento"
                                            placeholder="Ej: Descuento de verano 2024"
                                            {{-- required --}}
                                            value="{{ old('nomDescuento') }}"
                                            autofocus
                                        >
                                    </div>
                                    @error('nomDescuento')
                                        <div class="invalid-feedback d-block mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label
                                        class="text-secondary fw-500 mb-1"
                                        style="color: #38465E;"
                                    >
                                        Tipo descuento <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        class="form-select"
                                        style="border-radius: 4px;"
                                        name="tipoDescuento"
                                        id="tipoDescuento"
                                        {{-- required --}}
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
                                    @error('tipoDescuento')
                                        <div class="invalid-feedback d-block mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label
                                        class="text-secondary fw-500 mb-1"
                                        style="color: #38465E;"
                                    >
                                        Ámbito de aplicación
                                    </label>
                                    <div
                                        id="ambitoInfo"
                                        class="alert mb-0 px-3 py-2"
                                        style="background-color: #fef3c7; border-color: #fde68a; color: #92400e; border-radius: 4px;"
                                    >
                                        <small>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="14"
                                                height="14"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                class="me-1"
                                            >
                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="10"
                                                />
                                                <line
                                                    x1="12"
                                                    y1="16"
                                                    x2="12"
                                                    y2="12"
                                                />
                                                <line
                                                    x1="12"
                                                    y1="8"
                                                    x2="12.01"
                                                    y2="8"
                                                />
                                            </svg>
                                            <span id="ambitoTexto">Seleccione un tipo de descuento</span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos condicionales con animación -->
                            <div class="row g-4 mt-2">
                                <div
                                    class="col-md-6"
                                    id="divTienda"
                                    style="display: none;"
                                >
                                    <label
                                        class="text-secondary fw-500 mb-1"
                                        style="color: #38465E;"
                                    >
                                        Tienda <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-light border-end-0"
                                            style="border-radius: 4px 0 0 4px;"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="#38465E"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"
                                                />
                                            </svg>
                                        </span>
                                        <select
                                            class="form-select border-start-0 ps-0"
                                            style="border-radius: 0 4px 4px 0;"
                                            name="idTienda"
                                            id="idTienda"
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
                                    @error('idTienda')
                                        <div class="invalid-feedback d-block mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div
                                    class="col-md-6"
                                    id="divPlaza"
                                    style="display: none;"
                                >
                                    <label
                                        class="text-secondary fw-500 mb-1"
                                        style="color: #38465E;"
                                    >
                                        Plaza <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-light border-end-0"
                                            style="border-radius: 4px 0 0 4px;"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="#38465E"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                                />
                                            </svg>
                                        </span>
                                        <select
                                            class="form-select border-start-0 ps-0"
                                            style="border-radius: 0 4px 4px 0;"
                                            name="idPlaza"
                                            id="idPlaza"
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
                                    @error('idPlaza')
                                        <div class="invalid-feedback d-block mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fechas -->
                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <label
                                        class="text-secondary fw-500 mb-1"
                                        style="color: #38465E;"
                                    >
                                        Fecha inicio <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-light border-end-0"
                                            style="border-radius: 4px 0 0 4px;"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="#38465E"
                                                stroke-width="2"
                                            >
                                                <rect
                                                    x="3"
                                                    y="4"
                                                    width="18"
                                                    height="18"
                                                    rx="2"
                                                    ry="2"
                                                />
                                                <line
                                                    x1="16"
                                                    y1="2"
                                                    x2="16"
                                                    y2="6"
                                                />
                                                <line
                                                    x1="8"
                                                    y1="2"
                                                    x2="8"
                                                    y2="6"
                                                />
                                                <line
                                                    x1="3"
                                                    y1="10"
                                                    x2="21"
                                                    y2="10"
                                                />
                                            </svg>
                                        </span>
                                        <input
                                            class="form-control border-start-0 ps-0"
                                            style="border-radius: 0 4px 4px 0;"
                                            type="date"
                                            name="fechaInicio"
                                            id="fechaInicio"
                                            {{-- required --}}
                                            value="{{ old('fechaInicio') }}"
                                        >
                                    </div>
                                    @error('fechaInicio')
                                        <div class="invalid-feedback d-block mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label
                                        class="text-secondary fw-500 mb-1"
                                        style="color: #38465E;"
                                    >
                                        Fecha fin <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-light border-end-0"
                                            style="border-radius: 4px 0 0 4px;"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="#38465E"
                                                stroke-width="2"
                                            >
                                                <rect
                                                    x="3"
                                                    y="4"
                                                    width="18"
                                                    height="18"
                                                    rx="2"
                                                    ry="2"
                                                />
                                                <line
                                                    x1="16"
                                                    y1="2"
                                                    x2="16"
                                                    y2="6"
                                                />
                                                <line
                                                    x1="8"
                                                    y1="2"
                                                    x2="8"
                                                    y2="6"
                                                />
                                                <line
                                                    x1="3"
                                                    y1="10"
                                                    x2="21"
                                                    y2="10"
                                                />
                                                <line
                                                    x1="12"
                                                    y1="14"
                                                    x2="12"
                                                    y2="18"
                                                />
                                                <line
                                                    x1="16"
                                                    y1="14"
                                                    x2="16"
                                                    y2="18"
                                                />
                                                <line
                                                    x1="8"
                                                    y1="14"
                                                    x2="8"
                                                    y2="18"
                                                />
                                            </svg>
                                        </span>
                                        <input
                                            class="form-control border-start-0 ps-0"
                                            style="border-radius: 0 4px 4px 0;"
                                            type="date"
                                            name="fechaFin"
                                            id="fechaFin"
                                            {{-- required --}}
                                            value="{{ old('fechaFin') }}"
                                        >
                                    </div>
                                    @error('fechaFin')
                                        <div class="invalid-feedback d-block mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-end border-top mt-5 gap-3 pt-3">
                                <button
                                    type="reset"
                                    class="btn px-4"
                                    id="btnLimpiar"
                                    style="background-color: #f8f9fa; color: #38465E; border: 1px solid #dee2e6; border-radius: 4px;"
                                >
                                    <i class="fa fa-undo me-2"></i> Limpiar
                                </button>
                                <button
                                    type="submit"
                                    id="btnGenerarObject"
                                    class="btn px-4"
                                    style="background-color: #38465E; color: white; border: none; border-radius: 4px;"
                                >
                                    <i class="fa fa-save me-2"></i> Generar Descuento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel lateral de ayuda/información -->
            <div class="col-12 col-lg-4">
                <div
                    class="card sticky-top border-0 shadow-sm"
                    style="top: 20px; border-radius: 8px;"
                >
                    <div
                        class="card-header border-0 bg-white px-4 py-3"
                        style="border-bottom: 2px solid #38465E;"
                    >
                        <div class="d-flex align-items-center gap-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#38465E"
                                stroke-width="2"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="10"
                                />
                                <path d="M12 16v-4M12 8h.01" />
                            </svg>
                            <h6
                                class="fw-bold mb-0"
                                style="color: #38465E;"
                            >Información importante</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2 gap-2">
                                <div
                                    class="rounded-circle p-1"
                                    style="background-color: rgba(56, 70, 94, 0.1);"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="14"
                                        height="14"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#38465E"
                                        stroke-width="2"
                                    >
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                        />
                                    </svg>
                                </div>
                                <small class="text-muted fw-bold">Tipos de descuento</small>
                            </div>
                            <ul class="list-unstyled mb-0 ms-3">
                                <li class="mb-2"><small class="text-muted">🔹 <strong>Producto:</strong> Aplica a
                                        productos específicos</small></li>
                                <li class="mb-2"><small class="text-muted">🔹 <strong>Tienda:</strong> Aplica a toda una
                                        tienda</small></li>
                                <li class="mb-2"><small class="text-muted">🔹 <strong>Plaza:</strong> Aplica a todas las
                                        tiendas de una plaza</small></li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2 gap-2">
                                <div
                                    class="rounded-circle p-1"
                                    style="background-color: rgba(56, 70, 94, 0.1);"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="14"
                                        height="14"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#38465E"
                                        stroke-width="2"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="10"
                                        />
                                        <line
                                            x1="12"
                                            y1="16"
                                            x2="12"
                                            y2="12"
                                        />
                                        <line
                                            x1="12"
                                            y1="8"
                                            x2="12.01"
                                            y2="8"
                                        />
                                    </svg>
                                </div>
                                <small class="text-muted fw-bold">Recomendaciones</small>
                            </div>
                            <ul class="list-unstyled mb-0 ms-3">
                                <li class="mb-2"><small class="text-muted">✓ Usa nombres descriptivos para tus
                                        promociones</small></li>
                                <li class="mb-2"><small class="text-muted">✓ Verifica que las fechas sean
                                        correctas</small></li>
                                <li class="mb-2"><small class="text-muted">✓ Puedes agregar artículos después de crear
                                        la promoción</small></li>
                            </ul>
                        </div>

                        <!-- Resumen dinámico -->
                        <div
                            id="resumenContainer"
                            class="border-top mt-3 pt-3"
                            style="display: none;"
                        >
                            <div class="d-flex align-items-center mb-2 gap-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#38465E"
                                    stroke-width="2"
                                >
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                </svg>
                                <small class="text-muted fw-bold">Resumen de la promoción</small>
                            </div>
                            <div
                                id="resumenContent"
                                class="rounded p-3"
                                style="font-size: 13px; background-color: #f8f9fa; border-radius: 4px;"
                            >
                                <!-- El resumen se llenará dinámicamente -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-layout.page-container>

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

            // Textos informativos por tipo
            const textosAmbito = {
                1: '📦 Aplicará a productos específicos',
                2: '🏪 Aplicará a una tienda completa',
                3: '📍 Aplicará a una plaza completa'
            };

            // Función para mostrar/ocultar campos según tipo de descuento
            function toggleCamposPorTipo() {
                const tipo = parseInt(tipoDescuento.value);

                // Ocultar campos con animación
                if (divTienda.style.display === 'block') {
                    divTienda.style.animation = 'slideUp 0.3s ease';
                    setTimeout(() => {
                        divTienda.style.display = 'none';
                    }, 250);
                } else {
                    divTienda.style.display = 'none';
                }

                if (divPlaza.style.display === 'block') {
                    divPlaza.style.animation = 'slideUp 0.3s ease';
                    setTimeout(() => {
                        divPlaza.style.display = 'none';
                    }, 250);
                } else {
                    divPlaza.style.display = 'none';
                }

                // Limpiar required
                selectTienda.required = false;
                selectPlaza.required = false;

                // Actualizar texto informativo
                if (tipo && textosAmbito[tipo]) {
                    ambitoTexto.innerHTML = textosAmbito[tipo];
                } else {
                    ambitoTexto.innerHTML = 'Seleccione un tipo de descuento';
                }

                // Mostrar campo correspondiente
                setTimeout(() => {
                    switch (tipo) {
                        case 1:
                            // No mostrar nada
                            break;
                        case 2:
                            divTienda.style.display = 'block';
                            selectTienda.required = true;
                            divTienda.style.animation = 'slideDown 0.3s ease';
                            break;
                        case 3:
                            divPlaza.style.display = 'block';
                            selectPlaza.required = true;
                            divPlaza.style.animation = 'slideDown 0.3s ease';
                            break;
                        default:
                            break;
                    }
                    actualizarResumen();
                }, 250);
            }

            // Actualizar resumen dinámico
            function actualizarResumen() {
                const tipo = parseInt(tipoDescuento.value);
                const nombre = nomDescuento.value;
                const tienda = selectTienda.options[selectTienda.selectedIndex]?.text;
                const plaza = selectPlaza.options[selectPlaza.selectedIndex]?.text;
                const inicio = fechaInicio.value;
                const fin = fechaFin.value;

                if (nombre && tipo && inicio && fin) {
                    resumenContainer.style.display = 'block';
                    let html = `<div class="mb-2"><strong style="color: #38465E;">Nombre:</strong> ${nombre}</div>`;
                    html +=
                        `<div class="mb-2"><strong style="color: #38465E;">Tipo:</strong> ${tipoDescuento.options[tipoDescuento.selectedIndex]?.text || ''}</div>`;

                    if (tipo === 2 && tienda && tienda !== 'Seleccione una tienda') {
                        html +=
                            `<div class="mb-2"><strong style="color: #38465E;">Tienda:</strong> ${tienda}</div>`;
                    }
                    if (tipo === 3 && plaza && plaza !== 'Seleccione una plaza') {
                        html += `<div class="mb-2"><strong style="color: #38465E;">Plaza:</strong> ${plaza}</div>`;
                    }

                    if (inicio && fin) {
                        const fechaInicioObj = new Date(inicio);
                        const fechaFinObj = new Date(fin);
                        const diffTime = Math.abs(fechaFinObj - fechaInicioObj);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        html +=
                            `<div class="mb-2"><strong style="color: #38465E;">Vigencia:</strong> ${diffDays} días</div>`;
                    }

                    resumenContent.innerHTML = html;
                } else if (resumenContainer.style.display === 'block') {
                    resumenContainer.style.display = 'none';
                }
            }

            // Validar fechas
            function validarFechas() {
                if (fechaInicio.value && fechaFin.value) {
                    if (fechaFin.value < fechaInicio.value) {
                        fechaFin.setCustomValidity('La fecha fin debe ser mayor o igual a la fecha inicio');
                        fechaFin.classList.add('is-invalid');
                        actualizarResumen();
                        return false;
                    } else {
                        fechaFin.setCustomValidity('');
                        fechaFin.classList.remove('is-invalid');
                        actualizarResumen();
                        return true;
                    }
                }
                return true;
            }

            // Event listeners
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

            // Botón limpiar
            document.getElementById('btnLimpiar').addEventListener('click', function(e) {
                e.preventDefault();
                form.reset();
                setTimeout(() => {
                    toggleCamposPorTipo();
                    actualizarResumen();
                }, 100);
            });

            // Ejecutar inicial
            toggleCamposPorTipo();
            validarFechas();

            // Validar antes de enviar
            form.addEventListener('submit', function(e) {
                if (!validarFechas()) {
                    e.preventDefault();
                    alert('La fecha fin debe ser mayor o igual a la fecha inicio');
                    return false;
                }

                const tipo = parseInt(tipoDescuento.value);

                if (tipo === 2 && !selectTienda.value) {
                    e.preventDefault();
                    alert('Debe seleccionar una tienda para este tipo de descuento');
                    selectTienda.focus();
                    return false;
                }

                if (tipo === 3 && !selectPlaza.value) {
                    e.preventDefault();
                    alert('Debe seleccionar una plaza para este tipo de descuento');
                    selectPlaza.focus();
                    return false;
                }
            });
        });
    </script>

    <style>
        .form-control,
        .form-select {
            border-radius: 4px;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
            color: #38465E;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #38465E;
            box-shadow: 0 0 0 0px rgba(f, f, f, f);
        }

        .btn-light:hover {
            background-color: #e9ecef;
            transform: translateY(-1px);
        }

        .btn:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            border-radius: 4px 0 0 4px;
            color: #38465E;
        }

        .input-group .form-control {
            border-radius: 0 4px 4px 0;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        /* .card {
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }

                    .card:hover {
                        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
                    } */

        ::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(0.4);
        }

        /* Estilo para el botón principal */
        .btn[style*="background-color: #38465E"]:hover {
            background-color: #2c3a4f !important;
        }
    </style>
@endsection
