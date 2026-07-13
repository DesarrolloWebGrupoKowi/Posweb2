{{-- resources/views/Interfaz/index.blade.php --}}

<x-page-container title="Interfaz Cloud">
    <x-card-gradient-header
        icon="cloud-upload"
        title="Interfaz Cloud"
        subtitle="Consulta de pedidos por tipo de orden y fecha"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="{{ route('interfaz.index') }}"
            id="formFiltros"
            method="GET"
        >
            <x-form.group>
                <x-form.datalist
                    name="order_type"
                    label="Tipo de Orden"
                    icon="tag"
                    col="col-md-4"
                    :options="$orderTypes->pluck('DESCRIPCION', 'ORDER_TYPE')->toArray()"
                    :value="request('order_type')"
                    placeholder="Buscar tipo de orden..."
                    autofocus
                />

                <x-form.date
                    name="fecha"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-3"
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
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="search"
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
                            PEDIDOS ENCONTRADOS
                        </h5>
                        <small style="color: #64748b; font-size: 0.85rem;">
                            {{ $pedidos->count() }} resultados
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

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person me-1"></i>Cliente</th>
                            <th><i class="bi bi-hash me-1"></i>Pedido</th>
                            <th><i class="bi bi-circle me-1"></i>Estatus</th>
                            <th><i class="bi bi-chat-dots me-1"></i>Mensaje</th>
                            <th><i class="bi bi-file-text me-1"></i>Factura</th>
                            <th><i class="bi bi-upc me-1"></i>UUID</th>
                            <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pedidos as $pedido)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="background: #eff6ff; width: 28px; height: 28px;"
                                        >
                                            @if ($pedido->FACTURA)
                                                <i
                                                    class="bi bi-person"
                                                    style="color: #3b82f6; font-size: 0.75rem;"
                                                ></i>
                                            @else
                                                <i
                                                    class="bi bi-box"
                                                    style="color: #3b82f6; font-size: 0.75rem;"
                                                ></i>
                                            @endif
                                        </div>
                                        <span
                                            class="text-truncate"
                                            style="max-width: 350px; display: inline-block;"
                                            title="{{ $pedido->Buying_Party_Name }}"
                                        >
                                            {{ $pedido->ORDER_TYPE }} - {{ $pedido->Buying_Party_Name }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold tags-blue">
                                        {{ $pedido->Source_Transaction_Number }}
                                    </span>
                                </td>
                                <td>
                                    @if ($pedido->STATUS == 'PROCESADO')
                                        <span class="tags-green">
                                            <i class="bi bi-check-circle me-1"></i>{{ $pedido->STATUS }}
                                        </span>
                                    @elseif($pedido->STATUS == 'ERROR')
                                        <span class="tags-red">
                                            <i class="bi bi-x-circle me-1"></i>{{ $pedido->STATUS }}
                                        </span>
                                    @else
                                        <span class="tags-yellow">
                                            <i class="bi bi-exclamation-circle me-1"></i>SIN PROCESAR
                                        </span>
                                    @endif
                                </td>
                                <td style="position: relative;">
                                    @if ($pedido->MENSAJE_ERROR)
                                        <small
                                            style="color: #475569; cursor: pointer;"
                                            class="mensaje-tooltip"
                                            data-mensaje="{{ $pedido->MENSAJE_ERROR }}"
                                        >
                                            {{ Str::limit($pedido->MENSAJE_ERROR, 60) }}
                                        </small>
                                        <div
                                            class="tooltip-mensaje"
                                            style="display: none; position: absolute; z-index: 9999; background: #1e293b; color: white;
                                                        padding: 12px 16px; border-radius: 10px; font-size: 0.8rem; max-width: 400px;
                                                        word-wrap: break-word; box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                                                        left: 0; top: 100%; margin-top: 8px; line-height: 1.5;"
                                        >
                                            <div
                                                style="position: absolute; top: -6px; left: 20px; width: 12px; height: 12px;
                                                            background: #1e293b; transform: rotate(45deg);">
                                            </div>
                                            {{ $pedido->MENSAJE_ERROR }}
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($pedido->FACTURA)
                                        <span class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>{{ $pedido->FACTURA }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($pedido->UUID)
                                        <small style="color: #475569;">
                                            {{ Str::limit($pedido->UUID, 20) }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="color: #475569; font-size: 0.85rem;">
                                        {{ $pedido->Transaction_On ? \Carbon\Carbon::parse($pedido->Transaction_On)->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($pedido->STATUS !== 'PROCESADO')
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-enviar d-flex align-items-center btn-animated gap-1"
                                            style="background: #fffbeb; color: #f59e0b; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                            data-pedido="{{ $pedido->Source_Transaction_Number }}"
                                            data-row-id="row-{{ $pedido->Source_Transaction_Number }}"
                                        >
                                            <i class="bi bi-send"></i> ENVIAR
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="8"
                                    class="py-5 text-center"
                                >
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
                                        <p class="text-muted">No se encontraron pedidos con los filtros
                                            seleccionados</p>
                                        <a
                                            href="{{ route('interfaz.index') }}"
                                            class="btn btn-sm"
                                            style="background: #f1f5f9; color: #64748b; border-radius: 8px;"
                                        >
                                            <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card-gradient-header>

    <script>
        // Tooltips para mensajes
        document.addEventListener('DOMContentLoaded', function() {
            const tooltips = document.querySelectorAll('.mensaje-tooltip');

            tooltips.forEach(function(element) {
                const tooltip = element.nextElementSibling;
                let timeout;

                element.addEventListener('mouseenter', function(e) {
                    clearTimeout(timeout);
                    tooltip.style.display = 'block';

                    const rect = tooltip.getBoundingClientRect();
                    if (rect.right > window.innerWidth) {
                        tooltip.style.left = 'auto';
                        tooltip.style.right = '0';
                    }
                    if (rect.bottom > window.innerHeight) {
                        tooltip.style.top = 'auto';
                        tooltip.style.bottom = '100%';
                        tooltip.style.marginTop = '0';
                        tooltip.style.marginBottom = '8px';
                        const arrow = tooltip.querySelector('div');
                        if (arrow) {
                            arrow.style.top = 'auto';
                            arrow.style.bottom = '-6px';
                            arrow.style.transform = 'rotate(225deg)';
                        }
                    }
                });

                element.addEventListener('mouseleave', function() {
                    timeout = setTimeout(function() {
                        tooltip.style.display = 'none';
                    }, 200);
                });

                tooltip.addEventListener('mouseenter', function() {
                    clearTimeout(timeout);
                });

                tooltip.addEventListener('mouseleave', function() {
                    tooltip.style.display = 'none';
                });
            });
        });

        // ====================================================================================================
        // ENVÍO DE PEDIDOS (BTN-ENVIAR)
        // ====================================================================================================
        document.querySelectorAll('.btn-enviar').forEach(button => {
            button.addEventListener('click', async function() {
                const pedidoId = this.getAttribute('data-pedido');
                const rowId = this.getAttribute('data-row-id');
                const btn = this;
                const row = btn.closest('tr');
                btn.disabled = true;
                const origHTML = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';

                try {
                    const response = await fetch(`/DashTienda/enviar-pedido/${pedidoId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });
                    const result = await response.json();

                    // Obtener las celdas de la fila
                    const cells = row.querySelectorAll('td');
                    const statusCell = cells[2]; // Columna de Estatus (índice 2)
                    const messageCell = cells[3]; // Columna de Mensaje (índice 3)

                    if (result.ok) {
                        // Actualizar estatus a PROCESADO
                        statusCell.innerHTML = `
                            <span class="tags-green">
                                <i class="bi bi-check-circle me-1"></i>PROCESADO
                            </span>
                        `;

                        // Actualizar mensaje si viene en la respuesta
                        if (result.message) {
                            messageCell.innerHTML = `
                                <small style="color: #475569;">
                                    ${result.message.length > 60 ? result.message.substring(0, 60) + '...' : result.message}
                                </small>
                            `;
                        }

                        // Eliminar el botón
                        btn.remove();

                    } else {
                        // Actualizar estatus a ERROR
                        statusCell.innerHTML = `
                            <span class="tags-red">
                                <i class="bi bi-x-circle me-1"></i>ERROR
                            </span>
                        `;

                        // Actualizar mensaje de error
                        if (result.message) {
                            const truncatedMessage = result.message.length > 60 ?
                                result.message.substring(0, 60) + '...' : result.message;

                            messageCell.innerHTML = `
                                <small style="color: #475569; cursor: pointer;" class="mensaje-tooltip" data-mensaje="${result.message.replace(/"/g, '&quot;')}">
                                    ${truncatedMessage}
                                </small>
                                <div class="tooltip-mensaje" style="display: none; position: absolute; z-index: 9999; background: #1e293b; color: white;
                                        padding: 12px 16px; border-radius: 10px; font-size: 0.8rem; max-width: 400px;
                                        word-wrap: break-word; box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                                        left: 0; top: 100%; margin-top: 8px; line-height: 1.5;">
                                    <div style="position: absolute; top: -6px; left: 20px; width: 12px; height: 12px;
                                            background: #1e293b; transform: rotate(45deg);"></div>
                                    ${result.message}
                                </div>
                            `;

                            // Agregar eventos de tooltip al nuevo elemento
                            agregarEventosTooltip(messageCell.querySelector('.mensaje-tooltip'));
                        }

                        // Restaurar el botón
                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                        console.error('Error en el envío:', result.message);
                    }

                } catch (error) {
                    // Restaurar el botón en caso de error de conexión
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                    console.error('Error de conexión:', error);
                }
            });
        });

        // Función para agregar eventos de tooltip
        function agregarEventosTooltip(element) {
            if (!element) return;

            const tooltip = element.nextElementSibling;
            if (!tooltip) return;

            let timeout;

            element.addEventListener('mouseenter', function(e) {
                clearTimeout(timeout);
                tooltip.style.display = 'block';

                const rect = tooltip.getBoundingClientRect();
                if (rect.right > window.innerWidth) {
                    tooltip.style.left = 'auto';
                    tooltip.style.right = '0';
                }
                if (rect.bottom > window.innerHeight) {
                    tooltip.style.top = 'auto';
                    tooltip.style.bottom = '100%';
                    tooltip.style.marginTop = '0';
                    tooltip.style.marginBottom = '8px';
                    const arrow = tooltip.querySelector('div');
                    if (arrow) {
                        arrow.style.top = 'auto';
                        arrow.style.bottom = '-6px';
                        arrow.style.transform = 'rotate(225deg)';
                    }
                }
            });

            element.addEventListener('mouseleave', function() {
                timeout = setTimeout(function() {
                    tooltip.style.display = 'none';
                }, 200);
            });

            tooltip.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
            });

            tooltip.addEventListener('mouseleave', function() {
                tooltip.style.display = 'none';
            });
        }
    </script>
</x-page-container>
