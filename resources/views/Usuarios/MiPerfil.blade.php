@extends('plantillaBase.masterblade')
@section('title', 'Mi Perfil')
@section('contenido')
    <div class="container">
        <div class="main-body">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h2 class="card shadow p-1">Mi Perfil</h2>
                </div>
            </div>
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card rounded-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="d-flex flex-column align-items-start">
                                        <img src="{{ asset('img/cerdito.png') }}" alt="Admin" class="rounded-circle" width="150">
                                        <div class="mt-3">
                                            <h4>{{ Auth::user()->NomUsuario }}</h4>
                                            <p class="text-secondary mb-1">
                                                <i style="color: black" class="fa fa-user-circle"></i>
                                                {{ empty(Auth::user()->Empleado->Nombre) ? 'Nomina Vacia' : Auth::user()->Empleado->Nombre }} {{ empty(Auth::user()->Empleado->Apellidos) ? 'O Incorrecta' : Auth::user()->Empleado->Apellidos }}
                                            </p>
                                            <p class="text-secondary mb-1">
                                                <i style="color: black" class="fa fa-list-ol"></i>
                                                {{ Auth::user()->NumNomina }}
                                            </p>
                                            <p class="text-secondary mb-1">
                                                <i style="color: black" class="fa fa-address-card"></i>
                                                {{ Auth::user()->tipoUsuario->NomTipoUsuario }}</p>
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
                </div>
                <div id="EditarUsuario" class="col-md-6" style="display: none">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-1 my-2">
                                    <h6 class="mb-0"><i class="fa fa-envelope"></i></h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="mail" name="" id="" class="form-control" placeholder="Correo" value="{{ Auth::user()->Correo }}" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-1 my-2">
                                    <h6 class="mb-0"><i class="fa fa-building"></i></h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="text" name="" id="" class="form-control" placeholder="# Nómina" value="{{ Auth::user()->NumNomina }}" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-1 my-2" id="showPassword" style="cursor: pointer">
                                    <h6 class="mb-0"><i class="fa fa-unlock"></i></h6>
                                </div>
                                <div class="col-sm-9 text-secondary mb-0" id="editPassword" style="display: none">
                                    <input type="password" name="" id="" class="form-control" placeholder="Nueva Password" value="" required>
                                </div>
                            </div>
                            <hr>
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
        </div>
    </div>

    <script src="js/EditarMiUsuario.js"></script>
    @include('Usuarios.ModalEditarMiPerfil')
@endsection
