<x-page-container title="Catálogo de Tablas Para Actualizar">
        <x-card-gradient-header
            icon="table"
            title="Catálogo de Tablas"
            subtitle="Gestión de tablas para actualizar del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros de búsqueda -->
            <x-form.form action="/CatTablas">
                <x-form.group>
                    <x-form.text
                        name="txtFiltro"
                        label="Buscar tabla"
                        icon="search"
                        placeholder="Nombre de tabla..."
                        col="col-md-4"
                        :autofocus="true"
                    />
                </x-form.group>
                <div class="col-md-2 d-flex gap-2">
                    <x-form.submit
                        text="Buscar"
                        icon="funnel"
                    />
                    <x-form.clear />
                </div>
            </x-form.form>

            <!-- Tabla -->
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="fa fa-list me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Tablas
                        </h5>
                        <p class="section-content-subtitle">Listado de tablas registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarTabla"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar tabla
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-table me-1"></i>Tabla</th>
                                <th><i class="fa fa-circle me-1"></i>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tablas as $tabla)
                                <tr>
                                    <td style="font-weight: 500;">{{ $tabla->NomTabla }}</td>
                                    <td>
                                        <x-status-badge :status="!$tabla->Status" />
                                    </td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="2"
                                    title="Sin datos disponibles"
                                    message="No se encontraron tablas con los filtros seleccionados"
                                    icon="database"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatTablas"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $tablas])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('TablasUpdate.ModalAgregarTabla')
