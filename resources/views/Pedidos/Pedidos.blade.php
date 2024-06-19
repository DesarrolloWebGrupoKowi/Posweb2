@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Pedidos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <style>
        #ifrDatPedidos {
            height: 700px;
        }

        @media only screen and (max-width: 1200px) {
            #ifrDatPedidos {
                height: 430px;
            }
        }
    </style>

    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Pedidos - ' . $tienda->NomTienda,
                    'options' => [['name' => 'Pedidos guardados', 'value' => '/PedidosGuardados']],
                ])
                <form class="d-flex align-items-center justify-content-end gap-2" action="/DatPedidos" target="ifrDatPedidos"
                    id="formDatPedidos">
                    <div class="d-flex align-items-center gap-2">
                        <input class="form-control rounded" style="line-height: 18px/*  */" type="number"
                            name="cantArticulo" id="cantArticulo" placeholder="Cantidad">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" class="form-control rounded" style="line-height: 18px" name="txtCodEtiqueta"
                            id="txtCodEtiqueta" placeholder="Leer Código" minlength="12" maxlength="13" autocomplete="off"
                            autofocus required>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-center">
                @include('Alertas.Alertas')
            </div>
        </div>


        {{-- <iframe src="/MostrarPedidos" name="ifrDatPedidos" id="ifrDatPedidos" width="100%"></iframe> --}}
        <div class="content-table content-table-max-height content-table-full card border-0 p-4 h-full"
            style="border-radius: 10px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Articulo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th>Iva</th>
                        <th class="rounded-end">Eliminar</th>
                    </tr>
                </thead>

                <tbody>
                    @include('components.table-empty', ['items' => $datPedidos, 'colspan' => 6])
                    @foreach ($datPedidos as $datPedido)
                        <tr>
                            <td>{{ $datPedido->NomArticulo }}</td>
                            <td>{{ number_format($datPedido->CantArticulo, 3) }}</td>
                            <td>{{ number_format($datPedido->PrecioArticulo, 2) }}</td>
                            <td>{{ number_format($datPedido->SubTotalArticulo, 2) }}</td>
                            <td>{{ number_format($datPedido->IvaArticulo, 2) }}</td>
                            <td>
                                <button class="btn-table text-danger" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminar{{ $datPedido->IdDetPedidoTmp }}">
                                    @include('components.icons.delete')
                                </button>
                            </td>
                            @include('Pedidos.ModalConfirmacion')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="row align-items-center" style="width: 100%">
                <div class="col-3">
                    <h5 style="text-align: center; font-size: 16px">SUBTOTAL</h5>
                    <h2 style="text-align: center; font-size: 16px">${{ number_format($sumImporte, 2) }} </h2>
                </div>
                <div class="col-3">
                    <h5 style="text-align: center; font-size: 16px">IVA</h5>
                    <h2 style="text-align: center; font-size: 16px">${{ number_format($sumIva, 2) }}</h2>
                </div>
                <div class="col-3">
                    <h5 style="text-align: center; font-size: 16px">TOTAL</h5>
                    <h2 style="text-align: center; font-size: 16px">${{ number_format($total, 2) }}</h2>
                </div>
                <div class="col-3 d-flex justify-content-center" data-bs-toggle="modal"
                    data-bs-target="#ModalGuardarPedido">
                    <button class="btn btn-warning" style="display: {!! $banderaEnabled == 0 ? 'none' : 'block' !!}">
                        @include('components.icons.next') Siguiente
                    </button>
                </div>
            </div>
        </div>
        @include('Pedidos.ModalGuardarPedido')

    @endsection

    @section('scripts')
        <script>
            const btnGuardarPreventa = document.getElementById('btnGuardarPreventa');
            const guardarPedido = document.getElementById('guardarPedido');

            guardarPedido.addEventListener('submit', function() {
                btnGuardarPreventa.disabled = true;
            });
        </script>
        <script src="js/scriptPedidos.js"></script>
    @endsection
