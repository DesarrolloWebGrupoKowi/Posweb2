<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/logokowi.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}">
    <link href="{{ asset('material-icon/material-icon.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Icons/font-awesome.min.css') }}">
    <link rel="stylesheet" href="css/styleDashboardNew.css">
    <link rel="stylesheet" href="css/styleLogin.css">
    <title>Login</title>
</head>

<body>

    <div class="container-fluid" style="width: 600px;">
        <div class="row" style="padding: 20px;">
            <div class="card">
                <div class="container-float" style="text-align: center">
                    <h4 class="card-title">Ingrese Credenciales</h4>
                    <p class="card-category">
                        {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                    </p>
                </div>
                <div class="container">
                    @include('Alertas.Alertas')
                </div>
                <div class="container-fluid" style="padding: 20px;">
                    <form action="/authenticate" method="POST">
                        @csrf
                        {{-- <div style="text-align: center">
                            <div class="mb-3">
                                <img src="{{ asset('img/logokowi.png') }}" class="rounded-circle" width="100">
                            </div>
                            <h4>Ingrese Credenciales</h4>
                        </div> --}}
                        <label class="fw-bold text-secondary mb-1" style="font-size: 1rem">Nombre de usuario</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i style="color: gray"
                                    class="material-icons">person</i></span>
                            <input type="text" class="form-control" name="NomUsuario" value="{{ old('NomUsuario') }}"
                                placeholder="Nombre de Usuario" autofocus>
                        </div>

                        <label class="fw-bold text-secondary mb-1" style="font-size: 1rem">Contraseña</label>
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <i style="color: gray" class="material-icons">fingerprint</i>
                            </span>
                            <input type="password" class="form-control" name="Password" placeholder="Contraseña">
                        </div>
                        <div class="mb-3 btnLogin" style="text-align: center;">
                            <button id="loginBtn" class="btn btn-sm btn-dark">
                                <i class="fa fa-sign-in"></i> Iniciar Sesión
                            </button>
                            <button type="button" id="iniciandoSesionBtn" class="btn btn-sm btn-dark" hidden>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Iniciando Sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('loginBtn').hidden = true;
            document.getElementById('iniciandoSesionBtn').hidden = false;
        });
    </script>

    <script src="{{ asset('JQuery/jquery-3.6.0.min.js') }}"></script>
    @yield('contenido')

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    @yield('scriptTiendas')
    <script src="{{ asset('js/tiendasScript.js') }}"></script>

</body>

</html>

{{-- @extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Inicio de Sesión')
@section('dashboardWidth', 'width-general')
<link rel="stylesheet" href="css/styleLogin.css">
@section('contenido')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                @include('Alertas.Alertas')
            </div>
        </div>
    </div>
    <div class="container" style="width: 400px; margin-top: 6%;">
        <form action="/authenticate" method="POST">
            @csrf
            <div style="text-align: center">
                <div class="mb-3">
                    <img src="{{ asset('img/logokowi.png') }}" class="rounded-circle" width="100">
                </div>
                <h4>Ingrese Credenciales</h4>
            </div>
            <div class="inputs">
                <div class="form-group mb-3">
                    <span class="input-icon"><i style="color: gray" class="material-icons">person</i></span>
                    <input type="text" class="form-control" name="NomUsuario" value="{{ old('NomUsuario') }}"
                        placeholder="Nombre de Usuario" autofocus>
                </div>
                <div class="form-group mb-3">
                    <span class="input-icon"><i style="color: gray" class="material-icons">fingerprint</i></span>
                    <input type="password" class="form-control" name="Password" placeholder="Contraseña">
                </div>
            </div>
            <div class="mb-3 btnLogin" style="text-align: center;">
                <button id="loginBtn" class="btn login">
                    <i class="fa fa-sign-in"></i> Iniciar Sesión
                </button>
                <button type="button" id="iniciandoSesionBtn" class="btn login" hidden>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Iniciando Sesión
                </button>
            </div>
        </form>
        <br>
    </div>

    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('loginBtn').hidden = true;
            document.getElementById('iniciandoSesionBtn').hidden = false;
        });
    </script>
@endsection --}}
