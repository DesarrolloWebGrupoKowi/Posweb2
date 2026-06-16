@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Plazas')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="shop-window"
            title="Catálogo de Plazas"
            subtitle="Gestión de plazas del sistema"
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
                    action="/CatPlazas"
                    method="get"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-filter me-1"></i>Filtrar por estatus
                            </label>
                            <select
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                name="activo"
                                id="activo"
                                onchange="this.form.submit()"
                            >
                                <option value="">Estatus de plaza</option>
                                <option
                                    {{ $activo == '0' ? 'selected' : '' }}
                                    value="0"
                                >Activas</option>
                                <option
                                    {{ $activo == 1 ? 'selected' : '' }}
                                    value="1"
                                >Inactivas</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a
                                href="/CatPlazas"
                                class="btn btn-sm d-flex align-items-center gap-2"
                                style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px;"
                            >
                                <i class="fa fa-times-circle"></i> Limpiar
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
                                class="fa fa-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Plazas
                        </h5>
                        <p class="section-content-subtitle">Listado de plazas registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar plaza
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-building me-1"></i>Ciudad</th>
                                <th><i class="fa fa-map-marker me-1"></i>Estado</th>
                                <th><i class="fa fa-circle me-1"></i>Estatus</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($plazas as $plaza)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $plaza->IdPlaza }}</td>
                                    <td style="font-weight: 500;">{{ $plaza->NomPlaza }}</td>
                                    <td>{{ $plaza->ccNomCiudad }}</td>
                                    <td>
                                        <span
                                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            {{ $plaza->ceNomEstado }}
                                        </span>
                                    </td>
                                    <td>
                                        <x-status-badge :status="!$plaza->Status" />
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$plaza->IdPlaza"
                                                modal="ModalEditar"
                                                title="Editar plaza"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('Plazas.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="6"
                                    title="Sin datos disponibles"
                                    message="No se encontraron plazas con los filtros seleccionados"
                                    icon="shop"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatPlazas"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

    <!-- Modal Agregar Plaza -->
    @include('Plazas.ModalAgregar')
@endsection
