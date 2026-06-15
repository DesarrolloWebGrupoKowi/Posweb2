@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Familias')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <x-card-gradient-header
            icon="folder"
            title="Catálogo de Familias"
            subtitle="Gestión de familias del sistema"
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
                    action="/CatFamilias"
                >
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="fa fa-search me-1"></i>Buscar familia
                            </label>
                            <input
                                type="text"
                                name="txtFiltro"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Nombre de familia..."
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
                                    href="/CatFamilias"
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
                            ></i>Concentrado de Familias
                        </h5>
                        <p class="section-content-subtitle">Listado de familias registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar familia
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-folder me-1"></i>Familia</th>
                                {{-- <th><i class="fa fa-cog me-1"></i>Acciones</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($familias as $familia)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $familia->IdFamilia }}</td>
                                    <td style="font-weight: 500;">{{ $familia->NomFamilia }}</td>
                                    {{-- <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$familia->IdFamilia"
                                                modal="ModalEditar-"
                                                title="Editar familia"
                                                label="Editar"
                                            />
                                        </div>
                                    </td> --}}
                                </tr>
                                @include('Familias.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron familias con los filtros seleccionados"
                                    icon="folder"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatFamilias"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $familias])
            </div>
        </x-card-gradient-header>
    </div>

    @include('Familias.ModalAgregar')
@endsection
