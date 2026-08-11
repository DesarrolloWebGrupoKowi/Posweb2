<x-page-container title="Reporte Interfaz Autoservicio">
    <x-card-gradient-header
        icon="cloud-upload"
        title="Reporte Interfaz Autoservicio"
        subtitle="Consulta de pedidos enviados a Oracle"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/AutoservicioReporte">
            <x-form.group>
                <x-form.date
                    name="fecha"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-2"
                    :value="$fecha"
                    :autofocus="true"
                />
                <x-form.select
                    name="organizacion"
                    label="Organización"
                    icon="tag"
                    col="col-md-2"
                    :options="$tiposOrden->pluck('ORGANIZATION_NAME', 'ORGANIZATION_CODE')->toArray()"
                    placeholder="Todas"
                />
                <x-form.select
                    name="estatus"
                    label="Estatus"
                    icon="circle"
                    col="col-md-2"
                    :options="[
                        '' => 'Todos',
                        'NULL' => 'Sin Procesar',
                        'PROCESADO' => 'Procesado',
                        'ERROR' => 'Error',
                    ]"
                    :selected="$estatus"
                />
                <x-form.text
                    name="cliente"
                    label="Cliente"
                    icon="search"
                    placeholder="Nombre o folio..."
                    col="col-md-3"
                    :value="$cliente"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear url="/AutoservicioReporte" />
            </div>
        </x-form.form>

        <!-- Tabla -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: var(--text-secondary);"
                        ></i>Pedidos Interfazados
                    </h5>
                    <p class="section-content-subtitle">{{ $headers->total() }} pedidos encontrados</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-building me-1"></i>Cliente / Referencia</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                            <th><i class="bi bi-building me-1"></i>Organización</th>
                            <th><i class="bi bi-box-seam me-1"></i>Packing List</th>
                            <th><i class="bi bi-hash me-1"></i>Folio</th>
                            <th class="text-center"><i class="bi bi-circle me-1"></i>Estatus</th>
                            <th class="text-center"><i class="bi bi-cloud me-1"></i>Oracle</th>
                            <th class="text-end"><i class="bi bi-box me-1"></i>Kilos</th>
                            <th class="text-end"><i class="bi bi-box me-1"></i>Piezas</th>
                            <th class="text-end"><i class="bi bi-cash me-1"></i>Total</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($headers as $header)
                            <tr>
                                <!-- Cliente + Referencia combinados -->
                                <td>
                                    <span
                                        class="tags-blue"
                                        style="font-size: 0.7rem; margin-right: 4px;"
                                    >{{ $header->ORDER_TYPE }}</span>
                                    <span style="font-weight: 500;">{{ $header->Buying_Party_Name }}</span>
                                    @if ($header->CustomerPONumber)
                                        <small
                                            style="color: var(--text-muted);">({{ $header->CustomerPONumber }})</small>
                                    @endif
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ $header->Transaction_On ? \Carbon\Carbon::parse($header->Transaction_On)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td>
                                    <span style="color: var(--text-secondary); font-weight: 500">
                                        <i class="bi bi-building me-1"></i>
                                        <span>
                                            {{ $header->ORGANIZATION_CODE ? $header->ORGANIZATION_CODE : '-' }}
                                        </span>
                                        (<span>
                                            {{ $header->SUBINVENTORY_CODE ? $header->SUBINVENTORY_CODE : '-' }}
                                        </span>)
                                    </span>
                                </td>

                                <td>
                                    <span style="color: var(--text-secondary); font-weight: 500">
                                        <i class="bi bi-box-seam me-1"></i>
                                        <span>{{ $packingsPorFolio[$header->Source_Transaction_Identifier] }}</span>
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="tags-blue"
                                        style="font-size: 0.7rem;"
                                    >
                                        {{ $header->Source_Transaction_Identifier }}
                                    </span>
                                </td>
                                <!-- Estatus -->
                                <td class="text-center">
                                    @if ($header->STATUS === null)
                                        <span
                                            class="tags-yellow"
                                            style="font-size: 0.7rem;"
                                        >SIN PROCESAR</span>
                                    @elseif ($header->STATUS === 'PROCESADO')
                                        <span
                                            class="tags-green"
                                            style="font-size: 0.7rem;"
                                        >PROCESADO</span>
                                    @elseif ($header->STATUS === 'ERROR')
                                        <span
                                            class="tags-red"
                                            style="font-size: 0.7rem;"
                                        >ERROR</span>
                                    @else
                                        <span
                                            class="tags-yellow"
                                            style="font-size: 0.7rem;"
                                        >{{ $header->STATUS }}</span>
                                    @endif
                                </td>
                                <!-- Estatus Oracle -->
                                <td class="text-center">
                                    @if ($header->STATUS === 'PROCESADO')
                                        <span
                                            class="status-oracle"
                                            data-pedido="{{ $header->Source_Transaction_Identifier }}"
                                            style="font-size: 0.7rem;"
                                        >
                                            <span
                                                class="spinner-border spinner-border-sm text-muted"
                                                style="width: 0.6rem; height: 0.6rem;"
                                            ></span>
                                        </span>
                                    @else
                                        <span
                                            class="text-muted"
                                            style="font-size: 0.7rem;"
                                        >-</span>
                                    @endif
                                </td>
                                <!-- Kilos -->
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >
                                    {{ number_format($header->total_kilos, 2) }} kg
                                </td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >
                                    {{ number_format($header->total_piezas, 2) }} pz
                                </td>
                                <!-- Total -->
                                <td
                                    class="text-end"
                                    style="font-weight: 600;"
                                >
                                    ${{ number_format($header->total_importe, 2) }}
                                </td>
                                <!-- Acciones -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-animated d-flex align-items-center gap-1"
                                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: 1px solid var(--btn-gray-hover); border-radius: 8px; padding: 6px 12px; font-size: 0.8rem;"
                                            onclick="verDetalle('{{ $header->Source_Transaction_Identifier }}')"
                                        >
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                        <a
                                            href="/AutoservicioFacturacion?packlist={{ $packingsPorFolio[$header->Source_Transaction_Identifier] }}"
                                            target="_blank"
                                            class="btn-gray"
                                        >
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="11"
                                    class="py-5 text-center"
                                >
                                    <i
                                        class="bi bi-inbox"
                                        style="font-size: 2.5rem; color: var(--text-muted);"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: var(--text-secondary); font-size: 0.85rem;"
                                    >No se encontraron pedidos</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $headers])
        </div>
    </x-card-gradient-header>

    @include('AutoservicioFacturacion.modaldetalle')

</x-page-container>

<script>
    // ============================================================
    // CONSULTAR ESTATUS ORACLE PARA FILAS PROCESADAS
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-oracle').forEach(item => {
            const pedido = item.dataset.pedido;
            const apiUrl =
                `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.ok && data.dato?.lines) {
                        const estatusLineas = data.dato.lines.map(line => line.status);
                        const estatusUnicos = [...new Set(estatusLineas)];
                        const estatus = estatusUnicos.join(', ');

                        let clase = 'tags-green';
                        if (estatus === 'Canceled') clase = 'tags-red';
                        else if (estatus.includes('Awaiting')) clase = 'tags-yellow';

                        item.innerHTML =
                            `<span class="${clase}">${estatus}</span>`;
                    } else {
                        item.innerHTML =
                            '<span class="text-muted">-</span>';
                    }
                })
                .catch(() => {
                    item.innerHTML =
                        '<span class="text-muted">Error</span>';
                });
        });
    });

    function verDetalle(folio) {
        document.getElementById('detalleFolio').textContent = folio;
        const tbody = document.getElementById('detalleBody');
        tbody.innerHTML =
            '<tr><td colspan="6" class="text-center py-4"><span class="spinner-border spinner-border-sm" style="color: var(--text-muted);"></span> Cargando líneas...</td></tr>';

        fetch(`/api/autoservicio/detalle/${folio}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="6" class="text-center py-4 text-muted">Sin líneas</td></tr>';
                    return;
                }
                let html = '';
                let total = 0;
                data.forEach((l, i) => {
                    const cantidad = parseFloat(l.Ordered_Quantity || 0);
                    const precio = parseFloat(l.ADJUSTMENT_AMOUNT || 0);
                    const importe = cantidad * precio;
                    total += importe;

                    html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td style="font-weight: 500;">${l.Product_Number || '-'}</td>
                        <td class="text-end">${cantidad.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td>${l.Ordered_UOM || '-'}</td>
                        <td class="text-end">$${precio.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td class="text-end" style="font-weight: 600;">$${importe.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    </tr>`;
                });
                html += `
                    <tr class="bg-table-totals" style="font-weight: 700;">
                        <td colspan="5" class="text-end">TOTAL:</td>
                        <td class="text-end" style="color: var(--success-color);">$${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    </tr>`;
                tbody.innerHTML = html;
            })
            .catch(() => {
                tbody.innerHTML =
                    '<tr><td colspan="6" class="text-center py-4 text-danger">Error al cargar</td></tr>';
            });

        const modal = new bootstrap.Modal(document.getElementById('ModalDetalle'));
        modal.show();
    }
</script>
