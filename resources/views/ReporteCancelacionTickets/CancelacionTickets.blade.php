@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Módulo de Cancelación de Tickets')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Módulo de Cancelación de Tickets'])
            <form class="d-flex align-items-end" id="formVentaTicketDiario" action="ReporteSolicitudCancelacion">
                <div class="input-group" style="min-width: 200px">
                    <input type="date" class="form-control" name="txtFecha" id="txtFecha" value="{{ $fecha }}"
                        required>
                </div>
            </form>
        </div>
        <div>
            @include('Alertas.Alertas')

        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <p class="d-none">{{ $sum = 0 }}</p>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>FechaSolicitud</th>
                        <th>Caja</th>
                        <th>Ticket</th>
                        <th>Importe</th>
                        <th>Detalle</th>
                        <th class="rounded-end">Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($solicitudesCancelacion->count() == 0)
                        <tr>
                            <th style="text-align: center; font-size: 22px" colspan="8">No hay solicitudes de cancelación
                                de
                                tickets
                            </th>
                        </tr>
                    @else
                        @foreach ($solicitudesCancelacion as $solicitud)
                            @if (is_null($solicitud->Encabezado))
                                <tr>
                                    <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                    <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                    <th colspan="6">
                                        No ha subido la venta <i class="fa fa-exclamation-circle"></i>
                                    </th>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                    <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                    <td>{{ $solicitud->Encabezado->NumCaja }}</td>
                                    <td>{{ $solicitud->Encabezado->IdTicket }}</td>
                                    <th>${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}</th>
                                    <td>
                                        <i style="font-size: 22px; cursor: pointer; color:rgb(18, 167, 18)"
                                            class="fa fa-bar-chart" data-bs-toggle="modal"
                                            data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}"></i>
                                        @include('CancelacionTickets.ModalDetalleTicket')
                                    </td>
                                    <td>{{ $solicitud->MotivoCancelacion }}</td>
                                    <p class="d-none">{{ $sum += $solicitud->Encabezado->ImporteVenta }}</p>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <th></th>
                        <th></th>
                        @if ($sum)
                            <th>${{ number_format($sum, 2) }}</th>
                        @endif
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
