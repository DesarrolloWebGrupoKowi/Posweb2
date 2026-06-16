@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Usuarios')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="people-fill"
            title="Catálogo de Usuarios"
            subtitle="Gestión de usuarios del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros -->
            <x-form.form action="/CatUsuarios">
                <x-form.group>
                    <x-form.text
                        name="txtFiltro"
                        label="Nombre"
                        icon="search"
                        placeholder="Buscar usuario..."
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
                    <x-form.select
                        name="estatus"
                        label="Estatus"
                        icon="toggle-on"
                        col="col-md-2"
                        :options="['2' => 'Activos', '1' => 'Inactivos']"
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

            <!-- Tabla -->
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
                                <th><i class="bi bi-envelope me-1"></i>Correo</th>
                                <th><i class="bi bi-person-circle me-1"></i>Usuario</th>
                                <th><i class="bi bi-shield me-1"></i>Tipo</th>
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
                                    <td style="font-weight: 600; color: #0f172a;">{{ $usuario->NumNomina }}</td>
                                    <td>{{ $usuario->Nombre }} {{ $usuario->Apellidos }}</td>
                                    <td style="color: #64748b;">{{ $usuario->Correo }}</td>
                                    <td style="font-weight: 600;">{{ $usuario->NomUsuario }}</td>
                                    <td>{{ $usuario->NomTipoUsuario }}</td>
                                    <td>
                                        <x-status-badge :status="$usuario->Status != 1" />
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if ($usuario->Status)
                                                <x-table.buttons.activate-button
                                                    :id="$usuario->IdUsuario"
                                                    modal="modalActivarUsuario"
                                                    title="Activar usuario"
                                                />
                                            @else
                                                <x-table.buttons.edit-button
                                                    :id="$usuario->IdUsuario"
                                                    modal="ModalEditar"
                                                    title="Modificar usuario"
                                                    label="Ver"
                                                />
                                                <x-table.buttons.delete-button
                                                    :id="$usuario->IdUsuario"
                                                    modal="ModalEliminar"
                                                    title="Desactivar usuario"
                                                />
                                                <x-table.buttons.password-button
                                                    :id="$usuario->IdUsuario"
                                                    modal="modalCambiarPassword"
                                                    title="Cambiar contraseña"
                                                />
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @include('Usuarios.ModalActivarUsuario')
                                @include('Usuarios.ModalEditar')
                                @include('Usuarios.modalEliminar')
                                @include('Usuarios.ModalCambiarPassword')
                            @empty
                                <x-table-empty-data
                                    colspan="7"
                                    title="Sin datos disponibles"
                                    message="No se encontraron usuarios con los filtros seleccionados"
                                    icon="search"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatUsuarios"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $usuarios])
            </div>
        </x-card-gradient-header>
    </x-page-container>
    <!--Modal Agregar Usuario-->
    @include('Usuarios.ModalAgregar')
@endsection
