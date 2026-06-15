@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Promoción')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <div class="d-flex justify-content-sm-between align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title
                    :titulo="'Promoción ' . $descuento->NomDescuento"
                    :options="[['name' => 'Descuentos y promociones', 'value' => '/VerDescuentos']]"
                />
                <div class="d-flex gap-2">
                    <x-filters.buttons.link-button
                        href='/VerDescuentos'
                        text='Regresar'
                        icon='components.icons.arrow-left'
                    />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>

            @include('Alertas.Alertas')
        </x-layout.section-card>

        <!-- Alerta si la promoción está desactivada o expirada -->
        @php
            $fechaFin = \Carbon\Carbon::parse($descuento->FechaFin);
            $hoy = \Carbon\Carbon::today();
            $estaDesactivada = $descuento->Status == 1;
            $estaExpirada = $fechaFin->lt($hoy);
            $estaInactiva = $estaDesactivada || $estaExpirada;
        @endphp

        @if ($estaInactiva)
            <div
                class="process-status-card mb-0"
                style="border-radius: 10px; background: linear-gradient(135deg, #fff7ed 0%, #fffbeb 100%); border-left: 4px solid #f59e0b; border-top: 1px solid #fde68a; border-right: 1px solid #fde68a; border-bottom: 1px solid #fde68a;"
            >
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div
                                class="status-icon"
                                style="width: 48px; height: 48px; background-color: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    style="width: 24px; height: 24px; color: #d97706;"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                            </div>
                            <div>
                                @if ($estaDesactivada)
                                    <h5
                                        class="mb-1"
                                        style="color: #92400e; font-weight: 600;"
                                    >
                                        Promoción desactivada
                                    </h5>
                                    <p
                                        class="mb-0"
                                        style="color: #b45309;"
                                    >
                                        <span class="fw-500">Esta promoción se encuentra desactivada.</span>
                                        No es posible realizar modificaciones en los datos.
                                    </p>
                                @elseif ($estaExpirada)
                                    <h5
                                        class="mb-1"
                                        style="color: #92400e; font-weight: 600;"
                                    >
                                        Promoción expirada
                                    </h5>
                                    <p
                                        class="mb-0"
                                        style="color: #b45309;"
                                    >
                                        <span class="fw-500">Esta promoción ha expirado.</span>
                                        La fecha de vigencia ha terminado. No es posible realizar modificaciones.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- SECCIÓN 3: EDICIÓN DE PROMOCIÓN Y ARTÍCULOS -->
        <div
            class="flex-grow-1 d-flex flex-column flex-xl-row gap-4"
            {{-- style="min-height: 0;" --}}
        >
            <!-- Formulario datos generales -->
            <div
                class="col-12 col-xl-4 col-2xl-4 d-flex flex-column"
                style="min-width: 0; min-height: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    style="border-radius: 10px; min-height: 0;"
                >
                    <div class="d-flex align-items-center mb-3 gap-2">
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
                        <div class="flex-column">
                            <h5 class="mb-0 text-gray-800">INFORMACIÓN GENERAL</h5>
                            <h6
                                class="fw-semibold text-muted m-0"
                                style="font-size: 0.9rem;"
                            >
                                Configuración básica de la promoción
                            </h6>
                        </div>
                        <button
                            class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalEliminarConfirm{{ $descuento->IdEncDescuento }}"
                            title="Eliminar descuento"
                        >
                            @include('components.icons.arrow-down') Deshabilar
                        </button>

                        @include('Descuentos.ModalEliminarConfirm')
                    </div>

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

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="text-secondary fw-500 mb-1">Nombre del descuento</label>
                                <input
                                    class="form-control rounded"
                                    type="text"
                                    name="nomDescuento"
                                    value="{{ $descuento->NomDescuento }}"
                                    {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                >
                                @error('nomDescuento')
                                    <div class="invalid-feedback d-block mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if ($estaInactiva)
                                    <input
                                        type="hidden"
                                        name="nomDescuento"
                                        value="{{ $descuento->NomDescuento }}"
                                    >
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="text-secondary fw-500 mb-1">Tipo descuento</label>
                                <select
                                    class="form-select rounded"
                                    name="tipoDescuento"
                                    {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                >
                                    <option value="">Seleccione tipo descuento</option>
                                    @foreach ($tiposdescuentos as $td)
                                        <option
                                            value="{{ $td->IdTipoDescuento }}"
                                            {{ old('tipoDescuento', $descuento->TipoDescuento) == $td->IdTipoDescuento ? 'selected' : '' }}
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
                                @if ($estaInactiva)
                                    <input
                                        type="hidden"
                                        name="tipoDescuento"
                                        value="{{ $descuento->TipoDescuento }}"
                                    >
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="text-secondary fw-500 mb-1">Tienda</label>
                                <select
                                    class="form-select rounded"
                                    name="idTienda"
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
                                @error('idTienda')
                                    <div class="invalid-feedback d-block mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if ($estaInactiva)
                                    <input
                                        type="hidden"
                                        name="idTienda"
                                        value="{{ $descuento->IdTienda }}"
                                    >
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="text-secondary fw-500 mb-1">Plaza</label>
                                <select
                                    class="form-select rounded"
                                    name="idPlaza"
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
                                @error('idPlaza')
                                    <div class="invalid-feedback d-block mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if ($estaInactiva)
                                    <input
                                        type="hidden"
                                        name="idPlaza"
                                        value="{{ $descuento->IdPlaza }}"
                                    >
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="text-secondary fw-500 mb-1">Fecha inicio</label>
                                <input
                                    class="form-control rounded"
                                    type="date"
                                    name="fechaInicio"
                                    value="{{ $descuento->FechaInicio }}"
                                    {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                >
                                @error('fechaInicio')
                                    <div class="invalid-feedback d-block mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if (count($detalle) != 0 || $estaInactiva)
                                    <input
                                        type="hidden"
                                        name="fechaInicio"
                                        value="{{ $descuento->FechaInicio }}"
                                    >
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="text-secondary fw-500 mb-1">Fecha fin</label>
                                <input
                                    class="form-control rounded"
                                    type="date"
                                    name="fechaFin"
                                    value="{{ $descuento->FechaFin }}"
                                    {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                                >
                                @error('fechaFin')
                                    <div class="invalid-feedback d-block mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if (count($detalle) != 0 || $estaInactiva)
                                    <input
                                        type="hidden"
                                        name="fechaFin"
                                        value="{{ $descuento->FechaFin }}"
                                    >
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button
                                class="btn btn-warning px-4"
                                {{ count($detalle) != 0 || $estaInactiva ? 'disabled' : '' }}
                            >
                                <i class="fa fa-save me-2"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de artículos -->
            <div
                class="col-12 col-xl-8 col-2xl-8"
                style="min-width: 0;"
            >
                <div
                    class="card border-0 p-4"
                    style="border-radius: 10px;"
                >
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <h6 class="fw-semibold mb-3">📦 ARTÍCULOS EN PROMOCIÓN</h6>
                    </div>

                    <div class="mb-3">
                        <form
                            id="formPaquete"
                            action="/EditarDescuentoExistente/{{ $descuento->IdEncDescuento }}"
                            method="POST"
                        >
                            @csrf
                            <div
                                id="contenedorPaquete"
                                class="container"
                            ></div>
                        </form>

                        <div class="row align-items-end g-2">
                            <div class="flex-grow-1 col-auto">
                                <label class="text-secondary fw-500 mb-1">Código de artículo</label>
                                <input
                                    class="form-control rounded"
                                    list="articulos"
                                    name="codArticulo"
                                    id="codArticulo"
                                    placeholder="Buscar articulo"
                                    autocomplete="off"
                                    required
                                    autofocus
                                >
                                <datalist id="articulos">
                                    @foreach ($articulos as $articulo)
                                        <option
                                            class="prom{{ $articulo->CodArticulo }}"
                                            value="{{ $articulo->CodArticulo }}""
                                        >
                                            {{ $articulo->NomArticulo }}
                                        </option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-auto">
                                <h5 class="nomArticulo text-muted mb-0"></h5>
                                <h5
                                    hidden
                                    class="nomArticuloValid"
                                ></h5>
                            </div>
                        </div>
                    </div>

                    <div
                        class="table-responsive"
                        style="max-height: 400px; overflow-y: auto;"
                    >
                        <table
                            id="tblArticulos"
                            class="table-sm table"
                        >
                            <thead
                                class="table-head"
                                style="position: sticky; top: 0; z-index: 10;"
                            >
                                <tr>
                                    <th class="rounded-start">Código</th>
                                    <th>Artículo</th>
                                    <th>Lista precio</th>
                                    <th>Precio promoción</th>
                                    <th class="rounded-end">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detalle as $item)
                                    <tr
                                        class="articulo-row"
                                        style="{{ $item->Status == 1 ? 'text-decoration: line-through; color: #9ca3af;' : '' }}"
                                    >
                                        <td class="fw-500">{{ $item->CodArticulo }}</td>
                                        <td
                                            class="text-truncate"
                                            style="max-width: 200px;"
                                        >{{ $item->NomArticulo }}</td>
                                        <td>
                                            <select
                                                class="form-select form-select-sm"
                                                style="{{ $item->Status == 1 ? 'opacity: 0.6' : '' }}"
                                                name="listaPrecios[]"
                                                {{ $estaInactiva || $item->Status == 1 ? 'disabled' : '' }}
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
                                            {{-- @if ($estaInactiva)
                                                <input
                                                    type="hidden"
                                                    name="listaPrecios[]"
                                                    value="{{ $item->IdListaPrecio }}"
                                                >
                                            @endif --}}
                                        </td>
                                        <td>
                                            <input
                                                class="form-control form-control-sm"
                                                type="number"
                                                step="0.01"
                                                name="precioArticulo[]"
                                                style="{{ $item->Status == 1 ? 'opacity: 0.6' : '' }}"
                                                value="{{ number_format($item->PrecioDescuento, 2) }}"
                                                {{ $estaInactiva || $item->Status == 1 ? 'disabled' : '' }}
                                            >
                                            {{-- @if ($estaInactiva)
                                                <input
                                                    type="hidden"
                                                    name="precioArticulo[]"
                                                    value="{{ $item->PrecioDescuento }}"
                                                >
                                            @endif --}}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->Status == 0)
                                                <button
                                                    class="btn btn-sm btn-outline-danger btnEliminarArticulo"
                                                    title="Eliminar artículo"
                                                    {{ $estaInactiva ? 'disabled' : '' }}
                                                >
                                                    @include('components.icons.delete')
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td
                                            colspan="5"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="No hay artículos en promoción"
                                                icon="box"
                                                message="No se encontraron artículos registrados a esta promoción."
                                                suggestion="Agrega artículos utilizando el campo de búsqueda de código de artículo."
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button
                            id="btnConfirmar"
                            class="btn btn-warning px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalConfirmarGuardar"
                            {{ $estaInactiva ? 'disabled' : '' }}
                        >
                            <i class="fa fa-save me-2"></i> Guardar artículos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-layout.page-container>

    <div
        id="ulListaPrecios"
        class="d-none"
    >
        <select
            class="form-select form-select-sm"
            name="listaPrecios[]"
        >
            @foreach ($ListaPrecio as $lista)
                <option value="{{ $lista->IdListaPrecio }}">{{ $lista->NomListaPrecio }}</option>
            @endforeach
        </select>
    </div>

    @include('Descuentos.ModalArticuloRepetido')
    @include('Descuentos.ModalConfirmarGuardar')
    @include('Descuentos.ModalCantidadPrecioCero')

    <style>
        .table thead th {
            position: sticky;
            top: 0;
            background: rgb(30, 41, 59);
            color: white;
            z-index: 10;
        }

        .table-head th {
            background-color: #1e293b !important;
            color: white !important;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.75rem;
        }

        .tags-red {
            background-color: rgba(190, 24, 93, 0.1);
            color: #be185d;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .tags-green {
            background-color: rgba(3, 84, 63, 0.1);
            color: #03543f;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .tags-blue {
            background-color: rgba(30, 66, 159, 0.1);
            color: #1e429f;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const estaInactiva = {{ $estaInactiva ? 'true' : 'false' }};
        const tbody = document.querySelector('#tblArticulos tbody');

        if (estaInactiva) {
            document.getElementById('codArticulo').disabled = true;
        }

        // Función para verificar si hay filas en la tabla y mostrar/ocultar empty state
        function verificarEmptyState() {
            const filas = tbody.querySelectorAll('tr:not(.empty-row)');
            const emptyRow = tbody.querySelector('.empty-row');

            if (filas.length === 0) {
                if (!emptyRow) {
                    // Agregar fila de empty state
                    const emptyStateRow = `
                    <tr class="empty-row">
                        <td colspan="5" class="py-5 text-center">
                            <x-table-empty-state
                                title="No hay artículos en promoción"
                                icon="box"
                                message="No se encontraron artículos registrados a esta promoción."
                                suggestion="Agrega artículos utilizando el campo de búsqueda de código de artículo."
                            />
                        </td>
                    </tr>
                `;
                    tbody.insertAdjacentHTML('beforeend', emptyStateRow);
                }
            } else {
                if (emptyRow) {
                    emptyRow.remove();
                }
            }
        }

        // Función para agregar artículo a la tabla
        function agregarArticuloATabla(codigo, nombre, selectHtml) {
            // Eliminar empty state si existe
            const emptyRow = tbody.querySelector('.empty-row');
            if (emptyRow) {
                emptyRow.remove();
            }

            // Verificar si el artículo ya existe (solo en filas con clase articulo-row)
            let existe = false;
            tbody.querySelectorAll('tr.articulo-row').forEach(row => {
                const codigoExistente = row.querySelector('td:first-child')?.textContent;
                if (codigoExistente === codigo) {
                    existe = true;
                }
            });

            if (existe) {
                $('#ModalArticuloRpetido').modal('show');
                return false;
            }

            // Agregar nueva fila con la clase 'articulo-row'
            const nuevaFila = `
                <tr class="articulo-row" data-existe="false">
                    <td class="fw-500">${codigo}</td>
                    <td class="text-truncate" style="max-width: 200px;">${nombre}</td>
                    <td>${selectHtml}</td>
                    <td><input class="form-control form-control-sm" type="number" step="0.01" name="precioArticulo[]" placeholder="Precio" required></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger btnEliminarArticulo" title="Eliminar artículo">
                            @php echo view('components.icons.delete')->render(); @endphp
                        </button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', nuevaFila);

            return true;
        }

        document.getElementById('codArticulo').addEventListener('input', function(e) {
            if (estaInactiva) return;

            fetch('/BuscarCodArticuloPaquqete?codArticulo=' + e.target.value)
                .then(res => res.text())
                .then(respuesta => {
                    if (respuesta != '') {
                        document.querySelector('.nomArticulo').innerHTML = respuesta;
                        document.querySelector('.nomArticuloValid').innerHTML = respuesta;
                    } else {
                        if (document.getElementById('codArticulo').value == '') {
                            document.querySelector('.nomArticulo').innerHTML = '';
                        } else {
                            document.querySelector('.nomArticulo').innerHTML =
                                'Buscando Artículo... <i class="fa fa-clock-o"></i>';
                            document.querySelector('.nomArticuloValid').innerHTML = '';
                        }
                    }
                });
        });

        document.getElementById('codArticulo').addEventListener('keypress', (e) => {
            if (estaInactiva) return;

            const codArticulo = document.getElementById('codArticulo');
            const nomArticulo = document.querySelector('.nomArticulo');
            const nomArticuloValid = document.querySelector('.nomArticuloValid');

            if (e.key == 'Enter') {
                if (codArticulo.value != '' && nomArticulo.textContent != '' && nomArticuloValid.textContent !=
                    '') {
                    let selectHtml = document.querySelector('#ulListaPrecios').innerHTML;

                    const agregado = agregarArticuloATabla(
                        codArticulo.value,
                        nomArticulo.textContent,
                        selectHtml
                    );

                    if (agregado) {
                        codArticulo.value = '';
                        nomArticulo.textContent = '';
                        nomArticuloValid.textContent = '';
                    }
                }
                e.preventDefault();
            }
        });

        // $(document).on('click', '.btnEliminarArticulo', function() {
        //     if (estaInactiva) return;
        //     $(this).closest('tr').remove();
        //     verificarEmptyState();
        // });
        // Cambiar el evento de eliminar por desactivar
        // Cambiar el evento de eliminar por desactivar
        $(document).on('click', '.btnEliminarArticulo', function() {
            if (estaInactiva) return;

            const $fila = $(this).closest('tr');
            const codigoArticulo = $fila.find('td:first-child').text().trim();

            // Verificar si el artículo ya existe en la BD (tiene data-status o podemos verificar por otros medios)
            // Una forma es ver si el select o input tienen algún atributo o si la fila tiene un ID
            const existeEnBD = $fila.data('existe') === true ||
                $fila.find('select option[selected]').length > 0 ||
                $fila.data('id') !== undefined;

            if (!existeEnBD) {
                // Si es un artículo nuevo (solo en frontend), simplemente eliminamos la fila
                Swal.fire({
                    title: '¿Eliminar artículo?',
                    text: `¿Deseas eliminar el artículo ${codigoArticulo} de la lista?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#38465E',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $fila.remove();
                        verificarEmptyState();
                        Swal.fire({
                            icon: 'success',
                            title: 'Artículo eliminado',
                            text: 'El artículo ha sido eliminado de la lista',
                            confirmButtonColor: '#38465E'
                        });
                    }
                });
                return;
            }

            // Si existe en BD, hacer la desactivación por AJAX
            Swal.fire({
                title: '¿Desactivar artículo?',
                text: `¿Deseas desactivar el artículo ${codigoArticulo} de esta promoción?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#38465E',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
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
                                // Aplicar estilo de desactivado a la fila
                                $fila.css({
                                    'text-decoration': 'line-through',
                                    'color': '#9ca3af'
                                });
                                $fila.find('select, input[type="number"]').prop('disabled',
                                    true);
                                $fila.find('.btnEliminarArticulo').prop('disabled', true);
                                $fila.find('select, input[type="number"]').css('opacity',
                                    '0.6');
                                $fila.find('.btnEliminarArticulo').hide();

                                // Marcar la fila como desactivada
                                $fila.data('status', 1);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Artículo desactivado',
                                    text: 'El artículo ha sido desactivado de la promoción',
                                    confirmButtonColor: '#38465E'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message ||
                                        'Error al desactivar el artículo',
                                    confirmButtonColor: '#38465E'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al desactivar el artículo',
                                confirmButtonColor: '#38465E'
                            });
                        }
                    });
                }
            });
        });
        // $(document).on('click', '.btnEliminarArticulo', function() {
        //     if (estaInactiva) return;

        //     const $fila = $(this).closest('tr');
        //     const codigoArticulo = $fila.find('td:first-child').text().trim();

        //     // Confirmar desactivación
        //     Swal.fire({
        //         title: '¿Desactivar artículo?',
        //         text: `¿Deseas desactivar el artículo ${codigoArticulo} de esta promoción?`,
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#38465E',
        //         confirmButtonText: 'Sí, desactivar',
        //         cancelButtonText: 'Cancelar'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Enviar solicitud AJAX para desactivar
        //             $.ajax({
        //                 url: '/DesactivarArticuloPromocion',
        //                 method: 'POST',
        //                 data: {
        //                     _token: '{{ csrf_token() }}',
        //                     IdEncDescuento: '{{ $descuento->IdEncDescuento }}',
        //                     CodArticulo: codigoArticulo
        //                 },
        //                 success: function(response) {
        //                     if (response.success) {
        //                         // Aplicar estilo de desactivado a la fila
        //                         $fila.css({
        //                             'text-decoration': 'line-through',
        //                             'color': '#9ca3af'
        //                         });
        //                         $fila.find('select, input[type="number"]').prop('disabled',
        //                             true);
        //                         $fila.find('.btnEliminarArticulo').prop('disabled', true);
        //                         $fila.find('select, input[type="number"]').css('opacity',
        //                             '0.6');

        //                         // Ocultar el botón de eliminar
        //                         $fila.find('.btnEliminarArticulo').hide();

        //                         Swal.fire({
        //                             icon: 'success',
        //                             title: 'Artículo desactivado',
        //                             text: 'El artículo ha sido desactivado de la promoción',
        //                             confirmButtonColor: '#38465E'
        //                         });
        //                     } else {
        //                         Swal.fire({
        //                             icon: 'error',
        //                             title: 'Error',
        //                             text: response.message ||
        //                                 'Error al desactivar el artículo',
        //                             confirmButtonColor: '#38465E'
        //                         });
        //                     }
        //                 },
        //                 error: function(xhr) {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Error',
        //                         text: 'Ocurrió un error al desactivar el artículo',
        //                         confirmButtonColor: '#38465E'
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });

        // Función para guardar (editar paquete)
        // Función para guardar (editar paquete) - Versión más simple
        function guardarPaquete() {
            let enviar = 0;
            const contenedorPaquete = document.getElementById('contenedorPaquete');

            // Limpiar TODO el contenido del contenedor
            contenedorPaquete.innerHTML = '';

            // Recorrer SOLO las filas que tienen el botón de eliminar visible (artículos activos)
            $('#tblArticulos tbody tr.articulo-row').each(function() {
                const $fila = $(this);

                // Verificar si tiene botón de eliminar (los desactivados lo tienen oculto)
                const tieneBotonEliminar = $fila.find('.btnEliminarArticulo').length > 0 &&
                    $fila.find('.btnEliminarArticulo').is(':visible');

                // Solo procesar si tiene botón de eliminar visible (está activo)
                if (!tieneBotonEliminar) {
                    console.log('Fila desactivada omitida');
                    return;
                }

                const $td = $fila.find('td');
                const codigo = $td.eq(0).text().trim();
                const listaPrecio = $td.eq(2).find('select').val();
                const precio = $td.eq(3).find('input[type="number"]').val();

                if (!codigo || codigo === '' || codigo.includes('No hay artículos')) {
                    return;
                }

                console.log(`Enviando artículo activo: ${codigo}`);

                if (precio == 0 || precio == '' || precio === undefined) {
                    enviar++;
                }

                $(contenedorPaquete).append(`
            <input type="hidden" name="CodArticulo[]" value="${codigo}">
            <input type="hidden" name="listaPrecios[]" value="${listaPrecio}">
            <input type="hidden" name="PrecioArticulo[]" value="${precio}">
            <input type="hidden" name="Status[]" value="0">
        `);
            });

            const inputsCodigo = $(contenedorPaquete).find('input[name="CodArticulo[]"]');
            if (inputsCodigo.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin artículos activos',
                    text: 'Debe tener al menos un artículo activo en la promoción',
                    confirmButtonColor: '#38465E'
                });
                return false;
            }

            if (enviar > 0) {
                $('#ModalCantidadPrecioCero').modal('show');
                return false;
            }

            $('#formPaquete').submit();
        }
        // function guardarPaquete() {
        //     let enviar = 0;
        //     const contenedorPaquete = document.getElementById('contenedorPaquete');

        //     // Limpiar TODO el contenido del contenedor
        //     contenedorPaquete.innerHTML = '';

        //     // Recorrer SOLO las filas que tienen la clase 'articulo-row' (datos reales)
        //     $('#tblArticulos tbody tr.articulo-row').each(function() {
        //         const $td = $('td', this);
        //         const codigo = $td.eq(0).text().trim();
        //         const listaPrecio = $td.eq(2).find('select').val();
        //         const precio = $td.eq(3).find('input[type="number"]').val();
        //         const status = $td.eq(4).find('input[type="hidden"]').val();

        //         // Validar que el código sea válido (número o alfanumérico, no texto del empty state)
        //         if (!codigo || codigo === '' || codigo.length > 20 || codigo.includes('No hay artículos')) {
        //             console.log('Fila ignorada: código inválido', codigo);
        //             return;
        //         }

        //         console.log(`Agregando artículo: ${codigo}, Lista: ${listaPrecio}, Precio: ${precio}`);

        //         // Validar precio
        //         if (precio == 0 || precio == '' || precio === undefined) {
        //             enviar++;
        //         }

        //         // Agregar inputs ocultos
        //         $(contenedorPaquete).append(`
    //             <input type="hidden" name="CodArticulo[]" value="${codigo}">
    //             <input type="hidden" name="listaPrecios[]" value="${listaPrecio}">
    //             <input type="hidden" name="PrecioArticulo[]" value="${precio}">
    //         `);

        //         if (!listaPrecio || !precio || precio == 0) enviar++;
        //     });

        //     // Verificar que haya al menos un artículo
        //     const inputsCodigo = $(contenedorPaquete).find('input[name="CodArticulo[]"]');
        //     if (inputsCodigo.length === 0) {
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Sin artículos',
        //             text: 'Debe agregar al menos un artículo a la promoción',
        //             confirmButtonColor: '#38465E'
        //         });
        //         return false;
        //     }

        //     if (enviar > 0) {
        //         $('#ModalCantidadPrecioCero').modal('show');
        //         return false;
        //     }

        //     // Enviar el formulario
        //     $('#formPaquete').submit();
        // }

        // Evento para el botón Guardar del modal
        $(document).on('click', '#btnEditarPaquete', function(e) {
            e.preventDefault();
            guardarPaquete();
        });

        // Evento para el botón principal que abre el modal
        $(document).on('click', '#btnConfirmar', function(e) {
            e.preventDefault();

            // Validar que haya artículos
            const filas = $('#tblArticulos tbody tr:not(.empty-row)').length;
            if (filas === 0) {
                alert('Debe agregar al menos un artículo a la promoción');
                return false;
            }

            // Mostrar modal
            $('#ModalConfirmarGuardar').modal('show');
        });

        // Inicializar empty state al cargar
        verificarEmptyState();
    </script>
@endsection
