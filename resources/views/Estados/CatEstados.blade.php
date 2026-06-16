@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Estados')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="geo-alt"
            title="Catálogo de Estados"
            subtitle="Gestión de estados del sistema"
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
                    action="/CatEstados"
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
                                name="Activo"
                                id="Activo"
                                onchange="this.form.submit()"
                            >
                                <option value="">Estatus de estado</option>
                                <option
                                    {{ $activo == '0' ? 'selected' : '' }}
                                    value="0"
                                >Activos</option>
                                <option
                                    {{ $activo == '1' ? 'selected' : '' }}
                                    value="1"
                                >Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a
                                href="/CatEstados"
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
                            ></i>Concentrado de Estados
                        </h5>
                        <p class="section-content-subtitle">Listado de estados registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar Estado
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id Estado</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-circle me-1"></i>Estatus</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($estados as $estado)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $estado->IdEstado }}</td>
                                    <td style="font-weight: 500;">{{ $estado->NomEstado }}</td>
                                    <td>
                                        <x-status-badge :status="!$estado->Status" />
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$estado->IdEstado"
                                                modal="ModalEditar"
                                                title="Editar estado"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('Estados.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="4"
                                    title="Sin datos disponibles"
                                    message="No se encontraron estados registrados"
                                    icon="map"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatEstados"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $estados])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    <!-- Modal Agregar Estado -->
    @include('Estados.ModalAgregar')
@endsection
