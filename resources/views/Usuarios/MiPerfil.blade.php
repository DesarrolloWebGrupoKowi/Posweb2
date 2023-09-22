@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Mi Perfil')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Mi Perfil'])
        </div>

        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card p-4" style="border-radius: 20px;">
                    <div class="row">
                        <div class="col-auto">
                            <div class="d-flex flex-column align-items-start">
                                <img src="{{ asset('img/cerdito.png') }}" alt="Admin" class="rounded-circle"
                                    width="150">
                                <div class="mt-3">
                                    <h4>{{ Auth::user()->NomUsuario }}</h4>
                                    <p class="text-secondary mb-1">
                                        <i style="color: black" class="fa fa-user-circle"></i>
                                        {{ empty(Auth::user()->Empleado->Nombre) ? 'Nomina Vacia' : Auth::user()->Empleado->Nombre }}
                                        {{ empty(Auth::user()->Empleado->Apellidos) ? 'O Incorrecta' : Auth::user()->Empleado->Apellidos }}
                                    </p>
                                    <p class="text-secondary mb-1">
                                        <i style="color: black" class="fa fa-list-ol"></i>
                                        {{ Auth::user()->NumNomina }}
                                    </p>
                                    <p class="text-secondary mb-1">
                                        <i style="color: black" class="fa fa-address-card"></i>
                                        {{ Auth::user()->tipoUsuario->NomTipoUsuario }}
                                    </p>
                                    <p class="text-secondary mb-1">
                                        <i style="color: black" class="fa fa-envelope"></i>
                                        {{ Auth::user()->Correo }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @canany(['isAdmin', 'isGerente'])
                            <div class="col-auto">
                                <div class="d-flex flex-column align-items-end">
                                    <button class="btn btn-warning" id="btnEditarUsuario">
                                        <i class="fa fa-pencil-square-o"></i> Editar
                                    </button>
                                </div>
                            </div>
                        @endcanany
                    </div>
                </div>
            </div>

            <div id="EditarUsuario" class="col-md-6" style="display: none">
                <div class="card mb-3 p-4" style="border-radius: 20px;">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="mail" name="" id="" class="form-control"
                            placeholder="Escribe el correo" value="{{ Auth::user()->Correo }}" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                        <input type="text" name="" id="" class="form-control" placeholder="# Nómina"
                            value="{{ Auth::user()->NumNomina }}" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="showPassword" style="cursor: pointer"><i
                                class="fa fa-unlock"></i></span>
                        <input type="password" name="" class="form-control" placeholder="Nueva Password"
                            value="" required id="editPassword" style="display: none">
                    </div>
                    @canany(['isAdmin', 'isGerente'])
                        <div class="row">
                            <div class="col-sm-12">
                                <a class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#ModalEditarMiPerfil">
                                    <i class="fa fa-refresh"></i> Actualizar mi perfil
                                </a>
                            </div>
                        </div>
                    @endcanany
                </div>
            </div>
        </div>

    </div>

    <script src="js/EditarMiUsuario.js"></script>
    @include('Usuarios.ModalEditarMiPerfil')
@endsection
