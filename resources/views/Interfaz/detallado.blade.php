<x-page-container title="Interfaz Cloud - Detallado">
    <x-card-gradient-header
        icon="cloud-upload"
        title="Pedidos Rutas - Detallado"
        subtitle="Vista detallada de pedidos"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="{{ route('interfaz.detallado') }}"
            id="formFiltros"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="order_type"
                    label="Tipo de Orden"
                    icon="tag"
                    col="col-md-3"
                    :options="$orderTypes->pluck('DESCRIPCION', 'ORDER_TYPE')->toArray()"
                    :value="request('order_type')"
                    placeholder="Buscar tipo de orden..."
                    autofocus
                />
                <x-form.date
                    name="fecha"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha')"
                />

                <x-form.text
                    name="pedido"
                    label="Pedido"
                    icon="search"
                    placeholder="No. Transacción"
                    col="col-md-2"
                    :value="request('pedido')"
                />
                <x-form.checkbox-input
                    name="detallado"
                    label="Detalle"
                    :checked="request('detallado') == 'on'"
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

        <!-- Resultados -->
        <div
            class="rounded p-4 shadow-sm"
            style="background: white; border-radius: 12px;"
        >
            @if ($filtrosAplicados)
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5
                            class="mb-1"
                            style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                        >
                            PEDIDOS ENCONTRADOS - DETALLE
                        </h5>
                        <small style="color: #64748b; font-size: 0.85rem;">
                            {{ $pedidos->count() }} pedidos |
                            {{ $pedidos->sum(function ($p) {return count($p->lineas ?? []);}) }} artículos
                            @if (request('pedido'))
                                | Pedido: {{ request('pedido') }}
                            @else
                                | Tipo:
                                {{ $orderTypes->firstWhere('ORDER_TYPE', request('order_type'))->DESCRIPCION ?? request('order_type') }}
                                | Fecha: {{ \Carbon\Carbon::parse(request('fecha'))->format('d/m/Y') }}
                            @endif
                        </small>
                    </div>
                </div>
            @endif

            @php
                $totalGeneralCantidad = 0;
                $totalGeneralVenta = 0;
            @endphp

            @forelse ($pedidos as $pedido)
                @php
                    $status = $pedido->STATUS ?? null;
                    $mensajeError = $pedido->MENSAJE_ERROR ?? null;
                    $transactionOn = $pedido->Transaction_On ?? null;
                    $sourceTransactionNumber = $pedido->Source_Transaction_Number ?? null;
                    $factura = $pedido->FACTURA ?? null;
                    $lineas = $pedido->lineas ?? collect();
                    $numLineas = count($lineas);
                    $totalGeneralCantidad += $pedido->cantidad_total ?? 0;
                    $totalGeneralVenta += $pedido->venta_total ?? 0;
                @endphp

                <!-- Bloque de pedido -->
                <div
                    class="card mb-3"
                    style="border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden;"
                >
                    <!-- Header del pedido -->
                    <div style="background: #f8fafc; padding: 14px 20px; border-bottom: 2px solid #3b82f6;">
                        <div class="row align-items-center">
                            <!-- Cliente -->
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div
                                        class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="background: #eff6ff; width: 32px; height: 32px;"
                                    >
                                        @if ($factura)
                                            <i
                                                class="bi bi-person"
                                                style="color: #3b82f6; font-size: 0.85rem;"
                                            ></i>
                                        @else
                                            <i
                                                class="bi bi-box"
                                                style="color: #3b82f6; font-size: 0.85rem;"
                                            ></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span
                                            class="text-truncate"
                                            style="max-width: 220px; display: inline-block; font-weight: 600; color: #0f172a;"
                                            title="{{ $pedido->Buying_Party_Name }}"
                                        >
                                            {{ $pedido->ORDER_TYPE }} - {{ $pedido->Buying_Party_Name }}
                                        </span>
                                        @if ($mensajeError)
                                            <br>
                                            <small
                                                class="{{ $status === 'ERROR' ? 'text-danger' : 'text-success' }}"
                                                style="font-size: 0.7rem;"
                                            >
                                                <i
                                                    class="bi bi-info-circle me-1"></i>{{ Str::limit($mensajeError, 50) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-2">
                                <small style="color: #64748b; font-size: 0.7rem; display: block;">FECHA</small>
                                <span style="color: #475569; font-size: 0.85rem; font-weight: 500;">
                                    {{ $transactionOn ? \Carbon\Carbon::parse($transactionOn)->format('d/m/Y H:i') : 'N/A' }}
                                </span>
                            </div>

                            <!-- Pedido -->
                            <div class="col-md-2">
                                <small style="color: #64748b; font-size: 0.7rem; display: block;">PEDIDO</small>
                                <span
                                    class="fw-semibold tags-blue"
                                    style="font-size: 0.85rem;"
                                >
                                    {{ $sourceTransactionNumber }}
                                </span>
                            </div>

                            <!-- Estatus -->
                            <div class="col-md-2">
                                <small style="color: #64748b; font-size: 0.7rem; display: block;">ESTATUS</small>
                                <span
                                    class="{{ $status === 'PROCESADO' ? 'tags-green' : ($status === 'ERROR' ? 'tags-red' : 'tags-yellow') }}"
                                    style="font-size: 0.75rem;"
                                >
                                    @if ($status === 'PROCESADO')
                                        <i class="bi bi-check-circle me-1"></i>PROCESADO
                                    @elseif($status === 'ERROR')
                                        <i class="bi bi-x-circle me-1"></i>ERROR
                                    @else
                                        <i class="bi bi-exclamation-circle me-1"></i>SIN PROCESAR
                                    @endif
                                </span>
                            </div>

                            <!-- Resumen -->
                            <div class="col-md-3 text-end">
                                <div>
                                    <span style="font-weight: 600; color: #0f172a;">
                                        {{ $numLineas }} artículos
                                    </span>
                                </div>
                                <div>
                                    <span style="font-weight: 600; color: #64748b;">
                                        {{ number_format($pedido->cantidad_total ?? 0, 3) }} kg
                                    </span>
                                </div>
                                <div>
                                    <span style="font-weight: 700; color: #10b981; font-size: 1rem;">
                                        ${{ number_format($pedido->venta_total ?? 0, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de líneas -->
                    @if ($numLineas > 0)
                        <div
                            class="table-responsive"
                            style="margin: 0;"
                        >
                            <table
                                class="table-sm mb-0 table"
                                style="margin: 0;"
                            >
                                <thead style="background: #f1f5f9;">
                                    <tr>
                                        <th style="width: 50px; padding-left: 20px;">#</th>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Precio</th>
                                        <th
                                            class="text-end"
                                            style="padding-right: 20px;"
                                        >Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lineas as $index => $linea)
                                        @php
                                            $cantidad = $linea->Ordered_Quantity ?? 0;
                                            $precio = $linea->ADJUSTMENT_AMOUNT ?? 0;
                                            $importe = $cantidad * $precio;
                                        @endphp
                                        <tr>
                                            <td style="padding-left: 20px; color: #94a3b8; font-size: 0.8rem;">
                                                {{ $linea->Source_Transaction_Line_Number ?? $index + 1 }}
                                            </td>
                                            <td>
                                                <span
                                                    class="tags-blue"
                                                    style="font-size: 0.8rem;"
                                                >
                                                    {{ $linea->Product_Number ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span style="font-weight: 500; font-size: 0.85rem;">
                                                    {{ number_format($cantidad, 3) }}
                                                    <small
                                                        style="color: #94a3b8;">{{ $linea->Ordered_UOM ?? 'kg' }}</small>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span style="color: #64748b; font-size: 0.85rem;">
                                                    ${{ number_format($precio, 2) }}
                                                </span>
                                            </td>
                                            <td
                                                class="text-end"
                                                style="padding-right: 20px;"
                                            >
                                                <span style="font-weight: 600; color: #10b981; font-size: 0.85rem;">
                                                    ${{ number_format($importe, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            class="py-3 text-center"
                            style="color: #94a3b8;"
                        >
                            <i class="bi bi-inbox me-2"></i>Sin líneas registradas
                        </div>
                    @endif
                </div>
            @empty
                <div class="py-5 text-center">
                    @if (!$filtrosAplicados)
                        <i
                            class="bi bi-funnel"
                            style="font-size: 2.5rem; color: #94a3b8;"
                        ></i>
                        <h6 style="color: #0f172a;">Aplique filtros para consultar</h6>
                        <p class="text-muted">
                            Busque por número de pedido o seleccione tipo de orden y fecha
                        </p>
                    @else
                        <i
                            class="bi bi-inbox"
                            style="font-size: 2.5rem; color: #94a3b8;"
                        ></i>
                        <h5 style="color: #0f172a;">Sin resultados</h5>
                        <p class="text-muted">No se encontraron pedidos con los filtros seleccionados</p>
                        <a
                            href="{{ route('interfaz.index') }}"
                            class="btn btn-sm"
                            style="background: #f1f5f9; color: #64748b; border-radius: 8px;"
                        >
                            <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                        </a>
                    @endif
                </div>
            @endforelse

            <!-- Total General -->
            @if ($pedidos->count() > 0)
                <div
                    style="background: #1e293b; color: white; padding: 14px 20px; border-radius: 10px; margin-top: 10px;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span style="font-weight: 700; font-size: 1rem;">
                                <i class="bi bi-calculator me-2"></i>TOTAL GENERAL
                            </span>
                            <span style="font-size: 0.8rem; opacity: 0.7;">
                                {{ $pedidos->count() }} pedidos
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            <span style="font-weight: 600; font-size: 0.9rem;">
                                {{ number_format($totalGeneralCantidad, 3) }} kg
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            <span style="font-weight: 700; color: #10b981; font-size: 1.1rem;">
                                ${{ number_format($totalGeneralVenta, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-card-gradient-header>
</x-page-container>
