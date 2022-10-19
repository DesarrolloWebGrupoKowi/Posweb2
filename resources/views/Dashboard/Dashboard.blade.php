@extends('plantillaBase.masterblade')
@section('title','Dashboard PosWeb2')
<link rel="stylesheet" href="css/styleDashboard.css">
@section('contenido')
<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <h2 class="col-auto card shadow-lg p-1">Dashboard de {{ Auth::user()->tipoUsuario->NomTipoUsuario }}</h2>
    </div>
    <div class="container">
        @include('Alertas.Alertas')
    </div>
    <div class="row">
        @if ($menus->count() == 0)
        <div class="d-flex justify-content-center">
            <h3><i style="color: red" class="fa fa-exclamation-triangle"></i> No Hay Menus Para Este Tipo de Usuario!
            </h3>
        </div>
        @else
        @foreach ($menus as $headerMenu)
        <div>
            <h5>{{ $headerMenu->NomTipoMenu }}</h5>
        </div>
        @foreach ($headerMenu->DetalleMenu as $detalleMenu)
        <div class="col-lg-2 col-sm-3 mb-1">
            <div class="card-box {{ $detalleMenu->PivotMenu->BgColor }} rounded-3">
                <div class="inner">
                    <h6> {{ $detalleMenu->PivotMenu->NomMenu }} </h6>
                </div>
                <div class="icon">
                    <i class="{{ $detalleMenu->PivotMenu->Icono }}" aria-hidden="true"></i>
                </div>
                <a href="{{ $detalleMenu->PivotMenu->Link }}" class="card-box-footer"><i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endforeach
        @endforeach
        @endif
    </div>
</div>
@endsection