@extends('plantillaBase.masterblade')
@section('title', 'Recepción de Producto')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h3 class="card shadow p-1">Recepción de Producto - {{ $tienda->NomTienda }}</h3>
        </div>
    </div>
    <div class="container">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <table class="table table-sm table-responsive table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Origen</th>
                    <th>Tienda</th>
                    <th>Llegada</th>
                    <th>Status</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($recepcion->count() == 0)
                    <tr>
                        <td colspan="5">No Hay Recepciones Pendientes!</td>
                    </tr>
                @else
                    @foreach ($recepcion as $rTienda)
                        <tr>
                            <td>{{ $rTienda->PackingList }}</td>
                            <td>{{ $rTienda->IdTiendaOrigen }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($rTienda->FechaLlegada)) }}</td>
                            <td>{{ $rTienda->StatusRecepcion->NomStatusRecepcion }}</td>
                            <td>
                                <form class="d-inline" action="/RecepcionProducto">
                                    <input type="hidden" name="idRecepcion" value="{{ $rTienda->IdCapRecepcion }}">
                                    <button class="btn btn-sm" data-bs-toggle="mensaje" title="Recepcionar">
                                        <span class="material-icons">receipt_long</span>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#ModalCancelarRecepcion{{ $rTienda->IdCapRecepcion }}">
                                    <span style="color: red;" class="material-icons">cancel</span>
                                </button>
                            </td>
                            @include('Recepcion.ModalCancelarRecepcion')
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="container">
        <div class="row mb-3">
            <div class="col d-flex justify-content-start">
                <h4 class="card shadow">Detalle de Productos en Recepción</h4>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ModalAgregarProductoManual">
                    <i class="fa fa-plus-square"></i> Producto Manual
                </button>
            </div>
        </div>
        <form action="/RecepcionarProducto/{{ $idRecepcion }}" method="POST">
            @csrf
            <table class="table table-sm table-responsive table-striped shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Origen</th>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Confirmar</th>
                        <th style="text-align: center">Recepcionar <input checked type="checkbox"
                                class="form-check-input mt-1" name="chkTodos" id="chkTodos"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($detalleRecepcion))
                        <tr>
                            <td colspan="6">Productos a Recepcionar</td>
                        </tr>
                    @else
                        @foreach ($detalleRecepcion as $dRecepcion)
                            <tr>
                                <td>{{ $dRecepcion->PackingList }}</td>
                                <td>{{ $dRecepcion->CodArticulo }}</td>
                                <td>{{ $dRecepcion->NomArticulo }}</td>
                                <td>{{ number_format($dRecepcion->CantEnviada, 2) }}</td>
                                <td>
                                    <input style="width: 15vh" type="text" class="form-control form-control-sm"
                                        name="cantRecepcionada[{{ $dRecepcion->CodArticulo }}]"
                                        value="{{ $dRecepcion->CantEnviada }}">
                                </td>
                                <td style="text-align: center">
                                    <input checked type="checkbox" class="form-check-input"
                                        name="chkArticulo[{{ $dRecepcion->CodArticulo }}]" id="chkArticulo"
                                        value="{{ $dRecepcion->PackingList }}">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th style="text-align: center">Total:</th>
                        <th>{{ number_format($totalCantidad, 2) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            @if (!empty($detalleRecepcion))
                <div class="d-flex justify-content-end mb-3">
                    <div class="col-auto me-4">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalConfirmarRecepcion">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
                @include('Recepcion.ModalConfirmarRecepcion')
            @endif
        </form>
    </div>
    @include('Recepcion.ModalAgregarProductoManual')

    <script>
        const chkArticulos = document.querySelectorAll('#chkArticulo');
        const chkTodos = document.getElementById('chkTodos');

        chkTodos.addEventListener('click', (e) => {
            if (chkTodos.checked == true) {
                chkArticulos.forEach(element => {
                    element.checked = true;
                });
            } else {
                chkArticulos.forEach(element => {
                    element.checked = false;
                });
            }
        });
    </script>
@endsection
