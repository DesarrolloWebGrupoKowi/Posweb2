<x-page-container title="Catálogo de Tipos de Merma">
        <x-card-gradient-header
            icon="exclamation-triangle"
            title="Catálogo de Tipos de Merma"
            subtitle="Gestión de tipos de merma del sistema"
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
                            ></i>Concentrado de Tipos de Merma
                        </h5>
                        <p class="section-content-subtitle">Listado de tipos de merma registrados en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarTipoMerma"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar tipo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-tag me-1"></i>Tipo Merma</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tiposMerma as $tipoMerma)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $tipoMerma->IdTipoMerma }}</td>
                                    <td style="font-weight: 500;">{{ $tipoMerma->NomTipoMerma }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.delete-button
                                                :id="$tipoMerma->IdTipoMerma"
                                                modal="ModalEliminarArticuloTipoMerma"
                                                title="Eliminar tipo de merma"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('TiposMerma.ModalEliminarTipoMerma')
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron tipos de merma registrados"
                                    icon="receipt"
                                    :action="true"
                                    actionText="Agregar tipo"
                                    actionUrl="#"
                                    actionIcon="plus-circle"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('TiposMerma.ModalAgregarTipoMerma')
