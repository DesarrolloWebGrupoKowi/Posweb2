@extends('plantillaBase.masterblade')
@section('title','Pagina no encontrada')
@section('contenido')
    <div class="container">
        <div class="d-flex justify-content-center">
            <h2><i style="color: red" class="fa fa-exclamation-triangle"></i></h2>
        </div>
        <div class="d-flex justify-content-center">
            
            <h3>Upps!.. La pagina solicitada: <strong style="color: red">{{ url()->current() }}</strong> no fue encontrada mi buen amigo(a)!</h3>
        </div>
    </div>
@endsection