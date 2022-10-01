@extends('plantillaBase.masterblade')
@section('title', 'Transacciones por Tienda')
@section('contenido')
    <div class="container mb-3">
        <div class="d-flex justify-content-center">
            <div class="col-auto card shadow">
                <h2>Transacciones por Tienda</h2>
            </div>
        </div>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form id="formTransacciones" action="/TransaccionesTienda" method="GET">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($idTienda))
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <form action="EliminarTransaccionTienda/{{ $idTienda }}" method="POST">
                        @csrf
                        <div class="row d-flex justify-content-end me-3 mb-2">
                            <div class="col-auto">
                                <button class="btn btn-sm btn-danger">
                                    <i class="fa fa-close"></i> Eliminar
                                </button>
                            </div>
                        </div>
                        <table class="table table-responsive table-striped shadow">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tienda</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($tiendasAgregadas->count() == 0)
                                    <tr>
                                        <td colspan="2">No Hay Tiendas</td>
                                    </tr>
                                @else
                                    @foreach ($tiendasAgregadas as $tAgregada)
                                        <tr>
                                            <td>{{ $tAgregada->NomTienda }}</td>
                                            <td>
                                                <input class="form-check-input" type="checkbox" name="chkEliminar[]"
                                                    id="chkEliminar" value="{{ $tAgregada->IdTienda }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="col-6">
                    <form action="/AgregarTransaccionTienda/{{ $idTienda }}" method="POST">
                        @csrf
                        <div class="row d-flex justify-content-end me-3 mb-2">
                            <div class="col-auto">
                                <button class="btn btn-sm btn-warning">
                                    <i class="fa fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                        <table class="table table-responsive table-striped shadow">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tienda</th>
                                    <th>Agregar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($tiendasPorAgregar->count() == 0)
                                    <tr>
                                        <td colspan="2">No Hay Tiendas</td>
                                    </tr>
                                @else
                                    @foreach ($tiendasPorAgregar as $tPorAgregar)
                                        <tr>
                                            <td>{{ $tPorAgregar->NomTienda }}</td>
                                            <td>
                                                <input class="form-check-input" type="checkbox" name="chkAgregar[]"
                                                    id="chkAgregar" value="{{ $tPorAgregar->IdTienda }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        const idTienda = document.getElementById('idTienda');
        idTienda.addEventListener('change', (e) => {
            document.getElementById('formTransacciones').submit();
        });
    </script>
@endsection
