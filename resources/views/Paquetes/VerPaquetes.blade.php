@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Paquetes')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Paquetes'])
                <div>
                    <a href="/CatPaquetes" class="btn btn-sm btn-dark" title="Agregar Usuario">
                        Agregar paquete @include('components.icons.plus-circle')
                    </a>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                <h6>Paquetes Activos: ({{ $paquetesActivos }})</h6>
                @include('components.table-search')
            </div>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Paquete</th>
                        <th>Costo</th>
                        <th>Fecha Creación</th>
                        <th>Creado Por</th>
                        {{-- <th>Articulos</th>
                        <th>Editar</th> --}}
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $paquetes, 'colspan' => 5])
                    @foreach ($paquetes as $paquete)
                        <tr style="vertical-align: middle">
                            <td>{{ $paquete->IdPaquete }}</td>
                            <td>{{ $paquete->NomPaquete }}</td>
                            <td>${{ number_format($paquete->ImportePaquete, 2) }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($paquete->FechaCreacion)) }}</td>
                            <td>{{ strtoupper($paquete->Usuario->NomUsuario) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalArticulos{{ $paquete->IdPaquete }}" title="Ver detalle">
                                        @include('components.icons.list')
                                    </button>

                                    <a href="/EditarPaquete/{{ $paquete->IdPaquete }}" class="btn-table btn-table-success">
                                        @include('components.icons.edit')
                                    </a>

                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarConfirm{{ $paquete->IdPaquete }}"
                                        title="Eliminar paquete">
                                        @include('components.icons.delete')
                                    </button>
                                </div>
                                @include('Paquetes.ModalArticulos')
                                @include('Paquetes.ModalEliminarConfirm')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $paquetes])
        </div>
    </div>
@endsection
