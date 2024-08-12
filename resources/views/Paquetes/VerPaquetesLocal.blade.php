@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Paquetes')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Paquetes'])
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                <h6 class="text-secondary">Paquetes Activos: ({{ $paquetesActivos }})</h6>
                @include('components.table-search')
            </div>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Folio</th>
                        <th>Paquete</th>
                        <th>Recepcion</th>
                        <th>Venta</th>
                        <th>Costo</th>
                        <th>Fecha Creación</th>
                        {{-- <th>Creado Por</th> --}}
                        {{-- <th>Articulos</th> --}}
                        {{-- <th>Editar</th> --}}
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @if ($paquetes->count() == 0)
                        <tr>
                            <td colspan="7">No Hay Coincidencias!</td>
                        </tr>
                    @else
                        @foreach ($paquetes as $paquete)
                            <tr style="vertical-align: middle">
                                <td>{{ $paquete->IdPaquete }}</td>
                                <td>{{ $paquete->IdPreparado }}</td>
                                <td>{{ substr($paquete->NomPaquete, 0, -15) }} </td>
                                <td>{{ $paquete->CantidadEnvio }}</td>
                                <td>{{ $paquete->CantidadVendida ? $paquete->CantidadVendida : 0 }}</td>
                                <td>${{ number_format($paquete->ImportePaquete, 2) }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($paquete->FechaCreacion)) }}</td>
                                {{-- <td>{{ strtoupper($paquete->Usuario->NomUsuario) }}</td> --}}
                                <td>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalArticulos{{ $paquete->IdPaquete }}">
                                        @include('components.icons.list')
                                    </button>
                                    <button class="btn-table btn-table-show" data-bs-toggle="modal"
                                        data-bs-target="#ModalCantidadRecepcion{{ $paquete->IdPaquete }}">
                                        @include('components.icons.edit')
                                    </button>
                                    @if ($paquete->Status == 1)
                                        <a href="/ActivarPaquetes/{{ $paquete->IdPaquete }}"
                                            class="btn-table btn-table-success">
                                            @include('components.icons.arrow-up')
                                        </a>
                                    @else
                                        <a href="/DesactivarPaquetes/{{ $paquete->IdPaquete }}"
                                            class="btn-table btn-table-delete">
                                            @include('components.icons.arrow-down')
                                        </a>
                                    @endif
                                    @include('Paquetes.ModalArticulos')
                                    @include('Paquetes.ModalCantidadRecepcion')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @include('components.paginate', ['items' => $paquetes])
        </div>
    </div>
@endsection
