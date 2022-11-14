@extends('plantillaBase.masterblade')
@section('title', 'Pedidos')
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
    <div class="container">
        <div class="titulo">
            <h3>Pedidos - {{ $tienda->NomTienda }}</h3>
        </div>
        <form action="/DatPedidos" target="ifrDatPedidos" id="formDatPedidos">
            <div class="row">
                <div class="col-2">
                    <input class="form-control" type="number" name="cantArticulo" id="cantArticulo" placeholder="Cantidad">
                </div>
                <div class="col-3">
                    <input type="text" class="form-control" name="txtCodEtiqueta" id="txtCodEtiqueta"
                        placeholder="Leer Código" minlength="12" maxlength="13" autocomplete="off" autofocus required>
                </div>
                <div class="col-7 text-end">
                    <a href="/PedidosGuardados" type="button" class="btn" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Pedidos Guardados">
                        <span style="font-size: 35px" class="material-icons">shopping_cart_checkout</span>
                    </a>
                </div>
            </div>
        </form>
        <div class="mb-3 border border-warning rounded">
            <iframe src="/MostrarPedidos" name="ifrDatPedidos" id="ifrDatPedidos" width="100%"></iframe>
        </div>

    </div>

    <script src="js/scriptPedidos.js"></script>
@endsection
