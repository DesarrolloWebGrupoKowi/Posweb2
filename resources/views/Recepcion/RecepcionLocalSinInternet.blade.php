@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Recepción Sin Internet')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Recepción Sin Internet (Local)'])
            <form action="/AgregarProductoLocalSinInternet" method="GET">
                <div class="row ms-3">
                    <div class="col-auto">
                        <input class="form-control" list="articulos" name="codArticulo" id="codArticulo"
                            placeholder="Nombre del producto" autocomplete="off" required autofocus>
                        <datalist id="articulos">
                            @foreach ($articulos as $articulo)
                                <option value="{{ $articulo->CodArticulo }}">
                                    {{ $articulo->NomArticulo }}
                                </option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-auto">
                        <input class="form-control" type="number" name="cantArticulo" placeholder="Cantidad" step="any" min="0.01"
                            required>
                    </div>
                    <div class="col-auto">
                        <button class="btn card">
                            <span class="material-icons">add_circle</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        @if ($capturasSinInternet->count() > 0)
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th class="rounded-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($capturasSinInternet as $articuloLocal)
                            <tr>
                                <td>{{ $articuloLocal->CodArticulo }}</td>
                                <td>{{ $articuloLocal->NomArticulo }}</td>
                                <td>{{ number_format($articuloLocal->CantArticulo, 3) }}</td>
                                <td>
                                    <i class="fa fa-trash" style="color: red; font-size: 22px; cursor: pointer;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#EliminarArticulo{{ $articuloLocal->IdCapRecepcionManual }}"></i>
                                </td>
                                @include('Recepcion.ModalEliminarArticuloSinInternet')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="container">
                <div class="d-flex justify-content-center mt-4">
                    <div class="col-auto">
                        <button class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#confirmarRecepcionSinInternet">
                            <i class="fa fa-check"></i> Recepcionar Producto
                        </button>
                    </div>
                </div>
            </div>
            @include('Recepcion.ConfirmarRecepcionSinInternet')
        @endif
    </div>
@endsection
