<x-page-container title="Catálogo de Listas de Precio">
    <x-card-gradient-header
        icon="tags"
        title="Catálogo de Listas de Precio"
        subtitle="Gestión de listas de precio del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros de búsqueda -->
        <x-form.form action="/CatListasPrecio">
            <x-form.group>
                <x-form.text
                    name="txtFiltro"
                    label="Buscar lista de precio"
                    icon="search"
                    placeholder="Nombre de lista..."
                    col="col-md-4"
                    :autofocus="true"
                />
                <x-form.select
                    name="Iva"
                    label="IVA"
                    icon="percent"
                    col="col-md-2"
                    :options="['0' => '0%', '8' => '8%', '16' => '16%']"
                />
                <x-form.select
                    name="rangoPeso"
                    label="Rango de peso"
                    icon="weight"
                    col="col-md-2"
                    :options="[
                        '0-10' => '0 - 10 kg',
                        '10-50' => '10 - 50 kg',
                        '50-100' => '50 - 100 kg',
                        '100+' => 'Más de 100 kg',
                    ]"
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
                            class="bi bi-table me-2"
                            style="color: var(--text-secondary);"
                        ></i>Concentrado de Listas de Precio
                    </h5>
                    <p class="section-content-subtitle">Listado de listas de precio registradas en el sistema</p>
                </div>
                <button
                    type="button"
                    class="btn-create"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalAgregar"
                >
                    <i class="bi bi-plus-circle"></i> Agregar lista de precio
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Id</th>
                            <th><i class="bi bi-tag me-1"></i>Nombre</th>
                            <th><i class="bi bi-weight me-1"></i>Peso Mínimo</th>
                            <th><i class="bi bi-weight me-1"></i>Peso Máximo</th>
                            <th><i class="bi bi-percent me-1"></i>IVA</th>
                            <th><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($listasPrecio as $listaPrecio)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">
                                    {{ $listaPrecio->IdListaPrecio }}</td>
                                <td style="font-weight: 500;">{{ $listaPrecio->NomListaPrecio }}</td>
                                <td>{{ $listaPrecio->PesoMinimo }} kg</td>
                                <td>{{ $listaPrecio->PesoMaximo }} kg</td>
                                <td>
                                    <span class="tags-blue">{{ $listaPrecio->PorcentajeIva }}%</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <x-table.buttons.edit-button
                                            :id="$listaPrecio->IdListaPrecio"
                                            modal="ModalEditar"
                                            title="Editar lista de precio"
                                            label="Editar"
                                        />
                                    </div>
                                </td>
                            </tr>
                            @include('ListasPrecio.ModalEditar')
                        @empty
                            <x-table-empty-data
                                colspan="6"
                                title="Sin datos disponibles"
                                message="No se encontraron listas de precio con los filtros seleccionados"
                                icon="tags"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/CatListasPrecio"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $listasPrecio])
        </div>
    </x-card-gradient-header>
</x-page-container>

<!-- Modal Agregar Lista de Precio -->
@include('ListasPrecio.ModalAgregar')

<script src="js/ListasPrecioScript.js"></script>
