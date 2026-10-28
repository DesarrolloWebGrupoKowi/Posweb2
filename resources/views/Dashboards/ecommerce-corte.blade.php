<x-page-container title="Corte Ecommerce">
    <x-card-gradient-header
        icon="receipt"
        title="Corte Ecommerce"
        subtitle="Detalle de artículos vendidos en la tienda en línea por mes"
    >
        <x-slot:buttons>
            <x-header.buttons.refresh-button />
            <x-header.buttons.home-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="{{ route('DashEcommerce') }}">
            <x-form.group>
                <x-form.select
                    name="centro_venta"
                    label="Centro de venta"
                    icon="shop"
                    col="col-md-3"
                    placeholder="Todos los centros"
                    :options="$centrosVenta"
                />
                <div class="col-md-2">
                    <label
                        class="form-label"
                        for="mes"
                    >
                        <i class="bi bi-calendar3 me-1"></i>
                        Mes
                    </label>
                    <input
                        type="month"
                        class="form-control form-input"
                        name="mes"
                        id="mes"
                        value="{{ $mes }}"
                        autofocus
                    >
                </div>
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

        {{-- SECCIÓN 2: KPIs --}}
        <div class="p-4">
            <div class="row g-3">
                {{-- Tarjeta --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-indigo-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-indigo-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-indigo-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Tarjeta</span>
                                <i
                                    class="bi bi-credit-card-2-front"
                                    style="color: var(--kpi-icon-indigo); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($totales['tarjeta_debito'] + $totales['tarjeta_credito'], 2) }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">Déb:
                                ${{ number_format($totales['tarjeta_debito'], 2) }} | Créd:
                                ${{ number_format($totales['tarjeta_credito'], 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Facturas --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-purple-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-purple-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Solic. Facturas</span>
                                <i
                                    class="bi bi-file-text"
                                    style="color: var(--kpi-icon-purple); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($totales['facturas'], 2) }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">Contado:
                                ${{ number_format($totales['contado'], 2) }} | {{ count($cortesSolicitudes) }}
                                solicitud(es)</span>
                        </div>
                    </div>
                </div>

                {{-- Envío --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-orange-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-orange-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-orange-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Envío</span>
                                <i
                                    class="bi bi-truck"
                                    style="color: var(--kpi-icon-orange); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($totales['costo_envio'], 2) }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">Incluido en el
                                total</span>
                        </div>
                    </div>
                </div>

                {{-- Total General --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #1d4ed8; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Total General</span>
                                <i
                                    class="bi bi-graph-up"
                                    style="color: #3b82f6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($totales['total'], 2) }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">{{ $totales['tickets'] }}
                                ventas | {{ number_format($totales['kilos'], 2) }} kg</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 3: TABLA DE CORTE --}}
        <div class="px-4 pb-4">
            <div class="card-chart rounded p-4 shadow-sm">
                <div class="mb-3">
                    <h5
                        class="mb-1"
                        style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                    >CORTE ECOMMERCE {{ $centrosVenta[request('centro_venta')] ?? '' }}</h5>
                    <small
                        style="color: var(--text-secondary); font-size: 0.85rem;">{{ ucfirst($inicio->locale('es')->isoFormat('MMMM [de] YYYY')) }}</small>
                </div>

                <div class="table-responsive">
                    {{-- CORTE DE CONTADO --}}
                    @foreach ($cortesContado as $corte)
                        <div class="mb-4">
                            {{-- Encabezado del cliente --}}
                            <div
                                class="d-flex align-items-center mb-2 gap-3 rounded p-3"
                                style="background: var(--bg-light);"
                            >
                                <div
                                    class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="background: var(--icon-circle-blue-bg); width: 36px; height: 36px;"
                                >
                                    <i
                                        class="bi bi-box"
                                        style="color: var(--btn-blue-text); font-size: 0.9rem;"
                                    ></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <h6
                                            class="fw-semibold mb-0"
                                            style="color: var(--text-primary); font-size: 0.9rem;"
                                        >{{ $corte->NomClienteCloud ?? 'Cliente sin dar de alta en cloud' }}</h6>
                                        <span class="tags-blue">{{ $corte->CentroVenta }}</span>
                                    </div>
                                    <small style="color: var(--text-secondary);">
                                        @if ($corte->Bill_To)
                                            Bill To: {{ $corte->Bill_To }} |
                                        @endif
                                        {{ count($corte->pedidos) }} pedido(s)
                                    </small>
                                </div>
                            </div>

                            {{-- Un bloque por pedido ECO del cliente --}}
                            @foreach ($corte->pedidos as $pedido)
                                <div class="mb-3 ps-lg-4">
                                    <x-dashboard-ecommerce-pedido
                                        :oracleData="$pedido->OracleData"
                                        :fechaPago="$pedido->FechaPago"
                                    />

                                    {{-- Tabla de productos del pedido --}}
                                    <x-dashboard-ecommerce-articulos
                                        :articulos="$pedido->articulos"
                                        :costoEnvio="$pedido->CostoEnvio"
                                        :importePago="$pedido->ImportePago"
                                    />
                                </div>
                            @endforeach

                            {{-- Total del cliente --}}
                            @if (count($corte->pedidos) > 1)
                                @php $envioCliente = collect($corte->pedidos)->sum('CostoEnvio'); @endphp
                                <div
                                    class="d-flex justify-content-end bg-table-totals flex-wrap gap-4 rounded px-3 py-2"
                                    style="color: var(--text-primary);"
                                >
                                    <span class="fw-bold">TOTAL {{ $corte->NomClienteCloud }}:</span>
                                    <span class="fw-bold">{{ number_format($corte->Kilos, 3) }} kg</span>
                                    <span>Subtotal: ${{ number_format($corte->ImportePago - $envioCliente, 2) }}</span>
                                    <span>Envío: ${{ number_format($envioCliente, 2) }}</span>
                                    <span class="fw-bold">${{ number_format($corte->ImportePago, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    {{-- CORTE DE SOLICITUDES DE FACTURA --}}
                    @foreach ($cortesSolicitudes as $corte)
                        @php $venta = $corte->venta; @endphp
                        <div class="mb-4">
                            {{-- Encabezado del cliente --}}
                            <div
                                class="d-flex align-items-center mb-2 gap-3 rounded p-3"
                                style="background: var(--bg-light);"
                            >
                                <div
                                    class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="background: var(--icon-circle-blue-bg); width: 36px; height: 36px;"
                                >
                                    <i
                                        class="bi bi-person"
                                        style="color: var(--btn-blue-text); font-size: 0.9rem;"
                                    ></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <h6
                                            class="fw-semibold mb-0"
                                            style="color: var(--text-primary); font-size: 0.9rem;"
                                        >{{ $venta->NombreFactura ?? $venta->Cliente }}</h6>
                                        <span class="tags-blue">{{ $venta->CentroVenta }}</span>
                                        @if ($venta->ConstanciaFiscal)
                                            <a
                                                href="{{ $venta->ConstanciaFiscal }}"
                                                target="_blank"
                                                class="btn-gray"
                                                title="Constancia de situación fiscal"
                                            >
                                                <i class="bi bi-download"></i> Constancia
                                            </a>
                                        @endif
                                    </div>
                                    <small style="color: var(--text-secondary);">
                                        {{ $venta->NomTipoPago }}
                                        | Cliente: {{ $venta->Cliente }}
                                    </small>
                                </div>
                            </div>

                            <div class="ps-lg-4">
                                <x-dashboard-ecommerce-pedido
                                    :oracleData="$venta->OracleData"
                                    :fechaPago="$venta->Fecha_Pago"
                                />

                                {{-- Tabla de productos --}}
                                <x-dashboard-ecommerce-articulos
                                    :articulos="$corte->articulos"
                                    :costoEnvio="$venta->Costo_Envio"
                                    :importePago="$venta->Importe_Pago"
                                />
                            </div>
                        </div>
                    @endforeach

                    @if (count($cortesContado) == 0 && count($cortesSolicitudes) == 0)
                        <div class="py-5 text-center">
                            <i
                                class="bi bi-inbox"
                                style="font-size: 2.5rem; color: var(--text-muted);"
                            ></i>
                            <p
                                class="mt-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >No hay ventas entregadas en este mes</p>
                        </div>
                    @else
                        {{-- Total general --}}
                        <div
                            class="d-flex justify-content-end bg-table-totals flex-wrap gap-4 rounded p-3"
                            style="color: var(--text-primary);"
                        >
                            <span class="fw-bold">TOTAL GENERAL:</span>
                            <span class="fw-bold">{{ number_format($totales['kilos'], 2) }} kg</span>
                            <span>Subtotal: ${{ number_format($totales['total'] - $totales['costo_envio'], 2) }}</span>
                            <span>Envío: ${{ number_format($totales['costo_envio'], 2) }}</span>
                            <span class="fw-bold">${{ number_format($totales['total'], 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
