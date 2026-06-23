<x-page-container title="Catálogo de Familias">
        <x-card-gradient-header
            icon="folder"
            title="Catálogo de Familias"
            subtitle="Gestión de familias del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros de búsqueda -->
            <x-form.form action="/CatFamilias">
                <x-form.group>
                    <x-form.text
                        name="txtFiltro"
                        label="Buscar familia"
                        icon="search"
                        placeholder="Nombre de familia..."
                        col="col-md-4"
                        :autofocus="true"
                    />
                </x-form.group>
                <div class="col-md-2 d-flex gap-2">
                    <x-form.submit
                        text="Filtrar"
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
                                style="color: #64748b;"
                            ></i>Concentrado de Familias
                        </h5>
                        <p class="section-content-subtitle">Listado de familias registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar familia
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-hashtag me-1"></i>Id</th>
                                <th><i class="fa fa-folder me-1"></i>Familia</th>
                                {{-- <th><i class="fa fa-cog me-1"></i>Acciones</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($familias as $familia)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $familia->IdFamilia }}</td>
                                    <td style="font-weight: 500;">{{ $familia->NomFamilia }}</td>
                                    {{-- <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$familia->IdFamilia"
                                                modal="ModalEditar-"
                                                title="Editar familia"
                                                label="Editar"
                                            />
                                        </div>
                                    </td> --}}
                                </tr>
                                @include('Familias.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No se encontraron familias con los filtros seleccionados"
                                    icon="folder"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatFamilias"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $familias])
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('Familias.ModalAgregar')
