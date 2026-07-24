<x-page-container title="Tipo de Usuarios">
    <x-card-gradient-header
        icon="person-gear"
        title="Tipo de Usuarios"
        subtitle="Gestión de tipos de usuario del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="/CatTipoUsuarios"
            id="formTipoUsuarios"
        >
            <x-form.group>
                <x-form.select
                    name="filtroActivo"
                    label="Filtrar por estatus"
                    icon="funnel"
                    col="col-md-4"
                    :options="['0' => 'Activos', '1' => 'Inactivos']"
                    autofocus
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
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Concentrado de Tipos de Usuario
                    </h5>
                    <p class="section-content-subtitle">Listado de tipos de usuario registrados en el sistema</p>
                </div>
                <button
                    type="button"
                    class="btn-create"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalAgregar"
                >
                    <i class="bi bi-plus-circle"></i> Agregar tipo de usuario
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Id Tipo de Usuario</th>
                            <th><i class="bi bi-shield me-1"></i>Tipo de Usuario</th>
                            <th><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tipoUsuarios as $tipoUsuario)
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $tipoUsuario->IdTipoUsuario }}</td>
                                <td style="font-weight: 500;">{{ $tipoUsuario->NomTipoUsuario }}</td>
                                <td>
                                    @if ($filtroActivo != 1)
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$tipoUsuario->IdTipoUsuario"
                                                modal="ModalEditar"
                                                title="Editar tipo de usuario"
                                                label="Editar"
                                            />
                                            <x-table.buttons.delete-button
                                                :id="$tipoUsuario->IdTipoUsuario"
                                                modal="ModalConfirmar"
                                                title="Desactivar tipo de usuario"
                                            />
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @include('TipoUsuarios.ModalEditar')
                            @include('TipoUsuarios.ModalConfirmar')
                        @empty
                            <x-table-empty-data
                                colspan="3"
                                title="Sin datos disponibles"
                                message="No se encontraron tipos de usuario registrados"
                                icon="shield"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/CatTipoUsuarios"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $tipoUsuarios])
        </div>
    </x-card-gradient-header>
</x-page-container>

<!-- Modal Agregar Tipo de Usuario -->
@include('TipoUsuarios.ModalAgregar')
