@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Ciudades')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="building"
            title="Catálogo de Ciudades"
            subtitle="Gestión de ciudades del sistema"
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
                    action="/CatCiudades"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-search me-1"></i>Buscar ciudad
                            </label>
                            <input
                                type="text"
                                name="txtFiltro"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Nombre de ciudad..."
                                value="{{ request('txtFiltro') }}"
                                autofocus
                            >
                        </div>
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-map-marker me-1"></i>Estado
                            </label>
                            <select
                                name="IdEstado"
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            >
                                <option value="">Todos</option>
                                @foreach ($estados ?? [] as $estado)
                                    <option
                                        value="{{ $estado->IdEstado }}"
                                        {{ request('IdEstado') == $estado->IdEstado ? 'selected' : '' }}
                                    >
                                        {{ $estado->NomEstado }}
                                    </option>
                                @endforeach
                            </select>
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
                                    href="/CatCiudades"
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
                                class="fa fa-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Ciudades
                        </h5>
                        <p class="section-content-subtitle">Listado de ciudades registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar ciudad
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-map-marker me-1"></i>Estado</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ciudades as $ciudad)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $ciudad->IdCiudad }}</td>
                                    <td style="font-weight: 500;">{{ $ciudad->NomCiudad }}</td>
                                    <td>
                                        <span
                                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            {{ $ciudad->NomEstado }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$ciudad->IdCiudad"
                                                modal="ModalEditar"
                                                title="Editar ciudad"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('Ciudades.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="4"
                                    title="Sin datos disponibles"
                                    message="No se encontraron ciudades con los filtros seleccionados"
                                    icon="building"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatCiudades"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $ciudades])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    <!-- Modal Agregar Ciudad -->
    @include('Ciudades.ModalAgregar')
@endsection
