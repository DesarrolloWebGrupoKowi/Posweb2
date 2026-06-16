@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Tablas Para Actualizar')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="table"
            title="Catálogo de Tablas"
            subtitle="Gestión de tablas para actualizar del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros de búsqueda -->
            <div
                class="border-bottom p-4"
                style="border-color: #f1f5f9 !important;"
            >
                <form
                    method="GET"
                    action="/CatTablas"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-search me-1"></i>Buscar tabla
                            </label>
                            <input
                                type="text"
                                name="txtFiltro"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Nombre de tabla..."
                                value="{{ request('txtFiltro') }}"
                                autofocus
                            >
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button
                                    type="submit"
                                    class="btn btn-sm d-flex align-items-center flex-grow-1 gap-2"
                                    style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px;"
                                >
                                    <i class="fa fa-filter"></i> Filtrar
                                </button>
                                <a
                                    href="/CatTablas"
                                    class="btn btn-sm d-flex align-items-center gap-2"
                                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px;"
                                >
                                    <i class="fa fa-times-circle"></i> Limpiar
                                </a>
                            </div>
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
                                class="fa fa-list me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Tablas
                        </h5>
                        <p class="section-content-subtitle">Listado de tablas registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarTabla"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar tabla
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-table me-1"></i>Tabla</th>
                                <th><i class="fa fa-circle me-1"></i>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tablas as $tabla)
                                <tr>
                                    <td style="font-weight: 500;">{{ $tabla->NomTabla }}</td>
                                    <td>
                                        <x-status-badge :status="!$tabla->Status" />
                                    </td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="2"
                                    title="Sin datos disponibles"
                                    message="No se encontraron tablas con los filtros seleccionados"
                                    icon="database"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatTablas"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $tablas])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('TablasUpdate.ModalAgregarTabla')
@endsection
