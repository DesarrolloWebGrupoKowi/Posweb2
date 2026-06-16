@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Tipo de Menús')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="list-columns-reverse"
            title="Catálogo de Tipos de Menús"
            subtitle="Gestión de tipos de menú del sistema"
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
                    action="/CatTipoMenu"
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
                                <option
                                    {{ $activo == 0 ? 'selected' : '' }}
                                    value="0"
                                >Activos</option>
                                <option
                                    {{ $activo == 1 ? 'selected' : '' }}
                                    value="1"
                                >Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a
                                href="/CatTipoMenu"
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
                            ></i>Concentrado de Tipos de Menú
                        </h5>
                        <p class="section-content-subtitle">Listado de tipos de menú registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar tipo de menú
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tipoMenus as $tipoMenu)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $tipoMenu->IdTipoMenu }}</td>
                                    <td style="font-weight: 500;">{{ $tipoMenu->NomTipoMenu }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$tipoMenu->IdTipoMenu"
                                                modal="ModalEditar"
                                                title="Editar tipo de menú"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('TipoMenu.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron tipos de menú registrados"
                                    icon="tags"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatTipoMenu"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

    <!-- Modal Agregar Tipo de Menú -->
    @include('TipoMenu.ModalAgregar')
@endsection
