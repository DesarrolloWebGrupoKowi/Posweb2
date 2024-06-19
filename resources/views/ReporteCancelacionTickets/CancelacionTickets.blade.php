@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Módulo de Cancelación de Tickets')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Módulo de Cancelación de Tickets'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="ReporteSolicitudCancelacion">
                <div class="d-flex align-items-center gap-2">
                    <label for="txtFecha" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="txtFecha"
                        id="txtFecha" value="{{ $fecha }}" autofocus>
                </div>
                <button type="submit" class="d-none">Buscar</button>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>FechaSolicitud</th>
                        <th>Caja</th>
                        <th>Ticket</th>
                        <th>Importe</th>
                        <th>Status</th>
                        <th>Motivo</th>
                        <th class="rounded-end">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', [
                        'items' => $solicitudesCancelacion,
                        'colspan' => 8,
                    ])
                    @foreach ($solicitudesCancelacion as $solicitud)
                        @if (is_null($solicitud->Encabezado))
                            <tr>
                                <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                <th colspan="7">
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
                                    @if ($solicitud->SolicitudAprobada == '0')
                                        <span class="tags-green">Aprovada</span>
                                    @elseif ($solicitud->SolicitudAprobada == 1)
                                        <span class="tags-red">Rechazada</span>
                                    @else
                                        <span class="tags-yellow">Pendiente</span>
                                    @endif
                                </td>
                                <td class="puntitos">{{ $solicitud->MotivoCancelacion }}</td>
                                <td>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}">
                                        @include('components.icons.list')
                                    </button>
                                    @include('CancelacionTickets.ModalDetalleTicket')
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <th></th>
                        <th></th>
                        <th>${{ number_format($total, 2) }}</th>
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
