@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Paquetes')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Paquetes'])
            <div>
                <a href="/CatPaquetes" class="btn btn-sm btn-dark" title="Agregar Usuario">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar paquete
                </a>
                <a class="btn btn-dark-outline" href="/VerPaquetes">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-between pb-4 gap-4" action="/VerPaquetes" method="GET">
            <div>
                <h6>Paquetes Activos: ({{ $paquetesActivos }})</h6>
            </div>
            <div class="input-group" style="max-width: 300px">
                <input type="text" class="form-control" name="nomPaquete" id="nomPaquete" placeholder="Nombre de Paquete"
                    value="{{ $nomPaquete }}" required>
                <div class="input-group-append">
                    <button class="input-group-text"><span class="material-icons">search</span></button>
                </div>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Paquete</th>
                        <th>Costo</th>
                        <th>Fecha Creación</th>
                        <th>Creado Por</th>
                        <th>Articulos</th>
                        <th>Editar</th>
                        <th class="rounded-end">Eliminar</th>
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
                                <td>{{ $paquete->NomPaquete }}</td>
                                <td>${{ number_format($paquete->ImportePaquete, 2) }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($paquete->FechaCreacion)) }}</td>
                                <td>{{ strtoupper($paquete->Usuario->NomUsuario) }}</td>
                                <td>
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalArticulos{{ $paquete->IdPaquete }}">
                                        <span style="color: rgb(0, 0, 0)" class="material-icons">description</span>
                                    </button>
                                    @include('Paquetes.ModalArticulos')
                                </td>
                                <td>
                                    <a href="/EditarPaquete/{{ $paquete->IdPaquete }}" class="btn">
                                        <span class="material-icons">edit</span>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarConfirm{{ $paquete->IdPaquete }}">
                                        <span style="color: red" class="material-icons">delete_forever</span>
                                    </button>
                                    @include('Paquetes.ModalEliminarConfirm')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
