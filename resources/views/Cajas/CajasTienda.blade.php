@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Cajas Por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Cajas Por Tienda'])
                @if (!empty($idTienda))
                    <div>
                        <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                            data-bs-target="#ModalAgregarCajaTienda">
                            Agregar caja a tienda @include('components.icons.plus-circle')
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end pb-2" id="formCajaTienda" action="/CajasTienda"
                method="GET">
                <div class="input-group" style="max-width: 350px">
                    <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $tienda->IdTienda == $idTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th class="rounded-end">Caja(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $cajasTienda, 'colspan' => 2])
                    @foreach ($cajasTienda as $cajaTienda)
                        <tr>
                            <td>{{ $cajaTienda->NomTienda }}</td>
                            <td>{{ $cajaTienda->IdCaja }}</td>
                        </tr>
                    @endforeach
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
