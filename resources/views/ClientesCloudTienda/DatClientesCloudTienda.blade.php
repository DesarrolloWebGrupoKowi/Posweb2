@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Clientes Cloud Por Tienda')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid pt-4 width-95">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Clientes Cloud Por Tienda'])
            <div>
                <a href="/VerClientesCloudTienda" class="btn btn-sm btn-dark">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar menú
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-end justify-content-end flex-wrap pb-4 gap-2" action="/RelacionClienteCloudTienda"
            target="ifrClienteCloud">
            <div class="form-group" style="min-width: 300px">
                <label class="fw-bold text-secondary pb-1">Tienda</label>
                <select class="form-select" name="idTienda" id="idTienda">
                    @foreach ($tiendas as $tienda)
                        <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="min-width: 300px">
                <label class="fw-bold text-secondary pb-1">Cliente Cloud</label>
                <select class="form-select" name="idClienteCloud" id="idClienteCloud">
                    @foreach ($clientesCloud as $clienteCloud)
                        <option value="{{ $clienteCloud->IdClienteCloud }}">{{ $clienteCloud->NomClienteCloud }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-warning">
                <i class="fa fa-search"></i> Buscar
            </button>
        </form>

        <div class="row">
            <div class="col-lg-6">
                <iframe name="ifrClienteCloud" width="100%" height="600vh" frameborder="0"></iframe>
            </div>
            <div class="col-lg-6">
                <iframe name="ifrGuardarRelacionClienteCloud" width="100%" height="600vh" frameborder="0"></iframe>
            </div>
        </div>
    </div>
@endsection
