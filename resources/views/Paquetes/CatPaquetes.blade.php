<x-page-container title="Crear Paquete">
    <x-card-gradient-header
        icon="box-seam"
        title="Crear Paquete"
        subtitle="Configure un nuevo paquete de artículos"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <div class="p-4">
            <form
                id="formPaquete"
                action="/GuardarPaquete"
                method="POST"
            >
                @csrf
                <input
                    type="hidden"
                    id="importePaquete"
                    name="importePaquete"
                >
                <div id="contenedorPaquete"></div>

                <!-- Nombre del paquete -->
                <div class="card-modern mb-4">
                    <div class="card-body p-4">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-box me-1"></i>Nombre del paquete
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
                                name="nomPaquete"
                                id="nomPaquete"
                                placeholder="Nombre del paquete"
                                required
                                autofocus
                            >
                        </div>
                    </div>
                </div>

                <!-- Artículos del Paquete -->
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: #64748b;"
                            ></i>Artículos del Paquete
                        </h5>
                        <p class="section-content-subtitle">Lista de artículos agregados</p>
                    </div>
                    <a
                        href="/VerPaquetes"
                        class="btn-modern btn-outline-modern"
                    >
                        <i class="bi bi-boxes me-2"></i>Ver paquetes
                    </a>
                </div>

                <!-- Agregar artículo + Tabla -->
                <div class="card-modern">
                    <div
                        class="card-header border-bottom p-3"
                        style="background: #f8fafc;"
                    >
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8">
                                <label
                                    class="form-label fw-medium mb-1"
                                    style="color: #475569; font-size: 0.8rem;"
                                >
                                    <i class="bi bi-upc me-1"></i>Agregar artículo
                                </label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text"
                                        style="background: white; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                    >
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input
                                        class="form-control border-start-0"
                                        style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                        type="text"
                                        name="codArticulo"
                                        id="codArticulo"
                                        placeholder="Código de artículo"
                                    >
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
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table
                                class="table-hover table-custom mb-0 table"
                                id="tblArticulos"
                            >
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-upc me-1"></i>Código</th>
                                        <th><i class="bi bi-box me-1"></i>Artículo</th>
                                        <th><i class="bi bi-123 me-1"></i>Cantidad</th>
                                        <th><i class="bi bi-currency-dollar me-1"></i>Precio</th>
                                        <th><i class="bi bi-calculator me-1"></i>Importe</th>
                                        <th class="text-center"><i class="bi bi-trash me-1"></i>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="rowEmpty">
                                        <td colspan="6">
                                            <div class="py-5 text-center">
                                                <div class="empty-state-icon mx-auto mb-3">
                                                    <i
                                                        class="bi bi-inbox fs-3"
                                                        style="color: #94a3b8;"
                                                    ></i>
                                                </div>
                                                <h6 class="text-muted">Sin artículos agregados</h6>
                                                <small class="text-muted">Ingrese un código de artículo y presione Enter
                                                    para agregarlo</small>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #f8fafc; font-weight: 700; border-top: 2px solid #e2e8f0;">
                                        <td colspan="3"></td>
                                        <td class="py-2 text-end">Costo del Paquete:</td>
                                        <td class="py-2"><span
                                                class="totalPaquete"
                                                style="color: #059669;"
                                            >$0.00</span></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Botón generar -->
                <div class="d-flex justify-content-end mt-4">
                    <button
                        type="button"
                        id="btnGenerarObject"
                        class="btn-modern btn-agregar btn-disabled"
                        disabled
                    >
                        <i class="bi bi-floppy me-2"></i>Generar Paquete
                    </button>
                </div>
            </form>
        </div>
    </x-card-gradient-header>

    @include('Paquetes.ModalArticuloRepetido')
    @include('Paquetes.ModalConfirmarCreacionPaquete')
    @include('Paquetes.ModalPaqueteSinNombre')
    @include('Paquetes.ModalCantidadPrecioCero')
</x-page-container>

<script>
    document.getElementById('codArticulo').addEventListener('input', function(e) {
        fetch('/BuscarCodArticuloPaquqete?codArticulo=' + e.target.value)
            .then(res => res.text())
            .then(respuesta => {
                if (respuesta != '') {
                    document.querySelector('.nomArticulo').innerHTML = respuesta
                    document.querySelector('.nomArticuloValid').innerHTML = respuesta
                } else {
                    if (document.getElementById('codArticulo').value == '') {
                        document.querySelector('.nomArticulo').innerHTML = '';
                    } else {
                        document.querySelector('.nomArticulo').innerHTML =
                            'Buscando Artículo ... <i class="bi bi-hourglass-split"></i>';
                        document.querySelector('.nomArticuloValid').innerHTML = '';
                    }
                }
            });
    });

    // Prevenir submit del formulario al presionar Enter
    document.getElementById('formPaquete').addEventListener('submit', function(e) {
        if (document.getElementById('btnGenerarObject').disabled) {
            e.preventDefault();
            return false;
        }
    });

    document.getElementById('codArticulo').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            const codArticulo = document.getElementById('codArticulo');
            const nomArticulo = document.querySelector('.nomArticulo');
            const nomArticuloValid = document.querySelector('.nomArticuloValid');

            var contCodigosRepetidos = 0;
            $('#tblArticulos tbody tr:not(#rowEmpty)').each(function() {
                var $fila = $('td', this);
                $codArticulo = $fila.eq(0).text();
                document.getElementById('codArticulo').value == $codArticulo ? contCodigosRepetidos =
                    contCodigosRepetidos + 1 : '';
            });

            if (contCodigosRepetidos > 0) {
                $('#ModalArticuloRpetido').modal('show');
            } else {
                if (codArticulo.value != '' && nomArticulo.textContent != '' && nomArticuloValid.textContent !=
                    '') {
                    // Eliminar fila vacía si existe
                    $('#rowEmpty').remove();

                    document.querySelector('#tblArticulos tbody').insertRow(-1).innerHTML = `
                    <tr>
                        <td style="font-weight: 500; color: #0f172a;">${codArticulo.value}</td>
                        <td>${nomArticulo.textContent}</td>
                        <td><input class="form-control form-control-sm-modern input-cantidad" style="width: 100px; text-align: center;" type="number" name="cantArticulo[]" placeholder="Cantidad" min="0.01" step="any"></td>
                        <td><input class="form-control form-control-sm-modern input-precio" style="width: 120px; text-align: center;" type="number" name="precioArticulo[]" placeholder="Precio" min="0.01" step="any"></td>
                        <td class="text-end" style="font-weight: 500;">$0.00</td>
                        <td class="text-center">
                            <button type="button" class="btn-table-action btn-table-delete btnEliminarArticulo">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                    codArticulo.value = '';
                    nomArticulo.textContent = '';
                    verificarBoton();
                }
            }
        }
    });

    function verificarBoton() {
        const btn = document.getElementById('btnGenerarObject');
        const filas = $('#tblArticulos tbody tr:not(#rowEmpty)');

        if (filas.length === 0) {
            btn.disabled = true;
            btn.classList.add('btn-disabled');
            return;
        }

        // Verificar que todas las filas tengan cantidad y precio
        let todoCompleto = true;
        filas.each(function() {
            var $fila = $('td', this);
            var cantidad = $fila.eq(2).find('input[type="number"]').val();
            var precio = $fila.eq(3).find('input[type="number"]').val();

            if (!cantidad || !precio || cantidad == '' || precio == '' || parseFloat(cantidad) <= 0 ||
                parseFloat(precio) <= 0) {
                todoCompleto = false;
                return false;
            }
        });

        btn.disabled = !todoCompleto;
        if (todoCompleto) {
            btn.classList.remove('btn-disabled');
        } else {
            btn.classList.add('btn-disabled');
        }
    }

    $(document).on('click', '.btnEliminarArticulo', function() {
        $(this).closest('tr').remove();

        if ($('#tblArticulos tbody tr').length === 0) {
            $('#tblArticulos tbody').html(`
            <tr id="rowEmpty">
                <td colspan="6">
                    <div class="py-5 text-center">
                        <div class="empty-state-icon mx-auto mb-3">
                            <i class="bi bi-inbox fs-3" style="color: #94a3b8;"></i>
                        </div>
                        <h6 class="text-muted">Sin artículos agregados</h6>
                        <small class="text-muted">Ingrese un código de artículo y presione Enter para agregarlo</small>
                    </div>
                </td>
            </tr>
        `);
            document.querySelector('.totalPaquete').textContent = '$0.00';
            document.getElementById('importePaquete').value = '0.00';
        }

        verificarBoton();
        calcularTotales();
    });

    function calcularTotales() {
        let importe = 0;
        $('#tblArticulos tbody tr:not(#rowEmpty)').each(function() {
            var $fila = $('td', this);
            var cantidad = parseFloat($fila.eq(2).find('input[type="number"]').val()) || 0;
            var precio = parseFloat($fila.eq(3).find('input[type="number"]').val()) || 0;
            var subtotal = precio * cantidad;

            $fila.eq(4).html("$" + subtotal.toFixed(2));
            importe += subtotal;
        });

        document.querySelector('.totalPaquete').textContent = '$' + importe.toFixed(2);
        document.getElementById('importePaquete').value = importe.toFixed(2);
    }

    $(document).on('input', 'input[type="number"]', function() {
        calcularTotales();
        verificarBoton();
    });

    $(document).off('click', '#btnGenerarObject').on('click', '#btnGenerarObject', function() {
        if (this.disabled) return;

        if (document.getElementById('nomPaquete').value == '') {
            $('#ModalPaqueteSinNombre').modal('show');
            return;
        }

        $('#ModalConfirmarCreacionPaquete').modal('show');
    });

    $(document).off('click', '#btnCrearPaquete').on('click', '#btnCrearPaquete', function() {
        var hijos = $(document.getElementById('contenedorPaquete')).find('input').length;
        if (hijos > 0) {
            $(document.getElementById('contenedorPaquete')).find('input').remove();
        }

        $('#tblArticulos tbody tr:not(#rowEmpty)').each(function() {
            var $td = $('td', this);
            var cantidad = $td.eq(2).find('input[type="number"]').val();
            var precio = $td.eq(3).find('input[type="number"]').val();

            var cArticulo = document.getElementById('contenedorPaquete').appendChild(document
                .createElement('input'));
            cArticulo.name = 'CodArticulo[]';
            cArticulo.setAttribute("hidden", "true");
            cArticulo.value = $td.eq(0).text().trim();

            var nArticulo = document.getElementById('contenedorPaquete').appendChild(document
                .createElement('input'));
            nArticulo.name = 'CantArticulo[]';
            nArticulo.setAttribute("hidden", "true");
            nArticulo.value = cantidad;

            var pArticulo = document.getElementById('contenedorPaquete').appendChild(document
                .createElement('input'));
            pArticulo.name = 'PrecioArticulo[]';
            pArticulo.setAttribute("hidden", "true");
            pArticulo.value = precio;
        });

        document.getElementById('formPaquete').submit();
    });
</script>
