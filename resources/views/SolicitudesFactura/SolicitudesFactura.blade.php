<x-page-container title="Solicitudes Factura">
        <x-card-gradient-header
            icon="file-text"
            title="Solicitudes Factura"
            subtitle="Gestión de solicitudes de facturación"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros -->
            <x-form.form action="/SolicitudesFactura">
                <x-form.group>
                    <x-form.select
                        name="idTienda"
                        label="Tienda"
                        icon="shop"
                        col="col-md-3"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    />
                    <x-form.date
                        name="fecha"
                        label="Fecha"
                        icon="calendar3"
                        col="col-md-2"
                        :autofocus="true"
                    />
                    <x-form.text
                        name="rfc"
                        label="RFC"
                        icon="search"
                        placeholder="Buscar por RFC..."
                        col="col-md-2"
                    />
                    <x-form.text
                        name="nombre"
                        label="Nombre"
                        icon="person"
                        placeholder="Buscar por Nombre..."
                        col="col-md-2"
                    />
                </x-form.group>
                <div class="col-md-3 d-flex gap-2">
                    <x-form.submit
                        text="Filtrar"
                        icon="funnel"
                        class="flex-grow-1"
                    />
                    <x-form.clear />
                </div>
            </x-form.form>

            {{-- SECCIÓN 2: TABLA --}}
            <div class="p-4">
                <div
                    class="rounded p-4 shadow-sm"
                    style="background: white; border-radius: 12px;"
                >
                    <div class="table-responsive">
                        <table class="table-hover table-custom table">
                            <thead style="position: sticky; top: 0; z-index: 2;">
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>Folio</th>
                                    <th><i class="bi bi-ticket me-1"></i>Ticket</th>
                                    <th><i class="bi bi-shield-lock me-1"></i>Folio Encriptado</th>
                                    <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                                    <th><i class="bi bi-file-text me-1"></i>RFC</th>
                                    <th><i class="bi bi-person me-1"></i>Nombre</th>
                                    <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Total</th>
                                    <th class="text-center"><i class="bi bi-receipt me-1"></i>Pedido</th>
                                    <th class="text-center"><i class="bi bi-circle me-1"></i>Estatus</th>
                                    <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($solicitudes as $solicitud)
                                    <tr>
                                        <td style="font-weight: 600; color: #0f172a;">{{ $solicitud->IdSolicitudFactura }}
                                        </td>
                                        <td style="font-weight: 500;">{{ $solicitud->IdEncabezado }}</td>
                                        <td style="font-size: 0.8rem; color: #64748b;">
                                            {{ \Vinkla\Hashids\Facades\Hashids::encode($solicitud->IdEncabezado) }}
                                        </td>
                                        <td style="font-weight: 500;">{{ $solicitud->NomTienda }}</td>
                                        <td style="font-size: 0.85rem;">
                                            {{ \Carbon\Carbon::parse($solicitud->FechaSolicitud)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                                        </td>
                                        <td style="font-weight: 500;">{{ $solicitud->RFC }}</td>
                                        <td>
                                            <span
                                                class="text-truncate"
                                                style="max-width: 200px; display: inline-block;"
                                                title="{{ $solicitud->NomCliente }}"
                                            >
                                                {{ $solicitud->NomCliente }}
                                            </span>
                                        </td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 500;"
                                        >${{ number_format($solicitud->TotalFactura, 2) }}</td>
                                        <td class="text-center">
                                            @if ($solicitud->Source_Transaction_Identifier)
                                                <span
                                                    style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                >
                                                    {{ $solicitud->Source_Transaction_Identifier }}
                                                </span>
                                            @elseif($solicitud->Editar !== null)
                                                <span
                                                    style="background: #fef2f2; color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                >
                                                    SIN LIGAR
                                                </span>
                                            @else
                                                <span
                                                    style="background: #fffbeb; color: #f59e0b; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                >
                                                    SIN PEDIDO
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($solicitud->Status == 1)
                                                <span
                                                    style="background: #fef2f2; color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                >Cancelada</span>
                                            @elseif ($solicitud->Status == 0 && $solicitud->Editar != null)
                                                <span
                                                    style="background: #fffbeb; color: #f59e0b; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                >SIN PROCESAR</span>
                                            @else
                                                @if ($solicitud->InterfaceStatus == 'PROCESADO')
                                                    <span
                                                        style="background: #f0fdf4; color: #10b981; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                    >{{ $solicitud->InterfaceStatus }}</span>
                                                @elseif ($solicitud->InterfaceStatus == 'ERROR')
                                                    <span
                                                        style="background: #fef2f2; color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                    >{{ $solicitud->InterfaceStatus }}</span>
                                                @elseif ($solicitud->InterfaceStatus == 'PENDIENTE')
                                                    <span
                                                        style="background: #fffbeb; color: #f59e0b; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                    >{{ $solicitud->InterfaceStatus }}</span>
                                                @else
                                                    <span
                                                        style="background: #fffbeb; color: #f59e0b; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                                    >{{ $solicitud->InterfaceStatus ?: 'SIN PROCESAR' }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <x-table.buttons.link-button
                                                    :id="$solicitud->IdSolicitudFactura"
                                                    url="/SolicitudesFactura"
                                                    title="Ver detalle de solicitud"
                                                    label="Ver"
                                                    icon="list"
                                                />
                                            </div>
                                            @include('SolicitudesFactura.ModalCancelarSolicitud')
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
                                                style="font-size: 2.5rem; color: #94a3b8;"
                                            ></i>
                                            <p
                                                class="mt-2"
                                                style="color: #64748b; font-size: 0.85rem;"
                                            >Sin datos disponibles</p>
                                            <a
                                                href="/SolicitudesFactura"
                                                class="btn btn-sm d-flex align-items-center mx-auto mt-2 gap-1"
                                                style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; width: fit-content;"
                                            >
                                                <i class="bi bi-x-circle"></i> Limpiar filtros
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('components.paginate', ['items' => $solicitudes])
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>
