@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Clientes Cloud')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <x-card-gradient-header
            icon="cloud"
            title="Catálogo de Clientes Cloud"
            subtitle="Gestión de clientes cloud del sistema"
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
                            ></i>Concentrado de Clientes Cloud
                        </h5>
                        <p class="section-content-subtitle">Listado de clientes cloud registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar cliente
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id Cliente</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-user me-1"></i>Tipo de Cliente</th>
                                <th><i class="fa fa-file-text me-1"></i>Uso CFDI</th>
                                <th><i class="fa fa-credit-card me-1"></i>Método Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clientesCloud as $clienteCloud)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $clienteCloud->IdClienteCloud }}</td>
                                    <td style="font-weight: 500;">{{ $clienteCloud->NomClienteCloud }}</td>
                                    <td>
                                        <span
                                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            {{ $clienteCloud->TipoCliente }}
                                        </span>
                                    </td>
                                    <td style="color: #64748b; font-size: 0.85rem;">{{ $clienteCloud->UsoCfdi }}</td>
                                    <td>
                                        <span
                                            style="background: #f8fafc; color: #64748b; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500; border: 1px solid #e2e8f0;"
                                        >
                                            {{ $clienteCloud->MetodoPago }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="5"
                                    title="Sin datos disponibles"
                                    message="No se encontraron clientes cloud registrados"
                                    icon="cloud"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $clientesCloud])
            </div>
        </x-card-gradient-header>
    </div>

    @include('ClientesCloud.ModalAgregar')
@endsection
