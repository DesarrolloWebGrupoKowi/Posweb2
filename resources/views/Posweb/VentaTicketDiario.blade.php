@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Venta Por Ticket Diario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Venta Por Ticket Diario - ' . $tienda->NomTienda,
                ])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-end justify-content-end gap-2 pb-2" action="VentaTicketDiario">
                <div class="d-flex align-items-center gap-2">
                    <label for="fechaSolicitud" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="txtFecha"
                        id="txtFecha" value="{{ $fecha }}">
                </div>
                <div class="d-flex align-items-center gap-2">
                    {{-- <label for="fechaSolicitud" class="text-secondary" style="font-weight: 500">Buscar:</label> --}}
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="txtFolio"
                        id="txtFolio" value="{{ $txtFolio }}" placeholder="Folio..." autofocus>
                </div>
                <button type="submit" class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>
            <div class="content-table content-table-full" style="max-height: calc(65vh);">
                <table class="w-100">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Ticket</th>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Importe</th>
                            <th>Iva</th>
                            <th>Estatus</th>
                            <th class="rounded-end">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', ['items' => $tickets, 'colspan' => 9])
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->IdTicket }}</td>
                                <td>{{ $ticket->IdEncabezado }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                                <td>$ {{ number_format($ticket->ImporteVenta, 2) }}</td>
                                <td>{{ number_format($ticket->Iva, 2) }}</td>
                                <td>
                                    <span class="{{ $ticket->Subir == 1 ? 'tags-green' : 'tags-red' }}">
                                        @if ($ticket->Subir == 1)
                                            @include('components.icons.cloud-check')
                                        @else
                                            @include('components.icons.cloud-slash')
                                        @endif
                                    </span>

                                    @if ($ticket->StatusVenta == 1)
                                        <span class="me-2 tags-red" title="Ticket cancelado"> Cancelado </span>
                                    @elseif ($ticket->SolicitudCancelacionTicket)
                                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada != 1)
                                            <span class="me-2 tags-blue" title="Ticket en proceso de cancelación">
                                                Solicitud de cancelación
                                            </span>
                                        @else
                                            <span class="me-2 tags-yellow"
                                                title="La solicitud de cancelación fue rechazada">
                                                Cancelación rechazada
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if (Carbon\Carbon::parse($ticket->FechaVenta)->format('d/m/Y') == Carbon\Carbon::parse(now())->format('d/m/Y'))
                                            <form class="d-inline-flex" action="/ImprimirTicket">
                                                <input type="hidden" name="txtFecha" id="txtFecha"
                                                    value="{{ $ticket->FechaVenta }}" required>
                                                <input type="hidden" id="txtIdTicket" name="txtIdTicket"
                                                    placeholder="Ticket" size="4" value="{{ $ticket->IdTicket }}"
                                                    required>
                                                <button class="btn-table" title="Reimprimir">
                                                    @include('components.icons.print')
                                                </button>
                                            </form>
                                        @endif
                                        {{-- <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}" title="Detalle">
                                            @include('components.icons.list')
                                        </button>
                                        <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalTipoPago{{ $ticket->IdTicket }}" title="Tipo pago">
                                            @include('components.icons.dolar')
                                        </button> --}}
                                        {{-- @if (Carbon\Carbon::parse($ticket->FechaVenta)->format('d/m/Y') == Carbon\Carbon::parse(now())->format('d/m/Y')) --}}
                                        <form class="d-inline-flex" action="/SolicitudCancelacionTicket" method="GET">
                                            <input name="idTicket" value="{{ $ticket->IdEncabezado }}" type="hidden">

                                            <button class="btn-table" title="Detalle ticket">
                                                @include('components.icons.list')
                                            </button>
                                        </form>
                                        {{-- @endif --}}
                                        @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                            <button class="btn-table" data-bs-toggle="modal"
                                                data-bs-target="#ModalSolicitudFe{{ $ticket->IdTicket }}"
                                                title="Solicitud factura">
                                                @include('components.icons.check')
                                            </button>
                                            @include('Posweb.ModalSolicitudFe')
                                        @endif
                                    </div>
                                    @include('Posweb.ModalDetalleTicket')
                                    @include('Posweb.ModalTipoPago')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if ($tickets->count() > 0)
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="text-align: right; font-weight: bold;">Totales: </td>
                                <td style="font-weight: bold;">${{ number_format($total, 2) }}</td>
                                <td style="font-weight: bold;">${{ number_format($totalIva, 2) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <script>
        const txtFecha = document.getElementById('txtFecha');
        const formVentaTicketDiario = document.getElementById('formVentaTicketDiario');
        txtFecha.addEventListener('change', function() {
            formVentaTicketDiario.submit();
        });
    </script>
@endsection
