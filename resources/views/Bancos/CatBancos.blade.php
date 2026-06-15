@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Bancos')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <x-card-gradient-header
            icon="bank"
            title="Catálogo de Bancos"
            subtitle="Gestión de bancos del sistema"
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
                            ></i>Concentrado de Bancos
                        </h5>
                        <p class="section-content-subtitle">Listado de bancos registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarBanco"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar banco
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id Banco</th>
                                <th><i class="fa fa-bank me-1"></i>Banco</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bancos as $banco)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $banco->IdBanco }}</td>
                                    <td style="font-weight: 500;">{{ $banco->NomBanco }}</td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="2"
                                    title="Sin datos disponibles"
                                    message="No se encontraron bancos registrados"
                                    icon="bank"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $bancos])
            </div>
        </x-card-gradient-header>
    </div>

    @include('Bancos.ModalAgregarBanco')
@endsection
