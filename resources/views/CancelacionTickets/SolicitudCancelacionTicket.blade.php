@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud de Cancelación de Ticket')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row align-content-center">
                @include('components.title', [
                    'titulo' => 'Solicitud de Cancelación de Ticket',
                    'options' => [['name' => 'Venta por ticket diario', 'value' => '/VentaTicketDiario']],
                ])
                <div class="d-flex gap-2">
                    <a href="/ReporteSolicitudCancelacion" class="btn btn-dark">
                        Historial Cancelaciones @include('components.icons.text-file')
                    </a>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table d-flex">
            <div class="mx-auto">
                {{-- @if (!$idTicket)
                    <form class="card border-0 p-4" style="border-radius: 10px" action="/SolicitudCancelacionTicket"
                        method="GET">
                        <div class="d-flex flex-column gap-2">
                            <label class="text-secondary d-inline text-center"
                                style="font-weight: 500; white-space: nowrap;" for="idTicket">
                                No Ticket</label>
                            <input type="number" class="form-control rounded" style="line-height: 18px" id="idTicket"
                                name="idTicket" value="{{ $idTicket }}" autofocus required>
                        </div>
                        <button class="mt-2 btn btn-sm btn-warning">
                            Buscar
                        </button>
                    </form>
                @endif --}}


                <div class="d-flex flex-row gap-4" style="border-radius: 10px">
                    <form class="card border-0 p-4" action="/SolicitudCancelacionTicket" method="GET">
                        <div class="d-flex flex-column">
                            <label class="text-secondary" style="font-weight: 500; white-space: nowrap;" for="idTicket">
                                No Ticket</label>
                            <input type="number" class="form-control rounded" style="line-height: 18px" id="idTicket"
                                name="idTicket" value="{{ $idTicket }}" autofocus required>
                        </div>
                        <button class="mt-2 btn btn-sm btn-warning">
                            {{-- @include('components.icons.search') --}}Buscar
                        </button>
                    </form>
                    @if (!$ticket && $idTicket)
                        <div class="card border-0 p-4 justify-content-center">
                            <div>
                                <span class="tags-red" style="flex: 1">
                                    No se encuentra el ticket solicitado.
                                </span>
                            </div>
                        </div>
                    @endif
                    @isset($ticket->TipoPago)
                        <form class="card border-0 p-4 justify-content-center"
                            action="/SolicitarCancelacion/{{ $ticket->IdEncabezado }}" method="POST" style="flex: 1">
                            @if (!empty($idTicket) && $ticketConSolicitud == 'si')
                                <div class="d-flex gap-5">
                                    {{-- @if ($ticket->StatusVenta == 1)
                                        <span class="tags-red" style="flex: 1">
                                            Ticket con solicitud de cancelacion aprobada
                                        </span>
                                    @endif --}}
                                    @if ($ticket->SolicitudCancelacionTicket)
                                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada == 1)
                                            <span class="tags-yellow" style="flex: 1">
                                                Solicitud de cancelacion rechazada
                                            </span>
                                        @elseif ($ticket->StatusVenta == 1)
                                            <span class="tags-red" style="flex: 1">
                                                Ticket con solicitud de cancelacion aprobada
                                            </span>
                                        @else
                                            <span class="tags-blue" style="flex: 1">
                                                El ticket ya cuenta con solicitud de cancelación
                                            </span>
                                        @endif
                                    @endif
                                    @if ($ticket->Subir == 1)
                                        <span class="tags-green">
                                            @include('components.icons.cloud-slash')
                                        </span>
                                    @else
                                        <span class="tags-red">
                                            @include('components.icons.cloud-slash')
                                        </span>
                                    @endif
                                </div>
                            @else
                                @csrf
                                <div class="d-flex flex-wrap gap-2 gap-md-3">
                                    <div style="flex: 1; width: 25%; min-width: 290px;">
                                        <label class="text-secondary" style="font-weight:500">Motivo de cancelación</label>
                                        <input class="form-control rounded" style="line-height: 18px" type="text"
                                            name="motivoCancelacion" placeholder="Motivo de cancelación" required
                                            value="{{ old('motivoCancelacion') }}" autofocus>
                                    </div>
                                </div>
                                <div class="d-flex mt-2">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        @include('components.icons.block') Solicitar cancelación
                                    </button>
                                </div>
                                {{-- @include('CancelacionTickets.ModalConfirmarSolicitudCancelacion')
                            <form action="/SolicitarCancelacion/{{ $ticket->IdEncabezado }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-auto">
                                            <textarea class="form-control" name="motivoCancelacion" id="motivoCancelacion" cols="60" rows="5"
                                                placeholder="Motivo de Cancelación" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                                        <i class="fa fa-close"></i> Cerrar
                                    </button>
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa fa-ban"></i> Solicitar cancelación
                                    </button>
                                </div>
                            </form> --}}
                            @endif
                        </form>
                    @endisset
                </div>
                @isset($ticket->TipoPago)
                    <h2 class="mt-4 mb-2">Detalle de venta</h2>
                    <div class="card border-0 p-4" style="border-radius: 10px">
                        <table class="">
                            <thead class="table-head-secondary">
                                <tr>
                                    <th>Código</th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Paquete</th>
                                    <th>Pedido</th>
                                    <th>Rostisado</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: smaller">
                                @isset($ticket->detalle)
                                    @foreach ($ticket->detalle as $detalle)
                                        <tr>
                                            <td>{{ $detalle->CodArticulo }}</td>
                                            <td>{{ $detalle->NomArticulo }}</td>
                                            <td>{{ number_format($detalle->CantArticulo, 3) }}</td>
                                            <td>{{ number_format($detalle->PrecioArticulo, 2) }}</td>
                                            <td>{{ number_format($detalle->IvaArticulo, 2) }}</td>
                                            <td>{{ number_format($detalle->ImporteArticulo, 2) }}</td>
                                            <td>{{ $detalle->NomPaquete }}</td>
                                            <td>{{ $detalle->Cliente }}</td>
                                            <td>{{ $detalle->IdRosticero }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @include('components.table-empty', [
                                        'items' => [],
                                        'colspan' => 9,
                                    ])
                                @endisset
                            </tbody>
                        </table>
                    </div>

                    <h2 class="mt-4 mb-2">Pagos</h2>
                    <div class="card border-0 p-4" style="border-radius: 10px">
                        <table>
                            <thead class="table-head-secondary">
                                <th>Tipo de Pago</th>
                                <th class="text-center">Pago</th>
                                <th class="text-center">Por Pagar</th>
                            </thead>
                            <tbody style="font-size: smaller">
                                @isset($ticket->TipoPago)
                                    @foreach ($ticket->TipoPago as $tipoPago)
                                        <tr>
                                            <td>{{ $tipoPago->NomTipoPago }}</td>
                                            <td class="text-end">${{ number_format($tipoPago->PivotPago->Pago, 2) }}</td>
                                            <td class="text-end">
                                                @if ($tipoPago->PivotPago->Restante < 0)
                                                    {{ number_format($tipoPago->PivotPago->Restante, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach ($ticket->TipoPago as $tipoPago)
                                        @if ($tipoPago->PivotPago->Restante >= 0)
                                            <tr>
                                                <td></td>
                                                <td class="text-end">Total: ${{ number_format($ticket->ImporteVenta, 2) }}</t>
                                                <td class="text-end">Cambio:
                                                    ${{ number_format($tipoPago->PivotPago->Restante, 2) }}</t>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    @include('components.table-empty', [
                                        'items' => [],
                                        'colspan' => 3,
                                    ])
                                @endisset
                            </tbody>
                        </table>
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endsection
