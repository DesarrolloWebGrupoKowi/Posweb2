@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitudes Factura')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <x-layout.section-title>
                <x-title titulo="Solicitudes Factura" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </x-layout.section-title>
            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.select-input
                        name="idTienda"
                        label="Tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    />
                    <x-filters.inputs.date-input
                        name="fecha"
                        label="Fecha"
                        :value="request('fecha')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.text-input
                        name="rfc"
                        label="RFC"
                        placeholder="Buscar por RFC"
                    />
                    <x-filters.inputs.text-input
                        name="nombre"
                        label="Nombre"
                        placeholder="Buscar por Nombre"
                    />
                    {{-- <x-filters.inputs.text-input
                        name="pos"
                        label="Pedido"
                        placeholder="Buscar por Pedido POS_000000"
                    /> --}}
                    {{-- <x-filters.inputs.checkbox-input
                        name="sinProcesar"
                        label="Sin Procesar"
                        :checked="request('sinProcesar') == 'on'"
                        helperText="Ver sin procesar"
                    /> --}}
                </x-filters.filter-group>
                <x-slot:buttons>
                    <x-filters.buttons.clear-button />
                    {{-- <x-filters.buttons.advanced-button
                        :active="$filtrosAvanzadosActivos"
                        :hasBadge="true"
                    /> --}}
                    <x-filters.buttons.submit-button />
                </x-slot:buttons>
            </x-filters.filter-form>
        </x-layout.section-card>

        <!-- SECCIÓN: TABLAS -->
        <div
            class="flex-grow-1 d-flex gap-4 pb-4"
            {{-- style="min-height: 0;" --}}
        >
            <div
                class="d-flex flex-column"
                {{-- style="flex: 2; min-width: 0; min-height: 0;" --}}
                style="flex: 2; min-width: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    {{-- style="border-radius: 10px; min-height: 0;" --}}
                    style="border-radius: 10px;"
                >
                    <div class="table-responsive content-table-sm">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>Folio</th>
                                    <th>Ticket</th>
                                    <th>Folio Encriptado</th>
                                    <th>Tienda</th>
                                    <th>Fecha</th>
                                    <th>RFC</th>
                                    <th>Nombre</th>
                                    <th>Total</th>
                                    <th>Pedido</th>
                                    {{-- <th>Cliente</th> --}}
                                    {{-- <th>MP</th>
                                <th>CFDI</th> --}}
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($solicitudes as $solicitud)
                                    {{-- <tr style="line-height: .9rem"> --}}
                                    <tr>
                                        <td>{{ $solicitud->IdSolicitudFactura }}</td>
                                        <td>{{ $solicitud->IdEncabezado }}</td>
                                        <td> {{ \Vinkla\Hashids\Facades\Hashids::encode($solicitud->IdEncabezado) }}</td>
                                        <td style="min-width: 150px;">{{ $solicitud->NomTienda }}</td>
                                        <td style="min-width: 120px;">
                                            {{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                        <td>{{ $solicitud->RFC }}</td>
                                        <td style="min-width: 230px;">{{ $solicitud->NomCliente }}</td>
                                        <td class="text-end">${{ number_format($solicitud->TotalFactura, 2) }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                @if ($solicitud->Source_Transaction_Identifier)
                                                    <span
                                                        class="tags-blue"
                                                        style="white-space: nowrap;"
                                                    >
                                                        {{ $solicitud->Source_Transaction_Identifier }}
                                                    </span>
                                                @elseif($solicitud->Editar !== null)
                                                    <span
                                                        class="tags-red"
                                                        style="white-space: nowrap;"
                                                    >
                                                        SIN LIGAR
                                                    </span>
                                                    @if ($solicitud->Editar == '0')
                                                        <span class="tags-red">
                                                            NUEVO
                                                        </span>
                                                    @else
                                                        <span class="tags-red">
                                                            ACTUALIZAR
                                                        </span>
                                                    @endif
                                                @else
                                                    <span
                                                        class="tags-red"
                                                        style="white-space: nowrap;"
                                                    >
                                                        SIN PEDIDO
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($solicitud->Status == 1)
                                                <span class="tags-red">Cancelada</span>
                                            @elseif ($solicitud->Status == 0 && $solicitud->Editar != null)
                                                <span
                                                    class="tags-yellow"
                                                    style="white-space: nowrap;"
                                                >SIN PROCESAR</span>
                                            @else
                                                @if ($solicitud->InterfaceStatus == 'PROCESADO')
                                                    <span class="tags-green">{{ $solicitud->InterfaceStatus }}</span>
                                                @elseif ($solicitud->InterfaceStatus == 'ERROR')
                                                    <span class="tags-red">{{ $solicitud->InterfaceStatus }}</span>
                                                @elseif ($solicitud->InterfaceStatus == 'PENDIENTE')
                                                    <span class="tags-yellow">{{ $solicitud->InterfaceStatus }}</span>
                                                @else
                                                    <span
                                                        class="tags-yellow"
                                                        style="white-space: nowrap;"
                                                    >{{ $solicitud->InterfaceStatus ?: 'SIN PROCESAR' }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                {{-- <button
                                                    class="btn btn-sm btn-outline-primary d-flex gap-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalTickets{{ $solicitud->Id }}"
                                                    title="Detalle de ticket"
                                                >
                                                    @include('components.icons.list-ol')
                                                    <span class="d-none d-md-inline">DETALLE</span>
                                                </button> --}}
                                                <a
                                                    href="/SolicitudesFactura/{{ $solicitud->IdSolicitudFactura }}"
                                                    {{-- target="_blank" --}}
                                                    class="btn btn-sm btn-outline-primary d-flex gap-2"
                                                    title="Ver detalle de solicitud"
                                                >
                                                    @include('components.icons.list')
                                                    <span class="d-none d-md-inline">VER</span>
                                                </a>
                                                {{-- @if ($solicitud->Status == 0 && !$solicitud->InterfaceStatus == 'PROCESADO')
                                                    <button
                                                        class="btn btn-sm btn-outline-danger d-flex gap-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#ModalCancelarSolicitud{{ $solicitud->Id }}"
                                                        title="Cancelar solicitud"
                                                    >
                                                        @include('components.icons.delete')
                                                        <span class="d-none d-md-inline">Cancelar</span>
                                                    </button>
                                                @endif --}}
                                                {{-- @include('SolicitudesFactura.ModalTickets') --}}
                                                @include('SolicitudesFactura.ModalCancelarSolicitud')
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="14"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="Sin datos disponibles"
                                                icon="filter"
                                                :message="'No se encontraron resultados con los filtros seleccionados.'"
                                                :suggestion="'Intenta ampliar el rango de fechas o modificar los criterios de búsqueda.'"
                                                action="Limpiar filtros"
                                                actionUrl="/SolicitudesFactura"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('components.paginate', ['items' => $solicitudes])
                </div>
            </div>
        </div>

    </x-layout.page-container>

    <style>
        .table thead th {
            position: sticky;
            top: 0;
            background: rgb(30, 41, 59);
            z-index: 2;
        }
    </style>
@endsection
