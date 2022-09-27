@extends('plantillaBase.masterblade')
@section('title','Clientes Cloud Por Tienda')
@section('contenido')
<div class="container mb-3">
    <h2 class="titulo card">Clientes Cloud Por Tienda</h2>
    <div class="mt-3">
        @include('Alertas.Alertas')
    </div>
</div>
<div class="container">
    <div class="d-flex justify-content-end">
        <a href="/VerClientesCloudTienda" class="btn btn-warning">
            <i class="fa fa-user-secret"></i> Clientes
        </a>
    </div>
</div>
<div class="container mb-3">
    <form action="/RelacionClienteCloudTienda" target="ifrClienteCloud">
        <div class="row d-flex justify-content-center mb-3">
            <div class="col-4">
                <label for="">Tienda</label>
                <select class="form-select" name="idTienda" id="idTienda">
                    @foreach ($tiendas as $tienda)
                    <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label for="">Cliente Cloud</label>
                <select class="form-select" name="idClienteCloud" id="idClienteCloud">
                    @foreach ($clientesCloud as $clienteCloud)
                    <option value="{{ $clienteCloud->IdClienteCloud }}">{{ $clienteCloud->NomClienteCloud }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button class="btn btn-warning">
                <i class="fa fa-search"></i> Buscar
            </button>
        </div>
    </form>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <iframe name="ifrClienteCloud" width="100%" height="600vh" frameborder="0"></iframe>
        </div>
        <div class="col-6">
            <iframe name="ifrGuardarRelacionClienteCloud" width="100%" height="600vh" frameborder="0"></iframe>
        </div>
    </div>
</div>
@endsection