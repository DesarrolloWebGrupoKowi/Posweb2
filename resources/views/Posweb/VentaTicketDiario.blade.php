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

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-end justify-content-end gap-2 pb-2" action="VentaTicketDiario">
                <div class="d-flex align-items-center gap-2">
                    <label for="fechaSolicitud" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="txtFecha"
                        id="txtFecha" value="{{ $fecha }}" required autofocus>
                </div>
                <button class="d-none input-group-text"><span class="material-icons">search</span></button>
            </form>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Ticket</th>
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
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                            <td>$ {{ number_format($ticket->ImporteVenta, 2) }}</td>
                            <td>{{ number_format($ticket->Iva, 2) }}</td>
                            <td>
                                @if ($ticket->StatusVenta == 1)
                                    <span class="me-2 {{ $ticket->StatusVenta == 1 ? 'tags-red' : 'tags-green' }}">
                                        {{ $ticket->StatusVenta == 1 ? 'Cancelada' : 'Realizada' }}
                                    </span>
                                @endif

                                <span class="{{ $ticket->Subir == 1 ? 'tags-green' : 'tags-red' }}">
                                    {{ $ticket->Subir == 1 ? 'Online' : 'Offline' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if (Carbon\Carbon::parse($ticket->FechaVenta)->format('d/m/Y') == Carbon\Carbon::parse(now())->format('d/m/Y'))
                                        <div class="d-flex">
                                            <form action="/ImprimirTicket">
                                                <input class="form-control" type="hidden" name="txtFecha" id="txtFecha"
                                                    value="{{ $ticket->FechaVenta }}" required>
                                                <input style="text-align: center" class="form-control" type="hidden"
                                                    id="txtIdTicket" name="txtIdTicket" placeholder="Ticket" size="4"
                                                    value="{{ $ticket->IdTicket }}" required>
                                                <div>
                                                    <button class="btn-table" title="Reimprimir">
                                                        @include('components.icons.print')
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}" title="Detalle">
                                        @include('components.icons.list')
                                    </button>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalTipoPago{{ $ticket->IdTicket }}" title="Tipo pago">
                                        @include('components.icons.dolar')
                                    </button>
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

    <script>
        const txtFecha = document.getElementById('txtFecha');
        const formVentaTicketDiario = document.getElementById('formVentaTicketDiario');
        txtFecha.addEventListener('change', function() {
            formVentaTicketDiario.submit();
        });
    </script>
@endsection
