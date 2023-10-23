@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Recepción de Producto')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Recepción de Producto ' . $tienda->NomTienda])
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Origen</th>
                        <th>Tienda</th>
                        <th>Llegada</th>
                        <th>Status</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($recepcion->count() == 0)
                        <tr>
                            <td colspan="6">No Hay Recepciones Pendientes!</td>
                        </tr>
                    @else
                        @foreach ($recepcion as $rTienda)
                            <tr>
                                <td>{{ $rTienda->IdCapRecepcion }}</td>
                                <td>{{ $rTienda->PackingList }}</td>
                                <td>{{ $rTienda->NomTienda }}</td>
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

        <div class="mt-4 content-table content-table-full card p-4" style="border-radius: 20px">
            <div class="row mb-3">
                <div class="col d-flex justify-content-start">
                    <h4>Detalle de Productos en Recepción</h4>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarProductoManual">
                        <i class="fa fa-plus-square"></i> Producto Manual
                    </button>
                </div>
            </div>

            <form id="myform" action="/RecepcionarProducto/{{ $idRecepcion }}" method="POST">
                @csrf
                <input type="hidden" name="cantidad" value="{{ $cantidadPreparado }}">

                <table style="width: 100%">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Origen</th>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Confirmar</th>
                            @if ($cantidadPreparado)
                                <th>Cantidad platillos</th>
                            @endif
                            <th class="rounded-end" style="text-align: center">
                                Recepcionar
                                <input checked type="checkbox" class="form-check-input mt-3" name="chkTodos" id="chkTodos">
                            </th>
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
                                    <td>
                                        <span class="cantRecepcionada[{{ $dRecepcion->CodArticulo }}]">
                                            {{ number_format($dRecepcion->CantEnviada, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @if (!$cantidadPreparado)
                                                <input style="width: 15vh" type="text"
                                                    class="form-control form-control-sm"
                                                    name="cantRecepcionada[{{ $dRecepcion->CodArticulo }}]"
                                                    value="{{ $dRecepcion->CantEnviada }}">
                                            @else
                                                <input style="width: 15vh" type="hidden"
                                                    class="form-control form-control-sm "
                                                    name="cantRecepcionada[{{ $dRecepcion->CodArticulo }}]"
                                                    value="{{ $dRecepcion->CantEnviada }}">
                                                <input style="width: 15vh" type="text"
                                                    class="form-control form-control-sm"
                                                    name="hiddencantRecepcionada[{{ $dRecepcion->CodArticulo }}]"
                                                    value="{{ $dRecepcion->CantEnviada }}" disabled>
                                            @endif
                                        </div>
                                    </td>
                                    @if ($cantidadPreparado)
                                        <td>
                                            <div class="d-flex">
                                                <input style="width: 15vh" type="number" step="1"
                                                    class="form-control form-control-sm data-cant"
                                                    id="cantRecepcionada[{{ $dRecepcion->CodArticulo }}]"
                                                    value="{{ $cantidadPreparado }}" min="0">
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <input checked type="checkbox" class="form-check-input d-block"
                                            style="margin: 0 auto;" name="chkArticulo[{{ $dRecepcion->CodArticulo }}]"
                                            id="chkArticulo" value="{{ $dRecepcion->PackingList }}">
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
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#modalConfirmarRecepcion">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                    @include('Recepcion.ModalConfirmarRecepcion')
                @endif
            </form>
        </div>
    </div>


    @include('Recepcion.ModalAgregarProductoManual')

    <script>
        const chkArticulos = document.querySelectorAll('#chkArticulo');
        const chkTodos = document.getElementById('chkTodos');
        const myForm = document.getElementById('myform');

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

        myForm.addEventListener('submit', (e) => {
            const button = document.getElementById('button-submit');
            button.disabled = true;
        })

        document.addEventListener('change', (e) => {
            if (e.target.matches('.data-cant')) {
                let id = e.target.id;
                let value = parseInt(e.target.value);
                e.target.value = value;

                document.getElementsByName('cantidad')[0].value = value;

                document.querySelectorAll('.data-cant').forEach(item => {
                    item.value = value;
                    let myid = item.id;

                    // Aqui obtenemos el factor a multiplicar del paquete
                    let cantidadOriginal = {{ $cantidadPreparado ? $cantidadPreparado : 0 }};
                    let beforeValue = document.getElementsByClassName(myid)[0].textContent;

                    let factor = beforeValue / cantidadOriginal;
                    document.getElementsByName(myid)[0].value = (value * factor).toFixed(3);
                    document.getElementsByName('hidden' + myid)[0].value = (value * factor).toFixed(3);
                })

                // console.log(document.querySelectorAll('.data-cant-hidden'));


                // document.querySelectorAll('.data-cant-hidden').forEach(item => {
                //     item.value = value;
                //     let myid = item.id;

                //     // Aqui obtenemos el factor a multiplicar del paquete
                //     let cantidadOriginal = {{ $cantidadPreparado }};
                //     let beforeValue = document.getElementsByClassName(myid)[0].textContent;

                //     let factor = beforeValue / cantidadOriginal;
                //     document.getElementsByName(myid)[0].value = (value * factor).toFixed(3);
                // })
            }
        })
    </script>
@endsection
