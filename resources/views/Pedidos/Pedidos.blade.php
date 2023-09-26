@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Pedidos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <style>
        #ifrDatPedidos {
            height: 700px;
        }

        @media only screen and (max-width: 1366px) {
            #ifrDatPedidos {
                height: 430px;
            }
        }
    </style>

    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Pedidos - ' . $tienda->NomTienda])
            <div>
                <a href="/PedidosGuardados" class="btn btn-dark-outline">
                    <span class="material-icons">shopping_cart_checkout</span>
                </a>
            </div>
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/DatPedidos" target="ifrDatPedidos"
            id="formDatPedidos">
            <div class="col-2">
                <input class="form-control" type="number" name="cantArticulo" id="cantArticulo" placeholder="Cantidad">
            </div>
            <div class="col-3">
                <input type="text" class="form-control" name="txtCodEtiqueta" id="txtCodEtiqueta"
                    placeholder="Leer Código" minlength="12" maxlength="13" autocomplete="off" autofocus required>
            </div>
        </form>

        <iframe src="/MostrarPedidos" name="ifrDatPedidos" id="ifrDatPedidos" width="100%"></iframe>

    </div>

    <script src="js/scriptPedidos.js"></script>
@endsection
