@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ver Solicitudes de Factura')
@section('dashboardWidth', 'width-95')
<style>
    #constancia {
        font-size: 20px;
        color: black;
    }

    #constancia:hover {
        color: orange;
    }

    i {
        cursor: pointer;
    }

    i:hover {
        color: orange;
    }
</style>
@section('contenido')
    <div class="container-fluid pt-4 width-95">
        <div class="d-flex justify-content-sm-between align-items-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Solicitudes de Factura'])
            <form class="d-flex align-items-end justify-content-end gap-2" action="/VerSolicitudesFactura">
                <div class="form-group" style="min-width: 300px">
                    <label class="fw-bold text-secondary">Selecciona una fecha</label>
                    <input type="date" class="form-control" name="fechaSolicitud" value="{{ $fechaSolicitud }}" required>
                </div>

                <div class="input-group-append">
                    <button class="input-group-text"><span class="material-icons">search</span></button>
                </div>
            </form>
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Fecha</th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Correo</th>
                        <th style="text-align: center">Constancia</th>
                        <th style="text-align: center">Estado de Facturación</th>
                        <th class="rounded-end">Subir Constancia</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($solicitudesFactura->count() > 0)
                        @foreach ($solicitudesFactura as $solicitudFactura)
                            <tr>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($solicitudFactura->FechaSolicitud)) }}</td>
                                <td>{{ $solicitudFactura->NomCliente }}</td>
                                <td>{{ $solicitudFactura->RFC }}</td>
                                <td>{{ $solicitudFactura->Email }}</td>
                                <td style="text-align: center">
                                    @if (empty($solicitudFactura->ConstanciaSituacionFiscal))
                                        No Tiene Constancia
                                    @else
                                        <a id="constancia"
                                            href="/VerConstanciaCliente/{{ $solicitudFactura->IdSolicitudFactura }}"
                                            target="_blank"><i class="fa fa-book"></i></a>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if (empty($solicitudFactura->IdClienteCloud))
                                        <strong><i style="color: red" class="fa fa-exclamation-triangle"></i> Falta Ligar
                                            Cliente</strong>
                                    @else
                                        <strong><i class="fa fa-clock-o"></i> En Proceso de Facturación</strong>
                                    @endif
                                </td>
                                <td>
                                    @if (empty($solicitudFactura->ConstanciaSituacionFiscal) &&
                                            empty($solicitudFactura->IdClienteCloud) &&
                                            empty($solicitudFactura->Bill_To))
                                        <button class="btn" data-bs-toggle="modal"
                                            data-bs-target="#ModalEditarSolicitud{{ $solicitudFactura->IdSolicitudFactura }}">
                                            <i style="font-size: 20px;" class="fa fa-upload"></i>
                                        </button>
                                        @include('SolicitudFactura.ModalEditarSolicitud')
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Hay Solicitudes!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
@endsection
