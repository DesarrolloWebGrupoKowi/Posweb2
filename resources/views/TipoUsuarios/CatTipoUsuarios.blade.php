@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Tipo de Usuarios')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <x-card-gradient-header
            icon="person-gear"
            title="Tipo de Usuarios"
            subtitle="Gestión de tipos de usuario del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros -->
            <div
                class="border-bottom p-4"
                style="border-color: #f1f5f9 !important;"
            >
                <form
                    action="/CatTipoUsuarios"
                    id="formTipoUsuarios"
                    method="get"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-funnel me-1"></i>Filtrar por estatus
                            </label>
                            <select
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                name="filtroActivo"
                                id="filtroActivo"
                                onchange="document.getElementById('formTipoUsuarios').submit()"
                            >
                                <option
                                    {{ $filtroActivo == 0 ? 'selected' : '' }}
                                    value="0"
                                >Activos</option>
                                <option
                                    {{ $filtroActivo == 1 ? 'selected' : '' }}
                                    value="1"
                                >Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a
                                href="/CatTipoUsuarios"
                                class="btn btn-sm d-flex align-items-center gap-2"
                                style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px;"
                            >
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla -->
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Tipos de Usuario
                        </h5>
                        <p class="section-content-subtitle">Listado de tipos de usuario registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="bi bi-plus-circle"></i> Agregar tipo de usuario
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>Id Tipo de Usuario</th>
                                <th><i class="bi bi-shield me-1"></i>Tipo de Usuario</th>
                                <th><i class="bi bi-gear me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tipoUsuarios as $tipoUsuario)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $tipoUsuario->IdTipoUsuario }}</td>
                                    <td style="font-weight: 500;">{{ $tipoUsuario->NomTipoUsuario }}</td>
                                    <td>
                                        @if ($filtroActivo != 1)
                                            <div class="d-flex gap-2">
                                                <x-table.buttons.edit-button
                                                    :id="$tipoUsuario->IdTipoUsuario"
                                                    modal="ModalEditar"
                                                    title="Editar tipo de usuario"
                                                    label="Editar"
                                                />
                                                <x-table.buttons.delete-button
                                                    :id="$tipoUsuario->IdTipoUsuario"
                                                    modal="ModalConfirmar"
                                                    title="Desactivar tipo de usuario"
                                                />
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @include('TipoUsuarios.ModalEditar')
                                @include('TipoUsuarios.ModalConfirmar')
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron tipos de usuario registrados"
                                    icon="shield"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatTipoUsuarios"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $tipoUsuarios])
            </div>
        </x-card-gradient-header>
    </div>

    <!-- Modal Agregar Tipo de Usuario -->
    @include('TipoUsuarios.ModalAgregar')

    <script src="js/scriptTipoUsuarios.js"></script>
@endsection
