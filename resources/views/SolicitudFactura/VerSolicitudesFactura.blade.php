@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ver Solicitudes de Factura')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid pt-4 width-95 d-flex flex-column gap-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Solicitudes de Factura'])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-end justify-content-end gap-2 pb-2" action="/VerSolicitudesFactura">
                <div class="d-flex align-items-center gap-2">
                    <label for="fechaSolicitud" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fechaSolicitud"
                        id="fechaSolicitud" value="{{ $fechaSolicitud }}" autofocus>
                </div>
                <button class="d-none input-group-text"><span class="material-icons">search</span></button>
            </form>
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
                    @include('components.table-empty', ['items' => $solicitudesFactura, 'colspan' => 7])
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
                                    <a href="/VerConstanciaCliente/{{ $solicitudFactura->IdSolicitudFactura }}"
                                        target="_blank" class="btn-table text-dark" style="width: fit-content">
                                        @include('components.icons.list')
                                    </a>
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if (empty($solicitudFactura->IdClienteCloud))
                                    <strong>
                                        <span style="color: red">@include('components.icons.info')</span> Falta Ligar Cliente
                                    </strong>
                                @else
                                    <strong>@include('components.icons.clock') En Proceso de Facturación</strong>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (empty($solicitudFactura->ConstanciaSituacionFiscal) &&
                                        empty($solicitudFactura->IdClienteCloud) &&
                                        empty($solicitudFactura->Bill_To))
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditarSolicitud{{ $solicitudFactura->IdSolicitudFactura }}">
                                        @include('components.icons.upload')
                                    </button>
                                    @include('SolicitudFactura.ModalEditarSolicitud')
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
