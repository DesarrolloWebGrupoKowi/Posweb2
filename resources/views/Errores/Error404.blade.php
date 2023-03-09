@extends('plantillaBase.masterblade')
@section('title', 'Pagina no encontrada')
@section('contenido')
    <div class="container">
        <h2 style="text-align: center">
            <i style="color: red" class="fa fa-exclamation-triangle"></i> (≥o≤) <i style="color: red" class="fa fa-exclamation-triangle"></i>
        </h2>
        <h3 style="text-align: center">Upps!.. La pagina solicitada: <strong
                style="color: red">{{ url()->current() }}</strong> no fue encontrada mi buen amigo(a)!</h3>
    </div>
@endsection
