@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Movimientos de Producto')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <x-card-gradient-header
            icon="arrow-left-right"
            title="Catálogo de Movimientos de Producto"
            subtitle="Gestión de movimientos de producto del sistema"
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
                            ></i>Concentrado de Movimientos
                        </h5>
                        <p class="section-content-subtitle">Listado de movimientos de producto registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarMovimiento"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar movimiento
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-exchange me-1"></i>Movimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movimientosProducto as $mProducto)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $mProducto->IdMovimiento }}</td>
                                    <td style="font-weight: 500;">{{ $mProducto->NomMovimiento }}</td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="2"
                                    title="Sin datos disponibles"
                                    message="No se encontraron movimientos de producto registrados"
                                    icon="exchange"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $movimientosProducto])
            </div>
        </x-card-gradient-header>
    </div>

    @include('MovimientosProducto.ModalAgregarMovimiento')
@endsection
