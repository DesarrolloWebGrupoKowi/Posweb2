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
                        <th>RFC</th>
                        <th>Nombre</th>
                        <th>TipoPersona</th>
                        <th>Ciudad</th>
                        <th>Status</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($solicitudes) <= 0)
                        <td colspan="6">No Hay Ninguna Plaza!</td>
                    @else
                        @foreach ($solicitudes as $solicitud)
                            <tr>
                                <td>{{ $solicitud->Id }}</td>
                                <td>{{ $solicitud->NomTienda }}</td>
                                <td>{{ $solicitud->RFC }}</td>
                                <td>{{ $solicitud->NomCliente }}</td>
                                <td>{{ $solicitud->TipoPersona }}</td>
                                <td>{{ $solicitud->Ciudad }}</td>
                                <td> {{ $solicitud->Editar ? 'Actualizar' : 'Nuevo' }} </td>
                                <td>
                                    <a href="/SolicitudesFactura/{{ $solicitud->Id }}">
                                        <span class="material-icons">edit</span>
                                    </a>
                                </td>
                            </tr>
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
