<x-page-container title="Usuarios por Sucursal">
    <x-card-gradient-header
        icon="people-fill"
        title="Usuarios por Sucursal"
        subtitle="Asignación de sucursales a usuarios del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/UsuariosPorSucursal">
            <x-form.group>
                <x-form.text
                    name="txtFiltro"
                    label="Buscar"
                    icon="search"
                    placeholder="Nombre, usuario o correo..."
                    col="col-md-4"
                    :autofocus="true"
                />
                <x-form.select
                    name="IdTipoUsuario"
                    label="Tipo de usuario"
                    icon="person-badge"
                    col="col-md-3"
                    :options="$tipoUsuarios->pluck('NomTipoUsuario', 'IdTipoUsuario')->toArray()"
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
                            class="bi bi-table me-2"
                            style="color: var(--text-muted);"
                        ></i>Concentrado de Usuarios
                    </h5>
                    <p class="section-content-subtitle">
                        {{ $usuarios->total() }} usuarios registrados
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Nómina</th>
                            <th><i class="bi bi-person me-1"></i>Empleado</th>
                            <th><i class="bi bi-person-circle me-1"></i>Usuario</th>
                            <th><i class="bi bi-envelope me-1"></i>Correo</th>
                            <th><i class="bi bi-shield me-1"></i>Tipo</th>
                            <th><i class="bi bi-building me-1"></i>Sucursales</th>
                            <th><i
                                    class="bi bi-circle-fill me-1"
                                    style="font-size: 0.5rem;"
                                ></i>Estatus</th>
                            <th><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">{{ $usuario->NumNomina }}</td>
                                <td>{{ $usuario->Nombre }} {{ $usuario->Apellidos }}</td>
                                <td style="font-weight: 600;">{{ $usuario->NomUsuario }}</td>
                                <td style="color: var(--text-secondary);">{{ $usuario->Correo }}</td>
                                <td>{{ $usuario->NomTipoUsuario }}</td>
                                <td>
                                    @php
                                        $sucursalesArray = $usuario->SucursalesAsignadas
                                            ? explode(', ', $usuario->SucursalesAsignadas)
                                            : [];
                                    @endphp
                                    @if (count($sucursalesArray) > 0)
                                        <span
                                            class="tags-green"
                                            style="font-size: 0.75rem; cursor: default;"
                                        >
                                            <i class="bi bi-check-circle me-1"></i>
                                            {{ count($sucursalesArray) }} sucursal(es)
                                        </span>
                                    @else
                                        <span
                                            class="tags-yellow"
                                            style="font-size: 0.75rem; cursor: default;"
                                        >
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Sin asignar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <x-status-badge :status="$usuario->Status != 1" />
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-animated d-flex align-items-center gap-1"
                                        style="background: var(--btn-blue-bg); color: var(--btn-blue-text); border: 1px solid var(--btn-blue-hover); border-radius: 8px; padding: 6px 12px; font-size: 0.8rem;"
                                        onclick="abrirAsignarSucursales({{ $usuario->IdUsuario }}, '{{ $usuario->NomUsuario }}')"
                                        title="Asignar sucursales"
                                    >
                                        <i class="bi bi-building"></i> Asignar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="8"
                                    class="py-5 text-center"
                                >
                                    <i
                                        class="bi bi-inbox"
                                        style="font-size: 2.5rem; color: var(--text-muted);"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: var(--text-secondary); font-size: 0.85rem;"
                                    >
                                        No se encontraron usuarios
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $usuarios])
        </div>
    </x-card-gradient-header>

    <!-- Modal Asignar Sucursales -->
    @include('UsuariosPorSucursal.ModalAsignarSucursales')
</x-page-container>
