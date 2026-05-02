@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Configuración de Tienda')
@section('dashboardWidth', 'width-95')
@section('contenido')

    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <!-- HEADER CON FILTROS -->
        <div class="card border-0 p-4"
            style="border-radius: 10px; background-color: white;">
            <div class="row gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', ['titulo' => 'Configuración de Tienda'])
                </div>

                <!-- Selector de Tienda -->
                <form id="dateForm"
                    action=""
                    method="GET"
                    class="col-12 col-lg d-lg-flex justify-content-end">
                    <div class="row">
                        {{-- Campo de Tienda --}}
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px">Tienda</span>
                                <select class="form-control border-gray-300"
                                    id="tiendaSelect"
                                    name="tienda_id"
                                    style="font-size: 1rem;">
                                    <option value="-1">TODAS LAS TIENDAS</option>
                                    @foreach ($tiendas as $tienda)
                                        <option value="{{ $tienda->IdTienda }}"
                                            {{ request()->get('tienda_id') == $tienda->IdTienda ? 'selected' : '' }}>
                                            {{ $tienda->NomTienda }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Botón de Búsqueda --}}
                        <div class="col-12 col-md-auto">
                            <div>
                                <button type="submit"
                                    class="btn btn-outline-dark bg-dark text-white w-100"
                                    title="Buscar">
                                    @include('components.icons.search')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

            {{-- @if (isset($tiendaActual))
                <!-- Título de tienda seleccionada -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <span class="text-primary">@include('components.icons.store')</span>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $tiendaActual->NomTienda }}</h4>
                        <small class="text-muted">{{ $tiendaActual->NombreCorto ?? 'Sin nombre corto' }}</small>
                    </div>
                    <div class="ms-auto">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center">
                                <div class="me-2"
                                    style="width: 8px; height: 8px; border-radius: 50%;
                                         background-color: {{ $tiendaActual->Status == 0 ? '#10b981' : '#ef4444' }};">
                                </div>
                                <span
                                    class="small fw-500 {{ $tiendaActual->Status == 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $tiendaActual->Status == 0 ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif --}}
        </div>

        @if (isset($tiendaActual))
            <!-- SECCIÓN PRINCIPAL DE CONFIGURACIÓN -->
            <div class="row g-4">
                <!-- INFORMACIÓN BÁSICA DE LA TIENDA -->
                <div class="col-xl-5">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-gray-800 d-flex align-items-center"
                                style="font-size: 1.25rem; font-weight: 600;">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px;">
                                    <span class="text-primary">@include('components.icons.info')</span>
                                </div>
                                Información Básica
                            </h4>
                            <button class="btn btn-outline-primary"
                                onclick="editarInfoBasica()">
                                <span class="me-1">@include('components.icons.edit')</span>Editar
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Nombre de la Tienda</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->NomTienda ?? 'No especificada' }}</p>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Nombre Corto</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->NombreCorto ?? 'No especificada' }}</p>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Dirección</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Direccion ?? 'No especificada' }}</p>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Colonia</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Colonia ?? 'No especificada' }}</p>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Teléfono</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Telefono ?? 'No especificado' }}</p>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Correo Principal</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Correo ?? 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONFIGURACIÓN DEL SISTEMA -->
                <div class="col-xl-7">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-gray-800 d-flex align-items-center"
                                style="font-size: 1.25rem; font-weight: 600;">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px;">
                                    <span class="text-primary">@include('components.icons.file-text')</span>
                                </div>
                                Información fiscal y ERP
                            </h4>
                            <button class="btn btn-outline-primary"
                                onclick="editarInfoBasica()">
                                <span class="me-1">@include('components.icons.edit')</span>Editar
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-xl-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">RFC</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->RFC ?? 'No especificado' }}</p>
                            </div>

                            <div class="col-md-6 col-xl-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Centro de Costo</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->CentroCosto ?? 'No especificado' }}</p>
                            </div>

                            <div class="col-md-6 col-xl-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Lista de Precios</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">
                                    {{ $tiendaActual->IdListaPrecios }}
                                </p>
                            </div>

                            <div class="col-md-6 col-xl-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Almacén ERP</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Almacen ?? 'No especificado' }}</p>
                            </div>

                            <div class="col-md-6 col-xl-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Organización ERP</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Organization_Name ?? 'No especificado' }}
                                </p>
                            </div>

                            <div class="col-md-6 col-xl-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Subinventario</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Subinventory_Code ?? 'No especificado' }}
                                </p>
                            </div>

                            <div class="col-md-6 col-xl-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Tipo de Orden Cloud</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->Order_Type_Cloud ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CAJAS ASIGNADAS -->
                <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb; height: 100%;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-gray-800 d-flex align-items-center"
                                style="font-size: 1.25rem; font-weight: 600;">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px;">
                                    <span class="text-primary">@include('components.icons.cash')</span>
                                </div>
                                Cajas Asignadas
                            </h4>
                            <span class="text-muted"
                                style="font-size: 0.95rem;">{{ $cajas->where('asignado', 1)->count() }} asignada(s)</span>
                        </div>

                        @if (count($cajas) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0"
                                    style="font-size: 0.95rem;">
                                    <thead>
                                        <tr>
                                            <th>Caja</th>
                                            <th>Estado</th>
                                            <th>Última Venta</th>
                                            <th width="100px"
                                                class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cajas as $caja)
                                            <tr>
                                                <td>
                                                    <div class="fw-500"
                                                        style="font-size: 1rem;">CAJA {{ $caja->IdCaja ?? 'N/A' }}</div>
                                                    @if ($caja->IdEncabezado)
                                                        <span class="text-muted"
                                                            style="font-size: 0.85rem;">Enc:
                                                            {{ $caja->IdEncabezado }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $caja->asignado ? 'bg-success' : 'bg-light text-muted border' }}"
                                                        style="font-size: 0.85rem; padding: 0.35em 0.65em;">
                                                        {{ $caja->asignado ? 'Asignada' : 'Libre' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($caja->FechaVenta)
                                                        <span
                                                            style="font-size: 0.9rem;">{{ \Carbon\Carbon::parse($caja->FechaVenta)->format('d/m/Y H:i') }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if ($caja->asignado)
                                                        <!-- <button class="btn btn-sm btn-outline-danger"
                                                                                                                                    onclick="desasignarCaja({{ $caja->IdCaja }})"
                                                                                                                                    title="Desasignar">
                                                                                                                                    @include('components.icons.x')
                                                                                                                                </button> -->
                                                    @else
                                                        <button class="btn btn-outline-primary"
                                                            onclick="asignarCaja({{ $caja->IdCaja }})"
                                                            title="Asignar">
                                                            @include('components.icons.plus')
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="text-muted mb-3"
                                    style="font-size: 3rem;">
                                    @include('components.icons.cash')
                                </div>
                                <p class="text-muted mb-0"
                                    style="font-size: 1rem;">No hay cajas disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- CORREOS ASIGNADOS -->
                <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb; height: 100%;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-gray-800 d-flex align-items-center"
                                style="font-size: 1.25rem; font-weight: 600;">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px;">
                                    <span class="text-primary">@include('components.icons.mail')</span>
                                </div>
                                Correos Asignados
                            </h4>
                            <button class="btn btn-outline-primary"
                                onclick="gestionarCorreos()">
                                <span class="me-1">@include('components.icons.plus')</span>Gestionar
                            </button>
                        </div>

                        @php
                            $tiposCorreos = [
                                'EncargadoCorreo' => 'Encargado',
                                'GerenteCorreo' => 'Gerente',
                                'SupervisorCorreo' => 'Supervisor',
                                'AdministrativaCorreo' => 'Administrativa',
                                'AlmacenistaCorreo' => 'Almacenista',
                                'RecepcionCorreo' => 'Recepción',
                                'FacturistaCorreo' => 'Facturista',
                            ];

                            $hayCorreos = false;
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover mb-0"
                                style="font-size: 0.95rem;">
                                <thead>
                                    <tr>
                                        <th width="30%"
                                            style="font-size: 0.95rem; font-weight: 600;">Tipo</th>
                                        <th style="font-size: 0.95rem; font-weight: 600;">Correo(s)</th>
                                        <th width="15%"
                                            class="text-center"
                                            style="font-size: 0.95rem; font-weight: 600;">Principal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tiposCorreos as $campo => $tipoNombre)
                                        @if (!empty($tiendaActual->$campo))
                                            @php $hayCorreos = true; @endphp
                                            <tr>
                                                <td>
                                                    <span class="fw-500"
                                                        style="font-size: 1rem;">{{ $tipoNombre }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $correos = array_filter(
                                                            array_map('trim', explode(';', $tiendaActual->$campo)),
                                                        );
                                                    @endphp
                                                    @foreach ($correos as $index => $correo)
                                                        <div class="{{ $index > 0 ? 'mt-2' : '' }}">
                                                            <span class="text-muted"
                                                                style="font-size: 0.9rem;">{{ $correo }}</span>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @if ($campo == 'EncargadoCorreo')
                                                        <span class="badge bg-primary"
                                                            style="font-size: 0.85rem; padding: 0.35em 0.65em;">Sí</span>
                                                    @else
                                                        <span class="badge bg-light text-muted border"
                                                            style="font-size: 0.85rem; padding: 0.35em 0.65em;">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TIPOS DE PAGO PERMITIDOS -->
                {{-- <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-gray-800">
                                <span class="me-2 text-primary">@include('components.icons.credit-card')</span>
                                Tipos de Pago Permitidos
                            </h5>
                        </div>

                        @if (count($tiposPago) > 0)
                            <div class="row">
                                @foreach ($tiposPago as $tipo)
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-switch me-2">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    {{ $tipo->asignado ? 'checked' : '' }}>
                                            </div>
                                            <div>
                                                <label class="form-label small fw-500 text-muted mb-0">
                                                    {{ $tipo->NomTipoPago ?? 'Sin nombre' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted mb-0">No hay tipos de pago configurados</p>
                            </div>
                        @endif
                    </div>
                </div> --}}
                <!-- TIPOS DE PAGO PERMITIDOS -->
                <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-gray-800 d-flex align-items-center"
                                style="font-size: 1.25rem; font-weight: 600;">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px;">
                                    <span class="text-primary">@include('components.icons.credit-card')</span>
                                </div>
                                Tipos de Pago Permitidos
                            </h4>
                            <span class="text-muted"
                                style="font-size: 0.95rem;">{{ $tiposPago->where('asignado', true)->count() }}
                                activo(s)</span>
                        </div>

                        @if (count($tiposPago) > 0)
                            <div class="row g-2">
                                @foreach ($tiposPago as $tipo)
                                    <div class="col-12">
                                        <div
                                            class="d-flex align-items-center justify-content-between py-3 px-3 rounded hover-light">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-switch me-3 mb-0">
                                                    <input class="form-check-input toggle-pago"
                                                        type="checkbox"
                                                        data-id="{{ $tipo->IdTipoPago }}"
                                                        {{ $tipo->asignado ? 'checked' : '' }}
                                                        onchange="toggleTipoPago(this, {{ $tipo->IdTipoPago }})">
                                                </div>
                                                <div>
                                                    <div class="fw-500"
                                                        style="font-size: 1rem;">{{ $tipo->NomTipoPago ?? 'Sin nombre' }}
                                                    </div>
                                                    @if ($tipo->descripcion ?? false)
                                                        <div class="text-muted"
                                                            style="font-size: 0.85rem;">{{ $tipo->descripcion }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if ($tipo->asignado)
                                                    <span class="badge bg-success bg-opacity-10 text-success border-0"
                                                        style="font-size: 0.85rem; padding: 0.35em 0.65em;">
                                                        Habilitado
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted mb-0">No hay tipos de pago configurados</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- LISTAS DE PRECIO DISPONIBLES -->
                <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <h4 class="mb-4 text-gray-800 d-flex align-items-center"
                            style="font-size: 1.25rem; font-weight: 600;">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 42px; height: 42px;">
                                <span class="text-primary">@include('components.icons.tag')</span>
                            </div>
                            Listas de Precio
                        </h4>

                        @if (count($listaPrecio) > 0)
                            <div class="row">
                                @foreach ($listaPrecio as $item)
                                    <div class="col-md-12 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-switch me-3">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    {{ $item->asignado ? 'checked' : '' }}>
                                            </div>
                                            <div>
                                                <label class="form-label fw-500 text-muted mb-0"
                                                    style="font-size: 1rem;">
                                                    {{ $item->NomListaPrecio ?? 'Sin nombre' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0"
                                    style="font-size: 1rem;">No hay listas de precio configuradas</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- AUDITORÍA Y CONTROL -->
                <div class="col-xl-12">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">

                        <h4 class="mb-4 text-gray-800 d-flex align-items-center"
                            style="font-size: 1.25rem; font-weight: 600;">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 42px; height: 42px;">
                                <span class="text-warning">@include('components.icons.info')</span>
                            </div>
                            Auditoría y control
                        </h4>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Procesar corte</label>
                                <p class="mb-0">
                                    <span
                                        class="badge {{ $tiendaActual->procesarcorte == 0 ? 'bg-success' : 'bg-warning text-dark' }}"
                                        style="font-size: 0.85rem; padding: 0.35em 0.65em;">
                                        {{ $tiendaActual->procesarcorte == 0 ? 'Activo' : 'Detenido' }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Última actualización</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">
                                    {{ \Carbon\Carbon::parse($tiendaActual->fechaprocesarcorte)->format('d/m/Y H:i') ?? '—' }}
                                </p>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-500 text-muted mb-2"
                                    style="font-size: 0.9rem;">Usuario</label>
                                <p class="mb-0"
                                    style="font-size: 1rem;">{{ $tiendaActual->ceNombre ?? '—' }}
                                    {{ $tiendaActual->ceApellidos ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PERMISOS DE TRASPASO -->
                {{-- <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <h5 class="mb-3 text-gray-800">
                            <span class="me-2 text-primary">@include('components.icons.switch')</span>Permisos de Traspaso
                        </h5>

                        @if (count($tiendasTraspaso) > 0)
                            <div class="mt-3">
                                <label class="form-label small fw-500 text-muted">Tiendas de destino permitidas:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($tiendasTraspaso as $tiendaDestino)
                                        <span class="badge bg-light text-dark border">
                                            {{ $tiendaDestino->NomTienda }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div> --}}
                <!-- PERMISOS DE TRASPASO -->
                <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-gray-800 d-flex align-items-center"
                                style="font-size: 1.25rem; font-weight: 600;">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px;">
                                    <span class="text-primary">@include('components.icons.switch')</span>
                                </div>
                                Permisos de Traspaso
                            </h4>
                            <button class="btn btn-outline-primary"
                                onclick="gestionarTraspasos()">
                                <span class="me-1">@include('components.icons.edit')</span>Gestionar
                            </button>
                        </div>

                        @if (count($tiendasTraspaso) > 0)
                            <div class="mb-4">
                                <label class="form-label fw-500 text-muted mb-3"
                                    style="font-size: 0.95rem;">
                                    Tiendas destino permitidas ({{ count($tiendasTraspaso) }}):
                                </label>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach ($tiendasTraspaso->take(5) as $tiendaDestino)
                                        <span class="badge bg-light text-dark border d-flex align-items-center gap-1"
                                            style="font-size: 0.85rem; padding: 0.4em 0.7em;">
                                            {{ $tiendaDestino->NombreCorto ?? $tiendaDestino->NomTienda }}
                                            <button type="button"
                                                class="btn-close btn-close-sm"
                                                onclick="removerTraspaso({{ $tiendaDestino->IdTienda }})"
                                                style="font-size: 0.6rem;"></button>
                                        </span>
                                    @endforeach
                                    @if (count($tiendasTraspaso) > 5)
                                        <span class="badge bg-light text-muted border"
                                            style="font-size: 0.85rem; padding: 0.4em 0.7em;">
                                            +{{ count($tiendasTraspaso) - 5 }} más
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-4"
                                    style="font-size: 1rem;">No hay permisos de traspaso configurados</p>
                                <button class="btn btn-outline-primary"
                                    onclick="gestionarTraspasos()">
                                    <span class="me-1">@include('components.icons.plus')</span>Agregar Tiendas
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- USUARIOS ACTIVOS -->
                <div class="col-xl-6">
                    <div class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0 text-gray-800 d-flex align-items-center"
                                style="font-size: 1.25rem; font-weight: 600;">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px;">
                                    <span class="text-primary">@include('components.icons.users')</span>
                                </div>
                                Usuarios Asignados
                            </h4>
                            <button class="btn btn-outline-primary"
                                onclick="gestionarUsuarios()">
                                <span class="me-1">@include('components.icons.user')</span>Asignar
                            </button>
                        </div>

                        @if (count($usuarios) > 0)
                            <div class="table-responsive">
                                <table class="table"
                                    style="font-size: 0.95rem;">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 0.95rem; font-weight: 600;">Usuario</th>
                                            <th style="font-size: 0.95rem; font-weight: 600;">Nombre</th>
                                            <th style="font-size: 0.95rem; font-weight: 600;">Perfil</th>
                                            <th style="font-size: 0.95rem; font-weight: 600;">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usuarios as $usuario)
                                            <tr>
                                                <td style="font-size: 1rem;">{{ $usuario->NomUsuario ?? '' }}</td>
                                                <td style="font-size: 1rem;">{{ $usuario->Nombre ?? 'N/A' }}
                                                    {{ $usuario->Apellidos ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-info"
                                                        style="font-size: 0.85rem; padding: 0.35em 0.65em;">
                                                        {{ $usuario->NomTipoUsuario ?? 'Sin perfil' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $usuario->Status == 0 ? 'bg-success' : 'bg-secondary' }}"
                                                        style="font-size: 0.85rem; padding: 0.35em 0.65em;">
                                                        {{ $usuario->Status == 0 ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0"
                                    style="font-size: 1rem;">No hay usuarios asignados a esta tienda</p>
                            </div>
                        @endif

                        <div class="mt-4">
                            <span class="text-muted"
                                style="font-size: 0.9rem;">
                                <span class="me-1">@include('components.icons.info')</span>
                                Total: {{ count($usuarios) }} usuario(s) asignado(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- ESTADO SIN TIENDA SELECCIONADA -->
            <div class="card border-0 p-4 text-center"
                style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                <div class="text-muted mb-3"
                    style="font-size: 4rem;">
                    <x-icons.store-size size="4rem"
                        color="#9ca3af" />
                </div>
                <h4 class="text-muted mb-3">Selecciona una tienda</h4>
                <p class="text-muted mb-4">Por favor, selecciona una tienda del menú desplegable para ver su configuración.
                </p>
            </div>
        @endif
    </div>
@endsection

@section('styles')
    <style>
        .form-check-input:checked {
            background-color: #1e429f;
            border-color: #1e429f;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        /* .badge {
                                                                    font-weight: 500;
                                                                } */

        .hover-light:hover {
            background-color: #f8f9fa;
        }

        .btn-close-sm {
            padding: 0.25rem;
            font-size: 0.75rem;
        }

        /* Toggle switches personalizados */
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .form-check-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        /* Badges más compactos */
        .badge {
            padding: 0.25em 0.5em;
            font-size: 0.75em;
            font-weight: 500;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function editarInfoBasica() {
            // Implementar modal de edición
            alert('Funcionalidad de edición en desarrollo');
        }

        function gestionarCajas() {
            // Implementar gestión de cajas
            alert('Gestión de cajas en desarrollo');
        }

        function gestionarCorreos() {
            // Implementar gestión de correos
            alert('Gestión de correos en desarrollo');
        }

        function gestionarTiposPago() {
            // Implementar gestión de tipos de pago
            alert('Configuración de tipos de pago en desarrollo');
        }

        function gestionarUsuarios() {
            // Implementar gestión de usuarios
            alert('Gestión de usuarios en desarrollo');
        }
    </script>
@endsection
