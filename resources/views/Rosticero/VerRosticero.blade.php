@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Rosticero')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Rosticero'])
                <div>
                    <button id="buttonAgregar" type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        @include('components.icons.plus-circle') Agregar rostizado
                    </button>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Fecha</th>
                        <th>Rostizado</th>
                        <th>Materia prima</th>
                        <th>Materia venta</th>
                        <th>Merma estandar</th>
                        <th>Merma real</th>
                        <th>Disponible</th>
                        <th></th>
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $rostisados, 'colspan' => 9])
                    @foreach ($rostisados as $rostisado)
                        <tr style="vertical-align: middle">
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($rostisado->Fecha)) }}</td>
                            <td>{{ $rostisado->NomArticulo }}</td>
                            <td>{{ $rostisado->CantidadMatPrima }}</td>
                            <td>{{ $rostisado->CantidadVenta }}</td>
                            <td>{{ $rostisado->MermaStnd }}</td>
                            <td>{{ $rostisado->MermaReal }}</td>
                            <td>{{ $rostisado->Disponible }}</td>
                            <td>
                                @if ($rostisado->subir == 0)
                                    <span class="tags-red">Offline </span>
                                @else
                                    <span class="tags-green"> Online </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-evenly gap-2">
                                    {{-- /*{{ count($rostisado->Detalle) > 0 ? '' : 'disabled' }}* --}}
                                    <button
                                        class="{{ Session::get('id') == $rostisado->IdRosticero ? 'modalOpen' : '' }} btn-table"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalMostrarDetalle{{ $rostisado->IdDatRosticero }}">
                                        @include('components.icons.list')
                                    </button>
                                    @include('Rosticero.ModalMostrarDetalle')

                                    {{-- <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalAgregarDetalle{{ $rostisado->IdDatRosticero }}">
                                            @include('components.icons.edit')
                                        </button>
                                        @include('Rosticero.ModalAgregarDetalle') --}}

                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarConfirm{{ $rostisado->IdDatRosticero }}"
                                        title="Eliminar rostizado" {{ count($rostisado->Detalle) == 0 ? '' : 'disabled' }}>
                                        @include('components.icons.delete')
                                    </button>
                                    @include('Rosticero.ModalEliminarConfirm')

                                    {{-- <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarConfirm{{ $rostisado->IdDatRosticero }}"
                                            title="Eliminar rostizado"
                                            {{ count($rostisado->Detalle) == 0 ? '' : 'disabled' }}>
                                            @include('components.icons.check')
                                        </button> --}}
                                    {{-- @include('Rosticero.ModalEliminarConfirm') --}}
                                </div>
                                {{--
                                        <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalEditar{{ $rostisado->IdDatRosticero }}">
                                            @include('components.icons.plus')
                                        </button> --}}
                                {{-- <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $rostisado->IdDatRosticero }}">
                                        <span class="material-icons">edit</span>
                                    </button> --}}
                                {{-- <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $rostisado->IdDatRosticero }}">
                                        <span class="material-icons">edit</span>
                                    </button>
                                    @include('Rosticero.ModalEditar') --}}
                                {{-- <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarConfirm{{ $rostisado->IdDatRosticero }}">
                                        <span style="color: red" class="material-icons">delete_forever</span>
                                    </button>
                                    @include('Rosticero.ModalEliminarConfirm') --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $rostisados])
        </div>

        @include('Rosticero.ModalAgregar')
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rostisados.js') }}"></script>
@endsection
