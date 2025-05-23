@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Interfazar Rosticero')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-general d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Interfazar Rosticero'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="p-4 border-0 content-table content-table-full card" style="border-radius: 10px">
            <form class="flex-wrap gap-2 pb-2 d-flex align-items-center justify-content-end" action="/InterfazarRosticero"
                method="GET">
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            @php
                $baja = false;
                $alta = false;
            @endphp

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">ID</th>
                        <th>Fecha</th>
                        <th>Codigo baja</th>
                        <th>Codigo alta</th>
                        <th>Rostizado</th>
                        <th>Tienda</th>
                        <th>Cantidad baja</th>
                        <th>Cantidad alta</th>
                        <th>Tipo</th>
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $rostisados, 'colspan' => 9])
                    @foreach ($rostisados as $rostisado)
                        <tr style="vertical-align: middle">
                            <td>{{ $rostisado->IdRosticero }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($rostisado->Fecha)) }}</td>
                            <td>{{ $rostisado->CodigoMatPrima }}</td>
                            <td>{{ $rostisado->CodigoVenta }}</td>
                            <td>{{ $rostisado->NomArticulo }}</td>
                            <td>{{ $rostisado->NomTienda }}</td>
                            <td>{{ $rostisado->CantidadMatPrima }}</td>
                            <td>{{ $rostisado->CantidadVenta }}</td>
                            <td>
                                @if (!$rostisado->FechaInterfazBaja)
                                    <span class="tags-green">Baja</span>
                                @endif
                                @if (!$rostisado->FechaInterfazAlta)
                                    <span class="tags-blue">Alta</span>
                                @endif
                            </td>
                            <td>
                                @if ($rostisado->Lotes->count() == 0)
                                    <span class="tags-red">Sin lotes</span>
                                @else
                                    @php
                                        $baja = !$rostisado->FechaInterfazBaja;
                                        $alta = !$rostisado->FechaInterfazAlta;
                                    @endphp
                                    <button class="btn text-secondary" data-bs-toggle="modal"
                                        data-bs-target="#ModalLotes{{ $rostisado->IdDatRosticero }}">
                                        @include('components.icons.database')
                                    </button>
                                    @include('InterfazRosticero.ModalLotes')
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $rostisados])

            <div class="mb-1 d-flex justify-content-center">
                <div class="col-auto">
                    @if ($baja)
                        <button class="btn btn-outline-dark" data-bs-toggle="modal"
                            data-bs-target="#ModalConfirmarInterfaz">
                            Interfazar Bajas
                        </button>
                    @endif
                    @if ($alta)
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalConfirmarInterfazAlta">
                            Interfazar Altas
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @include('InterfazRosticero.ModalConfirmarInterfaz')
        @include('InterfazRosticero.ModalConfirmarInterfazAlta')
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rostisados.js') }}"></script>
@endsection
