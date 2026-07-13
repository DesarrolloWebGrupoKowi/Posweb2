<x-page-container title="Catálogo de Usuarios Tienda">
    <x-card-gradient-header
        icon="people-fill"
        title="Catálogo de Usuarios Tienda"
        subtitle="Gestione los usuarios asignados a cada tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form action="/CatUsuariosTienda">
            <x-form.group>
                <x-form.text
                    name="txtFiltro"
                    label="Nombre"
                    icon="search"
                    placeholder="Buscar usuario..."
                    col="col-md-3"
                    :autofocus="true"
                />
                <x-form.select
                    name="IdTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    placeholder="Todas las tiendas"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                />
                <x-form.select
                    name="IdPlaza"
                    label="Sucursales"
                    icon="building"
                    col="col-md-3"
                    placeholder="Todas las plazas"
                    :options="$plazas->pluck('NomPlaza', 'IdPlaza')->toArray()"
                />
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <x-form.submit
                    text="Filtrar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Concentrado de Usuarios
                    </h5>
                    <p class="section-content-subtitle">Listado de usuarios registrados en el sistema</p>
                </div>
                <button
                    type="button"
                    class="btn-create"
                    data-bs-toggle="modal"
                    data-bs-target="#ModalAgregar"
                >
                    <i class="bi bi-plus-circle"></i> Crear usuario
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Nómina</th>
                            <th><i class="bi bi-person me-1"></i>Empleado</th>
                            <th><i class="bi bi-person-circle me-1"></i>Usuario</th>
                            <th><i class="bi bi-shield me-1"></i>Tipo</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-building me-1"></i>Plaza</th>
                            <th><i class="bi bi-check-all me-1"></i>Todas</th>
                            <th><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuariosTienda as $usuarioTienda)
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $usuarioTienda->NumNomina }}</td>
                                <td>{{ $usuarioTienda->Nombre }} {{ $usuarioTienda->Apellidos }}</td>
                                <td style="font-weight: 600;">{{ $usuarioTienda->NomUsuario }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $usuarioTienda->NomTipoUsuario }}
                                    </span>
                                </td>
                                <td style="color: #64748b;">{{ $usuarioTienda->NomTienda ?? '-' }}</td>
                                <td style="color: #64748b;">{{ $usuarioTienda->NomPlaza ?? '-' }}</td>
                                <td>
                                    @if ($usuarioTienda->Todas == 0)
                                        <span class="badge-status badge-active">
                                            <i class="bi bi-check-circle me-1"></i>Sí
                                        </span>
                                    @else
                                        <span class="badge-status badge-inactive">
                                            <i class="bi bi-x-circle me-1"></i>No
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button
                                            class="btn-table-action btn-table-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}"
                                            title="Modificar usuario"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button
                                            class="btn-table-action btn-table-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}"
                                            title="Desactivar usuario"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('UsuariosTienda.ModalEditar')
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-search fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin datos disponibles</h6>
                                        <small class="text-muted">No se encontraron usuarios con los filtros
                                            seleccionados</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $usuariosTienda])
        </div>
    </x-card-gradient-header>

    @foreach ($usuariosTienda as $usuarioTienda)
        @include('UsuariosTienda.ModalEliminar')
    @endforeach
    @include('UsuariosTienda.ModalAgregar')
</x-page-container>
