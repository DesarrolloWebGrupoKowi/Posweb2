@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Tipos de Articulo')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <x-card-gradient-header
            icon="tag"
            title="Catálogo de Tipos de Artículo"
            subtitle="Gestión de tipos de artículo del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Tabla -->
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="fa fa-list me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Tipos de Artículo
                        </h5>
                        <p class="section-content-subtitle">Listado de tipos de artículo registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarTipoArticulo"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar tipo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-briefcase me-1"></i>Unidad de Negocio</th>
                                <th><i class="fa fa-tag me-1"></i>Tipo de Artículo</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tiposArticulo as $tipoArticulo)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $tipoArticulo->IdTipoArticulo }}</td>
                                    <td style="font-weight: 500;">{{ $tipoArticulo->NomTipoArticulo }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.delete-button
                                                :id="$tipoArticulo->IdCatTipoArticulo"
                                                modal="ModalEliminarTipoArticulo"
                                                title="Eliminar tipo de artículo"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('TipoArticulos.ModalEliminarTipoArticulo')
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron tipos de artículo registrados"
                                    icon="tag"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </div>

    @include('TipoArticulos.ModalAgregarTipoArticulo')
@endsection
