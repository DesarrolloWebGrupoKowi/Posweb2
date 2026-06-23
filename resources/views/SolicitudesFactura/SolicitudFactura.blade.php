<x-page-container title="Detalle de Solicitud de Factura">

        <x-card-gradient-header
            icon="file-earmark-text"
            title="Detalle de Solicitud"
            subtitle="Información completa de la solicitud de factura"
        >
            <x-slot:buttons>
                <a
                    href="/SolicitudesFactura"
                    class="btn-header-ghost"
                    style="background: #f1f5f9; color: #475569;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-arrow-left"></i> Solicitudes
                </a>
                <x-header.buttons.refresh-button />
                <x-header.buttons.home-button />
            </x-slot:buttons>

            {{-- Info rápida --}}
            <div class="d-flex justify-content-between align-items-center px-4 pb-2 pt-3">

                <div class="d-flex flex-wrap gap-3">
                    <span
                        style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;"
                    >
                        <i class="bi bi-hash me-1"></i>Folio:
                        <strong>{{ $solicitud->IdSolicitudFactura ?? ($solicitud['IdSolicitudFactura'] ?? 'N/A') }}</strong>
                    </span>
                    <span
                        style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;"
                    >
                        <i class="bi bi-receipt me-1"></i>Encabezado:
                        <strong>{{ $solicitud->IdEncabezado ?? ($solicitud['IdEncabezado'] ?? 'N/A') }}</strong>
                    </span>
                    <span
                        style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;"
                    >
                        <i class="bi bi-calendar3 me-1"></i>Fecha:
                        <strong>{{ \Carbon\Carbon::parse($solicitud->FechaSolicitud ?? ($solicitud['FechaSolicitud'] ?? now()))->format('d/m/Y H:i') }}</strong>
                    </span>
                </div>
                @if ($solicitud->Status == 0)
                    <button
                        type="button"
                        class="btn-create"
                        style="background: #ef4444;"
                        onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(239, 68, 68, 0.4)'"
                        onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalCancelarSolicitud{{ $solicitud->Id }}"
                    >
                        <i class="bi bi-x-circle"></i> Cancelar Solicitud
                    </button>
                    @include('SolicitudesFactura.ModalCancelarSolicitud')
                @endif
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="p-4">
                <div class="row g-4">
                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="col-lg-8">
                        {{-- Información del Cliente --}}
                        <div
                            class="mb-4 rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <h5
                                class="mb-3"
                                style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                            >
                                <i
                                    class="bi bi-person me-2"
                                    style="color: #64748b;"
                                ></i>Información del Cliente
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Nombre</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->NomCliente ?? ($solicitud['NomCliente'] ?? 'N/A') }}</div>
                                </div>
                                <div class="col-md-3">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">RFC</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->RFC ?? ($solicitud['RFC'] ?? 'N/A') }}</div>
                                </div>
                                <div class="col-md-3">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Tipo Persona</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->TipoPersona ?? ($solicitud['TipoPersona'] ?? 'N/A') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Correo</small>
                                    <div style="font-weight: 500; font-size: 0.85rem;">
                                        {{ $solicitud->Email ?? ($solicitud['Email'] ?? 'No registrado') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Correo Cloud</small>
                                    <div style="font-weight: 500; font-size: 0.85rem;">
                                        {{ $solicitud->EmailCloud ?? ($solicitud['EmailCloud'] ?? 'No registrado') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Teléfono</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->Telefono ?? ($solicitud['Telefono'] ?? 'No registrado') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Tienda</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->NomTienda ?? ($solicitud['NomTienda'] ?? 'N/A') }}</div>
                                </div>
                                <div class="col-12">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Dirección</small>
                                    <div style="font-weight: 500; font-size: 0.85rem;">
                                        {{ $solicitud->Calle ?? ($solicitud['Calle'] ?? '') }}
                                        {{ $solicitud->NumExt ?? ($solicitud['NumExt'] ?? '') }}
                                        {{ $solicitud->NumInt ?? ($solicitud['NumInt'] ?? '') }},
                                        {{ $solicitud->Colonia ?? ($solicitud['Colonia'] ?? '') }},
                                        C.P. {{ $solicitud->CodigoPostal ?? ($solicitud['CodigoPostal'] ?? '') }},
                                        {{ $solicitud->Ciudad ?? ($solicitud['Ciudad'] ?? '') }},
                                        {{ $solicitud->Estado ?? ($solicitud['Estado'] ?? '') }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Método de Pago</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->NomTipoPago ?? ($solicitud['NomTipoPago'] ?? ($solicitud->MetodoPago ?? 'N/A')) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Banco</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->NomBanco ?? ($solicitud['NomBanco'] ?? 'N/A') }}</div>
                                </div>
                                <div class="col-md-4">
                                    <small style="color: #94a3b8; font-size: 0.75rem;">Cuenta</small>
                                    <div style="font-weight: 500; font-size: 0.9rem;">
                                        {{ $solicitud->NumTarjeta ?? ($solicitud['NumTarjeta'] ?? 'N/A') }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Resumen de Ventas --}}
                        <div
                            class="rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5
                                    class="mb-0"
                                    style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                                >
                                    <i
                                        class="bi bi-graph-up me-2"
                                        style="color: #64748b;"
                                    ></i>Resumen de Ventas
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- @if ($solicitud->UUID)
                                        <span class="tags-blue">Facturación en Línea</span>
                                    @endif --}}

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table-hover table-custom table">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                                            <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                                            <th class="text-end"><i class="bi bi-cash me-1"></i>Precio</th>
                                            <th class="text-end"><i class="bi bi-percent me-1"></i>IVA</th>
                                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                                            <th><i class="bi bi-receipt me-1"></i>Pedido</th>
                                            <th><i class="bi bi-circle me-1"></i>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalImporte = 0; @endphp
                                        @forelse($ventasDetalle as $venta)
                                            @php
                                                $sourceId = $venta->Source_Transaction_Identifier ?? null;
                                                $status = $oracleData ? $oracleData->STATUS ?? null : null;
                                                $solicitudCancelacion = $venta->SolicitudCancelacion ?? null;

                                                if (!empty($solicitudCancelacion)) {
                                                    $statusClass = 'tags-red';
                                                    $statusText = 'CANCEL. SOLICITADA';
                                                } elseif (empty($sourceId)) {
                                                    $statusClass = 'tags-yellow';
                                                    $statusText = 'SIN PROCESAR';
                                                } elseif ($status == 'ERROR') {
                                                    $statusClass = 'tags-red';
                                                    $statusText = 'ERROR';
                                                } elseif ($status == 'PROCESADO') {
                                                    $statusClass = 'tags-green';
                                                    $statusText = 'PROCESADO';
                                                } elseif ($status == 'EN PROCESO') {
                                                    $statusClass = 'tags-yellow';
                                                    $statusText = 'EN PROCESO';
                                                } else {
                                                    $statusClass = 'tags-yellow';
                                                    $statusText = 'SIN PROCESAR';
                                                }
                                            @endphp
                                            <tr>
                                                <td style="font-weight: 500;">{{ $venta->CodArticulo ?? 'N/A' }}</td>
                                                <td
                                                    class="text-truncate"
                                                    style="max-width: 200px;"
                                                    title="{{ $venta->NomArticulo ?? '' }}"
                                                >{{ $venta->NomArticulo ?? 'N/A' }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >{{ number_format($venta->CantArticulo ?? 0, 3) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($venta->PrecioArticulo ?? 0, 2) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($venta->IvaArticulo ?? 0, 2) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($venta->ImporteArticulo ?? 0, 2) }}</td>
                                                <td>
                                                    @if (empty($sourceId) && $venta->SolicitudCancelacion != null)
                                                        <span class="tags-red">Sol. Cancelación</span>
                                                    @elseif($solicitud->Editar != null)
                                                        <span class="tags-red">SIN LIGAR</span>
                                                    @elseif(empty($sourceId))
                                                        <span class="tags-red">SIN PEDIDO</span>
                                                    @else
                                                        <span
                                                            class="{{ ($oracleData->STATUS ?? '') == 'ERROR' ? 'tags-red' : 'tags-blue' }}"
                                                        >
                                                            {{ substr_replace($sourceId, '_', 3, 0) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td><span class="{{ $statusClass }}">{{ $statusText }}</span></td>
                                            </tr>
                                            @php $totalImporte += ($venta->ImporteArticulo ?? 0); @endphp
                                        @empty
                                            <tr>
                                                <td
                                                    colspan="8"
                                                    class="py-5 text-center"
                                                >
                                                    <i
                                                        class="bi bi-inbox"
                                                        style="font-size: 2.5rem; color: #94a3b8;"
                                                    ></i>
                                                    <p
                                                        class="mt-2"
                                                        style="color: #64748b; font-size: 0.85rem;"
                                                    >No hay ventas asociadas</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                        @if (count($ventasDetalle) > 0)
                                            <tr style="background: #f8fafc; font-weight: 700;">
                                                <td
                                                    colspan="5"
                                                    class="text-end"
                                                >TOTAL:</td>
                                                <td class="text-end">${{ number_format($totalImporte, 2) }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="col-lg-4">
                        {{-- Información Fiscal --}}
                        <div
                            class="mb-4 rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <h5
                                class="mb-3"
                                style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                            >
                                <i
                                    class="bi bi-file-earmark-text me-2"
                                    style="color: #64748b;"
                                ></i>Información Fiscal
                            </h5>
                            <div class="mb-3">
                                <small style="color: #94a3b8; font-size: 0.75rem;">Régimen Fiscal</small>
                                <div style="font-weight: 600; font-size: 0.85rem;">
                                    {{ $solicitud->RegimenFiscal ?? ($solicitud['RegimenFiscal'] ?? 'N/A') }}</div>
                                <small
                                    style="color: #64748b;">{{ $solicitud->NomRegimenFiscal ?? ($solicitud['NomRegimenFiscal'] ?? '') }}</small>
                            </div>
                            <div class="mb-3">
                                <small style="color: #94a3b8; font-size: 0.75rem;">Uso CFDI</small>
                                <div style="font-weight: 600; font-size: 0.85rem;">
                                    {{ $solicitud->UsoCFDI ?? ($solicitud['UsoCFDI'] ?? 'N/A') }}</div>
                                <small
                                    style="color: #64748b;">{{ $solicitud->NomCFDI ?? ($solicitud['NomCFDI'] ?? '') }}</small>
                            </div>
                            <div>
                                <small style="color: #94a3b8; font-size: 0.75rem;">Método Pago</small>
                                <div style="font-weight: 500; font-size: 0.85rem;">
                                    {{ $solicitud->MetodoPago ?? ($solicitud['MetodoPago'] ?? 'N/A') }}</div>
                                <small
                                    style="color: #64748b;">{{ ucfirst(mb_strtolower($solicitud->NomMetodoPago ?? ($solicitud['NomMetodoPago'] ?? ''))) }}</small>
                            </div>
                        </div>

                        {{-- Estado Oracle --}}
                        <div
                            class="rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <h5
                                class="mb-3"
                                style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                            >
                                <i
                                    class="bi bi-receipt-cutoff me-2"
                                    style="color: #64748b;"
                                ></i>Facturación
                            </h5>

                            @if (!empty($solicitud->UUID))
                                {{-- Facturación en Línea (cliente) --}}
                                <div class="py-3 text-center">
                                    <div class="mb-3">
                                        <span
                                            class="tags-green"
                                            style="font-size: 0.85rem; padding: 6px 14px;"
                                        >
                                            <i class="bi bi-check-circle me-1"></i> Facturación en Línea
                                        </span>
                                    </div>
                                    <small style="color: #94a3b8; display: block; margin-bottom: 4px;">Factura realizada
                                        por el cliente</small>
                                    <div
                                        class="mt-2 rounded p-3"
                                        style="background: #f8fafc; word-break: break-all;"
                                    >
                                        <small style="color: #94a3b8; display: block; margin-bottom: 2px;">UUID</small>
                                        <code
                                            style="font-size: 0.8rem; color: #3b82f6; font-weight: 600;">{{ $solicitud->UUID }}</code>
                                    </div>
                                </div>
                            @else
                                {{-- Facturación en Tienda (facturista) --}}
                                <div class="py-3 text-center">
                                    <div class="mb-3">
                                        <span
                                            class="tags-yellow"
                                            style="font-size: 0.85rem; padding: 6px 14px;"
                                        >
                                            <i class="bi bi-clock me-1"></i> Factura en Tienda
                                        </span>
                                    </div>
                                    <small style="color: #94a3b8; display: block; margin-bottom: 4px;">Factura realizada en
                                        tienda</small>
                                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 8px;">
                                        <i class="bi bi-info-circle me-1"></i>
                                        La facturista debe timbrar esta solicitud
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>
