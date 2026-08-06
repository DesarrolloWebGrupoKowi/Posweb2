<x-page-container title="Actualizar Inventario">
    <x-card-gradient-header
        icon="box-seam"
        title="Actualizar Inventario"
        subtitle="Modifique el stock de artículos por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            <a
                href="/ReporteStockAdmin"
                class="btn-modern btn-outline-modern"
            >
                <i class="bi bi-bar-chart me-2"></i>Inventario
            </a>
        </x-slot:buttons>

        <x-form.form
            action="/UpdateStockViewAdmin"
            id="formStock"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    placeholder="Seleccione tienda"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="$idTienda ?? ''"
                    onchange="document.getElementById('formStock').submit()"
                    :autofocus="true"
                />
            </x-form.group>
        </x-form.form>

        @if (!empty($idTienda))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: var(--text-secondary);"
                            ></i>
                            Stock de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            {{ count($stocks) }} artículos ·
                            <span
                                id="contadorCambios"
                                style="color: var(--warning-color); font-weight: 600;"
                            >0</span> cambios
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Botón de carga masiva -->
                        <button
                            type="button"
                            class="btn-modern d-flex align-items-center gap-2"
                            style="background: var(--btn-amber-bg); color: var(--btn-amber-text); border: 1px solid var(--btn-amber-hover);"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalCargaMasiva"
                        >
                            <i class="bi bi-file-earmark-excel"></i> Carga Masiva
                        </button>
                        <!-- Botón para descargar plantilla -->
                        <a
                            href="/DescargarPlantillaStock/{{ $idTienda }}"
                            class="btn-modern d-flex align-items-center gap-2"
                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: 1px solid var(--btn-gray-hover);"
                            title="Descargar plantilla con códigos actuales"
                        >
                            <i class="bi bi-download"></i> Plantilla
                        </a>
                    </div>
                </div>

                <form
                    action="/UpdateStockAdmin/{{ $idTienda }}"
                    method="POST"
                    id="formActualizarStock"
                >
                    @csrf
                    <div
                        class="table-responsive"
                        style="max-height: 55vh; overflow-y: auto;"
                    >
                        <table class="table-hover table-custom table">
                            <thead style="position: sticky; top: 0; z-index: 2; background: var(--table-head-bg);">
                                <tr>
                                    <th
                                        style="cursor: pointer;"
                                        onclick="ordenarTabla(0)"
                                        title="Ordenar por código"
                                    >
                                        <i class="bi bi-upc me-1"></i>Código <i
                                            id="icono-0"
                                            class="bi bi-arrow-down-up ms-1"
                                            style="font-size: 0.7rem;"
                                        ></i>
                                    </th>
                                    <th
                                        style="cursor: pointer;"
                                        onclick="ordenarTabla(1)"
                                        title="Ordenar por artículo"
                                    >
                                        <i class="bi bi-box me-1"></i>Artículo <i
                                            id="icono-1"
                                            class="bi bi-arrow-down-up ms-1"
                                            style="font-size: 0.7rem;"
                                        ></i>
                                    </th>
                                    <th
                                        class="text-center"
                                        style="cursor: pointer;"
                                        onclick="ordenarTabla(2)"
                                        title="Ordenar por stock"
                                    >
                                        <i class="bi bi-boxes me-1"></i>Stock Actual <i
                                            id="icono-2"
                                            class="bi bi-arrow-down-up ms-1"
                                            style="font-size: 0.7rem;"
                                        ></i>
                                    </th>
                                    <th class="text-center"><i class="bi bi-pencil me-1"></i>Actualizar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stocks as $stock)
                                    <tr>
                                        <td style="font-weight: 600; color: var(--text-primary);">
                                            {{ $stock->CodArticulo }}</td>
                                        <td>{{ $stock->NomArticulo }}</td>
                                        <td class="text-center">
                                            <span
                                                class="stock-actual"
                                                style="font-weight: {{ $stock->StockArticulo <= 0 ? '700' : '500' }}; color: {{ $stock->StockArticulo <= 0 ? 'var(--danger-color)' : 'var(--text-primary)' }};"
                                            >
                                                {{ $stock->StockArticulo }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <input
                                                name="stock[{{ $stock->CodArticulo }}]"
                                                class="form-control form-control-sm-modern input-stock mx-auto"
                                                style="width: 120px; text-align: center;"
                                                type="number"
                                                step="any"
                                                value="{{ $stock->StockArticulo }}"
                                                data-original="{{ $stock->StockArticulo }}"
                                                data-codigo="{{ $stock->CodArticulo }}"
                                            >
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="py-5 text-center">
                                                <div class="empty-state-icon mx-auto mb-3">
                                                    <i
                                                        class="bi bi-box-seam fs-3"
                                                        style="color: var(--text-muted);"
                                                    ></i>
                                                </div>
                                                <h6 class="text-muted">Sin artículos disponibles</h6>
                                                <small class="text-muted">No se encontraron artículos para esta
                                                    tienda</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (count($stocks) != 0)
                        <div class="d-flex justify-content-end mt-4">
                            <button
                                type="submit"
                                id="btnGuardar"
                                class="btn-modern btn-agregar btn-disabled"
                                disabled
                            >
                                <i class="bi bi-floppy me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-shop display-1"
                        style="color: var(--border-medium);"
                    ></i>
                </div>
                <h5 style="color: var(--text-primary);">Seleccione una tienda</h5>
                <p class="text-muted">Elija una tienda del filtro para actualizar su inventario</p>
            </div>
        @endif
    </x-card-gradient-header>

    <!-- Modal Carga Masiva -->
    <div
        class="modal fade"
        id="ModalCargaMasiva"
        tabindex="-1"
        aria-labelledby="ModalCargaMasivaLabel"
        aria-hidden="true"
    >
        <div
            class="modal-dialog"
            style="margin-top: 10vh;"
        >
            <div
                class="modal-content border-0 shadow"
                style="border-radius: 10px; overflow: hidden;"
            >

                <!-- Modal Header -->
                <div
                    class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);"
                >
                    <h5
                        class="mb-0 text-white"
                        style="font-weight: 600; font-size: 1.1rem;"
                        id="ModalCargaMasivaLabel"
                    >
                        <div class="d-flex align-items-center gap-3 pb-2">
                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center"
                                style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                            >
                                <i class="bi bi-file-earmark-excel"></i>
                            </div>
                            <span>Carga Masiva de Stock</span>
                        </div>
                    </h5>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div
                            class="d-flex align-items-center justify-content-center mb-3 p-4"
                            style="border: 2px dashed var(--border-input); border-radius: 12px; background: var(--bg-light); cursor: pointer;"
                            onclick="document.getElementById('archivoExcel').click()"
                            ondrop="manejarSoltar(event)"
                            ondragover="manejarArrastrar(event)"
                        >
                            <div
                                class="text-center"
                                id="zonaCarga"
                            >
                                <i
                                    class="bi bi-cloud-upload"
                                    style="font-size: 2.5rem; color: var(--text-muted);"
                                ></i>
                                <p
                                    class="mb-1 mt-2"
                                    style="color: var(--text-secondary); font-weight: 500;"
                                >Arrastra tu archivo aquí</p>
                                <small style="color: var(--text-muted);">o haz clic para seleccionar</small>
                                <p
                                    class="mb-0 mt-2"
                                    style="color: var(--text-muted); font-size: 0.75rem;"
                                >Formatos: .xlsx, .xls, .csv</p>
                            </div>
                        </div>
                        <input
                            type="file"
                            id="archivoExcel"
                            accept=".xlsx,.xls,.csv"
                            style="display: none;"
                            onchange="procesarArchivo(event)"
                        >
                    </div>

                    <!-- Resultado de la carga -->
                    <div
                        id="resultadoCarga"
                        style="display: none;"
                    >
                        <hr style="border-color: var(--border-light);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-weight: 600; color: var(--text-primary); font-size: 0.85rem;">
                                <i
                                    class="bi bi-check-circle me-1"
                                    style="color: var(--success-color);"
                                ></i>Resultado
                            </span>
                            <span
                                id="cantidadProcesados"
                                style="font-size: 0.8rem; color: var(--text-secondary);"
                            ></span>
                        </div>
                        <div
                            class="table-responsive"
                            style="max-height: 200px; overflow-y: auto;"
                        >
                            <table
                                class="table-sm mb-0 table"
                                style="font-size: 0.75rem;"
                            >
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Stock Nuevo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaResultado"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Instrucciones -->
                    <hr style="border-color: var(--border-light);">
                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                        <p class="mb-1"><strong>Formato del archivo:</strong></p>
                        <ul class="mb-0 ps-3">
                            <li>Columna A: Código del artículo</li>
                            <li>Columna B: Nuevo stock</li>
                            <li>La primera fila debe ser el encabezado</li>
                        </ul>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button
                        type="button"
                        class="btn d-flex align-items-center gap-1"
                        data-bs-dismiss="modal"
                        style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='var(--btn-gray-hover)'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='var(--btn-gray-bg)'; this.style.transform='translateY(0)'"
                    >
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                    <button
                        type="button"
                        id="btnAplicarCarga"
                        class="btn d-flex align-items-center gap-1"
                        disabled
                        style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='linear-gradient(135deg, var(--gradient-end) 0%, var(--gradient-start) 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px var(--btn-gradient-shadow)'"
                        onmouseout="this.style.background='linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        <i class="bi bi-check-lg"></i> Aplicar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-page-container>

<style>
    .input-cambiado {
        border-color: #93c5fd !important;
        background-color: #f8faff !important;
    }

    #archivoExcel {
        display: none;
    }
</style>

<script>
    document.getElementById('idTienda').addEventListener('change', (e) => {
        document.getElementById('formStock').submit();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const inputsStock = document.querySelectorAll('.input-stock');
        const btnGuardar = document.getElementById('btnGuardar');
        const contadorCambios = document.getElementById('contadorCambios');

        function verificarCambios() {
            let cambios = 0;
            inputsStock.forEach(input => {
                const valorActual = parseFloat(input.value) || 0;
                const valorOriginal = parseFloat(input.dataset.original) || 0;
                if (valorActual !== valorOriginal) {
                    input.classList.add('input-cambiado');
                    cambios++;
                } else {
                    input.classList.remove('input-cambiado');
                }
            });
            if (contadorCambios) contadorCambios.textContent = cambios;
            if (btnGuardar) {
                if (cambios > 0) {
                    btnGuardar.disabled = false;
                    btnGuardar.classList.remove('btn-disabled');
                } else {
                    btnGuardar.disabled = true;
                    btnGuardar.classList.add('btn-disabled');
                }
            }
        }

        inputsStock.forEach(input => {
            input.addEventListener('input', verificarCambios);
            input.addEventListener('change', verificarCambios);
        });

        // ============================================================
        // CARGA MASIVA
        // ============================================================
        let datosCargados = [];

        window.manejarArrastrar = function(e) {
            e.preventDefault();
            e.stopPropagation();
        };

        window.manejarSoltar = function(e) {
            e.preventDefault();
            e.stopPropagation();
            const archivo = e.dataTransfer.files[0];
            if (archivo) leerExcel(archivo);
        };

        window.procesarArchivo = function(event) {
            const archivo = event.target.files[0];
            if (archivo) leerExcel(archivo);
        };

        function leerExcel(archivo) {
            const extension = archivo.name.split('.').pop().toLowerCase();

            if (extension === 'csv') {
                leerCSV(archivo);
            } else {
                leerXLSX(archivo);
            }
        }

        function leerCSV(archivo) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const texto = e.target.result;
                const lineas = texto.split('\n');
                const datos = [];

                // Detectar el separador (coma o punto y coma)
                const primeraLinea = lineas[0].trim();
                const separador = primeraLinea.includes(';') ? ';' : ',';

                // Saltar la primera línea (encabezado)
                for (let i = 1; i < lineas.length; i++) {
                    const linea = lineas[i].trim();
                    if (linea) {
                        // Usar el separador detectado
                        const columnas = linea.split(separador).map(c => c.trim().replace(/"/g, ''));
                        if (columnas.length >= 2 && columnas[0] !== '') {
                            datos.push({
                                codigo: columnas[0],
                                stock: parseFloat(columnas[1]) || 0
                            });
                        }
                    }
                }

                if (datos.length > 0) {
                    mostrarResultado(datos);
                } else {
                    alert(
                        'No se encontraron datos válidos en el archivo. Verifique que el archivo tenga las columnas: Código, Stock');
                }
            };
            reader.readAsText(archivo);
        }

        function leerXLSX(archivo) {
            // Verificar si la librería SheetJS está disponible
            if (typeof XLSX === 'undefined') {
                // Cargar SheetJS dinámicamente
                const script = document.createElement('script');
                script.src = 'https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js';
                script.onload = function() {
                    procesarXLSX(archivo);
                };
                document.head.appendChild(script);
            } else {
                procesarXLSX(archivo);
            }
        }

        function procesarXLSX(archivo) {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const primeraHoja = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(primeraHoja, {
                        header: 1
                    });

                    const datos = [];
                    // Saltar la primera fila (encabezado)
                    for (let i = 1; i < jsonData.length; i++) {
                        const fila = jsonData[i];
                        if (fila && fila.length >= 2 && fila[0]) {
                            datos.push({
                                codigo: String(fila[0]).trim(),
                                stock: parseFloat(fila[1]) || 0
                            });
                        }
                    }

                    if (datos.length > 0) {
                        mostrarResultado(datos);
                    } else {
                        alert('No se encontraron datos válidos en el archivo.');
                    }
                } catch (error) {
                    console.error('Error al leer el archivo:', error);
                    alert('Error al procesar el archivo. Verifique el formato.');
                }
            };
            reader.readAsArrayBuffer(archivo);
        }

        function mostrarResultado(datos) {
            datosCargados = datos;
            const tablaResultado = document.getElementById('tablaResultado');
            const resultadoCarga = document.getElementById('resultadoCarga');
            const cantidadProcesados = document.getElementById('cantidadProcesados');
            const btnAplicarCarga = document.getElementById('btnAplicarCarga');

            let encontrados = 0;
            let noEncontrados = 0;
            let html = '';

            datos.forEach(dato => {
                const inputStock = document.querySelector(`.input-stock[data-codigo="${dato.codigo}"]`);
                if (inputStock) {
                    encontrados++;
                    html += `
                        <tr>
                            <td style="font-weight: 500;">${dato.codigo}</td>
                            <td>${dato.stock}</td>
                            <td><span class="tags-green">✓ Encontrado</span></td>
                        </tr>`;
                } else {
                    noEncontrados++;
                    html += `
                        <tr>
                            <td style="font-weight: 500;">${dato.codigo}</td>
                            <td>${dato.stock}</td>
                            <td><span class="tags-red">✗ No encontrado</span></td>
                        </tr>`;
                }
            });

            tablaResultado.innerHTML = html;
            resultadoCarga.style.display = 'block';
            cantidadProcesados.innerHTML =
                `<span class="tags-green">${encontrados} encontrados</span> · <span class="tags-red">${noEncontrados} no encontrados</span>`;

            btnAplicarCarga.disabled = encontrados === 0;
            if (encontrados === 0) {
                btnAplicarCarga.style.opacity = '0.5';
            } else {
                btnAplicarCarga.style.opacity = '1';
            }
        }

        // Botón Aplicar Cambios
        document.getElementById('btnAplicarCarga').addEventListener('click', function() {
            datosCargados.forEach(dato => {
                const inputStock = document.querySelector(
                    `.input-stock[data-codigo="${dato.codigo}"]`);
                if (inputStock) {
                    inputStock.value = dato.stock;
                    // Disparar evento para que se marque como cambiado
                    inputStock.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                }
            });

            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('ModalCargaMasiva'));
            modal.hide();

            // Scroll al primer cambio
            const primerCambio = document.querySelector('.input-cambiado');
            if (primerCambio) {
                primerCambio.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            // Limpiar
            datosCargados = [];
            document.getElementById('archivoExcel').value = '';
            document.getElementById('resultadoCarga').style.display = 'none';
            document.getElementById('btnAplicarCarga').disabled = true;
        });

        // Limpiar al cerrar modal
        document.getElementById('ModalCargaMasiva').addEventListener('hidden.bs.modal', function() {
            document.getElementById('archivoExcel').value = '';
            document.getElementById('resultadoCarga').style.display = 'none';
            document.getElementById('btnAplicarCarga').disabled = true;
        });
    });
    // ============================================================
    // ORDENAR TABLA
    // ============================================================
    let direcciones = [true, true, true]; // true = ascendente

    function ordenarTabla(columna) {
        const tabla = document.querySelector('.table-custom tbody');
        const filas = Array.from(tabla.querySelectorAll('tr'));

        // Filtrar solo las filas con datos (excluir empty state)
        const filasDatos = filas.filter(fila => fila.querySelector('.input-stock'));

        if (filasDatos.length === 0) return;

        // Alternar dirección
        direcciones[columna] = !direcciones[columna];

        // Actualizar iconos
        for (let i = 0; i < 3; i++) {
            const icono = document.getElementById('icono-' + i);
            if (icono) {
                if (i === columna) {
                    icono.className = direcciones[i] ? 'bi bi-sort-up ms-1' : 'bi bi-sort-down ms-1';
                    icono.style.fontSize = '0.7rem';
                } else {
                    icono.className = 'bi bi-arrow-down-up ms-1';
                    icono.style.fontSize = '0.7rem';
                }
            }
        }

        filasDatos.sort((a, b) => {
            let valorA, valorB;

            if (columna === 0) {
                // Código - texto
                valorA = a.querySelector('td').textContent.trim();
                valorB = b.querySelector('td').textContent.trim();
            } else if (columna === 1) {
                // Artículo - texto
                valorA = a.querySelectorAll('td')[1].textContent.trim();
                valorB = b.querySelectorAll('td')[1].textContent.trim();
            } else if (columna === 2) {
                // Stock - número
                valorA = parseFloat(a.querySelector('.stock-actual').textContent.trim()) || 0;
                valorB = parseFloat(b.querySelector('.stock-actual').textContent.trim()) || 0;
            }

            if (typeof valorA === 'string') {
                return direcciones[columna] ? valorA.localeCompare(valorB) : valorB.localeCompare(valorA);
            } else {
                return direcciones[columna] ? valorA - valorB : valorB - valorA;
            }
        });

        // Reordenar filas
        const fragment = document.createDocumentFragment();
        filasDatos.forEach(fila => fragment.appendChild(fila));
        tabla.appendChild(fragment);
    }
</script>
