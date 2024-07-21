@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitudes Factura')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Solicitudes Factura'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/SolicitudesFactura" method="GET">
                <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
                <div class="col-auto">
                    <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Tienda</th>
                        <th>Descripcion</th>
                        <th>Cliente</th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Calle</th>
                        <th>NumExt</th>
                        <th>NumInt</th>
                        <th>C.P.</th>
                        <th>Colonia</th>
                        <th>Ciudad</th>
                        <th>Municipio</th>
                        <th>Estado</th>
                        <th>Pais</th>
                        <th>Telefono</th>
                        <th>Correo Electronico</th>
                        <th>Correo Electronico CLOUD</th>
                        <th>Metodo de pago</th>
                        <th>Banco</th>
                        <th>Cuenta</th>
                        {{-- <th>Ciudad</th> --}}
                        <th>Status</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $solicitudes, 'colspan' => 23])
                    @foreach ($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->IdSolicitudFactura }}</td>
                            <td>{{ $solicitud->NomTienda }}</td>
                            <td></td>
                            <td>{{ $solicitud->TipoPersona }}</td>
                            <td>{{ $solicitud->NomCliente }}</td>
                            <td>{{ $solicitud->RFC }}</td>
                            <td>{{ $solicitud->Calle }}</td>
                            <td>{{ $solicitud->NumExt }}</td>
                            <td>{{ $solicitud->NumInt }}</td>
                            <td>{{ $solicitud->CodigoPostal }}</td>
                            <td>{{ $solicitud->Colonia }}</td>
                            <td>{{ $solicitud->Ciudad }}</td>
                            <td>{{ $solicitud->Municipio }}</td>
                            <td>{{ $solicitud->Estado }}</td>
                            <td>{{ $solicitud->Pais }}</td>
                            <td>{{ $solicitud->Telefono }}</td>
                            <td>{{ $solicitud->Email }}</td>
                            <td></td>
                            <td>{{ $solicitud->NomTipoPago }}</td>
                            <td>{{ $solicitud->NomBanco }}</td>
                            <td>{{ $solicitud->NumTarjeta }}</td>
                            <td> {{ $solicitud->Editar ? 'Actualizar' : 'Nuevo' }} </td>
                            <td>
                                <a href="/SolicitudesFactura/{{ $solicitud->Id }}" class="btn-table">
                                    @include('components.icons.list')
                                </a>
                                <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                    data-bs-target="#ModalCancelarSolicitud{{ $solicitud->Id }}"
                                    title="Cancelar solicitud">
                                    @include('components.icons.delete')
                                </button>
                            </td>
                        </tr>
                        @include('SolicitudesFactura.ModalCancelarSolicitud')
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $solicitudes])
        </div>
    </div>
@endsection
