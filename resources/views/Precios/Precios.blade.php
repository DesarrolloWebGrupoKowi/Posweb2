<x-page-container title="Actualizar Precios">
    <x-card-gradient-header
        icon="currency-dollar"
        title="Actualizar Precios"
        subtitle="Gestione los precios de artículos por lista"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/Precios"
            method="GET"
            id="formFiltros"
        >
            <x-form.group>
                <x-form.text
                    name="cod_articulo"
                    label="Código"
                    icon="upc"
                    col="col-md-4"
                    placeholder="Buscar por código..."
                    autofocus
                />
                <x-form.text
                    name="nom_articulo"
                    label="Artículo"
                    icon="box"
                    col="col-md-3"
                    placeholder="Buscar por nombre..."
                    :value="$nomArticulo ?? ''"
                />
                <x-form.select
                    name="IdGrupo"
                    label="Grupo"
                    icon="collection"
                    col="col-md-3"
                    :options="$grupos->pluck('NomGrupo', 'IdGrupo')->toArray()"
                    :selected="$idGrupo ?? ''"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        @if (count($preciosAgrupados) > 0)
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: #64748b;"
                            ></i>Precios por Lista
                        </h5>
                        <p class="section-content-subtitle">
                            {{ count($preciosAgrupados) }} artículos ·
                            <span
                                id="contadorCambios"
                                style="color: #f59e0b; font-weight: 600;"
                            >0</span> cambios detectados
                        </p>
                    </div>
                </div>

                <form
                    action="/ActualizarPrecios"
                    method="POST"
                    id="formPrecios"
                >
                    @csrf
                    <div id="contenedorPrecios"></div>

                    <div
                        class="table-responsive"
                        style="max-height: 53vh; overflow-y: auto; overflow-x: auto;"
                    >
                        <table
                            class="table-hover table-custom table"
                            id="tablaPrecios"
                        >
                            <thead style="position: sticky; top: 0; z-index: 2; background: #f8fafc;">
                                <tr>
                                    <th style="position: sticky; left: 0; background: #f8fafc; z-index: 3;">
                                        <i class="bi bi-upc me-1"></i>Código
                                    </th>
                                    <th style="position: sticky; left: 60px; background: #f8fafc; z-index: 3;">
                                        <i class="bi bi-box me-1"></i>Artículo
                                    </th>
                                    @foreach ($listasPrecioUnicas as $idLista => $nomLista)
                                        <th
                                            class="text-end"
                                            style="min-width: 140px;"
                                        >
                                            <span
                                                style="font-size: 0.75rem; color: #64748b; display: block;">{{ $nomLista }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($preciosAgrupados as $codArticulo => $articulo)
                                    <tr data-grupo="{{ $articulo['idGrupo'] }}">
                                        <td
                                            style="position: sticky; left: 0; background: white; font-weight: 600; color: #0f172a; z-index: 1;">
                                            {{ $articulo['CodArticulo'] }}
                                        </td>
                                        <td style="position: sticky; left: 60px; background: white; z-index: 1;">
                                            <span
                                                class="text-truncate d-inline-block"
                                                style="max-width: 200px;"
                                                title="{{ $articulo['NomArticulo'] }}"
                                            >
                                                {{ $articulo['NomArticulo'] }}
                                            </span>
                                        </td>
                                        @foreach ($idsListas as $idLista)
                                            @php
                                                $precioLista = $articulo['precios'][$idLista] ?? null;
                                                $precioOriginal =
                                                    $precioLista !== null ? $precioLista['PrecioArticulo'] : '0.00';
                                                $esNuevo = $precioLista === null;
                                            @endphp
                                            <td class="text-end">
                                                <!-- SIN name, SIN hidden inputs -->
                                                <input
                                                    class="form-control form-control-sm-modern input-precio text-end"
                                                    style="border: 2px solid border-radius: 8px; padding: 4px 8px; font-size: 0.8rem; transition: all 0.3s ease;"
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    value="{{ $precioOriginal }}"
                                                    data-original="{{ $precioOriginal }}"
                                                    data-cod-articulo="{{ $codArticulo }}"
                                                    data-id-lista="{{ $idLista }}"
                                                >
                                                {{-- @if ($esNuevo)
                                                    <small
                                                        style="color: #f59e0b; font-size: 0.65rem; display: block;">Nuevo</small>
                                                @endif --}}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button
                            type="button"
                            id="btnGuardarPrecios"
                            class="btn-modern btn-agregar btn-disabled"
                            disabled
                            data-bs-toggle="modal"
                            data-bs-target="#ModalConfirmarActualizarPrecios"
                        >
                            <i class="bi bi-floppy me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        @elseif ($idGrupo)
            <div class="p-4">
                <div class="card-modern">
                    <div class="card-body p-4 text-center">
                        <div class="empty-state-icon mx-auto mb-3">
                            <i
                                class="bi bi-search fs-3"
                                style="color: #94a3b8;"
                            ></i>
                        </div>
                        <h6 class="text-muted">Sin resultados</h6>
                        <small class="text-muted">No se encontraron precios con los filtros seleccionados</small>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4">
                <div class="card-modern">
                    <div class="card-body p-4 text-center">
                        <div class="empty-state-icon mx-auto mb-3">
                            <i
                                class="bi bi-currency-dollar fs-3"
                                style="color: #94a3b8;"
                            ></i>
                        </div>
                        <h6 class="text-muted">Seleccione un filtro</h6>
                        <small class="text-muted">Especifique al menos un criterio de búsqueda para mostrar los
                            artículos</small>
                    </div>
                </div>
            </div>
        @endif

        @if (count($preciosAgrupados) > 0)
            @include('Precios.ModalConfirmarActualizarPrecios')
        @endif
    </x-card-gradient-header>
</x-page-container>

<style>
    .input-cambiado {
        border-color: #93c5fd !important;
        background-color: #f8faff !important;
    }

    .input-nuevo {
        border-color: #fcd34d !important;
        background-color: #fffdf5 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputsPrecio = document.querySelectorAll('.input-precio');
        const btnGuardar = document.getElementById('btnGuardarPrecios');
        const contadorCambios = document.getElementById('contadorCambios');
        const contenedorPrecios = document.getElementById('contenedorPrecios');

        function verificarCambios() {
            let cambios = 0;

            inputsPrecio.forEach(input => {
                const valorActual = parseFloat(input.value) || 0;
                const valorOriginal = parseFloat(input.dataset.original) || 0;

                if (valorActual !== valorOriginal) {
                    input.classList.add('input-cambiado');
                    cambios++;
                } else {
                    input.classList.remove('input-cambiado');
                }
            });

            contadorCambios.textContent = cambios;

            if (cambios > 0) {
                btnGuardar.disabled = false;
                btnGuardar.classList.remove('btn-disabled');
            } else {
                btnGuardar.disabled = true;
                btnGuardar.classList.add('btn-disabled');
            }
        }

        // Función para obtener el grupo del artículo desde el DOM
        function obtenerGrupoArticulo(codArticulo) {
            // Buscar en la tabla el grupo del artículo
            const fila = document.querySelector(`tr:has(.input-precio[data-cod-articulo="${codArticulo}"])`);
            if (fila) {
                const grupoDataset = fila.dataset.grupo;
                return grupoDataset ? parseInt(grupoDataset) : null;
            }
            return null;
        }

        // Nueva función para actualizar lista 4 cuando cambia lista 1
        function actualizarLista4(inputLista1) {
            const codArticulo = inputLista1.dataset.codArticulo;
            const idGrupo = obtenerGrupoArticulo(codArticulo);

            // Solo aplicar el descuento si el grupo es 1, 2 o 4
            if (idGrupo && [1, 2, 4].includes(idGrupo)) {
                const valorLista1 = parseFloat(inputLista1.value) || 0;
                const precioConDescuento = valorLista1 * 0.9; // 10% de descuento

                // Buscar el input correspondiente a la lista 4 para el mismo artículo
                const inputLista4 = document.querySelector(
                    `.input-precio[data-cod-articulo="${codArticulo}"][data-id-lista="4"]`
                );

                if (inputLista4) {
                    // Actualizar el valor con el descuento
                    inputLista4.value = precioConDescuento.toFixed(2);

                    // Marcar como cambiado si es diferente al original
                    const valorOriginalLista4 = parseFloat(inputLista4.dataset.original) || 0;
                    if (precioConDescuento !== valorOriginalLista4) {
                        inputLista4.classList.add('input-cambiado');
                    } else {
                        inputLista4.classList.remove('input-cambiado');
                    }

                    // Disparar evento change para que verificarCambios lo detecte
                    inputLista4.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            }
        }

        function prepararEnvio() {
            // Limpiar contenedor
            contenedorPrecios.innerHTML = '';
            let index = 0;

            inputsPrecio.forEach(input => {
                const valorActual = parseFloat(input.value) || 0;
                const valorOriginal = parseFloat(input.dataset.original) || 0;

                // Solo enviar los que cambiaron
                if (valorActual !== valorOriginal) {
                    const codArticulo = input.dataset.codArticulo;
                    const idLista = input.dataset.idLista;

                    contenedorPrecios.innerHTML += `
                    <input type="hidden" name="precios[${index}][CodArticulo]" value="${codArticulo}">
                    <input type="hidden" name="precios[${index}][IdListaPrecio]" value="${idLista}">
                    <input type="hidden" name="precios[${index}][PrecioArticulo]" value="${valorActual}">
                `;
                    index++;
                }
            });
        }

        inputsPrecio.forEach(input => {
            input.addEventListener('input', function() {
                // Si el input modificado es de la lista 1
                if (this.dataset.idLista === '1') {
                    actualizarLista4(this);
                }
                verificarCambios();
            });

            input.addEventListener('change', function() {
                // Si el input modificado es de la lista 1
                if (this.dataset.idLista === '1') {
                    actualizarLista4(this);
                }
                verificarCambios();
            });
        });

        btnGuardar.addEventListener('click', function(e) {
            if (btnGuardar.disabled) {
                e.preventDefault();
                return false;
            }
            prepararEnvio();
        });

        // Confirmar envío desde el modal
        const btnConfirmar = document.getElementById('btnConfirmarActualizar');
        if (btnConfirmar) {
            btnConfirmar.addEventListener('click', function() {
                document.getElementById('formPrecios').submit();
            });
        }
    });
</script>
