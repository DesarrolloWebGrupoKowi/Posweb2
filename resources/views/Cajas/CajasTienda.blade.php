@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Cajas Por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')

    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Cajas Por Tienda'])
            @if (!empty($idTienda))
                <div>
                    <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarCajaTienda">
                        <i class="fa fa-plus-circle pe-1"></i> Agregar caja a tienda
                    </button>
                </div>
            @endif
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4" id="formCajaTienda" action="/CajasTienda"
            method="GET">
            <div class="input-group" style="max-width: 350px">
                <select class="form-select shadow" name="idTienda" id="idTienda">
                    <option value="">Seleccione Tienda</option>
                    @foreach ($tiendas as $tienda)
                        <option {!! $tienda->IdTienda == $idTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th class="rounded-end">Caja(s)</th>
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
    </div>

    @include('Cajas.ModalAgregarCajaTienda')

    <script>
        document.getElementById('idTienda').addEventListener('change', (e) => {
            document.getElementById('formCajaTienda').submit();
        });
    </script>
@endsection
