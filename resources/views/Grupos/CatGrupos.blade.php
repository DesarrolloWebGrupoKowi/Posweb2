@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo Grupos')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="folder-symlink"
            title="Catálogo de Grupos"
            subtitle="Gestión de grupos del sistema"
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
                                class="fa fa-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Grupos
                        </h5>
                        <p class="section-content-subtitle">Listado de grupos registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar grupo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-folder me-1"></i>Grupo</th>
                                <th><i class="fa fa-circle me-1"></i>Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grupos as $grupo)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $grupo->IdGrupo }}</td>
                                    <td style="font-weight: 500;">{{ $grupo->NomGrupo }}</td>
                                    <td>
                                        <x-status-badge :status="!$grupo->Status" />
                                    </td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron grupos registrados"
                                    icon="collection"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('Grupos.ModalAgregar')
@endsection
