@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitudes Factura')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Solicitudes Factura'])
            <form class="d-flex align-items-center justify-content-end flex-wrap pb-2 gap-2" action="/SolicitudesFactura"
                method="GET">
                <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
                <div class="col-auto">
                    <select class="form-select" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn card">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </form>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>



        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                    @if (count($solicitudes) <= 0)
                        <td colspan="6">No Hay Ninguna Solicitud De Factura!</td>
                    @else
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
                                    <a href="/SolicitudesFactura/{{ $solicitud->Id }}" class="text-decoration-none">
                                        <span class="material-icons">edit</span>
                                    </a>
                                    <i class="material-icons eliminar" data-bs-toggle="modal"
                                        data-bs-target="#ModalCancelarSolicitud{{ $solicitud->Id }}">delete_forever</i>
                                </td>
                            </tr>
                            @include('SolicitudesFactura.ModalCancelarSolicitud')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {!! $solicitudes->links() !!}
        </div>
    </div>
@endsection
