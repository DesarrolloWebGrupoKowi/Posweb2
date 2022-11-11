@extends('plantillaBase.masterblade')
@section('title', 'Inicio de Sesión')
<link rel="stylesheet" href="css/styleLogin.css">
@section('contenido')
    <div class="mt-3 mb-3 ms-3 d-flex justify-content-start text-white">
        <h4>{{ $fechaHoy }}</h4>
    </div>
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
                <button class="btn login">
                    <i class="fa fa-sign-in"></i> Iniciar Sesión
                </button>
            </div>
        </form>
        <br>
    </div>
@endsection
