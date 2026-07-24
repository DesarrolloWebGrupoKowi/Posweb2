<x-page-container title="Catálogo de Ciudades">
        <x-card-gradient-header
            icon="building"
            title="Catálogo de Ciudades"
            subtitle="Gestión de ciudades del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros de búsqueda -->
            <x-form.form action="/CatCiudades">
                <x-form.group>
                    <x-form.text
                        name="txtFiltro"
                        label="Buscar ciudad"
                        icon="search"
                        placeholder="Nombre de ciudad..."
                        col="col-md-4"
                        :autofocus="true"
                    />
                    <x-form.select
                        name="IdEstado"
                        label="Estado"
                        icon="geo-alt"
                        col="col-md-3"
                        :options="$estados->pluck('NomEstado', 'IdEstado')->toArray()"
                    />
                </x-form.group>
                <div class="col-md-3 d-flex gap-2">
                    <x-form.submit
                        text="Buscar"
                        icon="funnel"
                        class="flex-grow-1"
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
                                style="color: #64748b;"
                            ></i>Concentrado de Ciudades
                        </h5>
                        <p class="section-content-subtitle">Listado de ciudades registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar ciudad
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-font me-1"></i>Nombre</th>
                                <th><i class="fa fa-map-marker me-1"></i>Estado</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ciudades as $ciudad)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $ciudad->IdCiudad }}</td>
                                    <td style="font-weight: 500;">{{ $ciudad->NomCiudad }}</td>
                                    <td>
                                        <span
                                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            {{ $ciudad->NomEstado }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$ciudad->IdCiudad"
                                                modal="ModalEditar"
                                                title="Editar ciudad"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('Ciudades.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="4"
                                    title="Sin datos disponibles"
                                    message="No se encontraron ciudades con los filtros seleccionados"
                                    icon="building"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatCiudades"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $ciudades])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    <!-- Modal Agregar Ciudad -->
    @include('Ciudades.ModalAgregar')
