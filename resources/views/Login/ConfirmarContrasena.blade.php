@extends('plantillaBase.masterblade')
@section('title','Confirmar Contraseña')
@section('contenido')
<div class="container">
    <div>
        <h1 class="titulo">Confirmar Contraseña</h1>
    </div>
    <div>
        <h2>{{Auth::user()->NomUsuario}}</h2>
        <form action="/ConfirmContrasena" method="POST">
            @csrf
            <input class="form-control" type="text" name="password" id="password" placeholder="Contraseña">
            <button class="btn btn-success">Confirmar</button>
        </form>
    </div>
</div>
@endsection