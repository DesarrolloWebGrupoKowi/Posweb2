<x-page-container title="Promoción">
    <x-card-gradient-header
        icon="tags"
        title="Promoción"
        subtitle="Gestione los artículos y configuración de la promoción"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        @php
            $fechaFin = \Carbon\Carbon::parse($descuento->FechaFin);
            $hoy = \Carbon\Carbon::today();
            $estaDesactivada = $descuento->Status == 1;
            $estaExpirada = $fechaFin->lt($hoy);
            $estaInactiva = $estaDesactivada || $estaExpirada;
        @endphp

        <div class="p-4">
            <!-- Encabezado con botón -->
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-pencil-square me-2"
                            style="color: #64748b;"
                        ></i>Editar: {{ $descuento->NomDescuento }}
                    </h5>
                    <p class="section-content-subtitle">Modifique la configuración y artículos de la promoción</p>
                </div>
                <a
                    href="/VerDescuentos"
                    class="btn-modern btn-outline-modern"
                >
                    <i class="bi bi-boxes me-2"></i>Ver descuentos
                </a>
            </div>

            @if ($estaInactiva)
                <div class="mb-4 px-0 pb-0">
                    <div
                        class="rounded-3 mb-0 p-4"
                        style="background: #fffbeb; border-left: 4px solid #f59e0b;"
                    >
                        <div class="d-flex align-items-center gap-3">
                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center"
                                style="background: #fef3c7; width: 40px; height: 40px;"
                            >
                                <i
                                    class="bi bi-exclamation-triangle"
                                    style="color: #f59e0b; font-size: 1.2rem;"
                                ></i>
                            </div>
                            <div>
                                @if ($estaDesactivada)
                                    <h6
                                        class="fw-bold mb-1"
                                        style="color: #92400e;"
                                    >Promoción desactivada</h6>
                                    <p
                                        class="mb-0"
                                        style="color: #a16207; font-size: 0.85rem;"
                                    >No es posible realizar modificaciones en los datos.</p>
                                @else
                                    <h6
                                        class="fw-bold mb-1"
                                        style="color: #92400e;"
                                    >Promoción expirada</h6>
                                    <p
                                        class="mb-0"
                                        style="color: #a16207; font-size: 0.85rem;"
                                    >La fecha de vigencia ha terminado. No es posible realizar modificaciones.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                <!-- Formulario datos generales -->
                <div class="col-xl-4">
                    <div class="card-modern">
                        <div
                            class="card-header border-bottom p-3"
                            style="background: #f8fafc;"
                        >
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i
                                        class="bi bi-gear"
                                        style="color: #64748b;"
                                    ></i>
                                    <h6
                                        class="fw-bold mb-0"
                                        style="color: #0f172a;"
                                    >Información General</h6>
                                </div>
                                <button
                                    class="btn-table-action btn-table-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarConfirm{{ $descuento->IdEncDescuento }}"
                                    title="Deshabilitar"
                                >
                                    <i class="bi bi-arrow-down-circle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form
                                action="/GuardarDescuento"
                                method="POST"
                            >
                                <input
                                    type="hidden"
                                    name="IdEncDescuento"
                                    value="{{ $descuento->IdEncDescuento }}"
                                >
                                @csrf

                                <div class="mb-3">
                                    <label
                                        class="form-label fw-medium mb-2"
                                        style="color: #475569; font-size: 0.85rem;"
                                    >Nombre del descuento</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="nomDescuento"
                                        value="{{ $descuento->NomDescuento }}"
                                        style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                        {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                    >
                                    @if ($estaInactiva)
                                        <input
                                            type="hidden"
                                            name="nomDescuento"
                                            value="{{ $descuento->NomDescuento }}"
                                        >
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label
                                        class="form-label fw-medium mb-2"
                                        style="color: #475569; font-size: 0.85rem;"
                                    >Tipo descuento</label>
                                    <select
                                        class="form-select"
                                        name="tipoDescuento"
                                        style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                        {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                    >
                                        @foreach ($tiposdescuentos as $td)
                                            <option
                                                value="{{ $td->IdTipoDescuento }}"
                                                {{ $descuento->TipoDescuento == $td->IdTipoDescuento ? 'selected' : '' }}
                                            >
                                                {{ $td->NomTipoDescuento }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($estaInactiva)
                                        <input
                                            type="hidden"
                                            name="tipoDescuento"
                                            value="{{ $descuento->TipoDescuento }}"
                                        >
                                    @endif
                                </div>

                                <div class="row g-3 my-0">
                                    <div
                                        class="col-md-6 mb-2 mt-0"
                                        id="divTienda"
                                        style="display: {{ $descuento->TipoDescuento == 2 ? 'block' : 'none' }};"
                                    >
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Tienda <span style="color: #ef4444;">*</span>
                                        </label>
                                        <select
                                            class="form-select"
                                            name="idTienda"
                                            id="idTienda"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                            {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                        >
                                            <option value="">Seleccione una tienda</option>
                                            @foreach ($tiendas as $tienda)
                                                <option
                                                    value="{{ $tienda->IdTienda }}"
                                                    {{ $tienda->IdTienda == $descuento->IdTienda ? 'selected' : '' }}
                                                >
                                                    {{ $tienda->NomTienda }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div
                                        class="col-md-6 mb-2 mt-0"
                                        id="divPlaza"
                                        style="display: {{ $descuento->TipoDescuento == 3 ? 'block' : 'none' }};"
                                    >
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >
                                            Plaza <span style="color: #ef4444;">*</span>
                                        </label>
                                        <select
                                            class="form-select"
                                            name="idPlaza"
                                            id="idPlaza"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                            {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                        >
                                            <option value="">Seleccione una plaza</option>
                                            @foreach ($plazas as $plaza)
                                                <option
                                                    value="{{ $plaza->IdPlaza }}"
                                                    {{ $plaza->IdPlaza == $descuento->IdPlaza ? 'selected' : '' }}
                                                >
                                                    {{ $plaza->NomPlaza }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 mt-0">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >Fecha inicio</label>
                                        <input
                                            class="form-control"
                                            type="date"
                                            name="fechaInicio"
                                            value="{{ $descuento->FechaInicio }}"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                            {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                        >
                                    </div>
                                    <div class="col-md-6 mt-0">
                                        <label
                                            class="form-label fw-medium mb-2"
                                            style="color: #475569; font-size: 0.85rem;"
                                        >Fecha fin</label>
                                        <input
                                            class="form-control"
                                            type="date"
                                            name="fechaFin"
                                            value="{{ $descuento->FechaFin }}"
                                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                            {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                        >
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button
                                        type="submit"
                                        class="btn-modern btn-agregar"
                                        {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                    >
                                        <i class="bi bi-floppy me-2"></i>Guardar cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @include('Descuentos.ModalEliminarConfirm')
                </div>

                <!-- Tabla de artículos -->
                <div class="col-xl-8">
                    <div class="card-modern">
                        <div
                            class="card-header border-bottom p-3"
                            style="background: #f8fafc;"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <i
                                    class="bi bi-box"
                                    style="color: #64748b;"
                                ></i>
                                <h6
                                    class="fw-bold mb-0"
                                    style="color: #0f172a;"
                                >Artículos en Promoción</h6>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form
                                id="formPaquete"
                                action="/EditarDescuentoExistente/{{ $descuento->IdEncDescuento }}"
                                method="POST"
                            >
                                @csrf
                                <div id="contenedorPaquete"></div>
                            </form>
                            <div class="row g-2 align-items-end mb-3">
                                <div class="col-md-12">
                                    <label
                                        class="form-label fw-medium mb-1"
                                        style="color: #475569; font-size: 0.8rem;"
                                    >
                                        <i class="bi bi-upc me-1"></i>Agregar artículo
                                    </label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text"
                                            style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                        >
                                            <i class="bi bi-upc-scan"></i>
                                        </span>
                                        <input
                                            class="form-control border-start-0"
                                            list="articulos"
                                            name="codArticulo"
                                            id="codArticulo"
                                            style="border: 1px solid #e2e8f0; border-left: none; padding: 8px 12px; font-size: 0.85rem;"
                                            placeholder="Buscar artículo"
                                            autocomplete="off"
                                            oninput="this.value = this.value.toUpperCase()"
                                            {{ $estaInactiva ? 'disabled' : '' }}
                                        >
                                        <datalist id="articulos">
                                            @foreach ($articulos as $articulo)
                                                <option value="{{ $articulo->CodArticulo }}">
                                                    {{ $articulo->NomArticulo }}</option>
                                            @endforeach
                                        </datalist>
                                        <button
                                            class="btn btn-modern btn-agregar"
                                            type="button"
                                            id="btnAgregarArticulo"
                                            style="border-radius: 0 8px 8px 0; padding: 8px 16px;"
                                            {{ $estaInactiva ? 'disabled' : '' }}
                                        >
                                            <i class="bi bi-plus-circle me-1"></i> Agregar
                                        </button>
                                    </div>
                                    <div class="mt-1">
                                        <span
                                            class="nomArticulo fw-medium"
                                            style="color: #3b82f6; font-size: 0.85rem;"
                                        ></span>
                                        <span
                                            hidden
                                            class="nomArticuloValid"
                                        ></span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="table-responsive"
                                style="max-height: 400px; overflow-y: auto;"
                            >
                                <table
                                    class="table-hover table-custom mb-0 table"
                                    id="tblArticulos"
                                >
                                    <thead style="position: sticky; top: 0; z-index: 2; background: #f8fafc;">
                                        <tr>
                                            <th><i class="bi bi-upc me-1"></i>Código</th>
                                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                                            <th><i class="bi bi-list-ol me-1"></i>Lista Precio</th>
                                            <th><i class="bi bi-currency-dollar me-1"></i>Precio Prom.</th>
                                            <th class="text-center"><i class="bi bi-trash me-1"></i>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($detalle as $item)
                                            <tr
                                                class="articulo-row {{ $item->Status == 1 ? 'opacity-50' : '' }}"
                                                style="{{ $item->Status == 1 ? 'background: #f8fafc;' : '' }}"
                                            >
                                                <td style="font-weight: 500; color: #0f172a;">
                                                    <span
                                                        style="{{ $item->Status == 1 ? 'text-decoration: line-through;' : '' }}"
                                                    >
                                                        {{ $item->CodArticulo }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        style="{{ $item->Status == 1 ? 'text-decoration: line-through;' : '' }}"
                                                    >
                                                        {{ $item->NomArticulo }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($item->Status == 1)
                                                        <span class="badge bg-light text-dark border">
                                                            {{ $item->NomListaPrecio ?? ($ListaPrecio->where('IdListaPrecio', $item->IdListaPrecio)->first()->NomListaPrecio ?? '-') }}
                                                        </span>
                                                    @else
                                                        <select
                                                            class="form-select form-select-sm-modern"
                                                            name="listaPrecios[]"
                                                            style="width: 150px; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; cursor: pointer; transition: all 0.2s ease;"
                                                            {{ $estaInactiva ? 'disabled' : '' }}
                                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'; this.style.outline='none';"
                                                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                                                        >
                                                            @foreach ($ListaPrecio as $lista)
                                                                <option
                                                                    value="{{ $lista->IdListaPrecio }}"
                                                                    {{ $lista->IdListaPrecio == $item->IdListaPrecio ? 'selected' : '' }}
                                                                >
                                                                    {{ $lista->NomListaPrecio }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->Status == 1)
                                                        <span
                                                            class="fw-medium"
                                                            style="color: #9ca3af;"
                                                        >
                                                            ${{ number_format($item->PrecioDescuento, 2) }}
                                                        </span>
                                                    @else
                                                        <input
                                                            class="form-control form-control-sm-modern"
                                                            type="number"
                                                            step="0.01"
                                                            name="precioArticulo[]"
                                                            style="width: 120px; text-align: center; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; transition: all 0.2s ease;"
                                                            value="{{ number_format($item->PrecioDescuento, 2) }}"
                                                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'; this.style.outline='none';"
                                                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                                                            {{ $estaInactiva ? 'disabled' : '' }}
                                                        >
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->Status == 1)
                                                        <span class="badge-status badge-inactive">
                                                            <i class="bi bi-archive me-1"></i>Desactivado
                                                        </span>
                                                    @else
                                                        <button
                                                            type="button"
                                                            class="btn-table-action btn-table-delete btnEliminarArticulo"
                                                            title="Eliminar artículo"
                                                            {{ $estaInactiva ? 'disabled' : '' }}
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="5">
                                                    <div class="py-5 text-center">
                                                        <div class="empty-state-icon mx-auto mb-3">
                                                            <i
                                                                class="bi bi-box fs-3"
                                                                style="color: #94a3b8;"
                                                            ></i>
                                                        </div>
                                                        <h6 class="text-muted">Sin artículos en promoción</h6>
                                                        <small class="text-muted">Agregue artículos usando el campo de
                                                            búsqueda</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button
                                    type="button"
                                    id="btnConfirmar"
                                    class="btn-modern btn-agregar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalConfirmarGuardar"
                                    disabled
                                >
                                    <i class="bi bi-floppy me-2"></i>Guardar artículos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card-gradient-header>

    <div
        id="ulListaPrecios"
        class="d-none"
    >
        <select
            class="form-select form-select-sm"
            name="listaPrecios[]"
            style="width: 150px;"
        >
            @foreach ($ListaPrecio as $lista)
                <option value="{{ $lista->IdListaPrecio }}">{{ $lista->NomListaPrecio }}</option>
            @endforeach
        </select>
    </div>

    @include('Descuentos.ModalArticuloRepetido')
    @include('Descuentos.ModalConfirmarGuardar')
    @include('Descuentos.ModalCantidadPrecioCero')
    @include('Descuentos.ModalEliminarArticulo')
    @include('Descuentos.ModalReactivarArticulo')
</x-page-container>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoDescuento = document.querySelector('select[name="tipoDescuento"]');
        const divTienda = document.getElementById('divTienda');
        const divPlaza = document.getElementById('divPlaza');
        const selectTienda = document.querySelector('select[name="idTienda"]');
        const selectPlaza = document.querySelector('select[name="idPlaza"]');

        function toggleCamposPorTipo() {
            const tipo = parseInt(tipoDescuento.value);

            // Resetear ambos campos
            divTienda.style.display = 'none';
            divPlaza.style.display = 'none';

            if (selectTienda) {
                selectTienda.required = false;
                selectTienda.value = '';
            }

            if (selectPlaza) {
                selectPlaza.required = false;
                selectPlaza.value = '';
            }

            // Mostrar solo el campo correspondiente
            switch (tipo) {
                case 1: // Producto - Ambos ocultos
                    console.log('Tipo Producto: Ambos campos ocultos');
                    break;

                case 2: // Tienda - Solo mostrar tienda
                    if (divTienda && selectTienda) {
                        divTienda.style.display = 'block';
                        selectTienda.required = true;
                        console.log('Tipo Tienda: Mostrando solo campo Tienda');
                    }
                    break;

                case 3: // Plaza - Solo mostrar plaza
                    if (divPlaza && selectPlaza) {
                        divPlaza.style.display = 'block';
                        selectPlaza.required = true;
                        console.log('Tipo Plaza: Mostrando solo campo Plaza');
                    }
                    break;

                default:
                    console.log('Tipo no reconocido');
                    break;
            }
        }

        // Configurar el evento change
        if (tipoDescuento) {
            // Ejecutar al cargar la página
            // toggleCamposPorTipo();

            // Ejecutar cuando cambie el select
            tipoDescuento.addEventListener('change', toggleCamposPorTipo);
        }

        // Validación adicional antes de enviar el formulario
        const formulario = document.querySelector('form[action="/GuardarDescuento"]');
        if (formulario) {
            formulario.addEventListener('submit', function(e) {
                const tipo = parseInt(tipoDescuento.value);

                if (tipo === 2 && (!selectTienda || !selectTienda.value)) {
                    e.preventDefault();
                    alert('Debe seleccionar una tienda para el tipo de descuento seleccionado');
                    return false;
                }

                if (tipo === 3 && (!selectPlaza || !selectPlaza.value)) {
                    e.preventDefault();
                    alert('Debe seleccionar una plaza para el tipo de descuento seleccionado');
                    return false;
                }
            });
        }
    });

    const estaInactiva = {{ $estaInactiva ? 'true' : 'false' }};
    const tbody = document.querySelector('#tblArticulos tbody');

    if (estaInactiva) {
        document.getElementById('codArticulo').disabled = true;
    }

    function verificarEmptyState() {
        const filas = tbody.querySelectorAll('tr:not(.empty-row)');
        const emptyRow = tbody.querySelector('.empty-row');
        if (filas.length === 0 && !emptyRow) {
            tbody.insertAdjacentHTML('beforeend', `
                <tr class="empty-row">
                    <td colspan="5">
                        <div class="py-5 text-center">
                            <div class="empty-state-icon mx-auto mb-3">
                                <i class="bi bi-box fs-3" style="color: #94a3b8;"></i>
                            </div>
                            <h6 class="text-muted">Sin artículos en promoción</h6>
                            <small class="text-muted">Agregue artículos usando el campo de búsqueda</small>
                        </div>
                    </td>
                </tr>`);
        } else if (filas.length > 0 && emptyRow) {
            emptyRow.remove();
        }
    }
    // Evento para el botón Agregar
    document.getElementById('btnAgregarArticulo').addEventListener('click', function() {
        if (estaInactiva) return;

        const codArticulo = document.getElementById('codArticulo');
        const nomArticulo = document.querySelector('.nomArticulo');
        const nomArticuloValid = document.querySelector('.nomArticuloValid');

        if (codArticulo.value != '' && nomArticulo.textContent != '' && nomArticuloValid.textContent != '') {
            const agregado = agregarArticuloATabla(
                codArticulo.value,
                nomArticulo.textContent,
                document.querySelector('#ulListaPrecios').innerHTML
            );
            if (agregado) {
                codArticulo.value = '';
                nomArticulo.textContent = '';
                nomArticuloValid.textContent = '';
                verificarBotonGuardar();
            }
        }
    });

    function agregarArticuloATabla(codigo, nombre, selectHtml) {
        const emptyRow = tbody.querySelector('.empty-row');
        if (emptyRow) emptyRow.remove();

        // Verificar duplicados
        let existe = false;
        tbody.querySelectorAll('tr.articulo-row').forEach(row => {
            const codigoExistente = row.querySelector('td:first-child')?.textContent.trim();
            if (codigoExistente === codigo.trim()) {
                existe = true;
            }
        });

        if (existe) {
            const filaExistente = Array.from(tbody.querySelectorAll('tr.articulo-row')).find(row => {
                return row.querySelector('td:first-child')?.textContent.trim() === codigo.trim();
            });

            if (filaExistente && filaExistente.classList.contains('opacity-50')) {
                // Guardar referencia para reactivar
                $('#ModalReactivarArticulo').data('fila', filaExistente);
                $('#ModalReactivarArticulo').data('codigo', codigo);
                $('#ModalReactivarArticulo').data('selectHtml', selectHtml);
                $('#ModalReactivarArticulo').modal('show');
                return false;
            }

            // Duplicado activo
            $('#ModalArticuloRpetido').modal('show');
            return false;
        }

        // Agregar nueva fila
        const selectEstilizado = selectHtml.replace('<select',
            '<select style="width: 150px; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; cursor: pointer; transition: all 0.2s ease;" onfocus="this.style.borderColor=\'#3b82f6\'; this.style.boxShadow=\'0 0 0 3px rgba(59, 130, 246, 0.1)\'; this.style.outline=\'none\'" onblur="this.style.borderColor=\'#e2e8f0\'; this.style.boxShadow=\'none\'"'
        );

        tbody.insertAdjacentHTML('beforeend', `
        <tr class="articulo-row">
            <td style="font-weight: 500; color: #0f172a;">${codigo.trim()}</td>
            <td>${nombre.trim()}</td>
            <td>${selectEstilizado}</td>
            <td><input class="form-control form-control-sm-modern" style="width: 120px; text-align: center; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; transition: all 0.2s ease;" type="number" step="0.01" name="precioArticulo[]" placeholder="Precio" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'; this.style.outline='none'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"></td>
            <td class="text-center">
                <button type="button" class="btn-table-action btn-table-delete btnEliminarArticulo" title="Eliminar artículo">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`);
        return true;
    }

    // Evento para reactivar artículo desde el modal
    $(document).off('click', '#btnReactivarArticulo').on('click', '#btnReactivarArticulo', function() {
        const filaExistente = $('#ModalReactivarArticulo').data('fila');
        const selectHtml = $('#ModalReactivarArticulo').data('selectHtml');

        $('#ModalReactivarArticulo').modal('hide');

        if (!filaExistente) return;

        // Quitar estilos de desactivado
        filaExistente.classList.remove('opacity-50');
        filaExistente.style.background = '';
        filaExistente.style.textDecoration = '';
        filaExistente.style.color = '';

        // Quitar tachado
        filaExistente.querySelectorAll('td:first-child span, td:nth-child(2) span').forEach(span => {
            span.style.textDecoration = '';
        });

        // Reconstruir celda de lista de precio
        const tdLista = filaExistente.querySelector('td:nth-child(3)');
        tdLista.innerHTML = selectHtml;
        const newSelect = tdLista.querySelector('select');
        if (newSelect) {
            newSelect.style.cssText =
                'width: 150px; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; text-align: center; cursor: pointer; transition: all 0.2s ease;';
            newSelect.addEventListener('focus', function() {
                this.style.borderColor = '#3b82f6';
                this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
                this.style.outline = 'none';
            });
            newSelect.addEventListener('blur', function() {
                this.style.borderColor = '#e2e8f0';
                this.style.boxShadow = 'none';
            });
        }

        // Reconstruir celda de precio
        const tdPrecio = filaExistente.querySelector('td:nth-child(4)');
        tdPrecio.innerHTML =
            `<input class="form-control form-control-sm-modern" style="width: 120px; text-align: center; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; transition: all 0.2s ease;" type="number" step="0.01" name="precioArticulo[]" placeholder="Precio" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'; this.style.outline='none'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">`;

        // Reconstruir celda de acciones
        const tdAcciones = filaExistente.querySelector('td:nth-child(5)');
        tdAcciones.innerHTML = `
        <button type="button" class="btn-table-action btn-table-delete btnEliminarArticulo" title="Eliminar artículo">
            <i class="bi bi-trash"></i>
        </button>`;

        verificarBotonGuardar();
    });

    document.getElementById('codArticulo').addEventListener('input', function(e) {
        if (estaInactiva) return;
        fetch('/BuscarCodArticuloPaquqete?codArticulo=' + e.target.value)
            .then(res => res.text())
            .then(respuesta => {
                if (respuesta != '') {
                    document.querySelector('.nomArticulo').innerHTML = respuesta;
                    document.querySelector('.nomArticuloValid').innerHTML = respuesta;
                } else {
                    document.querySelector('.nomArticulo').innerHTML = document.getElementById(
                            'codArticulo').value == '' ? '' :
                        'Buscando Artículo... <i class="bi bi-hourglass-split"></i>';
                    document.querySelector('.nomArticuloValid').innerHTML = '';
                }
            });
    });

    document.getElementById('codArticulo').addEventListener('keydown', function(e) {
        if (estaInactiva) return;
        if (e.key === 'Enter') {
            e.preventDefault();
            const codArticulo = document.getElementById('codArticulo');
            const nomArticulo = document.querySelector('.nomArticulo');
            const nomArticuloValid = document.querySelector('.nomArticuloValid');
            if (codArticulo.value != '' && nomArticulo.textContent != '' && nomArticuloValid.textContent !=
                '') {
                const agregado = agregarArticuloATabla(codArticulo.value, nomArticulo.textContent, document
                    .querySelector('#ulListaPrecios').innerHTML);
                if (agregado) {
                    codArticulo.value = '';
                    nomArticulo.textContent = '';
                    nomArticuloValid.textContent = '';
                }
            }
        }
    });

    $(document).on('click', '.btnEliminarArticulo', function() {
        if (estaInactiva) return;
        const $fila = $(this).closest('tr');
        const codigoArticulo = $fila.find('td:first-child').text().trim();
        const existeEnBD = $fila.find('select option[selected]').length > 0;

        // Guardar referencia en el modal
        $('#ModalEliminarArticulo').data('fila', $fila);
        $('#ModalEliminarArticulo').data('codigo', codigoArticulo);
        $('#ModalEliminarArticulo').data('existeEnBD', existeEnBD);

        if (!existeEnBD) {
            // Artículo nuevo - mostrar modal de eliminar
            $('#ModalEliminarArticuloLabel').text('Eliminar Artículo');
            $('#ModalEliminarArticuloMensaje').html(
                `¿Desea eliminar el artículo <strong>${codigoArticulo}</strong> de la lista?`);
            $('#ModalEliminarArticuloSubmensaje').text('El artículo se quitará de la lista.');
            $('#btnConfirmarEliminar').text('Eliminar').css('background', '#ef4444');
        } else {
            // Artículo existente en BD - mostrar modal de desactivar
            $('#ModalEliminarArticuloLabel').text('Desactivar Artículo');
            $('#ModalEliminarArticuloMensaje').html(
                `¿Desea desactivar el artículo <strong>${codigoArticulo}</strong> de esta promoción?`);
            $('#ModalEliminarArticuloSubmensaje').text(
                'El artículo permanecerá registrado pero no estará activo.');
            $('#btnConfirmarEliminar').text('Desactivar').css('background', '#f59e0b');
        }

        $('#ModalEliminarArticulo').modal('show');
    });

    // Evento para el botón de confirmación del modal
    $(document).off('click', '#btnConfirmarEliminar').on('click', '#btnConfirmarEliminar', function() {
        const $fila = $('#ModalEliminarArticulo').data('fila');
        const codigoArticulo = $('#ModalEliminarArticulo').data('codigo');
        const existeEnBD = $('#ModalEliminarArticulo').data('existeEnBD');

        $('#ModalEliminarArticulo').modal('hide');

        if (!existeEnBD) {
            // Eliminar fila
            $fila.remove();
            verificarEmptyState();
            verificarBotonGuardar();
        } else {
            // Desactivar artículo por AJAX
            $.ajax({
                url: '/DesactivarArticuloPromocion',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    IdEncDescuento: '{{ $descuento->IdEncDescuento }}',
                    CodArticulo: codigoArticulo
                },
                success: function(response) {
                    if (response.success) {
                        // Aplicar estilos visuales
                        // Aplicar estilos visuales
                        $fila.addClass('opacity-50');
                        $fila.css('background', '#f8fafc');

                        // Tachar código y nombre
                        $fila.find('td:eq(0) span').css('text-decoration', 'line-through');
                        $fila.find('td:eq(1) span').css('text-decoration', 'line-through');

                        // Reemplazar select por badge
                        const listaPrecio = $fila.find('td:eq(2) select option:selected').text();
                        $fila.find('td:eq(2)').html(`
                        <span class="badge bg-light text-dark border">${listaPrecio}</span>
                    `);

                        // Reemplazar input por texto
                        const precio = $fila.find('td:eq(3) input').val();
                        $fila.find('td:eq(3)').html(`
                        <span class="fw-medium" style="color: #9ca3af;">$${parseFloat(precio).toFixed(2)}</span>
                    `);

                        // Reemplazar botón por badge
                        $fila.find('td:eq(4)').html(`
                        <span class="badge-status badge-inactive">
                            <i class="bi bi-archive me-1"></i>Desactivado
                        </span>
                    `);

                        verificarBotonGuardar();
                    }
                }
            });
        }
    });

    function guardarPaquete() {
        const contenedorPaquete = document.getElementById('contenedorPaquete');
        contenedorPaquete.innerHTML = '';
        let enviar = 0;

        $('#tblArticulos tbody tr.articulo-row').each(function() {
            const $fila = $(this);
            if (!$fila.find('.btnEliminarArticulo').is(':visible')) return;
            const $td = $fila.find('td');
            const codigo = $td.eq(0).text().trim();
            const listaPrecio = $td.eq(2).find('select').val();
            const precio = $td.eq(3).find('input').val();
            if (!codigo || codigo.includes('No hay')) return;
            if (!precio || precio == '0') enviar++;
            $(contenedorPaquete).append(`
                <input type="hidden" name="CodArticulo[]" value="${codigo}">
                <input type="hidden" name="listaPrecios[]" value="${listaPrecio}">
                <input type="hidden" name="PrecioArticulo[]" value="${precio}">
                <input type="hidden" name="Status[]" value="0">`);
        });

        if ($(contenedorPaquete).find('input[name="CodArticulo[]"]').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin artículos activos',
                text: 'Debe tener al menos un artículo activo',
                confirmButtonColor: '#475569'
            });
            return false;
        }
        if (enviar > 0) {
            $('#ModalCantidadPrecioCero').modal('show');
            return false;
        }
        $('#formPaquete').submit();
    }

    $(document).on('click', '#btnEditarPaquete', function(e) {
        e.preventDefault();
        guardarPaquete();
    });
    $(document).on('click', '#btnConfirmar', function(e) {
        e.preventDefault();
        if ($('#tblArticulos tbody tr:not(.empty-row)').length === 0) {
            alert('Debe agregar al menos un artículo');
            return false;
        }
        $('#ModalConfirmarGuardar').modal('show');
    });

    function verificarBotonGuardar() {
        const btnGuardar = document.getElementById('btnConfirmar');
        if (!btnGuardar) return;

        // Si está inactiva, no hacer nada (ya está disabled)
        if (estaInactiva) return;

        let filas = Array.from(document.querySelectorAll('#tblArticulos tbody tr.articulo-row'));
        // Eliminar las que tienen la clase opacity-50 (desactivadas)
        filas = filas.filter(row => !row.classList.contains('opacity-50'));

        // Si no hay filas activas, deshabilitar
        if (filas.length === 0) {
            btnGuardar.disabled = true;
            btnGuardar.classList.add('btn-disabled');
            return;
        }

        let habilitar = false;

        filas.forEach(row => {
            const precioInput = row.querySelector('input[name="precioArticulo[]"]');
            const selectLista = row.querySelector('select[name="listaPrecios[]"]');

            // Verificar si es un artículo nuevo (sin option selected)
            const esNuevo = selectLista && !selectLista.querySelector('option[selected]');

            if (esNuevo) {
                // Es nuevo, verificar que tenga precio
                const precio = precioInput ? parseFloat(precioInput.value) : 0;
                if (precio && precio > 0) {
                    habilitar = true;
                }
            } else if (selectLista && precioInput) {
                // Ya existe en BD, verificar si hubo cambios
                const precioActual = parseFloat(precioInput.value) || 0;
                const precioOriginal = parseFloat(precioInput.defaultValue) || 0;
                const listaActual = selectLista.value;
                const listaOriginal = selectLista.querySelector('option[selected]')?.value;

                if (precioActual > 0 && (precioActual !== precioOriginal || listaActual !== listaOriginal)) {
                    habilitar = true;
                }
            }
        });

        btnGuardar.disabled = !habilitar;

        if (habilitar) {
            btnGuardar.classList.remove('btn-disabled');
        } else {
            btnGuardar.classList.add('btn-disabled');
        }
    }

    // Escuchar cambios en los inputs de precio
    $(document).on('input', 'input[name="precioArticulo[]"]', function() {
        verificarBotonGuardar();
    });

    // Escuchar cuando se agrega un nuevo artículo
    $(document).on('keydown', '#codArticulo', function(e) {
        if (e.key === 'Enter') {
            setTimeout(verificarBotonGuardar, 200);
        }
    });

    // Escuchar cuando se elimina un artículo
    $(document).on('click', '.btnEliminarArticulo', function() {
        setTimeout(verificarBotonGuardar, 300);
    });

    // Verificar al cargar la página
    setTimeout(verificarBotonGuardar, 500);

    verificarEmptyState();
</script>
