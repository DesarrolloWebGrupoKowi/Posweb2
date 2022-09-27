@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Paquetes')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Catálogo de Paquetes</h2>
        </div>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form action="/VerPaquetes" method="GET">
            <div class="row">
                <div class="col d-flex justify-content-start">
                    <input type="text" class="form-control" name="nomPaquete" id="nomPaquete"
                        placeholder="Nombre de Paquete" value="{{ $nomPaquete }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                <div class="col-auto">
                    <a class="btn card shadow" href="/VerPaquetes">
                        <span class="material-icons">refresh</span>
                    </a>
                </div>
                <div class="input-group flex-nowrap col d-flex justify-content-end">
                    <h5 class="text-left mt-2 me-3">Paquetes Activos: ({{ $paquetesActivos }})</h5>
                    <span class="input-group-text bg-white">Agregar Paquete</span>
                    <a href="/CatPaquetes" class="btn card shadow">
                        <span class="material-icons mt-0">add_circle</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <table class="table table-responsive table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Paquete</th>
                    <th>Costo</th>
                    <th>Fecha Creación</th>
                    <th>Creado Por</th>
                    <th>Articulos</th>
                    <th>Acciones</th>
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
@endsection
