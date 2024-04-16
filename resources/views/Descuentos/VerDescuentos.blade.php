@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Descuentos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Descuentos'])
            <div>
                <a href="/CatDescuentos" class="btn btn-sm btn-dark" title="Agregar Descuento">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar descuento
                </a>
                <a class="btn btn-dark-outline" href="/VerDescuentos">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-between pb-4 gap-4" action="/VerDescuentos" method="GET">
            <div>
                <h6>Descuentos Activos: ({{ $descuentosActivos }})</h6>
            </div>
            <div class="input-group" style="max-width: 300px">
                <input type="text" class="form-control" name="nomDescuento" id="nomDescuento"
                    placeholder="Nombre del descuento" value="{{ $nomDescuento }}" autofocus>
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
                        <th>Descuento</th>
                        <th>Tipo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Tienda</th>
                        <th>Plaza</th>
                        <th>Articulos</th>
                        <th>Editar</th>
                        <th class="rounded-end">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($descuentos->count() == 0)
                        <tr>
                            <td colspan="7">No Hay Coincidencias!</td>
                        </tr>
                    @else
                        @foreach ($descuentos as $descuento)
                            <tr style="vertical-align: middle">
                                <td>{{ $descuento->IdEncDescuento }}</td>
                                <td>{{ $descuento->NomDescuento }}</td>
                                <td>{{ $descuento->NomTipoDescuento }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaInicio)) }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaFin)) }}</td>
                                <td>{{ $descuento->NomTienda }}</td>
                                <td>{{ $descuento->NomPlaza }}</td>
                                <td>
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalArticulos{{ $descuento->IdEncDescuento }}">
                                        <span style="color: rgb(0, 0, 0)" class="material-icons">description</span>
                                    </button>
                                    @include('Descuentos.ModalArticulos')
                                </td>
                                <td>
                                    <a href="/EditarDescuento/{{ $descuento->IdEncDescuento }}" class="btn">
                                        <span class="material-icons">edit</span>
                                    </a>
                                </td>
                                {{-- <td>${{ number_format($paquete->ImportePaquete, 2) }}</td>
                                <td>{{ strtoupper($paquete->Usuario->NomUsuario) }}</td>
                                --}}
                                <td>
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarConfirm{{ $descuento->IdEncDescuento }}">
                                        <span style="color: red" class="material-icons">delete_forever</span>
                                    </button>
                                    @include('Descuentos.ModalEliminarConfirm')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
