@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Venta Por Ticket Diario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Venta Por Ticket Diario - ' . $tienda->NomTienda])
            <form class="d-flex align-items-end" id="formVentaTicketDiario" action="VentaTicketDiario">
                <div class="input-group" style="min-width: 200px">
                    <input type="date" class="form-control" name="txtFecha" id="txtFecha" value="{{ $fecha }}"
                        required>
                </div>
            </form>
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Ticket</th>
                        <th>Fecha</th>
                        <th>Importe</th>
                        <th>Iva</th>
                        <th>Detalle</th>
                        <th>Solicitud Factura</th>
                        <th>Status Venta</th>
                        <th class="rounded-end">En linea</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tickets->count() == 0)
                        <tr>
                            <td colspan="7">No Hay Ventas</td>
                        </tr>
                    @endif
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->IdTicket }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                            <td>$ {{ number_format($ticket->ImporteVenta, 2) }}</td>
                            <td>{{ number_format($ticket->Iva, 2) }}</td>
                            <td>
                                <i style="color: rgb(255, 145, 0); cursor: pointer; font-size: 20px"
                                    class="fa fa-info-circle" data-bs-toggle="modal"
                                    data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}"></i>
                                @include('Posweb.ModalDetalleTicket')
                                <i style="color: green; cursor: pointer; font-size: 20px" class="fa fa-usd"
                                    data-bs-toggle="modal" data-bs-target="#ModalTipoPago{{ $ticket->IdTicket }}"></i>
                                @include('Posweb.ModalTipoPago')
                            </td>
                            <td>
                                @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                    <i style="font-size: 18px; cursor: pointer" class="fa fa-check-square"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalSolicitudFe{{ $ticket->IdTicket }}"></i>
                                    @include('Posweb.ModalSolicitudFe')
                                @endif
                            </td>
                            <td style="color: red;">
                                @if ($ticket->StatusVenta == 1)
                                    <i style="font-size: 20px" class="fa fa-ban"></i>
                                @endif
                            </td>
                            <td>
                                @if ($ticket->Subir == 1)
                                    <i style="color: green; font-size: 20px" class="fa fa-check-circle-o"></i>
                                @else
                                    <i style="color: red; font-size: 20px" class="fa fa-times-circle-o"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th style="text-align: center">Totales: </th>
                        <th>${{ number_format($total, 2) }}</th>
                        <th>${{ number_format($totalIva, 2) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
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
