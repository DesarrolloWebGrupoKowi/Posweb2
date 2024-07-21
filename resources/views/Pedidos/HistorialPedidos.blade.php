@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial Pedidos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Historial Pedidos',
                    'options' => [['name' => 'Pedidos guardados', 'value' => '/PedidosGuardados']],
                ])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                @include('components.number-paginate')
                <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/HistorialGuardados">
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" class="form-control rounded" style="line-height: 18px" id="txtCliente"
                            name="txtCliente" value="{{ $txtCliente }}" placeholder="Nombre de Cliente" autofocus>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="date" class="form-control rounded" style="line-height: 18px" id="FechaPedido"
                            name="FechaPedido" value="{!! $fechaPedido !!}">
                    </div>
                    <button id="btnBuscar" class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </form>
            </div>
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
            @include('components.paginate', ['items' => $pedidos])
        </div>
    </div>
@endsection
