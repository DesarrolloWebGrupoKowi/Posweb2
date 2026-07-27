<x-page-container title="Catálogo de Plazas">
    <x-card-gradient-header
        icon="shop-window"
        title="Catálogo de Plazas"
        subtitle="Gestión de plazas del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="/CatPlazas"
            id="formPlazas"
        >
            <x-form.group>
                <x-form.select
                    name="activo"
                    label="Filtrar por estatus"
                    icon="funnel"
                    col="col-md-4"
                    :options="['0' => 'Activas', '1' => 'Inactivas']"
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
                                ></i>Concentrado de Plazas
                    </h5>
                    <p class="section-content-subtitle">Listado de plazas registradas en el sistema</p>
                </div>
                <button
                    type="button"
                    class="btn-create"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalAgregar"
                >
                    <i class="fa fa-plus-circle"></i> Agregar plaza
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="fa fa-hashtag me-1"></i>Id</th>
                            <th><i class="fa fa-font me-1"></i>Nombre</th>
                            <th><i class="fa fa-building me-1"></i>Ciudad</th>
                            <th><i class="fa fa-map-marker me-1"></i>Estado</th>
                            <th><i class="fa fa-circle me-1"></i>Estatus</th>
                            <th><i class="fa fa-cog me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plazas as $plaza)
                            <tr>
                                    <td style="font-weight: 600; color: var(--text-primary);">{{ $plaza->IdPlaza }}</td>
                                    <td style="font-weight: 500;">{{ $plaza->NomPlaza }}</td>
                                    <td>{{ $plaza->ccNomCiudad }}</td>
                                    <td>
                                        <span class="tags-blue">{{ $plaza->ceNomEstado }}</span>
                                </td>
                                <td>
                                    <x-status-badge :status="!$plaza->Status" />
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <x-table.buttons.edit-button
                                            :id="$plaza->IdPlaza"
                                            modal="ModalEditar"
                                            title="Editar plaza"
                                            label="Editar"
                                        />
                                    </div>
                                </td>
                                </tr>
                            @empty
                            <x-table-empty-data
                                colspan="6"
                                title="Sin datos disponibles"
                                message="No se encontraron plazas con los filtros seleccionados"
                                icon="shop"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/CatPlazas"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>

<!-- Modal Agregar Plaza -->
@foreach ($plazas as $plaza)
    @include('Plazas.ModalEditar')
@endforeach

@include('Plazas.ModalAgregar')
