@extends('plantillaBase.masterblade')
@section('title', 'Ver Clientes Cloud Por Tienda')
@section('contenido')
    <div class="container">
        <h2 class="titulo">Clientes Cloud Por Tienda</h2>
    </div>
    <div class="container">
        <form action="/VerClientesCloudTienda">
            <div class="row mb-3">
                <div class="col-3">
                    <input class="form-control" type="text" name="nomTienda" id="nomTienda" placeholder="Tienda"
                        value="{{ $nomTienda }}">
                </div>
                <div class="col-2">
                    <button class="btn card shadow">
                        <span class="material-icons">
                            search
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="container mb-3">
        @foreach ($tiendas as $tienda)
            <div class="accordion accordion-flush shadow rounded-3" id="accordionFlushExample{{ $tienda->IdTienda }}">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne{{ $tienda->IdTienda }}" aria-expanded="false"
                            aria-controls="flush-collapseOne">
                            {{ $tienda->IdTienda }}.- {{ $tienda->NomTienda }}
                            &nbsp;<span class="badge bg-primary">{{ $tienda->ClienteCloud->count() }}</span>
                        </button>
                    </h2>
                    <div id="flush-collapseOne{{ $tienda->IdTienda }}" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample{{ $tienda->IdTienda }}">
                        <div class="accordion-body">
                            @if ($tienda->ClienteCloud->count() == 0)
                                <ol class="list-group">
                                    <li class="d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">No Hay Clientes!</div>

                                        </div>
                                    </li>
                                </ol>
                            @else
                                @foreach ($tienda->ClienteCloud as $clienteCloudTienda)
                                    <ol class="list-group">
                                        <li class="d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">{{ $clienteCloudTienda->NomClienteCloud }}</div>
                                                {{ $clienteCloudTienda->IdClienteCloud }} -
                                                {{ $clienteCloudTienda->PivotCustomer->Ship_To }} -
                                                {{ $clienteCloudTienda->PivotCustomer->Bill_To }}
                                            </div>
                                        </li>
                                    </ol>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
