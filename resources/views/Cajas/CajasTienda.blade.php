@extends('plantillaBase.masterblade')
@section('title', 'Cajas Por Tienda')
@section('contenido')
    <div class="d-flex justify-content-center">
        <h2 class="col-auto card shadow p-1">Cajas Por Tienda</h2>
    </div>
    <div class="container mb-3">
        <form id="formCajaTienda" action="/CajasTienda" method="GET">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $tienda->IdTienda == $idTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if (!empty($idTienda))
                    <div class="col d-flex justify-content-end">
                        <button type="button" class="btn card shadow" data-bs-toggle="modal"
                            data-bs-target="#ModalAgregarCajaTienda">
                            <span class="material-icons">add_circle</span>
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
    <div class="container">
        <table class="table table-responsive table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Tienda</th>
                    <th>Caja(s)</th>
                </tr>
            </thead>
            <tbody>
                @if ($cajasTienda->count() == 0 && !empty($idTienda))
                    <tr>
                        <th colspan="2">No Hay Cajas!</th>
                    </tr>
                @elseif($cajasTienda->count() == 0 && empty($idTienda))
                    <tr>
                        <th colspan="2">Seleccione Tienda!</th>
                    </tr>
                @else
                    @foreach ($cajasTienda as $cajaTienda)
                        <tr>
                            <td>{{ $cajaTienda->NomTienda }}</td>
                            <td>{{ $cajaTienda->IdCaja }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    @include('Cajas.ModalAgregarCajaTienda')

    <script>
        document.getElementById('idTienda').addEventListener('change', (e) => {
            document.getElementById('formCajaTienda').submit();
        });
    </script>
@endsection
