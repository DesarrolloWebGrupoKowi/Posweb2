@extends('plantillaBase.masterblade')
@section('title', 'Pedidos Guardados')
<style>
    i {
        cursor: pointer;
    }
    .hiddenCell{
        visibility: hidden;
        border: 0;
    }
</style>
@section('contenido')
    <div class="container titulo card shadow mb-3">
        <h2>Pedidos Guardados - {{ $tienda->NomTienda }}</h2>
    </div>
    <div>
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form action="/PedidosGuardados">
            <div class="row">
                <div class="col-3">
                    <select id="selectBusqueda" name="selectBusqueda" class="form-select">
                        <option selected value="0">Seleccione</option>
                        <option {!! $selectBusqueda == 1 ? 'selected' : '' !!} value="1">Buscar por Cliente</option>
                        <option {!! $selectBusqueda == 2 ? 'selected' : '' !!} value="2">Buscar por Fecha</option>
                    </select>
                </div>
                <div id="divCliente" class="col-3" style="display: {!! $selectBusqueda == 1 ? 'block' : 'none' !!}">
                    <input type="text" class="form-control" id="txtCliente" name="txtCliente" value="{{ $txtCliente }}" placeholder="Nombre de Cliente">
                </div>
                <div class="col-2" id="divFechaPedido" style="display: {!! $selectBusqueda == 2 ? 'block' : 'none' !!}">
                    <input type="date" class="form-control" id="FechaPedido" name="FechaPedido" value="{!! $fechaPedido == '' ? date('Y-m-d') : $fechaPedido !!}">
                </div>
                <div id="divBuscar" class="col-2" style="display: {!! $selectBusqueda != 0 ? 'block' : 'none' !!}">
                    <button id="btnBuscar" class="btn">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                <div class="col-2 offset-2">
                    <a href="/PedidosGuardados" class="btn">
                        <span class="material-icons">visibility</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <table class="table table-responsive table-striped table-sm">
            <thead class="table-dark">
                <tr>
                    <th>Id de Pedido</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Importe</th>
                    <th>Fecha Pedido</th>
                    <th>Fecha a Recoger</th>
                    <th>Acciones</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @if($pedidos->isEmpty())
                    <td colspan="8">No Hay Pedidos!</td>
                @else
                @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->IdPedido }}</td>
                    <td>{{ $pedido->Cliente }}</td>
                    <td>{{ $pedido->Telefono }}</td>
                    <td>${{ number_format($pedido->ImportePedido, 2) }}</td>
                    <td>{{ strftime("%d %B %Y, %H:%M", strtotime($pedido->FechaPedido)) }}</td>
                    <td>{{ strftime("%d %B %Y", strtotime($pedido->FechaRecoger)) }}</td>
                    <td>
                        <i class="material-icons" data-bs-toggle="modal" data-bs-target="#ModalDetalle{{ $pedido->IdPedido }}">info</i>
                        @include('Pedidos.ModalDetalle')
                        @php
                            $inicioFecha = new DateTime($pedido->FechaPedido);
                            $hoy = new DateTime();
                            $dias = $inicioFecha->diff($hoy)->days;
                        @endphp 
                        @if($pedido->Status == 0 and $dias < 1)
                            <i class="material-icons" style="color: green" data-bs-toggle="modal" data-bs-target="#ModalEnviarAPreventa{{ $pedido->IdPedido }}">add_shopping_cart</i>
                            @include('Pedidos.ModalEnviarAPreventa')
                            <i class="material-icons eliminar" data-bs-toggle="modal" data-bs-target="#ModalCancelar{{ $pedido->IdPedido }}">delete_forever</i>
                            @include('Pedidos.ModalCancelarPedido')
                        @endif
                    </td>
                    <td>
                        @if($pedido->Status == 0)
                            <i style="color: black;" class="material-icons">pending</i>
                        @elseif($pedido->Status == 2)
                            <i data-bs-toggle="modal" data-bs-target="#ModalDatEncabezado{{ $pedido->IdPedido }}" style="color: green;" class="material-icons">done_all</i>
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
    <script>
        const selectBusqueda = document.getElementById('selectBusqueda');
        const divCliente = document.getElementById('divCliente');
        const divFechaPedido = document.getElementById('divFechaPedido');
        const divBuscar = document.getElementById('divBuscar');
        const FechaPedido = document.getElementById('FechaPedido');
        const txtCliente = document.getElementById('txtCliente');

        selectBusqueda.addEventListener('change', function(){
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