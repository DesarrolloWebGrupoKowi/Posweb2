    <x-page-container title="Devoluciones">
        <x-card-gradient-header
            icon="arrow-return-left"
            title="Devoluciones"
            subtitle="Gestión de devoluciones y notas de crédito"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros -->
            <x-form.form action="/Devoluciones">
                <x-form.group>
                    <x-form.text
                        name="folio"
                        label="Folio"
                        icon="receipt"
                        placeholder="Buscar por folio..."
                        col="col-md-3"
                        :autofocus="true"
                    />
                    <x-form.text
                        name="orden"
                        label="Orden Venta"
                        icon="clipboard-check"
                        placeholder="Buscar por orden..."
                        col="col-md-3"
                    />
                    <x-form.select
                        name="estatus"
                        label="Estatus"
                        icon="toggle-on"
                        col="col-md-2"
                        :options="[
                            '' => 'Todos',
                            'PENDIENTE' => 'Pendiente',
                            'PROCESADO' => 'Procesado',
                            'ERROR' => 'Error',
                        ]"
                    />
                </x-form.group>
                <div class="col-md-3 d-flex gap-2">
                    <x-form.submit
                        text="Buscar"
                        icon="funnel"
                        class="flex-grow-1"
                    />
                    <x-form.clear />
                </div>
            </x-form.form>

            <!-- Contenido dinámico según si hay folio seleccionado -->
            <div>
                @if ($devolucionSeleccionada)
                    <!-- ========== MODO DETALLE: Se seleccionó un folio ========== -->
                    @php
                        $total = $lineas->sum(function ($linea) {
                            return $linea->Ordered_Quantity * $linea->AdjustmentAmount;
                        });
                        $status = $devolucionSeleccionada->STATUS;
                    @endphp

                    <!-- Botones de Acción -->
                    <div class="row mb-4 px-4 pt-4">
                        <div class="d-flex justify-content-between">
                            <!-- Botón para regresar a la lista -->
                            <a
                                href="/Devoluciones?orden={{ request('orden') }}"
                                class="btn d-flex align-items-center"
                                style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 6px 16px; font-size: 0.85rem;"
                            >
                                <i class="bi bi-arrow-left me-1"></i> Regresar a lista
                            </a>
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                <!-- {{ Auth::user()->EmployeeName }} -->
                                <!-- Botón 1: Crear Pedido Devolución -->
                                @if (is_null($devolucionSeleccionada->STATUS) || strtoupper($devolucionSeleccionada->STATUS) === 'ERROR')
                                    <button
                                        type="button"
                                        class="btn btn-devolucion-action"
                                        id="btnCrearPedido"
                                        data-folio="{{ $devolucionSeleccionada->Source_Transaction_Identifier }}"
                                        data-action="crearPedido"
                                        style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                        onmouseover="this.style.background='#dbeafe'; this.style.borderColor='#93c5fd';"
                                        onmouseout="this.style.background='#eff6ff'; this.style.borderColor='#bfdbfe';"
                                    >
                                        <i class="bi bi-cart-plus me-2"></i>
                                        <span class="btn-text">Enviar Pedido Devolución</span>
                                        <span
                                            class="spinner-border spinner-border-sm d-none ms-2"
                                            role="status"
                                            style="width: 1rem; height: 1rem;"
                                        ></span>
                                    </button>
                                @endif

                                <!-- Botón 2: Crear Recepción de Devolución -->
                                @if (strtoupper($devolucionSeleccionada->STATUS) === 'PROCESADO' &&
                                        $devolucionSeleccionada->ReceiptNumber === null &&
                                        $estatusAgrupado == 'Awaiting Receiving')
                                    <button
                                        type="button"
                                        class="btn btn-devolucion-action"
                                        id="btnCrearRecepcion"
                                        data-folio="{{ $devolucionSeleccionada->Source_Transaction_Identifier }}"
                                        data-employe-name="{{ Auth::user()->EmployeeName }}"
                                        data-action="crearRecepcion"
                                        style="background: #fefce8; color: #854d0e; border: 1px solid #fef08a; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                        onmouseover="this.style.background='#fef9c3'; this.style.borderColor='#fde047';"
                                        onmouseout="this.style.background='#fefce8'; this.style.borderColor='#fef08a';"
                                    >
                                        <i class="bi bi-box-arrow-in-down me-2"></i>
                                        <span class="btn-text">Crear Recepción Devolución</span>
                                        <span
                                            class="spinner-border spinner-border-sm d-none ms-2"
                                            role="status"
                                            style="width: 1rem; height: 1rem;"
                                        ></span>
                                    </button>
                                @endif

                                <!-- Botón 3: Confirmar Recepción -->
                                @if (strtoupper($devolucionSeleccionada->STATUS) === 'PROCESADO' &&
                                        $devolucionSeleccionada->ReceiptNumber !== null &&
                                        $estatusAgrupado == 'Awaiting Receiving')
                                    <button
                                        type="button"
                                        class="btn btn-devolucion-action"
                                        id="btnConfirmarRecepcion"
                                        data-folio="{{ $devolucionSeleccionada->Source_Transaction_Identifier }}"
                                        data-action="confirmarRecepcion"
                                        style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                        onmouseover="this.style.background='#dcfce7'; this.style.borderColor='#86efac';"
                                        onmouseout="this.style.background='#f0fdf4'; this.style.borderColor='#bbf7d0';"
                                    >
                                        <i class="bi bi-check-circle me-2"></i>
                                        <span class="btn-text">Confirmar Recepción</span>
                                        <span
                                            class="spinner-border spinner-border-sm d-none ms-2"
                                            role="status"
                                            style="width: 1rem; height: 1rem;"
                                        ></span>
                                    </button>
                                @endif

                                <!-- Botón 4: Crear Factura Devolución -->
                                @if (strtoupper($devolucionSeleccionada->STATUS) === 'PROCESADO' &&
                                        $devolucionSeleccionada->ReceiptNumber !== null &&
                                        $estatusAgrupado == 'Awaiting Billing')
                                    <button
                                        type="button"
                                        class="btn btn-devolucion-action"
                                        id="btnCrearFactura"
                                        data-folio="{{ $devolucionSeleccionada->Source_Transaction_Identifier }}"
                                        data-action="crearFactura"
                                        style="background: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                                        onmouseover="this.style.background='#f3e8ff'; this.style.borderColor='#d8b4fe';"
                                        onmouseout="this.style.background='#faf5ff'; this.style.borderColor='#e9d5ff';"
                                    >
                                        <i class="bi bi-receipt me-2"></i>
                                        <span class="btn-text">Crear Factura Devolución</span>
                                        <span
                                            class="spinner-border spinner-border-sm d-none ms-2"
                                            role="status"
                                            style="width: 1rem; height: 1rem;"
                                        ></span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Información General - Ancho completo -->
                    <div class="col-12 px-4">
                        <div
                            class="mb-4 rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <h5
                                class="mb-4"
                                style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                            >
                                <i
                                    class="bi bi-info-circle me-2"
                                    style="color: #3b82f6;"
                                ></i>Información General de la Devolución
                            </h5>

                            <div class="row g-4">
                                <!-- 3 Columnas con los 8 campos -->
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Folio / Transacción Origen
                                        </label>
                                        <p style="font-weight: 500; color: #0f172a; margin-bottom: 0;">
                                            #{{ $devolucionSeleccionada->Source_Transaction_Identifier }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Orden Venta
                                        </label>
                                        <p style="color: #334155; font-size: 0.9rem; font-weight: 500; margin-bottom: 0;">
                                            {{ $devolucionSeleccionada->OrdenVenta ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Cliente
                                        </label>
                                        <p style="color: #334155; font-size: 0.9rem; font-weight: 500; margin-bottom: 0;">
                                            @if ($devolucionSeleccionada->Buying_Party_Number)
                                                <span
                                                    class="d-block text-muted"
                                                    style="font-size: 0.8rem;"
                                                >[{{ $devolucionSeleccionada->Buying_Party_Number }}]</span>
                                            @endif
                                            {{ $devolucionSeleccionada->Buying_Party_Name ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Fecha de Creación
                                        </label>
                                        <p
                                            class="mb-0"
                                            style="color: #475569; font-size: 0.9rem;"
                                        >
                                            <i
                                                class="bi bi-clock me-1"
                                                style="color: #94a3b8; font-size: 0.8rem;"
                                            ></i>
                                            <span style="font-weight: 500;">
                                                {{ $devolucionSeleccionada->Created_at ? \Carbon\Carbon::parse($devolucionSeleccionada->Created_at)->format('d/m/Y H:i') : '-' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Unidad de Negocio
                                        </label>
                                        <p
                                            class="mb-0"
                                            style="color: #475569; font-size: 0.9rem; font-weight: 500;"
                                        >
                                            {{ $devolucionSeleccionada->Requesting_Business_Unit ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Tipo de Orden
                                        </label>
                                        <p
                                            class="mb-0"
                                            style="color: #475569; font-size: 0.9rem; font-weight: 500;"
                                        >
                                            {{ $devolucionSeleccionada->ORDER_TYPE ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2 mt-0">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Almacén
                                        </label>
                                        <p
                                            class="mb-0"
                                            style="color: #334155; font-size: 0.9rem; font-weight: 500;"
                                        >
                                            {{ $devolucionSeleccionada->RequestedFulfillmentOrganizationName ?? '-' }}
                                            @if ($devolucionSeleccionada->RequestedFulfillmentOrganizationCode)
                                                <span
                                                    class="text-muted"
                                                    style="font-size: 0.8rem;"
                                                >({{ $devolucionSeleccionada->RequestedFulfillmentOrganizationCode }})</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-2 mt-0">
                                    <div class="mb-3">
                                        <label
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                        >
                                            Moneda / Total
                                        </label>
                                        <div class="d-flex align-items-center gap-2">
                                            <span
                                                class="badge bg-light text-dark border"
                                                style="font-weight: 500; font-size: 0.8rem; padding: 4px 10px;"
                                            >
                                                {{ $devolucionSeleccionada->Transactional_Currency_Code ?? '-' }}
                                            </span>
                                            <span
                                                class="fw-semibold"
                                                style="color: #dc2626; font-size: 1rem;"
                                            >
                                                @php
                                                    $total = $lineas->sum(function ($linea) {
                                                        return $linea->Ordered_Quantity * $linea->AdjustmentAmount;
                                                    });
                                                @endphp
                                                -${{ number_format(abs($total), 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 1 y PASO 2 lado a lado - Formato tipo dirección -->
                    <div class="px-4 pb-4">
                        <div class="row g-4">
                            <!-- PASO 1: Estado de la Transacción -->
                            <div class="col-md-6">
                                <div
                                    class="h-100 rounded p-4 shadow-sm"
                                    style="background: white; border-radius: 12px;"
                                >
                                    <h5
                                        class="mb-3"
                                        style="font-weight: 600; color: #0f172a; font-size: 0.95rem;"
                                    >
                                        <i
                                            class="bi bi-arrow-repeat me-2"
                                            style="color: #3b82f6;"
                                        ></i>Estado de la Transacción
                                    </h5>

                                    @php
                                        $hasTransaccion =
                                            $devolucionSeleccionada->Transaction_On || $devolucionSeleccionada->STATUS;
                                        $status = $devolucionSeleccionada->STATUS;
                                    @endphp

                                    @if ($hasTransaccion)
                                        <div class="d-flex align-items-start gap-3">
                                            <div
                                                class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width: 36px; height: 36px; background: #eff6ff;"
                                            >
                                                <i
                                                    class="bi bi-calendar-check"
                                                    style="color: #3b82f6; font-size: 1rem;"
                                                ></i>
                                            </div>
                                            <div>
                                                <p
                                                    class="mb-1"
                                                    style="color: #475569; font-size: 0.9rem;"
                                                >
                                                    @if ($devolucionSeleccionada->Transaction_On)
                                                        <span
                                                            style="color: #0f172a; font-weight: 500;">{{ $devolucionSeleccionada->Transaction_On }}</span>
                                                    @else
                                                        <span style="color: #94a3b8;">Pendiente</span>
                                                    @endif

                                                    <span style="color: #cbd5e1;"> · </span>

                                                    @if ($status === 'PROCESADO')
                                                        <span style="color: #475569;">
                                                            <i
                                                                class="bi bi-check-circle me-1"
                                                                style="color: #10b981;"
                                                            ></i>Procesado
                                                        </span>
                                                    @elseif ($status === 'ERROR')
                                                        <span style="color: #475569;">
                                                            <i
                                                                class="bi bi-x-circle me-1"
                                                                style="color: #ef4444;"
                                                            ></i>Error
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8;">
                                                            <i
                                                                class="bi bi-hourglass-split me-1"
                                                                style="color: #94a3b8;"
                                                            ></i>Pendiente
                                                        </span>
                                                    @endif

                                                    <span style="color: #cbd5e1;"> · </span>

                                                    @if ($devolucionSeleccionada->MENSAJE_ERROR)
                                                        <span style="color: #475569;">
                                                            <i
                                                                class="bi bi-chat-dots me-1"
                                                                style="color: #64748b;"
                                                            ></i>{{ $devolucionSeleccionada->MENSAJE_ERROR }}
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8;">
                                                            <i
                                                                class="bi bi-dash me-1"
                                                                style="color: #94a3b8;"
                                                            ></i>Sin mensaje
                                                        </span>
                                                    @endif
                                                </p>
                                                <span
                                                    class="badge"
                                                    style="background: #f1f5f9; color: #64748b; font-weight: 400; font-size: 0.65rem; padding: 2px 8px; border-radius: 4px;"
                                                >
                                                    <i
                                                        class="bi bi-clock me-1"></i>{{ $devolucionSeleccionada->Created_at ? \Carbon\Carbon::parse($devolucionSeleccionada->Created_at)->format('d/m/Y H:i') : '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <!-- EMPTY STATE: Sin transacción -->
                                        <div class="py-0 text-center">
                                            <div
                                                class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                style="width: 48px; height: 48px; background: #f8fafc;"
                                            >
                                                <i
                                                    class="bi bi-clock-history"
                                                    style="font-size: 1.25rem; color: #94a3b8;"
                                                ></i>
                                            </div>
                                            <p
                                                class="mb-0"
                                                style="color: #94a3b8; font-size: 0.85rem; font-weight: 500;"
                                            >
                                                Sin transacción registrada
                                            </p>
                                            <p style="color: #cbd5e1; font-size: 0.75rem; margin-bottom: 0;">
                                                La devolución aún no ha sido procesada
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- PASO 2: Recepción de Devolución -->
                            <div class="col-md-6">
                                <div
                                    class="h-100 rounded p-4 shadow-sm"
                                    style="background: white; border-radius: 12px;"
                                >
                                    <h5
                                        class="mb-3"
                                        style="font-weight: 600; color: #0f172a; font-size: 0.95rem;"
                                    >
                                        <i
                                            class="bi bi-box-arrow-in-down me-2"
                                            style="color: #eab308;"
                                        ></i>Recepción de Devolución
                                    </h5>

                                    @php
                                        $hasRecepcion =
                                            $devolucionSeleccionada->ReceiptNumber ||
                                            $devolucionSeleccionada->ReceiptHeaderId ||
                                            $devolucionSeleccionada->STATUS_RECEIPT;
                                        $statusReceipt = $devolucionSeleccionada->STATUS_RECEIPT;
                                    @endphp

                                    @if ($hasRecepcion)
                                        <div class="d-flex align-items-start gap-3">
                                            <div
                                                class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width: 36px; height: 36px; background: #fefce8;"
                                            >
                                                <i
                                                    class="bi bi-receipt"
                                                    style="color: #eab308; font-size: 1rem;"
                                                ></i>
                                            </div>
                                            <div>
                                                <p
                                                    class="mb-1"
                                                    style="color: #475569; font-size: 0.9rem;"
                                                >
                                                    @if ($devolucionSeleccionada->ReceiptNumber)
                                                        <span
                                                            style="color: #0f172a; font-weight: 500;">{{ $devolucionSeleccionada->ReceiptNumber }}</span>
                                                    @else
                                                        <span style="color: #94a3b8;">Pendiente</span>
                                                    @endif

                                                    <span style="color: #cbd5e1;"> · </span>

                                                    @if ($devolucionSeleccionada->ReceiptHeaderId)
                                                        <span
                                                            class="font-monospace"
                                                            style="color: #475569; font-size: 0.85rem;"
                                                        >ID: {{ $devolucionSeleccionada->ReceiptHeaderId }}</span>
                                                    @else
                                                        <span style="color: #94a3b8;">ID pendiente</span>
                                                    @endif

                                                    <span style="color: #cbd5e1;"> · </span>

                                                    @if ($statusReceipt === 'PROCESADO')
                                                        <span style="color: #475569;">
                                                            <i
                                                                class="bi bi-check-circle me-1"
                                                                style="color: #10b981;"
                                                            ></i>Procesado
                                                        </span>
                                                    @elseif ($statusReceipt === 'ERROR')
                                                        <span style="color: #475569;">
                                                            <i
                                                                class="bi bi-x-circle me-1"
                                                                style="color: #ef4444;"
                                                            ></i>Error
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8;">
                                                            <i
                                                                class="bi bi-hourglass-split me-1"
                                                                style="color: #94a3b8;"
                                                            ></i>Pendiente
                                                        </span>
                                                    @endif

                                                    <span style="color: #cbd5e1;"> · </span>

                                                    @if ($devolucionSeleccionada->MENSAJE_RECEIPT)
                                                        <span style="color: #475569;">
                                                            <i
                                                                class="bi bi-chat-dots me-1"
                                                                style="color: #64748b;"
                                                            ></i>{{ $devolucionSeleccionada->MENSAJE_RECEIPT }}
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8;">
                                                            <i
                                                                class="bi bi-dash me-1"
                                                                style="color: #94a3b8;"
                                                            ></i>Sin mensaje
                                                        </span>
                                                    @endif
                                                </p>
                                                @if ($devolucionSeleccionada->ReceiptNumber)
                                                    <span
                                                        class="badge"
                                                        style="background: #f1f5f9; color: #64748b; font-weight: 400; font-size: 0.65rem; padding: 2px 8px; border-radius: 4px;"
                                                    >
                                                        <i
                                                            class="bi bi-check-circle me-1"
                                                            style="color: #10b981;"
                                                        ></i>Recepción creada
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge"
                                                        style="background: #f1f5f9; color: #94a3b8; font-weight: 400; font-size: 0.65rem; padding: 2px 8px; border-radius: 4px;"
                                                    >
                                                        <i class="bi bi-clock me-1"></i>Esperando recepción
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- EMPTY STATE: Sin recepción -->
                                        <div class="py-0 text-center">
                                            <div
                                                class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                style="width: 48px; height: 48px; background: #f8fafc;"
                                            >
                                                <i
                                                    class="bi bi-box-seam"
                                                    style="font-size: 1.25rem; color: #94a3b8;"
                                                ></i>
                                            </div>
                                            <p
                                                class="mb-0"
                                                style="color: #94a3b8; font-size: 0.85rem; font-weight: 500;"
                                            >
                                                Sin recepción registrada
                                            </p>
                                            <p style="color: #cbd5e1; font-size: 0.75rem; margin-bottom: 0;">
                                                La recepción de la devolución aún no se ha creado
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de líneas -->
                    <!-- SECCIÓN: Tabla de Líneas de Devolución -->
                    <div class="px-4 pb-4">
                        <div
                            class="rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5
                                    class="mb-0"
                                    style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                                >
                                    <i
                                        class="bi bi-list-ul me-2"
                                        style="color: #ef4444;"
                                    ></i>Líneas de la Devolución
                                    <span style="color: #64748b; font-weight: 500;">({{ $lineas->count() }})</span>
                                </h5>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($estatusOracle)
                                        <span
                                            class="badge"
                                            style="background: #fef2f2; color: #dc2626; font-weight: 500; font-size: 0.7rem; padding: 4px 8px; border-radius: 4px;"
                                        >
                                            <i class="bi bi-database-fill me-1"></i>Oracle sincronizado
                                        </span>
                                    @else
                                        <span
                                            class="badge"
                                            style="background: #f8fafc; color: #94a3b8; font-weight: 500; font-size: 0.7rem; padding: 4px 8px; border-radius: 4px;"
                                        >
                                            <i class="bi bi-database me-1"></i>Sin datos Oracle
                                        </span>
                                    @endif

                                    <div class="d-flex align-items-center">
                                        <span
                                            style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; margin-right: 8px;"
                                        >Total</span>
                                        <span
                                            class="fw-bold"
                                            style="color: #0f172a; font-size: 1.15rem; letter-spacing: -0.2px;"
                                        >
                                            @php
                                                $total = $lineas->sum(function ($linea) {
                                                    return $linea->Ordered_Quantity * $linea->AdjustmentAmount;
                                                });
                                            @endphp
                                            -${{ number_format(abs($total), 2) }}
                                        </span>
                                        <span
                                            class="badge ms-2"
                                            style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.65rem; padding: 2px 6px; border-radius: 4px;"
                                        >
                                            {{ $devolucionSeleccionada->Transactional_Currency_Code ?? 'MXN' }}
                                        </span>
                                    </div>

                                    <!-- Botón Actualizar -->
                                    <button
                                        type="button"
                                        id="btnActualizarDetalle"
                                        class="btn btn-sm d-flex align-items-center gap-2"
                                        style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: 0.8rem; transition: all 0.2s;"
                                        onmouseover="this.style.background='#dbeafe'; this.style.borderColor='#93c5fd';"
                                        onmouseout="this.style.background='#eff6ff'; this.style.borderColor='#bfdbfe';"
                                        title="Actualizar información de la devolución"
                                    >
                                        <i class="bi bi-arrow-clockwise"></i>
                                        <span class="btn-text">Actualizar</span>
                                        <span
                                            class="spinner-border spinner-border-sm d-none"
                                            role="status"
                                            style="width: 0.8rem; height: 0.8rem;"
                                        ></span>
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table-hover table-custom table">
                                    <thead style="position: sticky; top: 0; z-index: 2;">
                                        <tr>
                                            <th style="width: 50px;"><i class="bi bi-hash me-1"></i>#</th>
                                            <th><i class="bi bi-upc-scan me-1"></i>Producto</th>
                                            <th class="text-end"><i class="bi bi-123 me-1"></i>Cantidad</th>
                                            <th style="width: 70px;"><i class="bi bi-rulers me-1"></i>UOM</th>
                                            <th class="text-end"><i class="bi bi-cash me-1"></i>Precio Unit.</th>
                                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Subtotal</th>
                                            <th><i class="bi bi-geo-alt me-1"></i>Almacén</th>
                                            <th><i class="bi bi-receipt me-1"></i>Orden Orig.</th>
                                            <th style="width: 50px;"><i class="bi bi-hash me-1"></i>Línea Orig.</th>
                                            @if ($estatusOracle)
                                                <th class="text-center"><i class="bi bi-oracle me-1"></i>Estatus Oracle
                                                </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($lineas as $index => $linea)
                                            @php
                                                $subtotal = $linea->Ordered_Quantity * $linea->AdjustmentAmount;

                                                // Buscar estatus Oracle correspondiente a esta línea
                                                $estatusOracleLinea = null;
                                                if ($estatusOracle && isset($estatusOracle['lines'])) {
                                                    foreach ($estatusOracle['lines'] as $lineaOracle) {
                                                        if ($lineaOracle['productNumber'] == $linea->Product_Number) {
                                                            $estatusOracleLinea = $lineaOracle;
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span
                                                        class="badge"
                                                        style="background: #f1f5f9; color: #64748b; font-weight: 600; font-size: 0.75rem; padding: 2px 8px; border-radius: 4px;"
                                                    >
                                                        {{ $index + 1 }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-semibold"
                                                        style="color: #0f172a; font-size: 0.85rem;"
                                                    >
                                                        {{ $linea->Product_Number ?? '-' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="text-end"
                                                    style="color: #475569; font-weight: 500; font-size: 0.85rem;"
                                                >
                                                    {{ is_numeric($linea->Ordered_Quantity) ? number_format((float) $linea->Ordered_Quantity, 3) : $linea->Ordered_Quantity }}
                                                </td>
                                                <td>
                                                    @if ($linea->Ordered_UOM)
                                                        <span
                                                            class="badge"
                                                            style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.75rem; padding: 3px 8px; border-radius: 4px;"
                                                        >
                                                            {{ $linea->Ordered_UOM }}
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8;">-</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="text-end"
                                                    style="color: #475569; font-weight: 500; font-size: 0.85rem;"
                                                >
                                                    ${{ number_format(abs((float) $linea->AdjustmentAmount), 2) }}
                                                </td>
                                                <td
                                                    class="fw-semibold text-end"
                                                    style="color: #dc2626; font-size: 0.85rem;"
                                                >
                                                    -${{ number_format(abs($subtotal), 2) }}
                                                </td>
                                                <td>
                                                    @if ($linea->Almacen)
                                                        <span
                                                            class="badge"
                                                            style="background: #fefce8; color: #854d0e; font-weight: 500; font-size: 0.7rem; padding: 3px 8px; border-radius: 4px;"
                                                        >
                                                            <i class="bi bi-building me-1"></i>{{ $linea->Almacen }}
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8;">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($linea->OriginalSourceOrderNumber)
                                                        <span
                                                            class="font-monospace"
                                                            style="color: #475569; font-size: 0.8rem; font-weight: 500;"
                                                        >
                                                            {{ $linea->OriginalSourceOrderNumber }}
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8;">-</span>
                                                    @endif
                                                </td>
                                                <td style="color: #64748b; font-size: 0.8rem;">
                                                    {{ $linea->OriginalSourceLineNumber ?? '-' }}
                                                </td>

                                                @if ($estatusOracle)
                                                    <td class="text-center">
                                                        @if ($estatusOracleLinea)
                                                            @php
                                                                $statusOracle = $estatusOracleLinea['status'] ?? 'N/A';
                                                                $statusLower = strtolower($statusOracle);
                                                            @endphp

                                                            @if (str_contains($statusLower, 'awaiting receiving'))
                                                                <span
                                                                    class="badge"
                                                                    style="background: #eff6ff; color: #1e40af; font-weight: 500; font-size: 0.7rem; padding: 3px 8px; border-radius: 4px;"
                                                                >
                                                                    <i class="bi bi-hourglass-split me-1"></i>Awaiting
                                                                    Receiving
                                                                </span>
                                                            @elseif (str_contains($statusLower, 'awaiting billing'))
                                                                <span
                                                                    class="badge"
                                                                    style="background: #fefce8; color: #854d0e; font-weight: 500; font-size: 0.7rem; padding: 3px 8px; border-radius: 4px;"
                                                                >
                                                                    <i class="bi bi-clock-fill me-1"></i>Awaiting Billing
                                                                </span>
                                                            @elseif ($statusLower === 'closed')
                                                                <span
                                                                    class="badge"
                                                                    style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.7rem; padding: 3px 8px; border-radius: 4px;"
                                                                >
                                                                    <i class="bi bi-check-circle-fill me-1"></i>Closed
                                                                </span>
                                                            @elseif ($statusLower === 'open')
                                                                <span
                                                                    class="badge"
                                                                    style="background: #f0fdf4; color: #166534; font-weight: 500; font-size: 0.7rem; padding: 3px 8px; border-radius: 4px;"
                                                                >
                                                                    <i class="bi bi-folder2-open me-1"></i>Open
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="badge"
                                                                    style="background: #f8fafc; color: #475569; font-weight: 500; font-size: 0.7rem; padding: 3px 8px; border-radius: 4px;"
                                                                >
                                                                    {{ $statusOracle }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span style="color: #94a3b8;">-</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    colspan="{{ $estatusOracle ? '11' : '10' }}"
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
                                                    >Sin líneas</h6>
                                                    <p
                                                        class="text-muted mb-0"
                                                        style="font-size: 0.85rem;"
                                                    >Esta devolución no tiene líneas registradas</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($lineas->count() > 0)
                                        <tfoot>
                                            <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                                                <td
                                                    colspan="{{ $estatusOracle ? '11' : '10' }}"
                                                    class="py-2"
                                                >
                                                    <div class="d-flex justify-content-end align-items-center gap-3">
                                                        <span style="color: #64748b; font-size: 0.8rem; font-weight: 500;">
                                                            Total de líneas: <strong
                                                                style="color: #0f172a;">{{ $lineas->count() }}</strong>
                                                        </span>
                                                        <span style="color: #cbd5e1;">|</span>
                                                        <span
                                                            style="color: #64748b; font-size: 0.8rem; font-weight: 500;">Total:</span>
                                                        <span style="color: #dc2626; font-size: 1.1rem; font-weight: 700;">
                                                            @php
                                                                $total = $lineas->sum(function ($linea) {
                                                                    return $linea->Ordered_Quantity *
                                                                        $linea->AdjustmentAmount;
                                                                });
                                                            @endphp
                                                            -${{ number_format(abs($total), 2) }}
                                                        </span>
                                                        <span
                                                            class="badge"
                                                            style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.65rem; padding: 2px 8px; border-radius: 4px;"
                                                        >
                                                            {{ $devolucionSeleccionada->Transactional_Currency_Code ?? 'MXN' }}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- ========== MODO LISTA: No hay folio seleccionado ========== -->
                    <div
                        class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                        <div>
                            <h5 class="section-content-title">
                                <i
                                    class="bi bi-table me-2"
                                    style="color: #64748b;"
                                ></i>Concentrado de Devoluciones
                            </h5>
                            <p class="section-content-subtitle">Listado de devoluciones registradas en el sistema</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table-hover table-custom table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-receipt me-1"></i>Folio</th>
                                    <th><i class="bi bi-clipboard-check me-1"></i>Orden Venta</th>
                                    <th><i class="bi bi-person me-1"></i>Cliente</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                                    <th><i class="bi bi-calendar-check me-1"></i>Transacción</th>
                                    <th><i class="bi bi-receipt-cutoff me-1"></i>Receipt Number</th>
                                    <th><i class="bi bi-hash me-1"></i>Receipt ID</th>
                                    <th><i class="bi bi-box-seam me-1"></i>Batch</th>
                                    <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Total</th>
                                    <th><i class="bi bi-currency-dollar me-1"></i>Moneda</th>
                                    <th><i
                                            class="bi bi-circle-fill me-1"
                                            style="font-size: 0.5rem;"
                                        ></i>Estatus</th>
                                    <th><i class="bi bi-exclamation-triangle me-1"></i>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($devoluciones as $devolucion)
                                    @php $status = $devolucion->STATUS; @endphp
                                    <tr
                                        style="cursor: pointer;"
                                        onclick="window.location.href='/Devoluciones?folio={{ urlencode($devolucion->Source_Transaction_Identifier) }}&orden={{ urlencode(request('orden')) }}'"
                                        title="{{ $devolucion->MENSAJE_ERROR ? 'Error: ' . $devolucion->MENSAJE_ERROR : 'Ver detalle' }}"
                                    >
                                        <td style="font-weight: 600; color: #0f172a;">
                                            {{ $devolucion->Source_Transaction_Identifier }}
                                        </td>
                                        <td>{{ $devolucion->OrdenVenta }}</td>
                                        <td>{{ $devolucion->Buying_Party_Name }}</td>
                                        <td style="color: #64748b;">
                                            {{ $devolucion->Created_at ? \Carbon\Carbon::parse($devolucion->Created_at)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td style="color: #64748b;">
                                            {{ $devolucion->Transaction_On ? $devolucion->Transaction_On : '-' }}
                                        </td>
                                        <td>
                                            @if ($devolucion->ReceiptNumber)
                                                <span
                                                    class="fw-semibold"
                                                    style="color: #0f172a;"
                                                >{{ $devolucion->ReceiptNumber }}</span>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($devolucion->ReceiptHeaderId)
                                                <span
                                                    class="font-monospace"
                                                    style="color: #475569; font-size: 0.85rem;"
                                                >{{ $devolucion->ReceiptHeaderId }}</span>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($devolucion->Batch_Name)
                                                <span
                                                    class="badge"
                                                    style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;"
                                                >
                                                    {{ $devolucion->Batch_Name }}
                                                </span>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 500; color: #dc2626;"
                                        >
                                            @php
                                                $total = $devolucion->lineas->sum(function ($linea) {
                                                    return $linea->Ordered_Quantity * $linea->AdjustmentAmount;
                                                });
                                            @endphp
                                            -${{ number_format(abs($total), 2) }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge"
                                                style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px;"
                                            >
                                                {{ $devolucion->Transactional_Currency_Code }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($status === 'PROCESADO')
                                                <span class="badge-status badge-active">
                                                    <i class="bi bi-check-circle me-1"></i>Procesado
                                                </span>
                                            @elseif ($status === 'ERROR')
                                                <span class="badge-status badge-inactive">
                                                    <i class="bi bi-x-circle me-1"></i>Error
                                                </span>
                                            @else
                                                <span class="badge-status badge-pending">
                                                    <i class="bi bi-hourglass-split me-1"></i>Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($devolucion->MENSAJE_ERROR && $status === 'ERROR')
                                                <span
                                                    class="d-inline-flex align-items-center gap-1"
                                                    style="background: #fef2f2; color: #b91c1c; font-size: 0.8rem; padding: 3px 8px; border-radius: 6px; cursor: help;"
                                                    title="{{ $devolucion->MENSAJE_ERROR }}"
                                                >
                                                    <i
                                                        class="bi bi-exclamation-triangle-fill"
                                                        style="font-size: 0.7rem;"
                                                    ></i>
                                                    <span
                                                        class="d-none d-xl-inline text-truncate"
                                                        style="max-width: 150px;"
                                                    >
                                                        {{ Str::limit($devolucion->MENSAJE_ERROR, 30) }}
                                                    </span>
                                                </span>
                                            @elseif ($devolucion->MENSAJE_ERROR && $status === 'PROCESADO')
                                                <span
                                                    class="d-inline-flex align-items-center gap-1"
                                                    style="background: #f0fdf4; color: #15803d; font-size: 0.8rem; padding: 3px 8px; border-radius: 6px; cursor: help;"
                                                    title="{{ $devolucion->MENSAJE_ERROR }}"
                                                >
                                                    <i
                                                        class="bi bi-check-circle-fill"
                                                        style="font-size: 0.7rem;"
                                                    ></i>
                                                    <span
                                                        class="d-none d-xl-inline text-truncate"
                                                        style="max-width: 150px;"
                                                    >
                                                        {{ Str::limit($devolucion->MENSAJE_ERROR, 30) }}
                                                    </span>
                                                </span>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="12"
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
                                            >Sin devoluciones</h6>
                                            <p
                                                class="text-muted mb-0"
                                                style="font-size: 0.85rem;"
                                            >No se encontraron devoluciones con los filtros seleccionados</p>
                                            <a
                                                href="/Devoluciones"
                                                class="btn btn-sm mt-3"
                                                style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 6px 16px; font-size: 0.8rem;"
                                            >
                                                <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('components.paginate', ['items' => $devoluciones])
                @endif
            </div>

            <!-- Modal de Confirmación Genérico -->
            <div
                class="modal fade"
                id="modalConfirmarAccion"
                tabindex="-1"
            >
                <div class="modal-dialog modal-dialog-centered">
                    <div
                        class="modal-content"
                        style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
                    >
                        <div class="p-4 text-center">
                            <!-- Icono dinámico -->
                            <div
                                id="confirmIconContainer"
                                class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 64px; height: 64px;"
                            >
                            </div>

                            <!-- Título dinámico -->
                            <h5
                                id="confirmTitle"
                                class="fw-bold mb-2"
                                style="color: #0f172a;"
                            ></h5>

                            <!-- Mensaje dinámico -->
                            <p
                                id="confirmMessage"
                                style="color: #64748b; font-size: 0.85rem; margin-bottom: 16px;"
                            ></p>

                            <!-- Info adicional -->
                            <div
                                id="confirmFolioInfo"
                                class="d-flex align-items-center justify-content-center mb-4 gap-2"
                            >
                                <span
                                    style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;"
                                >Folio</span>
                                <span
                                    id="confirmFolio"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-weight: 700; color: #0f172a; font-size: 1rem;"
                                ></span>
                            </div>

                            <!-- Spinner de carga (oculto inicialmente) -->
                            <div
                                id="confirmLoader"
                                class="d-none mb-3"
                            >
                                <div
                                    class="spinner-border"
                                    style="color: #3b82f6;"
                                    role="status"
                                >
                                    <span class="visually-hidden">Procesando...</span>
                                </div>
                                <p
                                    class="mt-2"
                                    style="color: #64748b; font-size: 0.85rem;"
                                >Procesando solicitud...</p>
                            </div>

                            <!-- Botones -->
                            <div
                                id="confirmButtons"
                                class="d-flex gap-2"
                            >
                                <button
                                    type="button"
                                    class="btn flex-grow-1"
                                    data-bs-dismiss="modal"
                                    style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                                >
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </button>
                                <button
                                    type="button"
                                    id="btnConfirmarAccion"
                                    class="btn flex-grow-1"
                                    style="color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                                >
                                    <i class="bi bi-check-circle me-1"></i> Confirmar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Resultado (Éxito/Error) -->
            <div
                class="modal fade"
                id="modalResultadoAccion"
                tabindex="-1"
            >
                <div class="modal-dialog modal-dialog-centered">
                    <div
                        class="modal-content"
                        style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
                    >
                        <div class="p-4 text-center">
                            <!-- Icono dinámico -->
                            <div
                                id="resultadoIconContainer"
                                class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 64px; height: 64px;"
                            >
                            </div>

                            <!-- Título -->
                            <h5
                                id="resultadoTitle"
                                class="fw-bold mb-2"
                                style="color: #0f172a;"
                            ></h5>

                            <!-- Mensaje -->
                            <p
                                id="resultadoMessage"
                                style="color: #64748b; font-size: 0.85rem; margin-bottom: 16px;"
                            ></p>

                            <!-- Botón -->
                            <button
                                type="button"
                                id="btnResultadoCerrar"
                                class="btn w-100"
                                style="color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                            >
                                Aceptar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </x-card-gradient-header>
        <script>
            // Inicializar el botón cuando el DOM esté listo
            document.addEventListener('DOMContentLoaded', function() {
                const btnActualizarDetalle = document.getElementById('btnActualizarDetalle');

                if (btnActualizarDetalle) {
                    btnActualizarDetalle.addEventListener('click', function() {
                        // Obtener el folio actual de la URL
                        const urlParams = new URLSearchParams(window.location.search);
                        const folio = urlParams.get('folio');

                        if (!folio) {
                            mostrarToast('No se encontró el folio de la devolución', 'error');
                            return;
                        }

                        // Mostrar estado de carga en el botón
                        const btnText = this.querySelector('.btn-text');
                        const btnIcon = this.querySelector('.bi');
                        const spinner = this.querySelector('.spinner-border');

                        this.disabled = true;
                        btnIcon.classList.add('d-none');
                        spinner.classList.remove('d-none');
                        btnText.textContent = 'Actualizando...';

                        // Llamar a la función existente y pasar callback para restaurar el botón
                        actualizarInformacion(folio, () => {
                            this.disabled = false;
                            btnIcon.classList.remove('d-none');
                            spinner.classList.add('d-none');
                            btnText.textContent = 'Actualizar';
                        });
                    });
                }
            });

            function actualizarInformacion(folio, callback = null) {
                // Mostrar algún indicador de carga si lo deseas
                console.log('Actualizando información para folio:', folio);

                fetch(`/devoluciones/${encodeURIComponent(folio)}/refresh`)
                    .then(r => {
                        if (!r.ok) {
                            throw new Error(`Error HTTP: ${r.status}`);
                        }
                        return r.json();
                    })
                    .then(data => {
                        console.log('Datos recibidos:', data);

                        const header = data.header;
                        const lineas = data.lineas;
                        const estatusOracle = data.estatusOracle;
                        const estatusAgrupado = data.estatusAgrupado;

                        // Actualizar Estado de la Transacción (Paso 1)
                        actualizarEstadoTransaccion(header);

                        // Actualizar Recepción de Devolución (Paso 2)
                        actualizarRecepcionDevolucion(header);

                        // Actualizar botones de acción
                        actualizarBotonesAccion(header, estatusAgrupado);

                        // Actualizar tabla de líneas
                        actualizarTablaLineas(lineas, header, estatusOracle);

                        // Actualizar total en la cabecera
                        actualizarTotal(lineas);

                        // Mostrar notificación de éxito
                        mostrarToast('Información actualizada correctamente', 'success');

                    })
                    .catch(error => {
                        console.error('Error al actualizar:', error);
                        mostrarToast('Error al actualizar la información: ' + error.message, 'error');
                    })
                    .finally(() => {
                        // Ejecutar callback si existe (para restaurar botones, etc.)
                        if (callback && typeof callback === 'function') {
                            callback();
                        }
                    });
            }

            /**
             * Actualiza la sección del Paso 1: Estado de la Transacción
             */
            function actualizarEstadoTransaccion(header) {
                const status = header.STATUS;

                // Seleccionar el contenedor específico del Paso 1
                const paso1Container = document.querySelector('.col-md-6:first-child');
                if (!paso1Container) return;

                // Buscar el segundo row (el que contiene los valores, no las etiquetas)
                const valoresRow = paso1Container.querySelectorAll('.row.g-2')[1];
                if (!valoresRow) return;

                // Actualizar Fecha Transacción (primera columna)
                const fechaCol = valoresRow.querySelector('.col-3:first-child');
                if (fechaCol) {
                    if (header.Transaction_On) {
                        fechaCol.innerHTML = `
                    <span style="color: #475569; font-weight: 500;">
                        <i class="bi bi-calendar-check me-1" style="color: #10b981;"></i>
                        ${header.Transaction_On}
                    </span>`;
                    } else {
                        fechaCol.innerHTML = `
                    <span style="color: #94a3b8;">
                        <i class="bi bi-clock me-1"></i>Pendiente
                    </span>`;
                    }
                }

                // Actualizar Estatus (segunda columna)
                const estatusCol = valoresRow.querySelector('.col-3:nth-child(2)');
                if (estatusCol) {
                    if (status === 'PROCESADO') {
                        estatusCol.innerHTML = `
                    <span class="badge bg-success-subtle text-success border-success-subtle border"
                        style="font-weight: 500; font-size: 0.8rem; padding: 4px 10px;">
                        <i class="bi bi-check-circle me-1"></i>Procesado
                    </span>`;
                    } else if (status === 'ERROR') {
                        estatusCol.innerHTML = `
                    <span class="badge bg-danger-subtle text-danger border-danger-subtle border"
                        style="font-weight: 500; font-size: 0.8rem; padding: 4px 10px;">
                        <i class="bi bi-x-circle me-1"></i>Error
                    </span>`;
                    } else {
                        estatusCol.innerHTML = `
                    <span style="color: #94a3b8;">
                        <i class="bi bi-hourglass-split me-1"></i>Pendiente
                    </span>`;
                    }
                }

                // Actualizar Mensaje del Sistema (tercera columna, col-6)
                const mensajeCol = valoresRow.querySelector('.col-6');
                if (mensajeCol) {
                    if (header.MENSAJE_ERROR) {
                        const color = status === 'ERROR' ? '#dc2626' : '#16a34a';
                        const icon = status === 'ERROR' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle';
                        mensajeCol.innerHTML = `
                    <span style="color: ${color}; font-weight: 500; word-break: break-word;">
                        <i class="bi ${icon} me-1"></i>
                        ${header.MENSAJE_ERROR}
                    </span>`;
                    } else {
                        mensajeCol.innerHTML = `
                    <span style="color: #94a3b8;">
                        <i class="bi bi-dash me-1"></i>Sin mensaje
                    </span>`;
                    }
                }
            }

            /**
             * Actualiza la sección del Paso 2: Recepción de Devolución
             */
            function actualizarRecepcionDevolucion(header) {
                // Seleccionar el contenedor específico del Paso 2
                const paso2Container = document.querySelector('.col-md-6:last-child');
                if (!paso2Container) return;

                // Buscar el segundo row (el que contiene los valores)
                const valoresRow = paso2Container.querySelectorAll('.row.g-2')[1];
                if (!valoresRow) return;

                // Actualizar Receipt Number (primera columna)
                const receiptNumberCol = valoresRow.querySelector('.col-2:first-child');
                if (receiptNumberCol) {
                    if (header.ReceiptNumber) {
                        receiptNumberCol.innerHTML = `
                    <span class="fw-semibold" style="color: #0f172a;">
                        <i class="bi bi-receipt-cutoff me-1" style="color:#10b981;"></i>
                        ${header.ReceiptNumber}
                    </span>`;
                    } else {
                        receiptNumberCol.innerHTML = `
                    <span style="color:#94a3b8;">
                        <i class="bi bi-clock me-1"></i>Pendiente
                    </span>`;
                    }
                }

                // Actualizar Receipt Header ID (segunda columna)
                const receiptHeaderCol = valoresRow.querySelector('.col-2:nth-child(2)');
                if (receiptHeaderCol) {
                    if (header.ReceiptHeaderId) {
                        receiptHeaderCol.innerHTML = `
                    <span class="font-monospace" style="color:#475569;font-weight:500;">
                        <i class="bi bi-hash me-1" style="color:#10b981;"></i>
                        ${header.ReceiptHeaderId}
                    </span>`;
                    } else {
                        receiptHeaderCol.innerHTML = `
                    <span style="color:#94a3b8;">
                        <i class="bi bi-clock me-1"></i>Pendiente
                    </span>`;
                    }
                }

                // Actualizar Status Receipt (tercera columna)
                const statusReceiptCol = valoresRow.querySelector('.col-2:nth-child(3)');
                if (statusReceiptCol) {
                    if (header.STATUS_RECEIPT === 'PROCESADO') {
                        statusReceiptCol.innerHTML = `
                    <span class="badge bg-success-subtle text-success border-success-subtle border"
                        style="font-weight:500;font-size:.8rem;padding:4px 10px;">
                        <i class="bi bi-check-circle me-1"></i>Procesado
                    </span>`;
                    } else if (header.STATUS_RECEIPT === 'ERROR') {
                        statusReceiptCol.innerHTML = `
                    <span class="badge bg-danger-subtle text-danger border-danger-subtle border"
                        style="font-weight:500;font-size:.8rem;padding:4px 10px;">
                        <i class="bi bi-x-circle me-1"></i>Error
                    </span>`;
                    } else {
                        statusReceiptCol.innerHTML = `
                    <span style="color:#94a3b8;">
                        <i class="bi bi-hourglass-split me-1"></i>Pendiente
                    </span>`;
                    }
                }

                // Actualizar Mensaje Receipt (cuarta columna, col-4)
                const mensajeReceiptCol = valoresRow.querySelector('.col-4');
                if (mensajeReceiptCol) {
                    if (header.MENSAJE_RECEIPT) {
                        const color = header.STATUS_RECEIPT === 'ERROR' ? '#dc2626' : '#16a34a';
                        const icon = header.STATUS_RECEIPT === 'ERROR' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle';
                        mensajeReceiptCol.innerHTML = `
                    <span style="color: ${color}; font-weight:500; word-break:break-word;">
                        <i class="bi ${icon} me-1"></i>
                        ${header.MENSAJE_RECEIPT}
                    </span>`;
                    } else {
                        mensajeReceiptCol.innerHTML = `
                    <span style="color:#94a3b8;">
                        <i class="bi bi-dash me-1"></i>Sin mensaje
                    </span>`;
                    }
                }
            }

            /**
             * Actualiza los botones de acción según el nuevo estado
             * Solo muestra/oculta los botones existentes sin recrearlos
             */
            function actualizarBotonesAccion(header, estatusAgrupado) {
                const status = header.STATUS ? header.STATUS.toUpperCase() : '';

                // Botón 1: Crear Pedido Devolución
                const btnCrearPedido = document.getElementById('btnCrearPedido');
                if (btnCrearPedido) {
                    if (!header.STATUS || status === 'ERROR') {
                        btnCrearPedido.style.display = '';
                        btnCrearPedido.dataset.folio = header.Source_Transaction_Identifier;
                    } else {
                        btnCrearPedido.style.display = 'none';
                    }
                }

                // Botón 2: Crear Recepción de Devolución
                const btnCrearRecepcion = document.getElementById('btnCrearRecepcion');
                if (btnCrearRecepcion) {
                    if (status === 'PROCESADO' && header.ReceiptNumber === null && estatusAgrupado === 'Awaiting Receiving') {
                        btnCrearRecepcion.style.display = '';
                        btnCrearRecepcion.dataset.folio = header.Source_Transaction_Identifier;
                    } else {
                        btnCrearRecepcion.style.display = 'none';
                    }
                }

                // Botón 3: Confirmar Recepción
                const btnConfirmarRecepcion = document.getElementById('btnConfirmarRecepcion');
                if (btnConfirmarRecepcion) {
                    if (status === 'PROCESADO' && header.ReceiptNumber !== null && estatusAgrupado === 'Awaiting Receiving') {
                        btnConfirmarRecepcion.style.display = '';
                        btnConfirmarRecepcion.dataset.folio = header.Source_Transaction_Identifier;
                    } else {
                        btnConfirmarRecepcion.style.display = 'none';
                    }
                }

                // Botón 4: Crear Factura Devolución
                const btnCrearFactura = document.getElementById('btnCrearFactura');
                if (btnCrearFactura) {
                    if (status === 'PROCESADO' && header.ReceiptNumber !== null && estatusAgrupado === 'Awaiting Billing') {
                        btnCrearFactura.style.display = '';
                        btnCrearFactura.dataset.folio = header.Source_Transaction_Identifier;
                    } else {
                        btnCrearFactura.style.display = 'none';
                    }
                }
            }

            /**
             * Actualiza la tabla de líneas
             */
            function actualizarTablaLineas(lineas, header, estatusOracle) {
                const tbody = document.querySelector('.table-custom tbody');
                const tfoot = document.querySelector('.table-custom tfoot');

                if (!tbody) return;

                if (lineas.length === 0) {
                    tbody.innerHTML = `
                <tr>
                    <td colspan="${estatusOracle ? '11' : '10'}" class="py-5 text-center">
                        <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px; background-color: #f1f5f9; border-radius: 50%;">
                            <i class="bi bi-inbox" style="font-size: 28px; color: #94a3b8;"></i>
                        </div>
                        <h6 class="fw-semibold mb-1" style="color: #475569;">Sin líneas</h6>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Esta devolución no tiene líneas registradas</p>
                    </td>
                </tr>`;

                    if (tfoot) tfoot.innerHTML = '';
                    return;
                }

                let html = '';

                lineas.forEach((linea, index) => {
                    const subtotal = linea.Ordered_Quantity * linea.AdjustmentAmount;

                    // Buscar estatus Oracle correspondiente
                    let estatusOracleLinea = null;
                    if (estatusOracle && estatusOracle.lines) {
                        estatusOracleLinea = estatusOracle.lines.find(
                            l => l.productNumber == linea.Product_Number
                        );
                    }

                    html += `
                <tr>
                    <td style="color: #64748b;">${index + 1}</td>
                    <td style="font-weight: 500; color: #0f172a;">${linea.Product_Number || '-'}</td>
                    <td class="text-end" style="color: #475569;">
                        ${isNaN(linea.Ordered_Quantity) ? linea.Ordered_Quantity : Number(linea.Ordered_Quantity).toLocaleString('en-US', {minimumFractionDigits: 3, maximumFractionDigits: 3})}
                    </td>
                    <td>
                        ${linea.Ordered_UOM ? `
                                                                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                                                                                        ${linea.Ordered_UOM}
                                                                                    </span>` : '<span style="color: #94a3b8;">-</span>'}
                    </td>
                    <td class="text-end" style="color: #475569;">
                        $${Math.abs(Number(linea.AdjustmentAmount)).toFixed(2)}
                    </td>
                    <td class="fw-semibold text-end" style="color: #dc2626;">
                        -$${Math.abs(subtotal).toFixed(2)}
                    </td>
                    <td>
                        ${linea.Almacen ? `
                                                                                    <span class="badge" style="background: #fefce8; color: #854d0e; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                                                                                        <i class="bi bi-building me-1"></i>${linea.Almacen}
                                                                                    </span>` : '<span style="color: #94a3b8;">-</span>'}
                    </td>
                    <td>
                        ${linea.OriginalSourceOrderNumber ? `
                                                                                    <span class="font-monospace" style="color: #475569; font-size: 0.85rem;">
                                                                                        ${linea.OriginalSourceOrderNumber}
                                                                                    </span>` : '<span style="color: #94a3b8;">-</span>'}
                    </td>
                    <td style="color: #64748b;">${linea.OriginalSourceLineNumber || '-'}</td>
                    ${estatusOracle ? generarColumnaEstatusOracle(estatusOracleLinea) : ''}
                </tr>`;
                });

                tbody.innerHTML = html;

                // Actualizar footer con totales
                if (tfoot) {
                    const total = lineas.reduce((sum, linea) => sum + (linea.Ordered_Quantity * linea.AdjustmentAmount), 0);
                    const colspan = estatusOracle ? '11' : '10';

                    tfoot.innerHTML = `
                <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                    <td colspan="${colspan}" class="py-1">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <span style="color: #64748b; font-size: 0.85rem; font-weight: 500;">
                                Total de líneas: <strong style="color: #0f172a;">${lineas.length}</strong>
                            </span>
                            <span style="color: #94a3b8;">|</span>
                            <span style="color: #64748b; font-size: 0.85rem; font-weight: 500;">Total:</span>
                            <span style="color: #dc2626; font-size: 1.1rem; font-weight: 700;">
                                -$${Math.abs(total).toFixed(2)}
                            </span>
                            <span class="badge" style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                                ${header.Transactional_Currency_Code || 'MXN'}
                            </span>
                        </div>
                    </td>
                </tr>`;
                }
            }

            /**
             * Genera la columna de estatus Oracle para una línea
             */
            function generarColumnaEstatusOracle(estatusOracleLinea) {
                if (!estatusOracleLinea) {
                    return '<td class="text-center"><span style="color: #94a3b8;">-</span></td>';
                }

                const statusOracle = estatusOracleLinea.status || 'N/A';
                const statusLower = statusOracle.toLowerCase();

                let badgeHtml = '';

                if (statusLower.includes('awaiting receiving')) {
                    badgeHtml = `
                <span class="badge" style="background: #eff6ff; color: #1e40af; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                    <i class="bi bi-hourglass-split me-1" style="color: #3b82f6;"></i>Awaiting Receiving
                </span>`;
                } else if (statusLower.includes('awaiting billing')) {
                    badgeHtml = `
                <span class="badge" style="background: #fefce8; color: #854d0e; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                    <i class="bi bi-clock-fill me-1" style="color: #eab308;"></i>Awaiting Billing
                </span>`;
                } else if (statusLower === 'closed') {
                    badgeHtml = `
                <span class="badge" style="background: #f1f5f9; color: #64748b; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                    <i class="bi bi-check-circle-fill me-1" style="color: #64748b;"></i>Closed
                </span>`;
                } else if (statusLower === 'open') {
                    badgeHtml = `
                <span class="badge" style="background: #f0fdf4; color: #166534; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                    <i class="bi bi-folder2-open me-1" style="color: #10b981;"></i>Open
                </span>`;
                } else {
                    badgeHtml = `
                <span class="badge" style="background: #f8fafc; color: #475569; font-weight: 500; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px;">
                    ${statusOracle}
                </span>`;
                }

                return `<td class="text-center">${badgeHtml}</td>`;
            }

            /**
             * Actualiza el total mostrado en la cabecera de información general
             */
            function actualizarTotal(lineas) {
                const totalContainer = document.querySelector('.col-md-2.mt-0 .fw-semibold[style*="color: #dc2626"]');
                if (totalContainer) {
                    const total = lineas.reduce((sum, linea) => sum + (linea.Ordered_Quantity * linea.AdjustmentAmount), 0);
                    totalContainer.textContent = `-$${Math.abs(total).toFixed(2)}`;
                }
            }

            /**
             * Muestra un toast de notificación
             */
            /**
             * Muestra un toast de notificación usando el estilo existente
             * @param {string} mensaje - El mensaje a mostrar
             * @param {string} tipo - success, danger, warning
             * @param {number} duracion - Duración en milisegundos (default: 5000)
             */
            function mostrarToast(mensaje, tipo = 'success', duracion = 5000) {
                // Buscar o crear el contenedor de toasts
                let container = document.querySelector('.alerts-toast-container');

                if (!container) {
                    container = document.createElement('div');
                    container.className = 'alerts-toast-container';
                    document.body.appendChild(container);
                }

                // Configuración según el tipo
                const config = {
                    success: {
                        icon: 'bi-check-circle-fill',
                        title: '¡Éxito!'
                    },
                    danger: {
                        icon: 'bi-exclamation-triangle-fill',
                        title: 'Error'
                    },
                    warning: {
                        icon: 'bi-exclamation-triangle-fill',
                        title: 'Advertencia'
                    },
                    info: {
                        icon: 'bi-info-circle-fill',
                        title: 'Información'
                    }
                };

                // Mapear tipos alternativos
                const tipoMapeado = {
                    'error': 'danger',
                    'success': 'success',
                    'warning': 'warning',
                    'info': 'info'
                };

                const tipoFinal = tipoMapeado[tipo] || 'info';
                const {
                    icon,
                    title
                } = config[tipoFinal];

                // Crear elemento toast
                const toast = document.createElement('div');
                toast.className = `toast-alert toast-${tipoFinal}`;
                toast.setAttribute('role', 'alert');

                toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="bi ${icon}"></i>
                    </div>
                    <div class="toast-content">
                        <strong>${title}</strong>
                        <span>${mensaje}</span>
                    </div>
                    <button type="button" class="toast-close" onclick="this.parentElement.remove()">
                        <i class="bi bi-x"></i>
                    </button>
                `;

                // Agregar al contenedor
                container.appendChild(toast);

                // Auto-eliminar después de la duración especificada
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(120%)';
                        toast.style.transition = 'all 0.3s ease';
                        setTimeout(() => {
                            if (toast.parentElement) {
                                toast.remove();
                            }
                        }, 300);
                    }
                }, duracion);
            }
            /**
             * Re-vincula los event listeners a los botones de acción recién creados
             */
            function vincularEventosBotones() {
                const buttons = document.querySelectorAll('.btn-devolucion-action');

                buttons.forEach(button => {
                    // Remover listeners antiguos (clonando el nodo)
                    const newButton = button.cloneNode(true);
                    button.parentNode.replaceChild(newButton, button);
                });

                // Volver a agregar los event listeners
                const nuevosBotones = document.querySelectorAll('.btn-devolucion-action');

                nuevosBotones.forEach(button => {
                    button.addEventListener('click', function() {
                        const folio = this.dataset.folio;
                        const employeName = this.dataset.employeName;
                        const action = this.dataset.action;
                        const config = actionConfig[action];

                        if (!config) return;

                        currentAction = action;
                        currentFolio = folio;
                        currentEmployeName = employeName;

                        // Configurar y mostrar modal de confirmación
                        document.getElementById('confirmTitle').textContent = config.title;
                        document.getElementById('confirmMessage').innerHTML = `
                    ${config.message}<br>
                    <small style="color: #94a3b8;">${config.extraInfo || ''}</small>
                `;

                        const iconContainer = document.getElementById('confirmIconContainer');
                        iconContainer.style.background = config.iconBg;
                        iconContainer.innerHTML =
                            `<i class="bi ${config.icon}" style="font-size: 2rem; color: ${config.iconColor};"></i>`;

                        document.getElementById('confirmFolio').textContent = folio;

                        const btnConfirmar = document.getElementById('btnConfirmarAccion');
                        btnConfirmar.style.background = config.buttonColor;

                        document.getElementById('confirmButtons').classList.remove('d-none');
                        document.getElementById('confirmLoader').classList.add('d-none');

                        modalConfirmar.show();
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const buttons = document.querySelectorAll('.btn-devolucion-action');

                // URLs de la API
                const apiUrls = {
                    crearPedido: 'https://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/Devolucion',
                    crearRecepcion: 'https://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/Recepcion',
                    confirmarRecepcion: 'https://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/ConfirmRecepcion',
                    crearFactura: 'https://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/SendArRecepcion'
                };

                // Configuración de cada acción
                const actionConfig = {
                    crearPedido: {
                        title: 'Enviar Pedido de Devolución',
                        message: 'Se creará el pedido de devolución en Oracle.',
                        // extraInfo: 'El estatus cambiará a AWAITING RECEIPT en Oracle.',
                        extraInfo: '',
                        icon: 'bi-cart-plus',
                        iconBg: '#eff6ff',
                        iconColor: '#3b82f6',
                        buttonColor: '#3b82f6',
                        successTitle: '¡Pedido enviado!',
                        successMessage: 'El pedido de devolución se ha enviado correctamente.',
                        errorTitle: 'Error al crear pedido'
                    },
                    crearRecepcion: {
                        title: 'Crear Recepción de Devolución',
                        message: 'Se creará la recepción de devolución.',
                        // extraInfo: 'Se verificará en SQL Server (XXKW_CREDIT_HEADERS).',
                        extraInfo: '',
                        icon: 'bi-box-arrow-in-down',
                        iconBg: '#fefce8',
                        iconColor: '#eab308',
                        buttonColor: '#eab308',
                        successTitle: '¡Recepción creada!',
                        successMessage: 'La recepción de devolución se ha creado correctamente.',
                        errorTitle: 'Error al crear recepción'
                    },
                    confirmarRecepcion: {
                        title: 'Confirmar Recepción',
                        message: 'Se confirmará la recepción de la devolución.',
                        // extraInfo: 'El estatus cambiará a AWAITING BILLING en Oracle.',
                        extraInfo: '',
                        icon: 'bi-check-circle',
                        iconBg: '#f0fdf4',
                        iconColor: '#10b981',
                        buttonColor: '#10b981',
                        successTitle: '¡Recepción confirmada!',
                        successMessage: 'La recepción se ha confirmado correctamente.',
                        errorTitle: 'Error al confirmar recepción'
                    },
                    crearFactura: {
                        title: 'Crear Factura de Devolución',
                        message: 'Se creará la factura de la devolución.',
                        // extraInfo: 'El estatus cambiará a CLOSED en Oracle.',
                        extraInfo: 'El estatus cambiará a CERRADO en Oracle.',
                        icon: 'bi-receipt',
                        iconBg: '#faf5ff',
                        iconColor: '#9333ea',
                        buttonColor: '#9333ea',
                        successTitle: '¡Factura creada!',
                        successMessage: 'La factura de devolución se ha creado correctamente.',
                        errorTitle: 'Error al crear factura'
                    }
                };

                // Referencias a los modales
                const modalConfirmar = new bootstrap.Modal(document.getElementById('modalConfirmarAccion'));
                const modalResultado = new bootstrap.Modal(document.getElementById('modalResultadoAccion'));

                // Variables para guardar la acción actual
                let currentAction = null;
                let currentFolio = null;
                let currentEmployeName = null;

                buttons.forEach(button => {
                    button.addEventListener('click', function() {
                        const folio = this.dataset.folio;
                        const employeName = this.dataset.employeName;
                        const action = this.dataset.action;
                        const config = actionConfig[action];

                        // Guardar acción actual
                        currentAction = action;
                        currentFolio = folio;
                        currentEmployeName = employeName;

                        // Configurar modal de confirmación
                        document.getElementById('confirmTitle').textContent = config.title;
                        document.getElementById('confirmMessage').textContent = config.message;

                        // Configurar icono
                        const iconContainer = document.getElementById('confirmIconContainer');
                        iconContainer.style.background = config.iconBg;
                        iconContainer.innerHTML =
                            `<i class="bi ${config.icon}" style="font-size: 2rem; color: ${config.iconColor};"></i>`;

                        // Configurar folio
                        document.getElementById('confirmFolio').textContent = folio;

                        // Configurar botón confirmar
                        const btnConfirmar = document.getElementById('btnConfirmarAccion');
                        btnConfirmar.style.background = config.buttonColor;

                        // Mostrar/ocultar info adicional si existe
                        const folioInfo = document.getElementById('confirmFolioInfo');
                        const extraInfoElement = document.querySelector('#confirmMessage + p');
                        // Podemos agregar el extraInfo como un párrafo adicional debajo del mensaje
                        // Por simplicidad, lo concatenamos al mensaje
                        document.getElementById('confirmMessage').innerHTML = `
                            ${config.message}<br>
                            <small style="color: #94a3b8;">${config.extraInfo}</small>
                        `;

                        // Mostrar botones y ocultar loader
                        document.getElementById('confirmButtons').classList.remove('d-none');
                        document.getElementById('confirmLoader').classList.add('d-none');

                        // Mostrar modal
                        modalConfirmar.show();
                    });
                });

                // Evento del botón confirmar
                document.getElementById('btnConfirmarAccion').addEventListener('click', function() {
                    if (!currentAction || !currentFolio) return;

                    const config = actionConfig[currentAction];
                    const url = apiUrls[currentAction];
                    let urlCompuesta = '';

                    //https://oracledevolucionrest.kowi.com.mx/api/NotaCreditoAr/Recepcion?Devolucion=DEV_ANT_23&Employee=NAVOJOA 5, FACTURISTA
                    if (currentAction == 'crearRecepcion') {
                        if (!currentEmployeName || currentEmployeName.trim() === '') {
                            modalConfirmar.hide();

                            document.getElementById('resultadoIconContainer').style.background = '#fefce8';
                            document.getElementById('resultadoIconContainer').innerHTML =
                                '<i class="bi bi-person-exclamation" style="font-size:2rem;color:#eab308;"></i>';

                            document.getElementById('resultadoTitle').textContent =
                                'Empleado Oracle no configurado';

                            document.getElementById('resultadoMessage').textContent =
                                'Para crear la recepción de devolución es necesario configurar el nombre de empleado Oracle.';

                            const btnCerrar = document.getElementById('btnResultadoCerrar');
                            btnCerrar.style.background = '#eab308';
                            btnCerrar.style.color = '#0f172a';
                            btnCerrar.textContent = 'Entendido';
                            btnCerrar.onclick = function() {
                                modalResultado.hide();
                                actualizarInformacion(currentFolio);
                            };

                            modalResultado.show();
                            return;
                        }
                        urlCompuesta =
                            `${url}?Devolucion=${encodeURIComponent(currentFolio)}&Employee=${currentEmployeName}`;
                    } else {
                        urlCompuesta = `${url}?Devolucion=${encodeURIComponent(currentFolio)}`;
                    }

                    console.log(urlCompuesta);

                    // Mostrar loader, ocultar botones
                    document.getElementById('confirmButtons').classList.add('d-none');
                    document.getElementById('confirmLoader').classList.remove('d-none');

                    // Ejecutar acción
                    fetch(`${url}?Devolucion=${encodeURIComponent(currentFolio)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Error HTTP: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.ok) {
                                throw new Error(data.message || "Error en la respuesta del servidor.");
                            }

                            // Cerrar modal de confirmación
                            modalConfirmar.hide();

                            // Mostrar modal de éxito
                            document.getElementById('resultadoIconContainer').style.background = '#f0fdf4';
                            document.getElementById('resultadoIconContainer').innerHTML =
                                '<i class="bi bi-check-circle" style="font-size: 2rem; color: #10b981;"></i>';
                            document.getElementById('resultadoTitle').textContent = config.successTitle;
                            document.getElementById('resultadoMessage').textContent = config.successMessage;

                            const btnCerrar = document.getElementById('btnResultadoCerrar');
                            btnCerrar.style.background = '#0f172a';
                            btnCerrar.textContent = 'Aceptar';
                            btnCerrar.onclick = function() {
                                modalResultado.hide();
                                actualizarInformacion(currentFolio);
                                window.location.reload();
                            };

                            modalResultado.show();

                            console.log('Respuesta:', data);
                        })
                        .catch(error => {
                            // Cerrar modal de confirmación
                            modalConfirmar.hide();

                            // Mostrar modal de error
                            document.getElementById('resultadoIconContainer').style.background = '#fef2f2';
                            document.getElementById('resultadoIconContainer').innerHTML =
                                '<i class="bi bi-x-circle" style="font-size: 2rem; color: #ef4444;"></i>';
                            document.getElementById('resultadoTitle').textContent = config.errorTitle;
                            document.getElementById('resultadoMessage').textContent = error.message;

                            const btnCerrar = document.getElementById('btnResultadoCerrar');
                            btnCerrar.style.background = '#ef4444';
                            btnCerrar.textContent = 'Cerrar';
                            btnCerrar.onclick = function() {
                                modalResultado.hide();
                                actualizarInformacion(currentFolio);
                                window.location.reload();
                            };

                            modalResultado.show();

                            console.error('Error:', error);
                        });
                });

                // Resetear al cerrar el modal de confirmación sin confirmar
                document.getElementById('modalConfirmarAccion').addEventListener('hidden.bs.modal', function() {
                    // Restaurar botones si se cerró durante la carga
                    document.getElementById('confirmButtons').classList.remove('d-none');
                    document.getElementById('confirmLoader').classList.add('d-none');
                });
            });
        </script>
    </x-page-container>
