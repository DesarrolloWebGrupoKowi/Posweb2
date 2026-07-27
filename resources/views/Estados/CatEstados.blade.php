<x-page-container title="Catálogo de Estados">
    <x-card-gradient-header
        icon="geo-alt"
        title="Catálogo de Estados"
        subtitle="Gestión de estados del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="/CatEstados"
            id="formEstados"
        >
            <x-form.group>
                <x-form.select
                    name="Activo"
                    label="Filtrar por estatus"
                    icon="funnel"
                    col="col-md-4"
                    :options="['0' => 'Activos', '1' => 'Inactivos']"
                    autofocus
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
                                    class="fa fa-table me-2"
                                    style="color: var(--text-secondary);"
                                ></i>Concentrado de Estados
                    </h5>
                    <p class="section-content-subtitle">Listado de estados registrados en el sistema</p>
                </div>
                <button
                    type="button"
                    class="btn-create"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalAgregar"
                >
                    <i class="fa fa-plus-circle"></i> Agregar Estado
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="fa fa-hashtag me-1"></i>Id Estado</th>
                            <th><i class="fa fa-font me-1"></i>Nombre</th>
                            <th><i class="fa fa-circle me-1"></i>Estatus</th>
                            <th><i class="fa fa-cog me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estados as $estado)
                            <tr>
                                    <td style="font-weight: 600; color: var(--text-primary);">{{ $estado->IdEstado }}</td>
                                <td style="font-weight: 500;">{{ $estado->NomEstado }}</td>
                                <td>
                                    <x-status-badge :status="!$estado->Status" />
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <x-table.buttons.edit-button
                                            :id="$estado->IdEstado"
                                            modal="ModalEditar"
                                            title="Editar estado"
                                            label="Editar"
                                        />
                                    </div>
                                </td>
                                </tr>
                            @empty
                            <x-table-empty-data
                                colspan="4"
                                title="Sin datos disponibles"
                                message="No se encontraron estados registrados"
                                icon="map"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/CatEstados"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $estados])
        </div>
    </x-card-gradient-header>
</x-page-container>

<!-- Modal Agregar Estado -->
@foreach ($estados as $estado)
    @include('Estados.ModalEditar')
@endforeach

@include('Estados.ModalAgregar')
