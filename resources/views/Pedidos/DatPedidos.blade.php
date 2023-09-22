<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}">
    <link rel="stylesheet" href="css/Style.css">
    <link href="material-icon/material-icon.css" rel="stylesheet">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
</head>
<style>
    i {
        cursor: pointer;
    }

    #tblPedido {
        height: 600px;
    }

    @media only screen and (max-width: 1200px) {
        #tblPedido {
            height: 330px;
        }
    }

    .pedidoAlert {
        position: absolute;
        /*margin-left: 60vh;*/
        margin-top: 20vh;
        border-radius: 15px;
        /*height: 25vh;
    width: 25vh;*/
        background-color: white;
        color: red;
        /*box-shadow: 0 .5rem .5rem rgb(255, 0, 0);*/
    }
</style>

<body class="bg-transparent">
    <div id="tblPedido" class="content-table content-table-full card p-4" style="border-radius: 20px">
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
            <div class="d-flex justify-content-center">
                @include('Alertas.Alertas')
            </div>
            <tbody>
                @foreach ($datPedidos as $datPedido)
                    <tr>
                        <td>{{ $datPedido->NomArticulo }}</td>
                        <td>{{ number_format($datPedido->CantArticulo, 3) }}</td>
                        <td>{{ number_format($datPedido->PrecioArticulo, 2) }}</td>
                        <td>{{ number_format($datPedido->SubTotalArticulo, 2) }}</td>
                        <td>{{ number_format($datPedido->IvaArticulo, 2) }}</td>
                        <td>
                            <i style="font-size: 20px" class="material-icons eliminar" data-bs-toggle="modal"
                                data-bs-target="#ModalEliminar{{ $datPedido->IdDetPedidoTmp }}">delete_forever</i>
                        </td>
                        @include('Pedidos.ModalConfirmacion')
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 card p-2" style="border-radius: 20px">
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
                    <i class="fa fa-arrow-circle-right"></i> Siguiente
                </button>
            </div>
        </div>
    </div>
    @include('Pedidos.ModalGuardarPedido')

    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<script>
    const btnGuardarPreventa = document.getElementById('btnGuardarPreventa');
    const guardarPedido = document.getElementById('guardarPedido');

    guardarPedido.addEventListener('submit', function() {
        btnGuardarPreventa.disabled = true;
    });
</script>
