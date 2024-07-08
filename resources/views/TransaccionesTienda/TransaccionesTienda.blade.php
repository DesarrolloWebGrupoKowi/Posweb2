@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Transacciones por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Transacciones por Tienda'])
                <form class="d-flex align-items-center justify-content-end" id="formTransacciones"
                    action="/TransaccionesTienda" method="GET">
                    <div class="form-group" style="min-width: 300px">
                        <label class="fw-bold text-secondary">Seleccione una tienda</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda"
                            required>
                            <option value="">Seleccione una tienda</option>
                            @foreach ($tiendas as $tienda)
                                <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        @if (empty($idTienda))
            <h2 class="text-center">Selecciona una tienda</h2>
        @endif

        @if (!empty($idTienda))
            <div class="row">

                <div class="col-6">
                    <form action="EliminarTransaccionTienda/{{ $idTienda }}" method="POST">
                        @csrf

                        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Tienda</th>
                                        <th class="rounded-end">Eliminar</th>
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
                            <div class="row d-flex justify-content-end mb-2">
                                <div class="col-auto">
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fa fa-close"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-6">
                    <form action="/AgregarTransaccionTienda/{{ $idTienda }}" method="POST">
                        @csrf
                        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Tienda</th>
                                        <th class="rounded-end">Agregar</th>
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
                            <div class="row d-flex justify-content-end mb-2">
                                <div class="col-auto">
                                    <button class="btn btn-sm btn-warning">
                                        <i class="fa fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        @endif
    </div>

    <script>
        const idTienda = document.getElementById('idTienda');
        idTienda.addEventListener('change', (e) => {
            document.getElementById('formTransacciones').submit();
        });
    </script>
@endsection
