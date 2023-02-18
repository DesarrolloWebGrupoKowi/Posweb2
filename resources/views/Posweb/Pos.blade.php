<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        .btnOpcion:hover {
            transform: scale(.95);
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="img/logokowi.png">
    <link href="material-icon/material-icon.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
    <link rel="stylesheet" href="css/stylePos.css">
    <title>:: Punto de Venta ::</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row shadow">
            <div class="col-3">
                <div class="row">
                    <div class="col-auto">
                        <a href="/Dashboard"><img class="rounded lKowi mt-1" src="img/logokowi.png" width="50"
                                height="50"></a>
                    </div>
                    <div class="col-auto mt-2">
                        <h6>{{ $usuario->NomCiudad }}</h6>
                        <h6 style="color: {!! empty($caja->NumCaja) ? 'red' : '' !!}">Caja: {!! empty($caja->NumCaja) ? 'No Hay Caja Activa' : $caja->NumCaja !!}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 mt-1 d-flex justify-content-center">
                <h1 style="font-weight: bold; position: absolute;">{{ $usuario->NomTienda }}</h1>
            </div>
            <div class="col-3" style="text-align: right">
                <h5>{{ $nombre }} {{ $apellido }}</h5>
                <h5>{{ $fechaHoy }}</h5>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="row mt-1 mb-1">
            <div id="pos" class="col-md-9 border border-warning border-2 shadow table-responsive">
                <table class="table table-responsive mb-2">
                    <thead style="font-size: 22px">
                        <tr>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th>Iva</th>
                            <th>
                                <i style="color: red; font-size: 18px; display: {!! ($preventa->count() == 0 ? 'none' : $banderaMultiPago > 0) ? 'none' : 'block' !!}"
                                    class="fa fa-trash" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarPreventa"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preventa as $pArticulo)
                            <tr style="{!! $pArticulo->IdPaquete != null ? 'background-color: rgb(213, 253, 213);' : '' !!}">
                                <td style="color: {!! $pArticulo->PrecioVenta == 0 ? 'red; font-weight:bold;' : '' !!}">
                                    @if ($pArticulo->IvaArticulo > 0)
                                        {{ $pArticulo->NomArticulo }} (I)
                                    @else
                                        {{ $pArticulo->NomArticulo }}
                                    @endif
                                </td>
                                <td style="color: {!! $pArticulo->PrecioVenta == 0 ? 'red; font-weight:bold;' : '' !!}">
                                    {{ number_format($pArticulo->CantArticulo, 3) }}</td>
                                <td style="color: {!! $pArticulo->PrecioVenta == 0 ? 'red; font-weight:bold;' : '' !!}">
                                    ${{ number_format($pArticulo->PrecioVenta, 2) }}</td>
                                <td style="color: {!! $pArticulo->SubTotalArticulo == 0 ? 'red; font-weight:bold;' : '' !!}">
                                    ${{ number_format($pArticulo->SubTotalArticulo, 2) }}</td>
                                <td style="color: {!! $pArticulo->PrecioVenta == 0 ? 'red; font-weight:bold;' : '' !!}">
                                    ${{ number_format($pArticulo->IvaArticulo, 2) }}</td>
                                <td>
                                    <i id="btnEliminar" class="fa fa-trash"
                                        style="color: red; display: {!! $banderaMultiPago > 0 ? 'none' : 'block' !!}" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminar{{ $pArticulo->IdDatVentaTmp }}"></i>
                                    @include('Posweb.ModalEliminar')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="opciones" class="col-md-3 border border-warning border-2 shadow">
                <div id="divCountArticulo" class="row mt-1 me-1 ms-1 shadow text-black">
                    <div class="col-12 mt-1">
                        @if (!empty($cliente))
                            <h6>Cliente : {{ $cliente->Nombre }} {{ $cliente->Apellidos }} <i data-bs-toggle="mensaje"
                                    title="Quitar Empleado"
                                    style="color: red; cursor: pointer; display: {!! $banderaMultiPago > 0 ? 'none' : '' !!}"
                                    class="fa fa-close" name="chkCliente" id="chkCliente"></i></h6>
                            <h6>Crédito Disponible : <strong
                                    style="color: {!! $creditoDisponible == 0 ? 'red' : '' !!}">${{ number_format($creditoDisponible, 2) }}</strong>
                            </h6>
                            <h6>Dinero Electrónico Disponible:
                                <strong>${{ number_format($monederoEmpleado, 2) }}</strong>
                            </h6>
                        @endif
                        <h6>Articulos : {{ $preventa->count() }}</h6>
                    </div>
                </div>
                <div style="text-align: center" class="row mt-2">
                    <div class="col-6">
                        <button {!! $banderaMultiPago > 0 ? 'disabled' : '' !!} class="btnOpcion" data-bs-toggle="modal"
                            data-bs-target="#ModalEmpleado">
                            <i class="fa fa-user"></i> EMPLEADO
                        </button>
                    </div>
                    <div class="col-6">
                        <button id="btnSolicitudFactura" class="btnOpcion">
                            <i class="fa fa-id-badge"></i> FACTURA
                        </button>
                    </div>
                </div>
                <div style="text-align: center" class="row mt-2">
                    <div class="col-6">
                        <button class="btnOpcion">
                            <i class="fa fa-comments"></i> EVENTOS
                        </button>
                    </div>
                    <div class="col-6">
                        <button id="btnPedidos" class="position-relative shadow btnOpcion">
                            <i class="fa fa-cart-plus"></i> PEDIDOS
                            @if ($pedidosPendientes > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $pedidosPendientes }}
                            </span>
                            @endif
                        </button>
                    </div>
                </div>
                <div style="text-align: center" class="row mt-2 mb-3">
                    <div class="col-6">
                        <form id="frmPaquetes" action="/PaquetesPreventa" method="GET">
                            <select class="btnOpcion" name="idPaquete" id="idPaquete" required>
                                <option style="color: white; background-color: black;" value="">PAQUETES</option>
                                @foreach ($paquetes as $paquete)
                                    <option style="color: black; background-color: white;"
                                        value="{{ $paquete->IdPaquete }}">{{ $paquete->NomPaquete }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-6">
                        <button class="btnOpcion" data-bs-toggle="modal" data-bs-target="#ModalOpciones">
                            <i class="fa fa-bars"></i> OPCIONES
                        </button>
                    </div>
                </div>
                <div class="border border-3 mb-3 rounded DetalleMultiPagoPos table-responsive"
                    style="font-size: 18px; display: {!! $banderaMultiPago > 0 ? 'block' : 'none' !!}">
                    <table class="table table-striped table-responsive">
                        <thead class="table-dark">
                            <tr>
                                <th>Tipo Pago</th>
                                <th>Pagado</th>
                                <th>Por Pagar</th>
                                <th>
                                    <i class="fa fa-trash-o"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datTipoPago as $pagoRestante)
                                <tr>
                                    <td>{{ $pagoRestante->NomTipoPago }}</td>
                                    <td>{{ number_format($pagoRestante->Pago, 2) }}</td>
                                    <td style="font-weight: bold; color: red;">
                                        {{ number_format($pagoRestante->Restante, 2) }}
                                    </td>
                                    <td>
                                        <form id="eliminarPago{{ $pagoRestante->IdDatTipoPago }}" action="EliminarPago/{{ $pagoRestante->IdDatTipoPago }}"
                                            method="POST">
                                            @csrf
                                            <i style="color: red" class="fa fa-trash" data-bs-toggle="mensaje"
                                                title="Eliminar Pago"
                                                onclick="event.preventDefault();
                                                document.getElementById('eliminarPago<?php echo $pagoRestante->IdDatTipoPago; ?>').submit();"></i>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <form id="formPos" action="/CalculosPreventa">
                    <input type="hidden" name="nNominaEmpleado"
                        value="{{ empty($cliente->NumNomina) ? '' : $cliente->NumNomina }}">
                    <div class="row mb-3">
                        <div class="col-6">
                            <input {!! $banderaMultiPago > 0 ? 'disabled' : '' !!} class="form-control txtPos" type="number"
                                name="txtCantidad" id="txtCantidad" placeholder="Peso" min="0.001"
                                step="any" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input {!! $banderaMultiPago > 0 ? 'disabled' : '' !!} type="text" name="txtCodigo" id="txtCodigo"
                                class="form-control txtPos" placeholder="Leer Código" autocomplete="off" required
                                autofocus maxlength="13" minlength="12" OnKeyPress="return teclas(event);">
                        </div>
                        <input hidden id="hacerSubmit" type="submit" />
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div id="subtotal" class="col-3 shadow border border-warning border-2">
                <h3>SUBTOTAL</h3>
                <h1>${{ $subTotalPreventa }}</h1>
            </div>
            <div id="iva" class="col-3 shadow border border-warning border-2">
                <h3>IVA</h3>
                <h1>${{ $ivaPreventa }}</h1>
            </div>
            <div id="total" class="col-3 shadow border border-warning border-2">
                <h3>TOTAL</h3>
                <h1>${{ $totalPreventa }}</h1>
            </div>
            <div id="botones" class="col-3 shadow border border-warning border-2">
                <div class="row">
                    <div class="col-6">
                        <button id="btnConsultar" class="btnOpcion" data-bs-toggle="modal"
                            data-bs-target="#ModalConsultar">
                            <i class="fa fa-search"></i> CONSULTAR
                        </button>
                    </div>
                    <div class="col-6">
                        @if ($banArticuloSinPrecio->count() == 0)
                            <button id="btnPagar" class="btnPagar" data-bs-toggle="modal"
                                data-bs-target="#ModalPagar">
                                <i class="fa fa-usd"></i> PAGAR
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Posweb.ModalConsultar')
    @include('Posweb.ModalEliminarPreventa')
    @include('Posweb.ModalPagar')
    @include('Posweb.ModalOpciones')
    @include('Posweb.ModalEmpleados')
    <script src="JQuery/jquery-3.6.0.min.js"></script>
    <script src="js/scriptPos.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="mensaje"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        document.getElementById('idPaquete').addEventListener('change', (e) => {
            if (document.getElementById('idPaquete').value != '') {
                document.getElementById('frmPaquetes').submit();
            }
        })
    </script>
</body>

</html>
