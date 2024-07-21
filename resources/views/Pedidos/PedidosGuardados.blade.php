@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Pedidos Guardados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Pedidos Guardados - ' . $tienda->NomTienda])
                <div class="d-flex gap-2">
                    <a href="/Pedidos" class="btn btn-sm btn-dark">
                        @include('components.icons.plus-circle') Realizar Pedido
                    </a>
                    <a href="/HistorialGuardados" class="btn btn-sm btn-dark">
                        Historial Pedidos @include('components.icons.text-file')
                    </a>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/PedidosGuardados">
                <div class="d-flex align-items-center gap-2">
                    <label for="codArticulo" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <select id="selectBusqueda" name="selectBusqueda" class="form-select rounded" style="line-height: 18px">
                        <option selected value="0">Seleccione</option>
                        <option {!! $selectBusqueda == 1 ? 'selected' : '' !!} value="1">Buscar por Cliente</option>
                        <option {!! $selectBusqueda == 2 ? 'selected' : '' !!} value="2">Buscar por Fecha</option>
                    </select>
                </div>
                <div id="divCliente" style="display: {!! $selectBusqueda == 1 ? 'block' : 'none' !!}">
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" class="form-control rounded" style="line-height: 18px" id="txtCliente"
                            name="txtCliente" value="{{ $txtCliente }}" placeholder="Nombre de Cliente">
                    </div>
                </div>
                <div class="col-2" id="divFechaPedido" style="display: {!! $selectBusqueda == 2 ? 'block' : 'none' !!}">
                    <div class="d-flex align-items-center gap-2">
                        <input type="date" class="form-control rounded" style="line-height: 18px" id="FechaPedido"
                            name="FechaPedido" value="{!! $fechaPedido == '' ? date('Y-m-d') : $fechaPedido !!}">
                    </div>
                </div>
                <div id="divBuscar" style="display: {!! $selectBusqueda != 0 ? 'block' : 'none' !!}">
                    <button id="btnBuscar" class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Folio</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Importe</th>
                        <th>Fecha Pedido</th>
                        <th>Fecha a Recoger</th>
                        <th>status</th>
                        <th class="rounded-end text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $pedidos, 'colspan' => 8])
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->IdPedido }}</td>
                            <td>{{ $pedido->Cliente }}</td>
                            <td>{{ $pedido->Telefono }}</td>
                            <td>${{ number_format($pedido->ImportePedido, 2) }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($pedido->FechaPedido)) }}</td>
                            <td>{{ strftime('%d %B %Y', strtotime($pedido->FechaRecoger)) }}</td>
                            <td>
                                @if ($pedido->Status == 0)
                                    <span class="tags-yellow">Pendiente</span>
                                @elseif($pedido->Status == 2)
                                    <span data-bs-toggle="modal" data-bs-target="#ModalDatEncabezado{{ $pedido->IdPedido }}"
                                        class="tags-green" style="cursor: pointer;">Enviado</span>
                                    @include('Pedidos.ModalEncabezado')
                                @else
                                    <span class="tags-red">Cancelado</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalle{{ $pedido->IdPedido }}">
                                        @include('components.icons.list')
                                    </button>
                                    @include('Pedidos.ModalDetalle')
                                    @php
                                        $inicioFecha = new DateTime($pedido->FechaPedido);
                                        $hoy = new DateTime();
                                        $dias = $inicioFecha->diff($hoy)->days;
                                    @endphp
                                    @if ($pedido->Status == 0 and $dias < 1)
                                        <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalEnviarAPreventa{{ $pedido->IdPedido }}">
                                            @include('components.icons.cart')
                                        </button>
                                        @include('Pedidos.ModalEnviarAPreventa')

                                        <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalCancelar{{ $pedido->IdPedido }}">
                                            @include('components.icons.delete')
                                        </button>
                                        @include('Pedidos.ModalCancelarPedido')
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const selectBusqueda = document.getElementById('selectBusqueda');
        const divCliente = document.getElementById('divCliente');
        const divFechaPedido = document.getElementById('divFechaPedido');
        const divBuscar = document.getElementById('divBuscar');
        const FechaPedido = document.getElementById('FechaPedido');
        const txtCliente = document.getElementById('txtCliente');

        selectBusqueda.addEventListener('change', function() {
            selectBusqueda.value == 1 ? (
                    divCliente.style.display = 'block',
                    divFechaPedido.style.display = 'none',
                    divBuscar.style.display = 'block',
                    txtCliente.disabled = false,
                    FechaPedido.disabled = true
                ) : selectBusqueda.value == 2 ? (
                    divCliente.style.display = 'none',
                    divFechaPedido.style.display = 'block',
                    divBuscar.style.display = 'block',
                    FechaPedido.disabled = false,
                    txtCliente.disabled = true
                ) :
                (
                    divCliente.style.display = 'none',
                    divFechaPedido.style.display = 'none',
                    divBuscar.style.display = 'none'
                );
        })
    </script>
@endsection
