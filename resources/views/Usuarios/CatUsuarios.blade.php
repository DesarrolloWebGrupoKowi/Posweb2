@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Usuarios')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y FILTROS -->

        <x-layout.section-card>
            <!-- Título y botones principales -->
            <x-layout.section-title>
                <x-title titulo="Catálogo de Usuarios" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </x-layout.section-title>
            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.text-input
                        name="txtFiltro"
                        label="Nombre"
                        placeholder="Buscar usuario"
                        autofocus
                    />
                    <x-filters.inputs.select-input
                        name="IdTipoUsuario"
                        label="Tipo de usuario"
                        :options="$tipoUsuarios->pluck('NomTipoUsuario', 'IdTipoUsuario')->toArray()"
                        width="180"
                    />
                    <x-filters.inputs.select-input
                        name="estatus"
                        label="Estatus"
                        :options="['1' => 'Activos', '2' => 'Inactivos']"
                        width="180"
                    />
                </x-filters.filter-group>
                <x-slot:buttons>
                    <x-filters.buttons.clear-button />
                    <x-filters.buttons.submit-button />
                </x-slot:buttons>
            </x-filters.filter-form>
        </x-layout.section-card>

        <!-- SECCIÓN: TABLAS -->
        <div
            class="flex-grow-1 d-flex gap-4 pb-4"
            {{-- style="min-height: 0;" --}}
        >
            <div
                class="d-flex flex-column"
                {{-- style="flex: 2; min-width: 0; min-height: 0;" --}}
                style="flex: 2; min-width: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    {{-- style="border-radius: 10px; min-height: 0;" --}}
                    style="border-radius: 10px;"
                >
                    <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                        <div class="flex-column">
                            <h5 class="mb-0 text-gray-800">CONCENTRADO DE USUARIOS</h5>
                        </div>
                        <div class="d-flex gap-2">
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                role="tooltip"
                                title="Crear Usuario"
                                class="btn btn-default Agregar"
                                data-bs-toggle="modal"
                                data-bs-target="#ModalAgregar"
                            >
                                Crear usuario
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive content-table-sm">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start">Nómina</th>
                                    <th>Empleado</th>
                                    <th>Correo</th>
                                    <th>Usuario</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Estatus</th>
                                    <th class="rounded-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('components.table-empty', ['items' => $usuarios, 'colspan' => 8])
                                @foreach ($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->NumNomina }}</td>
                                        <td>{{ $usuario->Nombre }} {{ $usuario->Apellidos }}</td>
                                        <td>{{ $usuario->Correo }}</td>
                                        <td>{{ $usuario->NomUsuario }}</td>
                                        <td>{{ $usuario->NomTipoUsuario }}</td>
                                        <td>
                                            @if ($usuario->Status == 1)
                                                <span class="tags-red"> Deshabilitado </span>
                                            @else
                                                <span class="tags-green"> Activo </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start gap-2">
                                                @if ($usuario->Status)
                                                    <button
                                                        class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalActivarUsuario{{ $usuario->IdUsuario }}"
                                                        title="Activar usuario"
                                                    >
                                                        @include('components.icons.switch')
                                                    </button>
                                                @else
                                                    <button
                                                        class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#ModalEditar{{ $usuario->IdUsuario }}"
                                                        title="Modificar usuario"
                                                    >
                                                        @include('components.icons.edit') Ver
                                                    </button>
                                                    <button
                                                        class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#ModalEliminar{{ $usuario->IdUsuario }}"
                                                        title="Desactivar usuario"
                                                    >
                                                        @include('components.icons.delete')
                                                    </button>
                                                    <button
                                                        class="btn btn-sm btn-outline-success d-flex align-items-center gap-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalCambiarPassword{{ $usuario->IdUsuario }}"
                                                        title="Cambiar contraseña"
                                                    >
                                                        @include('components.icons.key')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @include('Usuarios.ModalActivarUsuario')
                                    <!--Modal Editar Usuario-->
                                    @include('Usuarios.ModalEditar')
                                    <!--Modal Eliminar Usuario-->
                                    @include('Usuarios.modalEliminar')
                                    <!--Modal Valida Usuario-->
                                    @include('Usuarios.ModalValidarUsuario')
                                    <!--Modal Cambiar Contraseña-->
                                    @include('Usuarios.ModalCambiarPassword')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @include('components.paginate', ['items' => $usuarios])
                </div>
            </div>
        </div>
    </x-layout.page-container>

    <!--Modal Agregar Usuario-->
    @include('Usuarios.ModalAgregar')
@endsection
