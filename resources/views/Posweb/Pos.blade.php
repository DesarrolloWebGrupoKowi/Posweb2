<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="img/logokowi-v2.png">
    <link href="material-icon/material-icon.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Icons/font-awesome.min.css">
    <link rel="stylesheet" href="css/stylePosTailwind.css">
    <title>:: Punto de Venta ::</title>
    <style>
        input[type="checkbox"] {
            width: 2em;
            height: 2em;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column justify-content-between gap-4 p-4" style="min-height: 100vh">
        {{-- Header --}}
        <div class="d-flex flex-row card p-4" style="border-radius: 20px;">
            <div class="col-3">
                <div class="d-flex">
                    <a href="/Dashboard">
                        <img src="img/logokowi-v2.png" width="50" height="50">
                    </a>
                    <div class="ps-2" style="height: 50px">
                        <h6>{{ $usuario->NomCiudad }}</h6>
                        <h6 style="color: {!! empty($caja->NumCaja) ? 'red' : '' !!}">Caja: {!! empty($caja->NumCaja) ? 'No Hay Caja Activa' : $caja->NumCaja !!}</h6>
                    </div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center justify-content-center" style="height: 50px">
                <h1 class="slate-700-color text-center titulo">{{ $usuario->NomTienda }}</h1>
            </div>

            <div class="col-3" style="text-align: right; height: 50px">
                <h6 class="slate-700-color">{{ $nombre }} {{ $apellido }}</h6>
                <h6 class="slate-700-color">{{ $fechaHoy }}</h6>
            </div>
            {{-- Notificaciones --}}
        </div>

        <div class="position-absolute" style="top: 100px; width: calc(100% - 24px); z-index: 99;">
            <div class="col-auto">
                @include('Alertas.Alertas')
            </div>
        </div>

        {{-- Body --}}
        <div class="d-flex flex-xl-row-reverse flex-column flex-xl-row flex-grow-1 gap-4">

            {{-- Menu --}}
            <div id="opciones" class="col-12 col-xl-3 border p-4 card" style="border-radius: 20px;">
                <div id="divCountArticulo" class="d-flex border card text-black p-4">
                    <div class="col-12 mt-1">
                        @if (!empty($frecuenteSocio))
                            <h6>Cliente : {{ $frecuenteSocio->Nombre }} <i data-bs-toggle="mensaje"
                                    title="Quitar Empleado"
                                    style="color: red; cursor: pointer; display: {!! $banderaMultiPago > 0 ? 'none' : '' !!}"
                                    class="fa fa-close" name="chkCliente" id="chkCliente"></i>
                            </h6>
                            <h6>Dinero Electrónico Disponible:
                                <strong>${{ number_format($monederoEmpleado, 2) }}</strong>
                            </h6>
                        @endif
                        @if (!empty($cliente))
                            <h6><span class="orange">Cliente :</span> {{ $cliente->Nombre }} {{ $cliente->Apellidos }}
                                <i data-bs-toggle="mensaje" title="Quitar Empleado"
                                    style="color: red; cursor: pointer; display: {!! $banderaMultiPago > 0 ? 'none' : '' !!}"
                                    class="fa fa-close" name="chkCliente" id="chkCliente"></i>
                            </h6>
                            <h6><span class="orange">Crédito Disponible :</span> <strong
                                    style="color: {!! $creditoDisponible == 0 ? 'red' : '' !!}">${{ number_format($creditoDisponible, 2) }}</strong>
                            </h6>
                            <h6><span class="orange">Dinero Electrónico Disponible:</span>
                                <strong>${{ number_format($monederoEmpleado, 2) }}</strong>
                            </h6>
                        @endif
                        <h6><span class="orange">Articulos :</span> {{ $preventa->count() }}</h6>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button {!! $banderaMultiPago > 0 ? 'disabled' : '' !!} class="btnOpcion" data-bs-toggle="modal"
                        data-bs-target="#ModalEmpleado">
                        <i class="fa fa-user"></i> EMPLEADO
                    </button>
                    <button id="btnSolicitudFactura" class="btnOpcion">
                        <i class="fa fa-id-badge"></i> FACTURA
                    </button>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button id="btnReimprimirUltimo" class="btnOpcion">
                        <i class="fa fa-print" aria-hidden="true"></i> REIMPRIMIR
                    </button>
                    <button id="btnPedidos" class="position-relative shadow btnOpcion">
                        <i class="fa fa-cart-plus"></i> PEDIDOS
                        @if ($pedidosPendientes > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $pedidosPendientes }}
                            </span>
                        @endif
                    </button>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <form id="frmPaquetes" action="/PaquetesPreventa" method="GET" style="width: 50%">
                        <select class="btnOpcion" name="idPaquete" id="idPaquete" style="width: 100%;" required>
                            <option value="">PAQUETES
                            </option>
                            @foreach ($paquetes as $paquete)
                                <option style="color: black; background-color: white;"
                                    value="{{ $paquete->IdPaquete }}">{{ $paquete->NomPaquete }}</option>
                            @endforeach
                        </select>
                    </form>
                    <button class="btnOpcion" data-bs-toggle="modal" data-bs-target="#ModalOpciones">
                        <i class="fa fa-bars"></i> OPCIONES
                    </button>
                </div>
                <div class="content-table content-table-full card p-4 mt-2 border DetalleMultiPagoPos"
                    style="display: {!! $banderaMultiPago > 0 ? 'block' : 'none' !!}">
                    <table style="font-size: 11px">
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start">Tipo Pago</th>
                                <th>Pagado</th>
                                <th>Por Pagar</th>
                                <th class="rounded-end">
                                    <i class="fa fa-trash-o"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datTipoPago as $pagoRestante)
                                <tr>
                                    <td style="font-size: 11px">{{ $pagoRestante->NomTipoPago }}</td>
                                    <td style="font-size: 11px">{{ number_format($pagoRestante->Pago, 2) }}</td>
                                    <td style="font-weight: bold; color: red; font-size: 11px;">
                                        {{ number_format($pagoRestante->Restante, 2) }}
                                    </td>
                                    <td>
                                        <form id="eliminarPago{{ $pagoRestante->IdDatTipoPago }}"
                                            action="EliminarPago/{{ $pagoRestante->IdDatTipoPago }}" method="POST">
                                            @csrf
                                            <i style="color: red; font-size: 11px" class="fa fa-trash"
                                                data-bs-toggle="mensaje" title="Eliminar Pago"
                                                onclick="event.preventDefault();
                                                document.getElementById('eliminarPago<?php echo $pagoRestante->IdDatTipoPago; ?>').submit();"></i>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <form id="formPos" class="mt-4" action="/CalculosPreventa">
                    <input type="hidden" name="nNominaEmpleado"
                        value="{{ empty($cliente->NumNomina) ? '' : $cliente->NumNomina }}">
                    <div class="row mb-2">
                        <div class="col-6">
                            <input {!! $banderaMultiPago > 0 ? 'disabled' : '' !!} class="form-control txtPos" type="number"
                                name="txtCantidad" id="txtCantidad" placeholder="Peso" min="0.001"
                                step="any" autocomplete="off" tabindex="1">
                        </div>
                        <div class="col-6 d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" value="1" id="recorte"
                                name="recorte" tabindex="3">
                            <label class="form-check-label txtPos text-muted" for="recorte">
                                Recorte
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <input {!! $banderaMultiPago > 0 ? 'disabled' : '' !!} type="text" name="txtCodigo" id="txtCodigo"
                                class="form-control txtPos" placeholder="Leer Código" autocomplete="off" required
                                autofocus maxlength="13" minlength="12" OnKeyPress="return teclas(event);"
                                tabindex="2">
                        </div>
                        <input hidden id="hacerSubmit" type="submit" />
                    </div>
                    @include('Posweb.ModalRostisado')
                    <button class="d-none" id="abrirModalRosticero" data-bs-toggle="modal"
                        data-bs-target="#ModalRostisado">Abrir Modal</button>
                </form>
            </div>

            {{-- <div id="pos" class="col-12 col-xl-9 border border-warning border-2"> --}}
            {{-- Tabla --}}
            <div id="pos" class="content-table card p-4 border"
                style="border-radius: 20px; width: 100%; height: calc(100vh - 48px - 256px); overflow-y: scroll;">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th>Iva</th>
                            <th class="rounded-end">
                                <i style="color: red; font-size: 18px; display: {!! ($preventa->count() == 0 ? 'none' : $banderaMultiPago > 0) ? 'none' : 'block' !!}"
                                    class="fa fa-trash" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarPreventa"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preventa as $pArticulo)
                            <tr style="{!! $pArticulo->IdPaquete != null ? 'background-color: #F1F5F9;' : '' !!}">
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
        </div>

        {{-- Footer --}}
        <div class="d-flex gap-4">
            <div id="subtotal"
                class="col-3 border flex-shrink-1 d-flex flex-column justify-content-center align-items-center card"
                style="border-radius: 20px;">
                <h6 class="text-dark">SUBTOTAL</h6>
                <h1 class="text-secondary m-0">${{ $subTotalPreventa }}</h1>
            </div>
            <div id="iva"
                class="col-3 border flex-shrink-1 d-flex flex-column justify-content-center align-items-center card"
                style="border-radius: 20px;">
                <h6 class="text-dark">IVA</h6>
                <h1 class="text-secondary m-0">${{ $ivaPreventa }}</h1>
            </div>
            <div id="total"
                class="col-3 border flex-shrink-1 d-flex flex-column justify-content-center align-items-center card"
                style="border-radius: 20px;">
                <h6 class="text-dark">TOTAL</h6>
                <h1 class="text-secondary m-0">${{ $totalPreventa }}</h1>
            </div>
            <div id="botones" class="col-3 card d-flex flex-row align-items-center border p-4 gap-4"
                style="border-radius: 20px;">
                <button id="btnConsultar" class="btnOpcion" data-bs-toggle="modal" data-bs-target="#ModalConsultar">
                    <i class="fa fa-search"></i> CONSULTAR
                </button>
                @if ($banArticuloSinPrecio->count() == 0)
                    <button id="btnPagar" class="btnPagar" data-bs-toggle="modal" data-bs-target="#ModalPagar">
                        <i class="fa fa-usd"></i> PAGAR
                    </button>
                @endif
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

        // document.getElementById('formPos').addEventListener('submit', (e) => {
        //     console.log('Andamos enviando');
        //     e.target.preventDefault();
        // })
    </script>
</body>

</html>
