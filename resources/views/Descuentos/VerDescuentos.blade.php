@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Descuentos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Descuentos'])
                <div>
                    <a href="/CatDescuentos" class="btn btn-sm btn-dark" title="Agregar Descuento">
                        Agregar descuento @include('components.icons.plus-circle')
                    </a>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                <h6>Descuentos Activos: ({{ $descuentosActivos }})</h6>
                <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/VerDescuentos"
                    method="GET">
                    <div class="d-flex align-items-center gap-2">
                        <label for="txtFiltro" class="text-secondary" style="font-weight: 500">Buscar:</label>
                        <input type="text" class="form-control rounded" style="line-height: 18px" name="nomDescuento"
                            id="nomDescuento" value="{{ $nomDescuento }}" autofocus>
                    </div>
                    <button class="btn btn-dark-outline" title="Buscar">
                        @include('components.icons.search')
                    </button>
                </form>
            </div>

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
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $descuentos, 'colspan' => 10])
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
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalArticulos{{ $descuento->IdEncDescuento }}"
                                    title="Detalle de descuento">
                                    @include('components.icons.list')
                                </button>
                                <a href="/EditarDescuento/{{ $descuento->IdEncDescuento }}"
                                    class="btn-table btn-table-success" title="Editar descuento">
                                    @include('components.icons.edit')
                                </a>
                                <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarConfirm{{ $descuento->IdEncDescuento }}"
                                    title="Eliminar descuento">
                                    @include('components.icons.delete')
                                </button>
                                @include('Descuentos.ModalArticulos')
                                @include('Descuentos.ModalEliminarConfirm')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $descuentos])
        </div>
    </div>
@endsection
