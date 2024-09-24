@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitudes Factura')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-2 pt-4">

        <div class="card border border-5 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Solicitudes Factura'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div>
            <form class="d-flex align-items-center justify-content-end flex-wrap gap-2 pb-2 pt-4 pt-sm-0"
                action="/SolicitudesFactura" method="GET">
                <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
                <div class="col-12 col-sm-auto border border-5 p-2 bg-white" style="border-radius: 10px">
                    <select class="form-select rounded" style="line-height: 18px; border:0px" name="idTienda"
                        id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-auto text-center border border-5 p-2 bg-white" style="border-radius: 10px">
                    <button class="w-100 btn text-white " style="line-height: 18px; background: #10b981">
                        Buscar
                    </button>
                </div>
            </form>

            <div class="content-table content-table-flex-none card p-3 border border-5" style="border-radius: 10px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Id</th>
                            <th>Tienda</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Status</th>
                            <th class="rounded-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', ['items' => $solicitudes, 'colspan' => 23])
                        @foreach ($solicitudes as $solicitud)
                            {{-- <tr style="line-height: .9rem"> --}}
                            <tr>
                                <td>{{ $solicitud->IdSolicitudFactura }}</td>
                                <td>{{ $solicitud->NomTienda }}</td>
                                <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                <td>{{ $solicitud->TipoPersona }}</td>
                                <td>{{ $solicitud->NomCliente }}</td>
                                <td>{{ $solicitud->RFC }}</td>
                                <td>
                                    @if ($solicitud->Status == 1)
                                        <span class="tags-red">Cancelada</span>
                                    @endif
                                    @if ($solicitud->Status == 0 && $solicitud->Editar != null)
                                        <span class="tags-red">Pendiente de relacionar</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="/SolicitudesFactura/{{ $solicitud->Id }}" class="btn-table border border-3">
                                        @include('components.icons.list')
                                    </a>
                                    @if ($solicitud->Status == 0)
                                        <button class="btn-table btn-table-delete border border-3" data-bs-toggle="modal"
                                            data-bs-target="#ModalCancelarSolicitud{{ $solicitud->Id }}"
                                            title="Cancelar solicitud">
                                            @include('components.icons.delete')
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @include('SolicitudesFactura.ModalCancelarSolicitud')
                        @endforeach
                    </tbody>
                </table>

            </div>
            @include('components.paginate', ['items' => $solicitudes])
        </div>

        <div class="pt-3 pb-5">
            <span class="mb-2 text-sm fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                Solicitudes pendientes de ralacionar
            </span>
            <div class="content-table content-table-flex-none card p-3 border border-5" style="border-radius: 10px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Id</th>
                            <th>Tienda</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th class="rounded-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', [
                            'items' => $solicitudesPendientes,
                            'colspan' => 7,
                        ])
                        @foreach ($solicitudesPendientes as $solicitud)
                            {{-- <tr style="line-height: .9rem"> --}}
                            <tr>
                                <td>{{ $solicitud->IdSolicitudFactura }}</td>
                                <td>{{ $solicitud->NomTienda }}</td>
                                <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                <td>{{ $solicitud->TipoPersona }}</td>
                                <td>{{ $solicitud->NomCliente }}</td>
                                <td>{{ $solicitud->RFC }}</td>
                                <td>
                                    @if ($solicitud->Status == 1)
                                        <span class="tags-red">Cancelada</span>
                                    @endif
                                    @if ($solicitud->Status == 0 && $solicitud->Editar != null)
                                        <span class="tags-red">Pendiente de relacionar</span>
                                    @endif
                                </td>
                            </tr>
                            @include('SolicitudesFactura.ModalCancelarSolicitud')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
