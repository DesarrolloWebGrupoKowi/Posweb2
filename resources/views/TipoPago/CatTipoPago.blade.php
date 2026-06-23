<x-page-container title="Catálogo de Tipos de Pago">
        <x-card-gradient-header
            icon="cash-stack"
            title="Catálogo de Tipos de Pago"
            subtitle="Gestión de tipos de pago del sistema"
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
                            ></i>Concentrado de Tipos de Pago
                        </h5>
                        <p class="section-content-subtitle">Listado de tipos de pago registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar tipo de pago
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id de Tipo de Pago</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-barcode me-1"></i>Clave Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tiposPago as $tipoPago)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $tipoPago->IdTipoPago }}</td>
                                    <td style="font-weight: 500;">{{ $tipoPago->NomTipoPago }}</td>
                                    <td>
                                        <span
                                            style="background: #f8fafc; color: #64748b; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500; border: 1px solid #e2e8f0;"
                                        >
                                            {{ $tipoPago->ClaveSat }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron tipos de pago registrados"
                                    icon="credit-card"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('TipoPago.ModalAgregar')
