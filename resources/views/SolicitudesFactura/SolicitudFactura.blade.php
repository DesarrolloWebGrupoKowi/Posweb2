@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Detalle de Solicitud de Factura')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y BOTONES -->
        <x-layout.section-card>
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <div>
                    <x-title
                        titulo="Detalle de Solicitud"
                        :options="[['name' => 'Solicitudes', 'value' => '/SolicitudesFactura']]"
                    />

                    {{-- <div class="d-flex mt-2 flex-wrap gap-2">
                        <span class="badge bg-light text-dark" style="font-size: 0.7rem;">
                            Folio: <strong>{{ $solicitud->IdSolicitudFactura ?? $solicitud['IdSolicitudFactura'] ?? 'N/A' }}</strong>
                        </span>
                        <span class="badge bg-light text-dark" style="font-size: 0.7rem;">
                            Encabezado: <strong>{{ $solicitud->IdEncabezado ?? $solicitud['IdEncabezado'] ?? 'N/A' }}</strong>
                        </span>
                        <span class="badge bg-light text-dark" style="font-size: 0.7rem;">
                            Fecha: <strong>{{ \Carbon\Carbon::parse($solicitud->FechaSolicitud ?? $solicitud['FechaSolicitud'] ?? now())->format('d/m/Y H:i') }}</strong>
                        </span>
                    </div> --}}
                </div>
                <div class="d-flex gap-2">
                    <x-filters.buttons.back-button />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>
        </x-layout.section-card>

        <!-- INFORMACIÓN DEL CLIENTE Y FISCAL -->
        <div
            class="d-flex flex-shrink-0 gap-4"
            style="min-height: 0;"
        >
            <!-- SECCION DE INFORMACION DEL CLIENTE Y VENTA -->
            <div
                class="d-flex flex-column gap-4"
                style="flex: 2; min-width: 0; min-height: 0;"
            >
                <!-- TABLA DE INFORMACION DEL CLIENTE COMPLETA -->
                <div
                    class="d-flex flex-column"
                    style="min-width: 0; min-height: 0;"
                >
                    <div
                        class="card d-flex flex-column border-0 p-4"
                        style="border-radius: 10px; min-height: 0;"
                    >
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-semibold mb-3">👤 Información del Cliente</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table-sm table">
                                <tbody>
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Nombre: </td>
                                        <td>
                                            <small>{{ $solicitud->NomCliente ?? ($solicitud['NomCliente'] ?? 'N/A') }}</small>
                                        </td>
                                        <td class="text-muted">Cliente:</td>
                                        <td>
                                            {{-- <span
                                                class="badge tags-blue"
                                                style="font-weight: 500"
                                            > --}}
                                            <small>
                                                {{ $solicitud->TipoPersona ?? ($solicitud['TipoPersona'] ?? 'N/A') }}
                                            </small>
                                            {{-- </span> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">RFC:</td>
                                        <td colspan="3">
                                            <small>{{ $solicitud->RFC ?? ($solicitud['RFC'] ?? 'N/A') }}</small>
                                        </td>
                                    </tr>
                                    <!-- Fila: Correo Electrónico y Correo Cloud -->
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Correo Electrónico:</td>
                                        <td>
                                            <small>{{ $solicitud->Email ?? ($solicitud['Email'] ?? 'No registrado') }}</small>
                                        </td>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Correo Electrónico CLOUD:</td>
                                        <td>
                                            <small>{{ $solicitud->EmailCloud ?? ($solicitud['EmailCloud'] ?? 'No registrado') }}</small>
                                        </td>
                                    </tr>
                                    <!-- Fila: Teléfono -->
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Teléfono:</td>
                                        <td colspan="3">
                                            <small>{{ $solicitud->Telefono ?? ($solicitud['Telefono'] ?? 'No registrado') }}</small>
                                        </td>
                                    </tr>
                                    <!-- Fila 4: Dirección completa -->
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Dirección:</td>
                                        <td colspan="3">
                                            <small>
                                                {{ $solicitud->Calle ?? ($solicitud['Calle'] ?? 'N/A') }}
                                                {{ $solicitud->NumExt ?? ($solicitud['NumExt'] ?? '') }}
                                                {{ $solicitud->NumInt ?? ($solicitud['NumInt'] ?? '') }},
                                                {{ $solicitud->Colonia ?? ($solicitud['Colonia'] ?? 'N/A') }},
                                                C.P.
                                                {{ $solicitud->CodigoPostal ?? ($solicitud['CodigoPostal'] ?? 'N/A') }}<br>
                                                {{ $solicitud->Ciudad ?? ($solicitud['Ciudad'] ?? 'N/A') }},
                                                {{ $solicitud->Municipio ?? ($solicitud['Municipio'] ?? 'N/A') }},
                                                {{ $solicitud->Estado ?? ($solicitud['Estado'] ?? 'N/A') }},
                                                {{ $solicitud->Pais ?? ($solicitud['Pais'] ?? 'MÉXICO') }}
                                            </small>
                                        </td>
                                    </tr>

                                    <!-- Fila 5: Tienda -->
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Tienda:</td>
                                        <td colspan="3">
                                            <small>
                                                {{ $solicitud->NomTienda ?? ($solicitud['NomTienda'] ?? 'N/A') }}
                                                (ID: {{ $solicitud->IdTienda ?? ($solicitud['IdTienda'] ?? 'N/A') }})
                                            </small>
                                        </td>
                                    </tr>

                                    <!-- Fila 6: Método de Pago -->
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Método de Pago:</td>
                                        <td colspan="3">
                                            <small>{{ $solicitud->NomTipoPago ?? ($solicitud['NomTipoPago'] ?? ($solicitud->MetodoPago ?? ($solicitud['MetodoPago'] ?? 'N/A'))) }}</small>
                                        </td>
                                    </tr>

                                    <!-- Fila 7: Cuenta -->
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Banco:</td>
                                        <td>
                                            <small>{{ $solicitud->NomBanco ?? ($solicitud['NomBanco'] ?? 'N/A') }}</small>
                                        </td>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Cuenta:</td>
                                        <td>
                                            <small>{{ $solicitud->NumTarjeta ?? ($solicitud['NumTarjeta'] ?? 'N/A') }}</small>
                                        </td>
                                    </tr>

                                    <!-- Fila 8: Bill To y ID Cloud -->
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Bill To:</td>
                                        <td>
                                            <code><small>{{ $solicitud->Bill_To ?? ($solicitud['Bill_To'] ?? 'N/A') }}</small></code>
                                        </td>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >ID Cloud:</td>
                                        <td>
                                            <code><small>{{ $solicitud->IdClienteCloud ?? ($solicitud['IdClienteCloud'] ?? 'N/A') }}</small></code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Fecha solicitud:</td>
                                        <td colspan="3">
                                            <span style="font-weight: 500">
                                                {{ \Carbon\Carbon::parse($solicitud->FechaSolicitud ?? ($solicitud['FechaSolicitud'] ?? now()))->locale('es')->isoFormat('D [de] MMMM [de] YYYY, H:mm') }}
                                            </span>
                                        </td>
                                        {{-- <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Fecha subida:</td>
                                        <td>
                                            <span style="font-weight: 500">
                                                {{ \Carbon\Carbon::parse($solicitud->FechaSolicitud ?? ($solicitud['FechaSubida'] ?? now()))->locale('es')->isoFormat('D [de] MMMM [de] YYYY, H:mm') }}
                                            </span>
                                        </td> --}}
                                    </tr>

                                    <!-- Fila 9: Usuario Solicitud y Descripción -->
                                    {{-- <tr>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Usuario Solicitud:</td>
                                        <td colspan="3">
                                            <code><small>{{ $solicitud->IdUsuarioSolicitud ?? ($solicitud['IdUsuarioSolicitud'] ?? 'N/A') }}</small></code>
                                        </td>
                                        <td
                                            class="text-muted"
                                            style="font-weight: 500"
                                        >Descripción:</td>
                                        <td>
                                            <small>{{ $solicitud->Descripcion ?? ($solicitud['Descripcion'] ?? 'N/A') }}</small>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE VENTAS DETALLADA -->
                <x-layout.section-card>
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <h6 class="fw-semibold mb-0">📊 Resumen de Ventas
                            {{ $solicitud->NomTienda ?? ($solicitud['NomTienda'] ?? 'N/A') }}
                        </h6>

                        <div class="d-flex justify-content-center align-items-center">
                            <span class="tags-blue">
                                Facturación en Línea
                            </span>
                        </div>
                        {{-- <div class="d-flex gap-3">
                            <div class="text-end">
                                <small class="text-muted d-block">Total Importe</small>
                                <strong
                                    class="text-success">${{ number_format(collect($ventasDetalle)->sum('total_importe'), 2) }}</strong>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Total Cantidad</small>
                                <strong>{{ number_format(collect($ventasDetalle)->sum('total_cantidad'), 2) }} kg</strong>
                            </div>
                        </div> --}}
                    </div>

                    <div class="table-responsive content-table-sm mb-0">
                        <table class="mt-2 table">
                            <thead class="table-head">
                                <tr>
                                    <th>Código</th>
                                    <th>Artículo</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Iva</th>
                                    <th class="text-end">Importe</th>
                                    <th>Pedido</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasDetalle as $venta)
                                    <tr>
                                        <td>
                                            {{ is_array($venta) ? $venta['CodArticulo'] ?? 'N/A' : $venta->CodArticulo ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ is_array($venta) ? $venta['NomArticulo'] ?? 'N/A' : $venta->NomArticulo ?? 'N/A' }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format(is_array($venta) ? $venta['CantArticulo'] ?? 0 : $venta->CantArticulo ?? 0, 3) }}
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format(is_array($venta) ? $venta['PrecioArticulo'] ?? 0 : $venta->PrecioArticulo ?? 0, 2) }}
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format(is_array($venta) ? $venta['IvaArticulo'] ?? 0 : $venta->IvaArticulo ?? 0, 2) }}
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format(is_array($venta) ? $venta['ImporteArticulo'] ?? 0 : $venta->ImporteArticulo ?? 0, 2) }}
                                        </td>
                                        <td>
                                            @php
                                                $sourceId = $venta->Source_Transaction_Identifier ?? null;
                                            @endphp
                                            @if (empty($sourceId) && $venta->SolicitudCancelacion != null)
                                                <span class="tags-red">Solicitud Cancelación</span>
                                            @elseif($solicitud->Editar != null)
                                                <span class="tags-red">SIN LIGAR</span>
                                                @if ($solicitud->Editar == '0')
                                                    <span class="tags-red">NUEVO</span>
                                                @else
                                                    <span class="tags-red">ACTUALIZAR</span>
                                                @endif
                                            @elseif(empty($sourceId))
                                                <span class="tags-red">SIN PEDIDO</span>
                                            @else
                                                @php
                                                    $status = is_object($oracleData)
                                                        ? $oracleData->STATUS ?? null
                                                        : (is_array($oracleData)
                                                            ? $oracleData['STATUS'] ?? null
                                                            : null);
                                                @endphp
                                                <span class="{{ $status == 'ERROR' ? 'tags-red' : 'tags-blue' }}">
                                                    {{ substr_replace($sourceId, '_', 3, 0) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                // Obtener el Source_Transaction_Identifier del detalle
                                                $sourceId = $venta->Source_Transaction_Identifier ?? null;

                                                // Buscar el status en OracleData usando el sourceId
                                                // $oracleData = $oracleData[$sourceId] ?? null;
                                                $status = $oracleData ? $oracleData->STATUS ?? null : null;
                                                $mensajeError = $oracleData ? $oracleData->MENSAJE_ERROR ?? null : null;
                                                $solicitudCancelacion = $venta->SolicitudCancelacion ?? null;

                                                // Determinar el estado a mostrar
                                                if (!empty($solicitudCancelacion)) {
                                                    $statusClass = 'tags-red';
                                                    $statusText = 'CANCELACIÓN SOLICITADA';
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
                                                } elseif (empty($status)) {
                                                    $statusClass = 'tags-yellow';
                                                    $statusText = 'SIN PROCESAR';
                                                } else {
                                                    $statusClass = 'tags-yellow';
                                                    $statusText = 'SIN PROCESAR';
                                                }
                                            @endphp

                                            <span class="{{ $statusClass }} d-inline-flex align-items-center gap-1">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="8"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="No hay ventas asociadas"
                                                icon="credit-card"
                                                message="No se encontraron ventas para esta solicitud de factura."
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light">
                                    <td
                                        colspan="3"
                                        class="p-1 text-end"
                                        style="font-weight: 500"
                                    >TOTALES:</td>
                                    <td class="text-end"></td>
                                    <td class="text-end"></td>
                                    <td
                                        class="p-1 text-end"
                                        style="font-weight: 500"
                                    >
                                        ${{ number_format(collect($ventasDetalle)->sum('ImporteArticulo'), 2) }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-layout.section-card>
            </div>

            <!-- COLUMNA DERECHA: INFORMACIÓN FISCAL Y ORACLE -->
            <div
                class="d-flex flex-column gap-4"
                style="flex: 1; min-width: 0;"
            >
                <!-- INFORMACION FISCAL -->
                <div
                    class="card border-0 p-4"
                    style="border-radius: 10px;"
                >
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">📑 Información Fiscal</h6>
                    </div>

                    <div class="d-flex mb-2 gap-4">
                        <span
                            class="text-muted d-block"
                            style="width: 120px; font-weight: 500"
                        >Régimen Fiscal</span>
                        <p
                            class="d-block m-0"
                            style="font-weight: 500;"
                        >
                            <strong>{{ $solicitud->RegimenFiscal ?? ($solicitud['RegimenFiscal'] ?? 'N/A') }}</strong>
                            <small>{{ $solicitud->NomRegimenFiscal ?? ($solicitud['NomRegimenFiscal'] ?? 'No especificado') }}</small>
                        </p>
                    </div>

                    <div class="d-flex mb-2 gap-4">
                        <span
                            class="text-muted d-block"
                            style="width: 120px; font-weight: 500"
                        >Uso CFDI</span>
                        <p
                            class="d-block m-0"
                            style="font-weight: 500;"
                        >
                            <strong>{{ $solicitud->UsoCFDI ?? ($solicitud['UsoCFDI'] ?? 'N/A') }}</strong>
                            <small>{{ $solicitud->NomCFDI ?? ($solicitud['NomCFDI'] ?? 'N/A') }}</small>
                        </p>
                    </div>

                    <div class="d-flex mb-2 gap-4">
                        <span
                            class="text-muted d-block"
                            style="width: 120px; font-weight: 500"
                        >Método Pago</span>
                        <p
                            class="d-block m-0"
                            style="font-weight: 500;"
                        >
                            {{ $solicitud->MetodoPago ?? ($solicitud['MetodoPago'] ?? 'N/A') }}
                            <small>{{ ucfirst(mb_strtolower($solicitud->NomMetodoPago ?? ($solicitud['NomMetodoPago'] ?? 'N/A'))) }}</small>

                        </p>
                    </div>
                </div>

                <!-- ESTADO ORACLE (versión simplificada para objeto) -->
                <div
                    class="card border-0 p-4"
                    style="border-radius: 10px;"
                >
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">⚙️ Estado en Oracle</h6>
                        {{-- <span class="badge {{ ($oracleData->STATUS ?? '') === 'PROCESADO' ? 'tags-green' : 'tags-red' }}">
                            {{ $oracleData->STATUS ?? 'SIN PROCESAR' }}
                        </span> --}}
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted d-block">Fecha transacción </small>
                        <p
                            class="d-block m-0"
                            style="font-weight: 500"
                        >
                            @if (!empty($oracleData->Transaction_On))
                                {{ \Carbon\Carbon::parse($oracleData->Transaction_On)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted d-block">Pedido</small>
                        @if (!empty($oracleData->Source_Transaction_Number))
                            <span class="tags-blue">
                                {{ $oracleData->Source_Transaction_Number }}
                            </span>
                        @else
                            <p
                                class="d-block m-0"
                                style="font-weight: 500"
                            >
                                N/A
                            </p>
                        @endif
                    </div>

                    @if (!empty($solicitud->UUID))
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted d-block">UUID</small>
                            <span class="tags-blue">
                                {{ $solicitud->UUID }}
                            </span>
                        </div>
                    @endif

                    @if (!empty($oracleData->MENSAJE_ERROR))
                        <div class="border-top mt-2 pt-2">
                            <small
                                class="{{ ($oracleData->STATUS ?? '') === 'PROCESADO' ? 'text-success' : 'text-danger' }}"
                                style="font-size: 0.75rem;"
                            >
                                <strong>MENSAJE:</strong>
                                {{ $oracleData->MENSAJE_ERROR }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-layout.page-container>
@endsection
