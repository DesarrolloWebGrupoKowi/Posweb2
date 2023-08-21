@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Pedidos Guardados')
@section('dashboardWidth', 'width-95')
<style>
    i {
        cursor: pointer;
    }

    .hiddenCell {
        visibility: hidden;
        border: 0;
    }
</style>
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Pedidos Guardados - ' . $tienda->NomTienda])
            <div>
                <a href="/PedidosGuardados" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                    {{-- <span class="material-icons">visibility</span> --}}
                </a>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <form class="d-flex align-items-center justify-content-end pb-2 gap-2" action="/PedidosGuardados">
            <div class="input-group" style="max-width: 300px">
                <select id="selectBusqueda" name="selectBusqueda" class="form-select">
                    <option selected value="0">Seleccione</option>
                    <option {!! $selectBusqueda == 1 ? 'selected' : '' !!} value="1">Buscar por Cliente</option>
                    <option {!! $selectBusqueda == 2 ? 'selected' : '' !!} value="2">Buscar por Fecha</option>
                </select>
            </div>
            <div id="divCliente" style="display: {!! $selectBusqueda == 1 ? 'block' : 'none' !!}">
                <div class="input-group" style="max-width: 300px">
                    <input type="text" class="form-control" id="txtCliente" name="txtCliente" value="{{ $txtCliente }}"
                        placeholder="Nombre de Cliente">
                </div>
            </div>
            <div class="col-2" id="divFechaPedido" style="display: {!! $selectBusqueda == 2 ? 'block' : 'none' !!}">
                <div class="input-group" style="max-width: 300px">
                    <input type="date" class="form-control" id="FechaPedido" name="FechaPedido"
                        value="{!! $fechaPedido == '' ? date('Y-m-d') : $fechaPedido !!}">
                </div>
            </div>
            <div id="divBuscar" style="display: {!! $selectBusqueda != 0 ? 'block' : 'none' !!}">
                <button id="btnBuscar" class="btn btn-dark-outline">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id de Pedido</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Importe</th>
                        <th>Fecha Pedido</th>
                        <th>Fecha a Recoger</th>
                        <th>Acciones</th>
                        <th class="rounded-end">Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($pedidos->isEmpty())
                        <td colspan="8">No Hay Pedidos!</td>
                    @else
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->IdPedido }}</td>
                                <td>{{ $pedido->Cliente }}</td>
                                <td>{{ $pedido->Telefono }}</td>
                                <td>${{ number_format($pedido->ImportePedido, 2) }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($pedido->FechaPedido)) }}</td>
                                <td>{{ strftime('%d %B %Y', strtotime($pedido->FechaRecoger)) }}</td>
                                <td>
                                    <i class="material-icons" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalle{{ $pedido->IdPedido }}">info</i>
                                    @include('Pedidos.ModalDetalle')
                                    @php
                                        $inicioFecha = new DateTime($pedido->FechaPedido);
                                        $hoy = new DateTime();
                                        $dias = $inicioFecha->diff($hoy)->days;
                                    @endphp
                                    @if ($pedido->Status == 0 and $dias < 1)
                                        <i class="material-icons" style="color: green" data-bs-toggle="modal"
                                            data-bs-target="#ModalEnviarAPreventa{{ $pedido->IdPedido }}">add_shopping_cart</i>
                                        @include('Pedidos.ModalEnviarAPreventa')
                                        <i class="material-icons eliminar" data-bs-toggle="modal"
                                            data-bs-target="#ModalCancelar{{ $pedido->IdPedido }}">delete_forever</i>
                                        @include('Pedidos.ModalCancelarPedido')
                                    @endif
                                </td>
                                <td>
                                    @if ($pedido->Status == 0)
                                        <i style="color: black;" class="material-icons">pending</i>
                                    @elseif($pedido->Status == 2)
                                        <i data-bs-toggle="modal"
                                            data-bs-target="#ModalDatEncabezado{{ $pedido->IdPedido }}"
                                            style="color: green;" class="material-icons">done_all</i>
                                        @include('Pedidos.ModalEncabezado')
                                    @else
                                        <i style="color: red;" class="material-icons">cancel</i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
