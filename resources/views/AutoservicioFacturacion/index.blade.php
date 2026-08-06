<x-page-container title="Facturación Autoservicio">
    <x-card-gradient-header
        icon="receipt"
        title="Facturación Autoservicio"
        subtitle="Generación de interfaz de facturación para clientes de autoservicio"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Buscador -->
        <x-form.form action="/AutoservicioFacturacion">
            <x-form.group>
                <x-form.text
                    name="packlist"
                    label="PackList"
                    icon="search"
                    placeholder="Código del PackList (ej: 2026JL23MV)"
                    col="col-md-4"
                    autofocus
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        @if (isset($header))
            <!-- Resultados -->
            <div class="p-4">
                <!-- Subtítulo + Botón de Acción -->
                <div class="px-0 pb-4 pt-0">
                    <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                        <div>
                            <h5 class="section-content-title">
                                <i
                                    class="bi bi-receipt me-1"
                                    style="color: var(--text-secondary);"
                                ></i>
                                PackList: {{ $packList }}
                            </h5>
                            <p class="section-content-subtitle">
                                {{ count($lineas) }} artículos ·
                                Folio:
                                <strong>{{ $packingorder->Source_Transaction_Identifier ?? 'Pendiente' }}</strong>
                                · Estatus:
                                @if (!$packingorder)
                                    <span style="font-size: 0.7rem;">Sin Pedido</span>
                                @elseif ($packingorder->STATUS === null && $packingorder->MENSAJE_ERROR === null)
                                    <span style="font-size: 0.7rem;">Interfazado</span>
                                @elseif ($packingorder->STATUS === 'ENVIADO' || $packingorder->STATUS === 'PROCESADO')
                                    <span style="font-size: 0.7rem;">Enviado</span>
                                @elseif ($packingorder->STATUS === 'DESPACHADO')
                                    <span style="font-size: 0.7rem;">Despachado</span>
                                @elseif ($packingorder->MENSAJE_ERROR !== null)
                                    <span style="font-size: 0.7rem;">Error</span>
                                @else
                                    <span style="font-size: 0.7rem;">{{ $packingorder->STATUS ?? 'Desconocido' }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            {{-- Sin pedido: Botón Generar Pedido --}}
                            @if (!$packingorder)
                                <button
                                    type="button"
                                    id="btnGenerarPedido"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: var(--btn-green-bg); color: var(--btn-green-text); border: 1px solid var(--btn-green-hover); border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                    onmouseover="this.style.background='var(--btn-green-hover)';"
                                    onmouseout="this.style.background='var(--btn-green-bg)';"
                                >
                                    <i class="bi bi-plus-circle me-2"></i> Generar Pedido
                                </button>
                            @endif

                            {{-- Paso 2: Enviar Pedido a Oracle --}}
                            @if ($packingorder && $packingorder->STATUS === null && $packingorder->MENSAJE_ERROR === null)
                                <button
                                    type="button"
                                    id="btnAccion"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: var(--btn-blue-bg); color: var(--btn-blue-text); border: 1px solid var(--btn-blue-hover); border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                    onmouseover="this.style.background='var(--btn-blue-hover)';"
                                    onmouseout="this.style.background='var(--btn-blue-bg)';"
                                    onclick="enviarPedido()"
                                >
                                    <i class="bi bi-send me-2"></i> Enviar Pedido a Oracle
                                </button>
                            @endif

                            {{-- Paso 3: Despacho --}}
                            @if ($packingorder && $packingorder->STATUS === 'PROCESADO')
                                <button
                                    type="button"
                                    id="btnAccion"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: #fefce8; color: #854d0e; border: 1px solid #fef08a; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                    onmouseover="this.style.background='#fef9c3'; this.style.borderColor='#fde047';"
                                    onmouseout="this.style.background='#fefce8'; this.style.borderColor='#fef08a';"
                                    onclick="despacharPedido()"
                                >
                                    <i class="bi bi-box-arrow-right me-2"></i> Despachar Inventario
                                </button>
                            @endif

                            {{-- Paso 4: Generar Factura --}}
                            @if ($packingorder && $packingorder->STATUS === 'DESPACHADO')
                                <button
                                    type="button"
                                    id="btnAccion"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                    onmouseover="this.style.background='#f3e8ff'; this.style.borderColor='#d8b4fe';"
                                    onmouseout="this.style.background='#faf5ff'; this.style.borderColor='#e9d5ff';"
                                    onclick="generarFactura()"
                                >
                                    <i class="bi bi-receipt me-2"></i> Generar Factura
                                </button>
                            @endif

                            {{-- Error --}}
                            {{-- @if ($packingorder && $packingorder->MENSAJE_ERROR !== null)
                                <button
                                    type="button"
                                    id="btnAccion"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                    onmouseover="this.style.background='#fee2e2'; this.style.borderColor='#fca5a5';"
                                    onmouseout="this.style.background='#fef2f2'; this.style.borderColor='#fecaca';"
                                    onclick="reintentarPedido()"
                                >
                                    <i class="bi bi-arrow-repeat me-2"></i> Reintentar
                                </button>
                            @endif --}}
                        </div>
                    </div>
                </div>
                <!-- ============================================================ -->
                <!-- SECCIÓN 1: HEADER -->
                <!-- ============================================================ -->
                <div class="seccion-facturacion mb-4">
                    <div class="seccion-header">
                        <div class="d-flex align-items-center gap-2">
                            <div class="seccion-icono">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <span>Información del Pedido</span>
                        </div>
                        <span class="seccion-badge">{{ $packList }}</span>
                    </div>
                    <div class="seccion-body">
                        <div class="row g-3">
                            <!-- Fila 1: Datos generales -->
                            <div class="col-md-3">
                                <div class="info-row">
                                    <span class="info-titulo">PackList</span>
                                    <span
                                        class="info-dato"
                                        style="color: var(--btn-blue-text); font-weight: 600;"
                                    >{{ $packList }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-row">
                                    <span class="info-titulo">Cliente</span>
                                    <span class="info-dato">
                                        {{ $header->Destino }} - {{ $header->cliente }}
                                    </span>
                                    @if ($header->cliente ?? false)
                                        <small style="color: var(--text-muted); font-size: 0.72rem; display: block;">
                                            {{ $header->NOMBRE_CLIENTE }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <!-- Estatus de Interfaz -->
                            <div class="col-md-3 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo"><i class="bi bi-diagram-3 me-1"></i>Estatus de
                                        Interfaz</span>
                                    <div class="d-flex flex-column mt-1 gap-1">
                                        {{-- Tags de estado --}}
                                        <div class="d-flex align-items-center gap-2">
                                            @if (!$packingorder)
                                                {{-- Sin pedido --}}
                                                <span
                                                    class="tags-red"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    <i class="bi bi-x-circle me-1"></i>SIN PEDIDO
                                                </span>
                                            @elseif ($packingorder->STATUS === null && $packingorder->MENSAJE_ERROR === null)
                                                {{-- Disponible para enviar --}}
                                                <span
                                                    class="tags-green"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    {{ $packingorder->Source_Transaction_Identifier }}
                                                </span>
                                                <span
                                                    class="tags-yellow"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    SIN PROCESAR
                                                </span>
                                            @elseif ($packingorder->STATUS === 'PROCESADO')
                                                {{-- Enviado --}}
                                                <span
                                                    class="tags-green"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    {{ $packingorder->Source_Transaction_Identifier }}
                                                </span>
                                                <span
                                                    class="tags-green"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    <i class="bi bi-cloud-check me-1"></i>
                                                    PROCESADO
                                                </span>
                                            @elseif ($packingorder->MENSAJE_ERROR !== null)
                                                {{-- Error --}}
                                                <span
                                                    class="tags-red"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Error
                                                </span>
                                                <span
                                                    class="tags-red"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    {{ $packingorder->Source_Transaction_Identifier }}
                                                </span>
                                            @else
                                                {{-- Otro estado --}}
                                                <span
                                                    class="tags-yellow"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    <i class="bi bi-hourglass-split me-1"></i>Estado:
                                                    {{ $packingorder->STATUS ?? 'Desconocido' }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Información adicional debajo --}}
                                        @if ($packingorder)
                                            @if ($packingorder->Batch_Name)
                                                <small style="color: var(--text-muted);">
                                                    <i class="bi bi-box me-1"></i>Batch:
                                                    <span
                                                        style="font-weight: 500;">{{ $packingorder->Batch_Name }}</span>
                                                </small>
                                            @endif
                                            @if ($packingorder->MENSAJE_ERROR)
                                                <small
                                                    style="color: {{ $packingorder->STATUS === 'Error' ? 'var(--danger-color)' : 'var(--success-color)' }};"
                                                >
                                                    <i
                                                        class="bi bi-info-circle me-1"></i>{{ $packingorder->MENSAJE_ERROR }}
                                                </small>
                                            @endif
                                            @if (!$packingorder->Batch_Name && !$packingorder->MENSAJE_ERROR)
                                                <small style="color: var(--text-muted);">
                                                    Folio:
                                                    <strong>{{ $packingorder->Source_Transaction_Identifier }}</strong>
                                                </small>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Estatus de ORACLE -->
                            <div class="col-md-3 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo">
                                        <i class="bi bi-cloud me-1"></i>Estatus Oracle
                                    </span>
                                    <span class="info-dato">
                                        <span
                                            class="tags-yellow"
                                            style="font-size: 0.8rem;"
                                        >
                                            <i class="bi bi-hourglass-split me-1"></i>Pendiente
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <!-- Fila 2: IDs y configuración -->
                            <div class="col-md-3 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo">ID Cliente</span>
                                    <span class="info-dato">{{ $header->ID_CLIENTE ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo">Tipo Cliente</span>
                                    <span class="info-dato">{{ $header->TIPO_CLIENTE ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo">Términos</span>
                                    <span class="info-dato fw-semibold">{{ $header->TERMINOS ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo">Business Unit</span>
                                    <span class="info-dato">{{ $header->BusinessUnitName ?? '-' }}</span>
                                </div>
                            </div>

                            <!-- Fila 3: Direcciones -->
                            <div class="col-md-6 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo"><i class="bi bi-truck me-1"></i>SHIP_TO (Envío)</span>
                                    <span
                                        class="info-dato"
                                        style="font-family: monospace; font-size: 0.78rem;"
                                    >{{ $header->SHIP_TO ?? '-' }}</span>
                                    @if ($ship_to && $ship_to->direccion)
                                        <span class="info-direccion">{{ $ship_to->direccion }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mt-0">
                                <div class="info-row">
                                    <span class="info-titulo"><i class="bi bi-receipt me-1"></i>BILL_TO
                                        (Facturación)</span>
                                    <span
                                        class="info-dato"
                                        style="font-family: monospace; font-size: 0.78rem;"
                                    >{{ $header->BILL_TO ?? '-' }}</span>
                                    @if ($bill_to && $bill_to->direccion)
                                        <span class="info-direccion">{{ $bill_to->direccion }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form
                    action="/AutoservicioFacturacion/enviar"
                    method="POST"
                    id="formEnviar"
                >
                    <!-- ============================================================ -->
                    <!-- SECCIÓN 1: CONFIGURACIÓN DE FACTURACIÓN -->
                    <!-- ============================================================ -->
                    <div class="seccion-facturacion mb-4">
                        <div class="seccion-header">
                            <div class="d-flex align-items-center gap-2">
                                <div
                                    class="seccion-icono"
                                    style="background: var(--btn-amber-bg);"
                                >
                                    <i
                                        class="bi bi-gear"
                                        style="color: var(--btn-amber-text); font-size: 0.8rem;"
                                    ></i>
                                </div>
                                <span>Configuración de Facturación</span>
                            </div>
                            @if ($packingorder)
                                <span
                                    class="seccion-badge"
                                    style="background: var(--bg-subtle); color: var(--text-subtle); font-size: 0.7rem;"
                                >
                                    <i class="bi bi-lock me-1"></i>Solo lectura
                                </span>
                            @endif
                        </div>
                        <div class="seccion-body">
                            <div class="row g-3">
                                <!-- Uso CFDI -->
                                <div class="col-md-4">
                                    <label
                                        class="form-label fw-medium mb-2"
                                        style="color: var(--text-secondary); font-size: 0.8rem;"
                                    >
                                        <i class="bi bi-file-earmark-text me-1"></i>Uso CFDI
                                    </label>
                                    @if ($packingorder)
                                        {{-- Solo lectura --}}
                                        <div
                                            class="configurado-actual"
                                            style="margin-bottom: 0;"
                                        >
                                            <div class="configurado-body">
                                                <span
                                                    style="font-weight: 600; color: var(--text-primary);"
                                                    id="textoUsoCfdi"
                                                >
                                                    {{ $packingorder->UCFDI ?? '-' }}
                                                    {{ $cfdiNombre ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        <input
                                            type="hidden"
                                            name="uso_cfdi"
                                            value="{{ $packingorder->UCFDI }}"
                                        >
                                    @else
                                        {{-- Select editable --}}
                                        <select
                                            name="uso_cfdi"
                                            class="form-select @error('uso_cfdi') is-invalid @enderror"
                                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                        >
                                            <option value="">Seleccione...</option>
                                            @foreach ($uso_cfdi as $uso)
                                                <option
                                                    value="{{ $uso->FLEX_VALUE }}"
                                                    {{ old('uso_cfdi') == $uso->FLEX_VALUE ? 'selected' : '' }}
                                                >
                                                    {{ $uso->FLEX_VALUE }} - {{ $uso->DESCRIPTION }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('uso_cfdi')
                                            <div
                                                class="invalid-feedback"
                                                style="font-size: 0.8rem; color: #dc3545;"
                                            >
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    @endif
                                </div>

                                <!-- Método de Pago -->
                                <div class="col-md-4">
                                    <label
                                        class="form-label fw-medium mb-2"
                                        style="color: var(--text-secondary); font-size: 0.8rem;"
                                    >
                                        <i class="bi bi-credit-card me-1"></i>Método de Pago
                                    </label>
                                    @if ($packingorder)
                                        {{-- Solo lectura --}}
                                        <div
                                            class="configurado-actual"
                                            style="margin-bottom: 0;"
                                        >
                                            <div class="configurado-body">
                                                <span
                                                    style="font-weight: 600; color: var(--text-primary);"
                                                    id="textoMetodoPago"
                                                >
                                                    {{ $packingorder->METODO_PAGO ?? '-' }}
                                                    {{ $metodoNombre ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        <input
                                            type="hidden"
                                            name="metodo_pago"
                                            value="{{ $packingorder->METODO_PAGO }}"
                                        >
                                    @else
                                        {{-- Select editable --}}
                                        <select
                                            name="metodo_pago"
                                            class="form-select @error('metodo_pago') is-invalid @enderror"
                                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                        >
                                            <option value="">Seleccione...</option>
                                            @foreach ($metodo_pago as $metodo)
                                                <option
                                                    value="{{ $metodo->FLEX_VALUE }}"
                                                    {{ old('metodo_pago') == $metodo->FLEX_VALUE ? 'selected' : '' }}
                                                >
                                                    {{ $metodo->FLEX_VALUE }} - {{ $metodo->DESCRIPTION }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('metodo_pago')
                                            <div
                                                class="invalid-feedback"
                                                style="font-size: 0.8rem; color: #dc3545;"
                                            >
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    @endif
                                </div>

                                <!-- Forma de Pago -->
                                <div class="col-md-4">
                                    <label
                                        class="form-label fw-medium mb-2"
                                        style="color: var(--text-secondary); font-size: 0.8rem;"
                                    >
                                        <i class="bi bi-cash-coin me-1"></i>Forma de Pago
                                    </label>
                                    @if ($packingorder)
                                        {{-- Solo lectura --}}
                                        <div
                                            class="configurado-actual"
                                            style="margin-bottom: 0;"
                                        >
                                            <div class="configurado-body">
                                                <span
                                                    style="font-weight: 600; color: var(--text-primary);"
                                                    id="textoFormaPago"
                                                >
                                                    {{ $packingorder->FORMA_PAGO ?? '-' }}
                                                    {{ $formaNombre ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        <input
                                            type="hidden"
                                            name="forma_pago"
                                            value="{{ $packingorder->FORMA_PAGO }}"
                                        >
                                    @else
                                        {{-- Select editable --}}
                                        <select
                                            name="forma_pago"
                                            class="form-select @error('forma_pago') is-invalid @enderror"
                                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                        >
                                            <option value="">Seleccione...</option>
                                            @foreach ($forma_pago as $forma)
                                                <option
                                                    value="{{ $forma->FLEX_VALUE }}"
                                                    {{ old('forma_pago') == $forma->FLEX_VALUE ? 'selected' : '' }}
                                                >
                                                    {{ $forma->FLEX_VALUE }} - {{ $forma->DESCRIPTION }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('forma_pago')
                                            <div
                                                class="invalid-feedback"
                                                style="font-size: 0.8rem; color: #dc3545;"
                                            >
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- SECCIÓN 2: LÍNEAS -->
                    <!-- ============================================================ -->
                    <div class="seccion-facturacion mb-4">
                        <div class="seccion-header">
                            <div class="d-flex align-items-center gap-2">
                                <div class="seccion-icono">
                                    <i class="bi bi-list-ul"></i>
                                </div>
                                <span>Líneas del Pedido</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span
                                    class="seccion-badge"
                                    style="background: var(--bg-subtle); color: var(--text-subtle);"
                                >
                                    {{ count($lineas) }} líneas
                                </span>

                                @if (!$packingorder)
                                    <button
                                        type="button"
                                        id="btnAgregarLinea"
                                        {{-- class="btn btn-sm d-flex align-items-center btn-agregar-linea gap-1" --}}
                                        style="background: var(--btn-green-bg); color: var(--btn-green-text); border: 1px solid var(--btn-green-hover); border-radius: 8px; padding: 6px 12px; font-size: 0.8rem; display: none;"
                                        onclick="agregarFilaVacia()"
                                    >
                                        <i class="bi bi-plus-circle"></i> Agregar Línea
                                    </button>
                                    <button
                                        type="button"
                                        id="btnEditarTabla"
                                        class="btn btn-sm d-flex align-items-center gap-1"
                                        style="background: var(--btn-amber-bg); color: var(--btn-amber-text); border: 1px solid var(--btn-amber-hover); border-radius: 8px; padding: 6px 12px; font-size: 0.8rem;"
                                        onclick="toggleEdicion()"
                                    >
                                        <i class="bi bi-pencil"></i> <span id="btnEditarTexto">Editar</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="seccion-body p-0">

                            @csrf
                            <input
                                type="hidden"
                                name="packlist"
                                value="{{ $packList }}"
                            >
                            <input
                                type="hidden"
                                name="header_data"
                                value="{{ json_encode($header) }}"
                            >

                            @php
                                // Agrupar líneas del PackList por código
                                $lineasAgrupadas = [];
                                foreach ($lineas as $linea) {
                                    $codigo = $linea->CODIGO;
                                    if (!isset($lineasAgrupadas[$codigo])) {
                                        $lineasAgrupadas[$codigo] = [];
                                    }
                                    $lineasAgrupadas[$codigo][] = $linea;
                                }

                                // Indexar líneas de interfaz por código
                                // Indexar líneas de interfaz por código
                                $interfazPorCodigo = [];
                                if (isset($packingorderlines)) {
                                    foreach ($packingorderlines as $il) {
                                        $codigo = $il->Product_Number ?? ($il->PRODUCT_NUMBER ?? null);
                                        if ($codigo) {
                                            $interfazPorCodigo[$codigo] = $il;
                                        }
                                    }
                                }

                                $index = 0;
                            @endphp

                            <div class="table-responsive">
                                <table
                                    class="table-hover table-custom table"
                                    id="tablaLineas"
                                >
                                    <thead style="position: sticky; top: 0; z-index: 2;">
                                        <tr>
                                            <th style="width: 40px; border-radius: 0px;">#</th>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th class="text-start">UOM</th>
                                            <th class="text-end">Cantidad</th>
                                            <th class="text-end">Precio</th>
                                            <th class="text-end">Importe</th>
                                            @if ($packingorder)
                                                <th
                                                    class="text-center"
                                                    style="width: 100px; border-radius: 0px;"
                                                >Interfaz</th>
                                            @else
                                                <th
                                                    class="text-center"
                                                    style="width: 120px; border-radius: 0px;"
                                                >Tipo</th>
                                            @endif
                                            @if (!$packingorder)
                                                <th
                                                    class="text-center"
                                                    style="width: 60px; border-radius: 0px; display: none;"
                                                    id="colAccion"
                                                >Acción</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lineasAgrupadas as $codigo => $items)
                                            @php
                                                $esDuplicado = count($items) == 2;

                                                // Buscar la opción PIEZA y KG
                                                $lineaPieza = null;
                                                $lineaKg = null;
                                                foreach ($items as $item) {
                                                    if (
                                                        strtoupper($item->UOM) == 'PIEZA.' ||
                                                        strtoupper($item->UOM) == 'PIEZA'
                                                    ) {
                                                        $lineaPieza = $item;
                                                    } else {
                                                        $lineaKg = $item;
                                                    }
                                                }

                                                // Por defecto mostrar PIEZA si existe, si no, la única línea
                                                $lineaMostrar = $lineaPieza ?? $items[0];
                                                $tieneAlternativa = $esDuplicado && $lineaPieza && $lineaKg;

                                                $cantidad =
                                                    $lineaMostrar->UOM === 'PIEZA.'
                                                        ? $lineaMostrar->CANTCNV
                                                        : $lineaMostrar->CANTIDAD;
                                                $precio = $lineaMostrar->PRECIO ?? 0;
                                                $importe = $lineaMostrar->importe ?? $cantidad * $precio;
                                                $uom = $lineaMostrar->UOM == 'SIN UNIDAD' ? '-' : $lineaMostrar->UOM;

                                                // Buscar en interfaz
                                                $lineaInterfaz = $interfazPorCodigo[$codigo] ?? null;

                                                // Determinar estado de comparación
                                                $estadoInterfaz = '';
                                                $bgColor = '';
                                                if ($packingorder) {
                                                    if (!$lineaInterfaz) {
                                                        $estadoInterfaz = 'eliminada';
                                                        $bgColor = 'background: #fef2f2;';
                                                    } else {
                                                        $cantInterfaz = floatval(
                                                            $lineaInterfaz->Ordered_Quantity ??
                                                                ($lineaInterfaz->ORDERED_QUANTITY ?? 0),
                                                        );
                                                        $precioInterfaz = floatval(
                                                            $lineaInterfaz->ADJUSTMENT_AMOUNT ??
                                                                ($lineaInterfaz->ADJUSMET_AMOUNT ?? 0),
                                                        );
                                                        $uomInterfaz =
                                                            $lineaInterfaz->Ordered_UOM ??
                                                            ($lineaInterfaz->ORDERED_UOM ?? '');

                                                        if (
                                                            abs($cantidad - $cantInterfaz) > 0.001 ||
                                                            abs($precio - $precioInterfaz) > 0.001 ||
                                                            $uom != $uomInterfaz
                                                        ) {
                                                            $estadoInterfaz = 'modificada';
                                                            $bgColor = 'background: #fefce8;';
                                                        } else {
                                                            $estadoInterfaz = 'igual';
                                                        }
                                                    }
                                                }
                                            @endphp

                                            <tr
                                                class="linea-item"
                                                data-codigo="{{ $codigo }}"
                                                data-index="{{ $index }}"
                                                data-es-duplicado="{{ $tieneAlternativa ? '1' : '0' }}"
                                                data-uom-actual="{{ $uom }}"
                                                style="{{ $bgColor }}"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="lineas[{{ $index }}][CODIGO]"
                                                    value="{{ $codigo }}"
                                                >
                                                <td>{{ $index + 1 }}</td>
                                                <td style="font-weight: 500;">{{ $codigo }}</td>
                                                <td>
                                                    <span
                                                        class="producto-texto">{{ $lineaMostrar->NOMBREPROD ?: '-' }}</span>
                                                    @if (!$packingorder)
                                                        <input
                                                            type="text"
                                                            name="lineas[{{ $index }}][NOMBREPROD]"
                                                            class="form-control form-control-sm producto-input"
                                                            value="{{ $lineaMostrar->NOMBREPROD ?: '' }}"
                                                            style="display: none; font-size: 0.78rem;"
                                                        >
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if ($packingorder && $estadoInterfaz == 'modificada' && $uom != $uomInterfaz)
                                                        <span class="uom-texto">{{ $uom }}</span>
                                                        <br><small
                                                            style="color: var(--btn-blue-text); font-size: 0.65rem;"
                                                        >{{ $uomInterfaz }}</small>
                                                    @else
                                                        <span class="uom-texto">{{ $uom }}</span>
                                                    @endif
                                                    @if (!$packingorder)
                                                        <select
                                                            name="lineas[{{ $index }}][UOM]"
                                                            class="form-select form-select-sm uom-select"
                                                            style="display: none; font-size: 0.78rem; width: 110px; margin: 0 auto;"
                                                        >
                                                            <option
                                                                value="KILOGRAMO"
                                                                {{ strtoupper($uom) == 'KILOGRAMO' ? 'selected' : '' }}
                                                            >KILOGRAMO</option>
                                                            <option
                                                                value="PIEZA."
                                                                {{ strtoupper($uom) == 'PIEZA.' ? 'selected' : '' }}
                                                            >PIEZA.</option>
                                                        </select>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if ($packingorder && $estadoInterfaz == 'modificada' && abs($cantidad - $cantInterfaz) > 0.001)
                                                        <span>{{ number_format($cantidad, 2) }}</span>
                                                        <br><small
                                                            style="color: var(--btn-blue-text); font-size: 0.65rem;"
                                                        >{{ number_format($cantInterfaz, 2) }}</small>
                                                    @else
                                                        <span
                                                            class="cantidad-texto">{{ number_format($cantidad, 2) }}</span>
                                                    @endif
                                                    @if (!$packingorder)
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            name="lineas[{{ $index }}][CANTIDAD]"
                                                            class="form-control form-control-sm cantidad-input text-end"
                                                            value="{{ $cantidad }}"
                                                            style="display: none; width: 100px; margin-left: auto;"
                                                            onchange="recalcularImporte(this)"
                                                        >
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if ($packingorder && $estadoInterfaz == 'modificada' && abs($precio - $precioInterfaz) > 0.001)
                                                        <span>${{ number_format($precio, 2) }}</span>
                                                        <br><small
                                                            style="color: var(--btn-blue-text); font-size: 0.65rem;"
                                                        >${{ number_format($precioInterfaz, 2) }}</small>
                                                    @else
                                                        <span
                                                            class="precio-texto">${{ number_format($precio, 2) }}</span>
                                                    @endif
                                                    @if (!$packingorder)
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            name="lineas[{{ $index }}][PRECIO]"
                                                            class="form-control form-control-sm precio-input text-end"
                                                            value="{{ $precio }}"
                                                            style="display: none; width: 100px; margin-left: auto;"
                                                            onchange="recalcularImporte(this)"
                                                        >
                                                    @endif
                                                </td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 600;"
                                                >
                                                    @php
                                                        $importeInterfaz = isset($cantInterfaz, $precioInterfaz)
                                                            ? $cantInterfaz * $precioInterfaz
                                                            : 0;
                                                    @endphp
                                                    @if ($packingorder && $estadoInterfaz == 'modificada' && abs($importe - $importeInterfaz) > 0.01)
                                                        <span>${{ number_format($importe, 2) }}</span>
                                                        <br><small
                                                            style="color: var(--btn-blue-text); font-size: 0.65rem;"
                                                        >${{ number_format($importeInterfaz, 2) }}</small>
                                                    @else
                                                        <span
                                                            class="importe-texto">${{ number_format($importe, 2) }}</span>
                                                    @endif
                                                    <input
                                                        type="hidden"
                                                        name="lineas[{{ $index }}][IMPORTE]"
                                                        class="importe-hidden"
                                                        value="{{ $importe }}"
                                                    >
                                                </td>

                                                @if ($packingorder)
                                                    <td class="text-center">
                                                        @if ($estadoInterfaz == 'igual')
                                                            <span
                                                                class="tags-green"
                                                                style="font-size: 0.7rem;"
                                                            >
                                                                <i class="bi bi-check-circle"></i> Igual
                                                            </span>
                                                        @elseif ($estadoInterfaz == 'modificada')
                                                            <span
                                                                class="tags-yellow"
                                                                style="font-size: 0.7rem;"
                                                                title="Cantidad/Precio/UOM diferente en interfaz"
                                                            >
                                                                <i class="bi bi-pencil"></i> Modif.
                                                            </span>
                                                        @elseif ($estadoInterfaz == 'eliminada')
                                                            <span
                                                                class="tags-red"
                                                                style="font-size: 0.7rem;"
                                                                title="Esta línea está en el PackList pero no en la interfaz"
                                                            >
                                                                <i class="bi bi-trash"></i> Eliminada
                                                            </span>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        @if ($tieneAlternativa)
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-alternar"
                                                                style="background: var(--btn-blue-bg); color: var(--btn-blue-text); border: 1px solid var(--btn-blue-hover); border-radius: 6px; padding: 4px 8px; font-size: 0.7rem; white-space: nowrap;"
                                                                onclick="alternarTipo(this, '{{ $codigo }}')"
                                                            >
                                                                <i class="bi bi-arrow-repeat me-1"></i> Cambiar a KG
                                                            </button>
                                                        @else
                                                            <span
                                                                class="tags-green"
                                                                style="font-size: 0.7rem;"
                                                            >Directo</span>
                                                        @endif
                                                    </td>
                                                @endif
                                                @if (!$packingorder)
                                                    <td
                                                        class="text-center"
                                                        style="display: none;"
                                                        id="tdQuitar-{{ $index }}"
                                                    >
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-remover-linea"
                                                            style="background: var(--tag-red-bg); color: var(--tag-red-text); border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.7rem;"
                                                            onclick="quitarLinea(this)"
                                                            title="Quitar línea"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                            @php $index++; @endphp
                                        @endforeach
                                        {{-- Mostrar líneas que están en la interfaz pero NO en el PackList --}}
                                        @if ($packingorder && isset($packingorderlines))
                                            @foreach ($packingorderlines as $il)
                                                @php
                                                    $codigo = $il->Product_Number ?? ($il->PRODUCT_NUMBER ?? null);
                                                    if (!$codigo || isset($lineasAgrupadas[$codigo])) {
                                                        continue;
                                                    }
                                                    $cantidad = floatval(
                                                        $il->Ordered_Quantity ?? ($il->ORDERED_QUANTITY ?? 0),
                                                    );
                                                    $precio = floatval(
                                                        $il->ADJUSTMENT_AMOUNT ?? ($il->ADJUSMET_AMOUNT ?? 0),
                                                    );
                                                    $importe = $cantidad * $precio;
                                                    $uom = $il->Ordered_UOM ?? ($il->ORDERED_UOM ?? '-');
                                                @endphp
                                                <tr style="background: #f0fdf4;">
                                                    <td>-</td>
                                                    <td style="font-weight: 500;">{{ $codigo }}</td>
                                                    <td><span class="producto-texto">-</span></td>
                                                    <td class="text-start"><span
                                                            class="uom-texto">{{ $uom }}</span></td>
                                                    <td class="text-end"><span
                                                            class="cantidad-texto">{{ number_format($cantidad, 2) }}</span>
                                                    </td>
                                                    <td class="text-end"><span
                                                            class="precio-texto">${{ number_format($precio, 2) }}</span>
                                                    </td>
                                                    <td
                                                        class="text-end"
                                                        style="font-weight: 600;"
                                                    ><span
                                                            class="importe-texto">${{ number_format($importe, 2) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="tags-green"
                                                            style="font-size: 0.7rem;"
                                                            title="Esta línea ya no está en el PackList"
                                                        >
                                                            <i class="bi bi-plus"></i> Agregada
                                                        </span>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        {{-- Fila de Totales --}}
                                        @php
                                            $totalCantidad = 0;
                                            $totalImporte = 0;

                                            if ($packingorder && isset($packingorderlines)) {
                                                // Sumar de las líneas de interfaz
                                                foreach ($packingorderlines as $il) {
                                                    $cantidad = floatval(
                                                        $il->Ordered_Quantity ?? ($il->ORDERED_QUANTITY ?? 0),
                                                    );
                                                    $precio = floatval(
                                                        $il->ADJUSTMENT_AMOUNT ?? ($il->ADJUSMET_AMOUNT ?? 0),
                                                    );
                                                    $totalCantidad += $cantidad;
                                                    $totalImporte += $cantidad * $precio;
                                                }
                                            } else {
                                                // Sumar de las líneas del PackList
                                                foreach ($lineasAgrupadas as $codigo => $items) {
                                                    $lineaPieza = null;
                                                    $lineaKg = null;
                                                    foreach ($items as $item) {
                                                        if (
                                                            strtoupper($item->UOM) == 'PIEZA.' ||
                                                            strtoupper($item->UOM) == 'PIEZA'
                                                        ) {
                                                            $lineaPieza = $item;
                                                        } else {
                                                            $lineaKg = $item;
                                                        }
                                                    }
                                                    $lineaMostrar = $lineaPieza ?? $items[0];
                                                    $cantidad =
                                                        $lineaMostrar->UOM === 'PIEZA.'
                                                            ? $lineaMostrar->CANTCNV
                                                            : $lineaMostrar->CANTIDAD;
                                                    $precio = $lineaMostrar->PRECIO ?? 0;
                                                    $totalCantidad += $cantidad;
                                                    $totalImporte += $lineaMostrar->importe ?? $cantidad * $precio;
                                                }
                                            }

                                            // Calcular colspan dinámico
                                            $colspan = 3; // #, Código, Producto, UOM
                                            if ($packingorder) {
                                                $colspan++;
                                            } // Interfaz
                                            if (!$packingorder) {
                                                $colspan++;
                                            } // Acción
                                        @endphp
                                        <tr
                                            class="bg-table-totals"
                                            style="font-weight: 700;"
                                        >
                                            <td
                                                colspan="{{ $colspan }}"
                                                class="text-end"
                                                style="color: var(--text-primary);"
                                            >TOTALES:</td>
                                            <td
                                                class="text-end"
                                                id="totalCantidad"
                                                style="color: var(--text-primary);"
                                            >{{ number_format($totalCantidad, 2) }}</td>
                                            <td></td>
                                            <td
                                                class="text-end"
                                                id="totalImporte"
                                                style="color: var(--success-color);"
                                            >${{ number_format($totalImporte, 2) }}</td>
                                            @if ($packingorder)
                                                <td></td>
                                            @endif
                                            @if (!$packingorder)
                                                <td></td>
                                            @endif
                                            <td
                                                id="columnaFinal"
                                                style="display: none;"
                                            ></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- Estado vacío -->
            <div class="p-5 text-center">
                <i
                    class="bi bi-search"
                    style="font-size: 3rem; color: var(--text-muted);"
                ></i>
                <h5
                    class="mt-3"
                    style="color: var(--text-primary);"
                >Buscar PackList</h5>
                <p style="color: var(--text-muted);">Ingrese el código del PackList para consultar y enviar a
                    facturación</p>
            </div>
        @endif
    </x-card-gradient-header>

    <style>
        /* ============================================================ */
        /* SECCIÓN FACTURACIÓN */
        /* ============================================================ */
        .seccion-facturacion {
            border: 1px solid var(--border-input);
            border-radius: 12px;
            overflow: hidden;
            background: var(--card-bg);
        }

        .seccion-header {
            background: var(--bg-subtle);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-light);
        }

        .seccion-header span {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .seccion-icono {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--btn-blue-bg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .seccion-icono i {
            color: var(--btn-blue-text);
            font-size: 0.8rem;
        }

        .seccion-badge {
            background: var(--btn-blue-bg);
            color: var(--btn-blue-text);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .seccion-body {
            padding: 16px;
        }

        /* Dirección en info-row */
        .info-direccion {
            display: block;
            color: var(--text-secondary);
            font-size: 0.72rem;
            margin-top: 3px;
            line-height: 1.3;
            word-break: break-word;
        }

        /* Info rows */
        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-titulo {
            display: block;
            color: var(--text-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-dato {
            color: var(--text-primary);
            font-size: 0.82rem;
            font-weight: 500;
        }

        .btn-agregar-linea {
            display: none;
        }
    </style>

    <script>
        // ============================================================
        // ALTERNAR ENTRE KG Y PIEZA
        // ============================================================
        // Datos originales de las líneas (para alternar)
        const lineasOriginales = @json($lineas);

        function alternarTipo(btn, codigo) {
            const fila = btn.closest('tr');
            const uomActual = fila.dataset.uomActual;
            const index = fila.dataset.index;

            // Buscar las dos líneas originales para este código
            const lineasCodigo = lineasOriginales.filter(l => l.CODIGO == codigo);

            let nuevaLinea;
            if (uomActual === 'PIEZA.' || uomActual === 'PIEZA') {
                // Cambiar a KG
                nuevaLinea = lineasCodigo.find(l => l.UOM !== 'PIEZA.' && l.UOM !== 'PIEZA');
                btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Cambiar a PIEZA';
                fila.dataset.uomActual = nuevaLinea.UOM;
            } else {
                // Cambiar a PIEZA
                nuevaLinea = lineasCodigo.find(l => l.UOM === 'PIEZA.' || l.UOM === 'PIEZA');
                btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Cambiar a KG';
                fila.dataset.uomActual = nuevaLinea.UOM;
            }

            if (nuevaLinea) {
                const cantidad = nuevaLinea.UOM === 'PIEZA.' ? nuevaLinea.CANTCNV : nuevaLinea.CANTIDAD;
                const precio = nuevaLinea.PRECIO ?? 0;
                const importe = nuevaLinea.importe ?? (cantidad * precio);

                // Actualizar valores visibles
                fila.querySelector('.producto-texto').textContent = nuevaLinea.NOMBREPROD || 'Sin nombre';
                fila.querySelector('.uom-texto').textContent = nuevaLinea.UOM || '-';
                fila.querySelector('.cantidad-texto').textContent = Number(cantidad).toFixed(2);
                fila.querySelector('.precio-texto').textContent = '$' + Number(precio).toFixed(2);
                fila.querySelector('.importe-texto').textContent = '$' + Number(importe).toFixed(2);

                // Actualizar inputs ocultos
                const productoInput = fila.querySelector('.producto-input');
                const uomSelect = fila.querySelector('.uom-select');
                const cantidadInput = fila.querySelector('.cantidad-input');
                const precioInput = fila.querySelector('.precio-input');
                const importeHidden = fila.querySelector('.importe-hidden');

                if (productoInput) productoInput.value = nuevaLinea.NOMBREPROD || '';
                if (uomSelect) uomSelect.value = nuevaLinea.UOM;
                if (cantidadInput) cantidadInput.value = cantidad;
                if (precioInput) precioInput.value = precio;
                if (importeHidden) importeHidden.value = importe;
            }
            recalcularTotales();
        }

        // ============================================================
        // EDITAR TABLA
        // ============================================================
        let modoEdicion = false;

        function toggleEdicion() {
            modoEdicion = !modoEdicion;
            const btnTexto = document.getElementById('btnEditarTexto');
            const btnEditar = document.getElementById('btnEditarTabla');
            const btnAgregar = document.getElementById('btnAgregarLinea');
            const colAccion = document.getElementById('colAccion');
            const colEnd = document.getElementById('columnaFinal');

            // Mostrar/ocultar inputs
            document.querySelectorAll('.producto-texto, .uom-texto, .cantidad-texto, .precio-texto').forEach(el => {
                el.style.display = modoEdicion ? 'none' : '';
            });
            document.querySelectorAll('.producto-input, .uom-select, .cantidad-input, .precio-input').forEach(el => {
                el.style.display = modoEdicion ? '' : 'none';
            });

            // Mostrar/ocultar columna de acciones y botones de quitar
            if (colAccion) colAccion.style.display = modoEdicion ? '' : 'none';
            document.querySelectorAll('[id^="tdQuitar-"]').forEach(el => {
                el.style.display = modoEdicion ? '' : 'none';
            });

            // Mostrar/ocultar botón de Agregar
            if (btnAgregar) btnAgregar.style.display = modoEdicion ? '' : 'none';

            // Mostrar/ocultar botón de Agregar
            if (colEnd) colEnd.style.display = modoEdicion ? '' : 'none';

            if (modoEdicion) {
                btnTexto.textContent = 'Vista previa';
                btnEditar.style.background = 'var(--btn-green-bg)';
                btnEditar.style.color = 'var(--btn-green-text)';
                btnEditar.style.borderColor = 'var(--btn-green-hover)';
            } else {
                btnTexto.textContent = 'Editar';
                btnEditar.style.background = 'var(--btn-amber-bg)';
                btnEditar.style.color = 'var(--btn-amber-text)';
                btnEditar.style.borderColor = 'var(--btn-amber-hover)';
            }
        }

        // RECALCULAR IMPORTE
        function recalcularImporte(input) {
            const fila = input.closest('tr');
            const cantidad = parseFloat(fila.querySelector('.cantidad-input')?.value || fila.querySelector(
                '.cantidad-texto')?.textContent) || 0;
            const precio = parseFloat(fila.querySelector('.precio-input')?.value || fila.querySelector('.precio-texto')
                ?.textContent.replace('$', '')) || 0;
            const importe = cantidad * precio;

            const importeTexto = fila.querySelector('.importe-texto');
            const importeHidden = fila.querySelector('.importe-hidden');

            if (importeTexto) importeTexto.textContent = '$' + importe.toFixed(2);
            if (importeHidden) importeHidden.value = importe.toFixed(2);
            recalcularTotales();
        }

        // QUITAR LÍNEA
        function quitarLinea(btn) {
            const fila = btn.closest('tr');
            fila.style.display = 'none';
            fila.querySelectorAll('input, select').forEach(el => el.disabled = true);
            // Marcar como eliminada
            fila.classList.add('linea-eliminada');
            recalcularTotales();
        }

        // ============================================================
        // AGREGAR FILA VACÍA (CON INPUTS)
        // ============================================================
        function agregarFilaVacia() {
            const tbody = document.querySelector('#tablaLineas tbody');
            const filaTotales = tbody.querySelector('.bg-table-totals');
            const numFilas = tbody.querySelectorAll('.linea-item:not(.linea-eliminada)').length;

            const tr = document.createElement('tr');
            tr.className = 'linea-item linea-agregada';
            tr.style.background = '#f0fdf4';

            tr.innerHTML = `
                <td>${numFilas + 1}</td>
                <td>
                    <input type="text" class="form-control form-control-sm producto-codigo-input"
                        placeholder="Código" style="font-size: 0.75rem; width: 100px;">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm producto-nombre-input"
                        placeholder="Nombre del producto" style="font-size: 0.75rem;">
                </td>
                <td class="text-center">
                    <select class="form-select form-select-sm producto-uom-select" style="font-size: 0.75rem; width: 110px; margin: 0 auto;">
                        <option value="KILOGRAMO">KILOGRAMO</option>
                        <option value="PIEZA.">PIEZA.</option>
                    </select>
                </td>
                <td class="text-end">
                    <input type="number" step="0.01" class="form-control form-control-sm text-end producto-cantidad-input"
                        value="0" style="font-size: 0.75rem; width: 100px; margin-left: auto;"
                        onchange="recalcularImporteFila(this)">
                </td>
                <td class="text-end">
                    <input type="number" step="0.01" class="form-control form-control-sm text-end producto-precio-input"
                        value="0" style="font-size: 0.75rem; width: 100px; margin-left: auto;"
                        onchange="recalcularImporteFila(this)">
                </td>
                <td class="text-end" style="font-weight: 600;">
                    <span class="producto-importe-texto">$0.00</span>
                </td>
                <td class="text-center">
                    <span class="tags-green" style="font-size: 0.7rem;">Agregada</span>
                </td>
                <td id="tdQuitar-${numFilas + 1}" class="text-center">
                    <button type="button" class="btn btn-sm btn-remover-linea"
                        style="background: var(--tag-red-bg); color: var(--tag-red-text); border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.7rem;"
                        onclick="quitarLinea(this)" title="Quitar línea">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

            // Insertar antes de la fila de totales
            tbody.insertBefore(tr, filaTotales);

            // Re-numerar filas
            renumerarFilas();
            recalcularTotales();
        }

        // RENUMERAR FILAS
        function renumerarFilas() {
            const filas = document.querySelectorAll('#tablaLineas tbody tr.linea-item:not(.linea-eliminada)');
            filas.forEach((fila, i) => {
                fila.querySelector('td:first-child').textContent = i + 1;
            });
        }

        // RECALCULAR IMPORTE DE FILA AGREGADA
        function recalcularImporteFila(input) {
            const fila = input.closest('tr');
            const cantidad = parseFloat(fila.querySelector('.producto-cantidad-input')?.value) || 0;
            const precio = parseFloat(fila.querySelector('.producto-precio-input')?.value) || 0;
            const importe = cantidad * precio;

            const importeTexto = fila.querySelector('.producto-importe-texto');
            if (importeTexto) importeTexto.textContent = '$' + importe.toFixed(2);

            recalcularTotales();
        }

        // RECALCULAR TOTALES
        function recalcularTotales() {
            const filas = document.querySelectorAll('#tablaLineas tbody tr.linea-item:not(.linea-eliminada)');
            let totalCantidad = 0;
            let totalImporte = 0;

            filas.forEach(fila => {
                if (fila.style.display === 'none') return;

                let cantidad = 0;
                let importe = 0;

                // Intentar obtener de inputs de línea agregada
                const cantidadInputAgregada = fila.querySelector('.producto-cantidad-input');
                const precioInputAgregada = fila.querySelector('.producto-precio-input');
                const importeTextoAgregada = fila.querySelector('.producto-importe-texto');

                if (cantidadInputAgregada) {
                    cantidad = parseFloat(cantidadInputAgregada.value) || 0;
                    if (importeTextoAgregada) {
                        importe = parseFloat(importeTextoAgregada.textContent.replace('$', '').replace(/,/g, '')) ||
                            0;
                    }
                } else {
                    // Intentar obtener de inputs normales
                    const cantidadInput = fila.querySelector('.cantidad-input');
                    const cantidadTexto = fila.querySelector('.cantidad-texto');
                    if (cantidadInput && cantidadInput.style.display !== 'none') {
                        cantidad = parseFloat(cantidadInput.value) || 0;
                    } else if (cantidadTexto) {
                        cantidad = parseFloat(cantidadTexto.textContent.replace(/,/g, '')) || 0;
                    } else {
                        const celdas = fila.querySelectorAll('td');
                        if (celdas.length >= 5) {
                            cantidad = parseFloat(celdas[4].textContent.trim().replace(/,/g, '')) || 0;
                        }
                    }

                    const importeTexto = fila.querySelector('.importe-texto');
                    const importeHidden = fila.querySelector('.importe-hidden');
                    if (importeHidden) {
                        importe = parseFloat(importeHidden.value) || 0;
                    } else if (importeTexto) {
                        importe = parseFloat(importeTexto.textContent.replace('$', '').replace(/,/g, '')) || 0;
                    } else {
                        const celdas = fila.querySelectorAll('td');
                        if (celdas.length >= 7) {
                            importe = parseFloat(celdas[6].textContent.trim().replace('$', '').replace(/,/g, '')) ||
                                0;
                        }
                    }
                }

                totalCantidad += cantidad;
                totalImporte += importe;
            });

            const totalCantidadEl = document.getElementById('totalCantidad');
            const totalImporteEl = document.getElementById('totalImporte');

            if (totalCantidadEl) totalCantidadEl.textContent = totalCantidad.toFixed(2);
            if (totalImporteEl) totalImporteEl.textContent = '$' + totalImporte.toFixed(2);
        }

        // ============================================================
        // BOTONES PARA EL MANEJO DEL PEDIDO (GENERAR PEDIDO, ENVIAR, DESPACHAR Y GENERAR FACTURA)
        // ============================================================
        // GENERAR PEDIDO (VALIDAR + ENVIAR FORM)
        const btnGenerarPedido = document.getElementById('btnGenerarPedido');
        if (btnGenerarPedido) {
            btnGenerarPedido.addEventListener('click', function() {
                // Agregar líneas nuevas como inputs hidden
                const lineasAgregadas = document.querySelectorAll('.linea-agregada:not(.linea-eliminada)');
                let idx = document.querySelectorAll('.linea-item:not(.linea-eliminada)').length;
                lineasAgregadas.forEach(fila => {
                    let codigo, producto, uom, cantidad, precio, importe;

                    // Intentar obtener de inputs directos (nuevas líneas con inputs)
                    const codigoInput = fila.querySelector('.producto-codigo-input');
                    const productoInput = fila.querySelector('.producto-nombre-input');
                    const uomSelect = fila.querySelector('.producto-uom-select');
                    const cantidadInput = fila.querySelector('.producto-cantidad-input');
                    const precioInput = fila.querySelector('.producto-precio-input');
                    const importeTexto = fila.querySelector('.producto-importe-texto');

                    if (codigoInput) {
                        // Es una línea agregada con inputs directos
                        codigo = codigoInput.value.trim();
                        producto = productoInput?.value.trim() || '';
                        uom = uomSelect?.value || '';
                        cantidad = parseFloat(cantidadInput?.value) || 0;
                        precio = parseFloat(precioInput?.value) || 0;
                        importe = parseFloat(importeTexto?.textContent.replace('$', '').replace(/,/g,
                            '')) || (cantidad * precio);
                    } else {
                        // Es una línea agregada con texto en celdas
                        const celdas = fila.querySelectorAll('td');
                        codigo = celdas[1]?.textContent.trim() || '';
                        producto = celdas[2]?.textContent.trim() || '';
                        uom = celdas[3]?.textContent.trim() || '';
                        cantidad = parseFloat(celdas[4]?.textContent.trim().replace(/,/g, '')) || 0;
                        precio = parseFloat(celdas[5]?.textContent.trim().replace('$', '').replace(/,/g,
                            '')) || 0;
                        importe = parseFloat(celdas[6]?.textContent.trim().replace('$', '').replace(/,/g,
                            '')) || (cantidad * precio);
                    }

                    const form = document.getElementById('formEnviar');

                    const camposValores = {
                        'CODIGO': codigo,
                        'NOMBREPROD': producto,
                        'UOM': uom,
                        'CANTIDAD': cantidad,
                        'PRECIO': precio,
                        'IMPORTE': importe
                    };

                    Object.entries(camposValores).forEach(([campo, valor]) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `lineas[${idx}][${campo}]`;
                        input.value = valor;
                        form.appendChild(input);
                    });

                    idx++;
                });

                // Deshabilitar líneas eliminadas para que no se envíen
                document.querySelectorAll('.linea-eliminada input, .linea-eliminada select').forEach(el => {
                    el.disabled = true;
                });

                const btn = this;
                const form = document.getElementById('formEnviar');

                let errores = [];

                // 1. Validar configuración de facturación
                const usoCfdi = document.querySelector('select[name="uso_cfdi"]')?.value;
                const metodoPago = document.querySelector('select[name="metodo_pago"]')?.value;
                const formaPago = document.querySelector('select[name="forma_pago"]')?.value;

                if (!usoCfdi || usoCfdi === '') {
                    errores.push('Debe seleccionar el Uso CFDI');
                }
                if (!metodoPago || metodoPago === '') {
                    errores.push('Debe seleccionar el Método de Pago');
                }
                if (!formaPago || formaPago === '') {
                    errores.push('Debe seleccionar la Forma de Pago');
                }

                // 2. Validar líneas
                const filas = document.querySelectorAll('#tablaLineas tbody tr.linea-item:not(.linea-eliminada)');
                let lineaNumero = 0;
                let lineasValidas = 0;

                filas.forEach((fila, index) => {
                    // Saltar filas ocultas
                    if (fila.style.display === 'none') return;

                    lineaNumero = index + 1;

                    // Obtener código (dataset o celda)
                    let codigo = fila.dataset.codigo;
                    if (!codigo) {
                        const celdaCodigo = fila.querySelector('td:nth-child(2)');
                        codigo = celdaCodigo ? celdaCodigo.textContent.trim() : '';
                    }

                    const productoInput = fila.querySelector('.producto-input');
                    const uomSelect = fila.querySelector('.uom-select');
                    const cantidadInput = fila.querySelector('.cantidad-input');
                    const precioInput = fila.querySelector('.precio-input');

                    const productoTexto = fila.querySelector('.producto-texto');
                    const uomTexto = fila.querySelector('.uom-texto');
                    const cantidadTexto = fila.querySelector('.cantidad-texto');
                    const precioTexto = fila.querySelector('.precio-texto');

                    const enEdicion = productoInput && productoInput.style.display !== 'none';

                    let producto, uom, cantidad, precio;

                    if (enEdicion) {
                        producto = productoInput?.value?.trim() || '';
                        uom = uomSelect?.value || '';
                        cantidad = parseFloat(cantidadInput?.value) || 0;
                        precio = parseFloat(precioInput?.value) || 0;
                    } else {
                        producto = productoTexto?.textContent?.trim() || '';
                        uom = uomTexto?.textContent?.trim() || '';
                        cantidad = parseFloat(cantidadTexto?.textContent?.replace(/,/g, '')) || 0;
                        precio = parseFloat(precioTexto?.textContent?.replace('$', '').replace(/,/g, '')) ||
                            0;
                    }

                    // Para líneas agregadas con inputs directos
                    if (!producto) {
                        const inputProducto = fila.querySelector('.producto-nombre-input');
                        if (inputProducto) {
                            producto = inputProducto.value.trim();
                            codigo = fila.querySelector('.producto-codigo-input')?.value.trim() || codigo;
                            uom = fila.querySelector('.producto-uom-select')?.value || uom;
                            cantidad = parseFloat(fila.querySelector('.producto-cantidad-input')?.value) ||
                                cantidad;
                            precio = parseFloat(fila.querySelector('.producto-precio-input')?.value) ||
                                precio;
                        }
                    }
                    if (!uom && !enEdicion) {
                        const celdaUom = fila.querySelector('td:nth-child(4)');
                        uom = celdaUom ? celdaUom.textContent.trim() : '';
                    }
                    if (cantidad === 0 && !enEdicion) {
                        const celdaCantidad = fila.querySelector('td:nth-child(5)');
                        cantidad = celdaCantidad ? parseFloat(celdaCantidad.textContent.trim().replace(/,/g,
                            '')) || 0 : 0;
                    }
                    if (precio === 0 && !enEdicion) {
                        const celdaPrecio = fila.querySelector('td:nth-child(6)');
                        precio = celdaPrecio ? parseFloat(celdaPrecio.textContent.trim().replace('$', '')
                            .replace(/,/g, '')) || 0 : 0;
                    }

                    if (!codigo || codigo === '') {
                        errores.push(`Línea ${lineaNumero}: El código del artículo está vacío`);
                    }
                    if (!producto || producto === '' || producto === 'Sin nombre') {
                        errores.push(
                            `Línea ${lineaNumero} (${codigo || '?'}): El nombre del producto está vacío`
                        );
                    }
                    if (!uom || uom === '' || uom === '-') {
                        errores.push(
                            `Línea ${lineaNumero} (${codigo || '?'}): Debe seleccionar la unidad de medida (UOM)`
                        );
                    }
                    if (!cantidad || cantidad <= 0) {
                        errores.push(
                            `Línea ${lineaNumero} (${codigo || '?'}): La cantidad debe ser mayor a 0`);
                    }
                    if (!precio || precio <= 0) {
                        errores.push(
                            `Línea ${lineaNumero} (${codigo || '?'}): El precio debe ser mayor a 0`);
                    }

                    if (codigo && producto && uom && cantidad > 0 && precio > 0) {
                        lineasValidas++;
                    }
                });

                if (lineasValidas === 0) {
                    errores.push('No hay líneas válidas para enviar');
                }

                // 3. Si hay errores, mostrar toast
                if (errores.length > 0) {
                    mostrarToastError(errores);
                    return;
                }

                // 4. Mostrar spinner y enviar
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Generando...';
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'none';

                form.submit();
            });
        }

        // BOTÓN 2: ENVIAR PEDIDO A ORACLE
        function enviarPedido() {
            const btn = document.getElementById('btnAccion');
            if (!btn) return;

            const folio = '{{ $packingorder->Source_Transaction_Identifier ?? '' }}';

            if (!folio) {
                mostrarToastError(['No se encontró el folio del pedido']);
                return;
            }

            btn.disabled = true;
            const origHTML = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Enviando...';
            btn.style.opacity = '1';

            // Llamar a la API de Oracle
            fetch(`https://oracleorderrest.kowi.com.mx/api/SalesOrder/PostSales?OrdenVta=${folio}&Origen=AUT`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.ok) {
                        const orderNumber = result.dato?.orderNumber || '';
                        const mensaje = result.dato?.messageText || result.message || 'Pedido enviado correctamente';
                        mostrarToast(`${mensaje} ${orderNumber ? '| Orden: ' + orderNumber : ''}`, 'success');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        const errorMsg = result.message || 'Error desconocido';
                        mostrarToastError(['Error al enviar: ' + errorMsg]);
                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                    }
                })
                .catch(error => {
                    mostrarToastError(['Error de conexión: ' + error.message]);
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                });
        }

        // BOTÓN 3: DESPACHAR INVENTARIO
        function despacharPedido() {
            const btn = document.getElementById('btnAccion');
            if (!btn) return;

            const pedido = '{{ $packingorder->Source_Transaction_Identifier ?? '' }}';

            if (!pedido) {
                mostrarToastError(['No se encontró el número de pedido']);
                return;
            }

            btn.disabled = true;
            const origHTML = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Despachando...';
            btn.style.opacity = '1';

            fetch(`https://oracledespachorest.kowi.com.mx/api/PickWave/Despacho?Orden=${pedido}`)
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        mostrarToast('Inventario despachado correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostrarToastError(['Error al despachar: ' + (data.message || 'Error desconocido')]);
                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                    }
                })
                .catch(error => {
                    mostrarToastError(['Error de conexión: ' + error.message]);
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                });
        }

        // BOTÓN 4: GENERAR FACTURA
        function generarFactura() {
            const btn = document.getElementById('btnAccion');
            if (!btn) return;

            const pedido = '{{ $packingorder->Source_Transaction_Identifier ?? '' }}';

            if (!pedido) {
                mostrarToastError(['No se encontró el número de pedido']);
                return;
            }

            btn.disabled = true;
            const origHTML = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Generando factura...';
            btn.style.opacity = '1';

            fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/Factura?Orden=${pedido}`, {
                    method: 'POST'
                })
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        mostrarToast('Factura generada correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostrarToastError(['Error al generar factura: ' + (data.message || 'Error desconocido')]);
                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                    }
                })
                .catch(error => {
                    mostrarToastError(['Error de conexión: ' + error.message]);
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                });
        }

        // ============================================================
        // MOSTRAR TOAST
        // ============================================================
        // MOSTRAR TOAST DE ÉXITO
        function mostrarToast(mensaje, tipo = 'success') {
            const config = {
                success: {
                    clase: 'toast-success',
                    icono: 'bi-check-circle-fill',
                    titulo: '¡Éxito!'
                },
                warning: {
                    clase: 'toast-warning',
                    icono: 'bi-exclamation-triangle-fill',
                    titulo: 'Atención'
                },
                danger: {
                    clase: 'toast-danger',
                    icono: 'bi-exclamation-triangle-fill',
                    titulo: 'Error'
                }
            };
            const cfg = config[tipo] || config.success;

            const toastHTML = `
                <div class="toast-alert ${cfg.clase} show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999;">
                    <div class="toast-icon"><i class="bi ${cfg.icono}"></i></div>
                    <div class="toast-content"><strong>${cfg.titulo}</strong><span>${mensaje}</span></div>
                    <button type="button" class="toast-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                </div>`;

            document.body.insertAdjacentHTML('beforeend', toastHTML);

            setTimeout(() => {
                const toast = document.querySelector('.toast-alert.show');
                if (toast) toast.remove();
            }, 5000);
        }

        // MOSTRAR TOAST DE ERROR
        function mostrarToastError(errores) {
            // Eliminar toast existente
            const toastExistente = document.querySelector('.toast-validacion');
            if (toastExistente) toastExistente.remove();

            // Crear lista de errores
            let listaErrores = '';
            errores.forEach(error => {
                listaErrores += `<li>${error}</li>`;
            });

            // Crear toast
            const toast = document.createElement('div');
            toast.className = 'toast-alert toast-danger show toast-validacion';
            toast.style.position = 'fixed';
            toast.style.top = '80px';
            toast.style.right = '20px';
            toast.style.zIndex = '9999';
            toast.style.maxHeight = '80vh';
            toast.style.overflowY = 'auto';
            toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="toast-content">
                        <strong>Errores de validación (${errores.length})</strong>
                        <ul class="mb-0 ps-3" style="font-size: 0.78rem; margin-top: 4px;">
                            ${listaErrores}
                        </ul>
                    </div>
                    <button type="button" class="toast-close" onclick="this.parentElement.remove()">
                        <i class="bi bi-x"></i>
                    </button>
                `;

            document.body.appendChild(toast);

            // Auto-eliminar después de 8 segundos
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(120%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    if (toast.parentElement) toast.remove();
                }, 300);
            }, 8000);
        }
    </script>
</x-page-container>
