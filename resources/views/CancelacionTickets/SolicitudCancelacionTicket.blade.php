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
                    @isset($ticket)
                        @if (
                            \Carbon\Carbon::today()->toDateString() == \Carbon\Carbon::parse($ticket->FechaVenta)->toDateString() &&
                                empty($ticket->SolicitudCancelacionTicket))
                            <button data-bs-toggle="modal" data-bs-target="#ModalCancelarTicket" class="btn btn-danger"
                                style="border-radius: 50rem; display: flex; align-items: center;">
                                Cancelar ticket @include('components.icons.delete')
                            </button>
                            {{-- <button class="btn-table" data-bs-toggle="modal" data-bs-target="#ModalCancelarTicket"
                            title="Solicitud factura">
                            Cancelar ticket @include('components.icons.delete')
                        </button> --}}
                            @include('CancelacionTickets.ModalCancelarTicket')
                        @endif
                    @endisset
                    <a href="/ReporteSolicitudCancelacion" class="btn btn-dark">
                        Historial Cancelaciones @include('components.icons.text-file')
                    </a>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table">
            <div class="card border-0 p-4" style="border-radius: 10px">
                @if (empty($ticket->TipoPago))
                    <div class="d-flex justify-content-center">
                        <form class="d-flex align-items-start gap-2" action="/SolicitudCancelacionTicket" method="GET">
                            <div>
                                <input type="number" class="form-control rounded" style="line-height: 18px" id="idTicket"
                                    name="idTicket" value="{{ $idTicket }}" autofocus placeholder="Buscar folio/ticket">
                            </div>
                            <button class="btn btn-sm btn-warning">Buscar </button>
                        </form>
                    </div>
                @endif
                @isset($ticket->TipoPago)
                    <div class="d-flex">
                        <div style="flex: 1">
                            <p class="fw-500 mb-1">
                                <b style="color: #333;">Folio:</b> <span
                                    style="color: #4f5464">{{ $ticket->IdEncabezado }}</span>
                            </p>
                            <p class="fw-500 mb-1">
                                <b style="color: #333;">Fecha:</b> <span style="color: #4f5464">
                                    {{ \Carbon\Carbon::parse($ticket->FechaVenta)->locale('es')->isoFormat('D MMMM YYYY, HH:mm') }}
                                </span>
                            </p>
                            <p class="fw-500 mb-1">
                                <b style="color: #333;">No Ticket:</b> <span style="color: #4f5464">
                                    {{ $ticket->IdTicket }}</span>
                            </p>
                            <p class="fw-500 mb-1">
                                <b style="color: #333;">Artículos:</b> <span
                                    style="color: #4f5464">{{ count($ticket->detalle) }}</span>
                            </p>
                            <p class="fw-500 mb-1">
                                <b style="color: #333;">Caja:</b> <span style="color: #4f5464">{{ $ticket->IdDatCaja }}</span>
                            </p>
                            <p class="fw-500 mb-1">
                                <b style="color: #333;">Cajero:</b>
                                <span style="color: #4f5464">{{ trim($usuario) ? $usuario : 'Usuario no registrado' }}</span>
                            </p>

                            @if (!empty($ticket->NumNomina))
                                {{-- <hr> --}}
                                @if (!empty($empleado))
                                    <p class="fw-500 mb-1">
                                        <b style="color: #333;">Empleado</b>
                                        <span style="color: #4f5464;">
                                            {{ $empleado->NumNomina . ' ' . $empleado->Nombre . ' ' . $empleado->Apellidos }}
                                        </span>
                                    </p>
                                @endif
                                @if (!empty($frecuenteSocio))
                                    <p class="fw-500 mb-1">
                                        <b style="color: #333;">Socio</b>
                                        <span style="color: #4f5464;">
                                            {{ $frecuenteSocio->FolioViejo . ' ' . $frecuenteSocio->Nombre }}
                                        </span>
                                    </p>
                                @endif
                                {{-- <hr> --}}
                            @endif
                        </div>
                        <form class="d-flex align-items-start gap-2" action="/SolicitudCancelacionTicket" method="GET">
                            <div>
                                {{-- <label class="text-secondary" style="font-weight: 500; white-space: nowrap;" for="idTicket">
                                    No Ticket</label> --}}
                                <input type="number" class="form-control rounded" style="line-height: 18px" id="idTicket"
                                    name="idTicket" value="{{ $idTicket }}" autofocus required placeholder="Buscar folio">
                            </div>
                            <button class="btn btn-sm btn-warning">Buscar </button>
                        </form>
                    </div>
                    @isset($ticket->SolicitudCancelacionTicket)
                        <p style="text-align: center">
                            <span class="tag tags-blue mb-1">Solicitud de cancelacion:
                                <b> {{ ' ' . $ticket->SolicitudCancelacionTicket->SolicitudCancelacion }}</b>
                            </span>
                            <br>
                            @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada == null)
                                <span class="tag tags-yellow"> <b>Pendiente</b> </span>
                            @endif
                            @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada == '0')
                                <span class="tag tags-green"> <b>Aprovada</b> </span>
                            @endif
                            @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada == 1)
                                <span class="tag tags-red"> <b>Denegada</b> </span>
                            @endif
                        </p>
                    @endisset
                @endisset

                @isset($ticket->TipoPago)
                    <div class="d-flex justify-content-between pt-2">
                        <h2 class="mt-1 mb-2" style="font-size: 1rem">Detalle de venta</h2>
                        <span class="{{ $ticket->Subir == 1 ? 'tags-green' : 'tags-red' }}">
                            @if ($ticket->StatusVenta == 1)
                                @include('components.icons.cloud-slash')
                            @else
                                @include('components.icons.cloud-slash')
                            @endif
                        </span>
                    </div>
                    <table class="">
                        <thead class="table-head-secondary text-white bg-secondary">
                            <tr>
                                <th>Código</th>
                                <th style="width: 40%;">Articulo</th>
                                <th>Cantidad</th>
                                <th style="text-align: center">Precio</th>
                                <th style="text-align: center">Iva</th>
                                <th style="text-align: center">Importe</th>
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
                                        <td style="text-align: right">${{ number_format($detalle->PrecioArticulo, 2) }}</td>
                                        <td style="text-align: right">${{ number_format($detalle->IvaArticulo, 2) }}</td>
                                        <td style="text-align: right">${{ number_format($detalle->ImporteArticulo, 2) }}</td>
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

                    <h2 class="mt-4 mb-2" style="font-size: 1rem">Pagos</h2>
                    <table>
                        <thead class="table-head-secondary bg-secondary text-white">
                            <th style="width: 70%;">Tipo de Pago</th>
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
                            @else
                                @include('components.table-empty', [
                                    'items' => [],
                                    'colspan' => 3,
                                ])
                            @endisset
                        </tbody>
                    </table>


                    <div class="text-end">
                        <p class="fw-500 mb-1">
                            <b style="color: #333;">SubTotal:</b>
                            $<span style="display: inline-block; color: #4f5464; width: 100px">
                                {{ number_format($ticket->SubTotal, 2) }}
                            </span>
                        </p>
                        <p class="fw-500 mb-1">
                            <b style="color: #333;">IVA:</b>
                            $<span style="display: inline-block; color: #4f5464; width: 100px">
                                {{ number_format($ticket->IVA, 2) }}
                            </span>
                        </p>
                        <p class="fw-500 mb-1">
                            <b style="color: #333;">Cambio:</b>
                            $<span style="display: inline-block; color: #4f5464; width: 100px">
                                {{ number_format($cambio->Restante, 2) }}
                            </span>
                        </p>
                        <p class="fw-500 mb-1">
                            <b style="color: #333;">Total:</b>
                            $<span style="display: inline-block; color: #4f5464; width: 100px">
                                {{ number_format($ticket->ImporteVenta, 2) }}
                            </span>
                        </p>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Gracias por tu compra. ¡Vuelve pronto!</p>
                    </div>

                @endisset
            </div>

        </div>
    </div>
@endsection
