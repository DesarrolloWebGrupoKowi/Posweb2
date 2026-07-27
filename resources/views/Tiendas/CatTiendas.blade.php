<x-page-container title="Catálogo de Tiendas">
    <x-card-gradient-header
        icon="shop"
        title="Catálogo de Tiendas"
        subtitle="Gestión de tiendas del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros de búsqueda -->
        <x-form.form action="/CatTiendas">
            <x-form.group>
                <x-form.text
                    name="filtroTienda"
                    label="Buscar tienda"
                    icon="search"
                    placeholder="Buscar tienda..."
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
                            class="fa fa-table me-2"
                            style="color: var(--text-secondary);"
                        ></i>Concentrado de Tiendas
                    </h5>
                    <p class="section-content-subtitle">Listado de tiendas registradas en el sistema</p>
                </div>
                <button
                    type="button"
                    class="btn-create"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalAgregar"
                >
                    <i class="fa fa-plus-circle"></i> Agregar tienda
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="fa fa-hashtag me-1"></i>Id</th>
                            <th><i class="fa fa-font me-1"></i>Tienda</th>
                            <th><i class="fa fa-phone me-1"></i>Teléfono</th>
                            <th><i class="fa fa-map-marker me-1"></i>Dirección</th>
                            <th><i class="fa fa-building me-1"></i>Ciudad</th>
                            <th><i class="fa fa-circle me-1"></i>Estatus</th>
                            <th><i class="fa fa-cog me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tiendas as $tienda)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">{{ $tienda->IdTienda }}</td>
                                <td style="font-weight: 500;">{{ $tienda->NomTienda }}</td>
                                <td style="color: var(--text-secondary);">{{ $tienda->Telefono }}</td>
                                <td>{{ $tienda->Direccion }}</td>
                                <td>
                                    <span class="tags-blue">{{ $tienda->ccNomCiudad }}</span>
                                </td>
                                <td>
                                    <x-status-badge :status="!$tienda->Status" />
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <x-table.buttons.edit-button
                                            :id="$tienda->IdTienda"
                                            modal="ModalEditar"
                                            title="Editar tienda"
                                            label="Editar"
                                        />
                                        <x-table.buttons.delete-button
                                            :id="$tienda->IdTienda"
                                            modal="ModalEliminar"
                                            title="Eliminar tienda"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-table-empty-data
                                colspan="7"
                                title="Sin datos disponibles"
                                message="No se encontraron tiendas con los filtros seleccionados"
                                icon="shop"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/CatTiendas"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $tiendas])
        </div>
    </x-card-gradient-header>

    @foreach ($tiendas as $tienda)
        @include('Tiendas.ModalEditar')
        @include('Tiendas.ModalEliminar')
    @endforeach
    <!-- Modal Agregar Tienda -->
    @include('Tiendas.ModalAgregar')
</x-page-container>
