<x-page-container title="Catálogo de Tipo de Menús">
    <x-card-gradient-header
        icon="list-columns-reverse"
        title="Catálogo de Tipos de Menús"
        subtitle="Gestión de tipos de menú del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="/CatTipoMenu"
            id="formTipoMenu"
        >
            <x-form.group>
                <x-form.select
                    name="activo"
                    label="Filtrar por estatus"
                    icon="funnel"
                    col="col-md-4"
                    :options="['0' => 'Activos', '1' => 'Inactivos']"
                    {{-- onchange="this.form.submit()" --}}
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
                            style="color: #64748b;"
                        ></i>Concentrado de Tipos de Menú
                    </h5>
                    <p class="section-content-subtitle">Listado de tipos de menú registrados en el sistema</p>
                </div>
                <button
                    type="button"
                    class="btn-create"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalAgregar"
                >
                    <i class="fa fa-plus-circle"></i> Agregar tipo de menú
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="fa fa-hashtag me-1"></i>Id</th>
                            <th><i class="fa fa-font me-1"></i>Nombre</th>
                            <th><i class="fa fa-image me-1"></i>Icono</th>
                            <th><i class="fa fa-layer-group me-1"></i>Posición</th>
                            <th><i class="fa fa-cog me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tipoMenus as $tipoMenu)
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $tipoMenu->IdTipoMenu }}</td>
                                <td style="font-weight: 500;">{{ $tipoMenu->NomTipoMenu }}</td>
                                <td style="font-weight: 500;">
                                    @if (!empty($tipoMenu->Icono))
                                        {{-- Suponemos que almacenas 'fa fa-user', 'bi bi-house', etc --}}
                                        <i class="{{ $tipoMenu->Icono }} me-1"></i>
                                    @endif
                                    {{ $tipoMenu->Icono }}
                                </td>
                                <td style="font-weight: 500;">
                                    {{ $tipoMenu->Posicion }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <x-table.buttons.edit-button
                                            :id="$tipoMenu->IdTipoMenu"
                                            modal="ModalEditar"
                                            title="Editar tipo de menú"
                                            label="Editar"
                                        />
                                    </div>
                                </td>
                            </tr>
                            @include('TipoMenu.ModalEditar')
                        @empty
                            <x-table-empty-data
                                colspan="3"
                                title="Sin datos disponibles"
                                message="No se encontraron tipos de menú registrados"
                                icon="tags"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/CatTipoMenu"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>

<!-- Modal Agregar Tipo de Menú -->
@include('TipoMenu.ModalAgregar')
