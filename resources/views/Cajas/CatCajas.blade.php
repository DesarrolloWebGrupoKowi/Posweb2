<x-page-container title="Catálogo de Cajas">
        <x-card-gradient-header
            icon="inbox"
            title="Catálogo de Cajas"
            subtitle="Gestión de cajas del sistema"
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
                            ></i>Concentrado de Cajas
                        </h5>
                        <p class="section-content-subtitle">Listado de cajas registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar caja
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id Caja</th>
                                <th><i class="fa fa-archive me-1"></i>Número de Caja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cajas as $caja)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $caja->IdCaja }}</td>
                                    <td style="font-weight: 500;">{{ $caja->NumCaja }}</td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="2"
                                    title="Sin datos disponibles"
                                    message="No se encontraron cajas registradas"
                                    icon="inbox"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('Cajas.ModalAgregar')
