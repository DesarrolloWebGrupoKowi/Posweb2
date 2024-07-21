@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Rosticero')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Rosticero'])
                <div class="d-flex gap-2">
                    <button id="buttonAgregar" type="button" class="btn btn-dark" role="tooltip" title="Agregar rostizado"
                        data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar rostizado @include('components.icons.plus-circle')
                    </button>
                    <button type="button" class="btn btn-dark" role="tooltip" title="Recalentar rostizado"
                        data-bs-toggle="modal" data-bs-target="#ModalRecalentado">
                        Recalentado @include('components.icons.switch')
                    </button>
                    <button type="button" class="btn btn-dark" role="tooltip" title="Recalentar rostizado"
                        data-bs-toggle="modal" data-bs-target="#ModalMermar">
                        Mermar @include('components.icons.down')
                    </button>
                    <a href="/HistorialRosticero" class="btn btn-dark">
                        Historial Rostizado @include('components.icons.text-file')
                    </a>
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
                        <th class="rounded-start">Id</th>
                        {{-- <th class="rounded-start">Fecha</th> --}}
                        <th>Fecha</th>
                        <th>Rostizado</th>
                        <th>Materia prima</th>
                        <th>Cantidad venta</th>
                        <th>Merma estandar</th>
                        <th>Merma real</th>
                        <th>Recalentado</th>
                        <th>Disponible</th>
                        <th></th>
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $rostisados, 'colspan' => 11])
                    @foreach ($rostisados as $rostisado)
                        <tr style="vertical-align: middle">
                            <td>{{ $rostisado->IdRosticero }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($rostisado->Fecha)) }}</td>
                            <td>{{ $rostisado->NomArticulo }}</td>
                            <td>{{ $rostisado->CantidadMatPrima }}</td>
                            <td>{{ $rostisado->CantidadVenta }}</td>
                            <td>{{ $rostisado->MermaStnd }}</td>
                            <td>
                                <span class="{{ $rostisado->MermaReal > $rostisado->MermaStnd ? 'text-danger' : '' }}">
                                    {{ $rostisado->MermaReal }}
                                </span>
                            </td>
                            <td>
                                @php
                                    // Obtener un arreglo con todos los valores de CantMermaRecalentado
                                    $cantidades = array_column($rostisado->newdetalle, 'CantMermaRecalentado');

                                    // Obtener la suma de los valores en $cantidades
                                    $sumaCantMermaRecalentado = array_sum($cantidades);
                                @endphp
                                <span class="{{ $sumaCantMermaRecalentado > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($sumaCantMermaRecalentado, 3) }}
                                </span>
                            </td>
                            <td>{{ $rostisado->Disponible }}</td>
                            <td>
                                @if ($rostisado->subir == 0)
                                    <span class="tags-red" title="Fuera de linea"> @include('components.icons.cloud-slash') </span>
                                @else
                                    <span class="tags-green" title="En linea"> @include('components.icons.cloud-check') </span>
                                @endif

                                @if ($rostisado->Status == 1)
                                    <span class="tags-red ms-2" title="Cancelado"> @include('components.icons.x') </span>
                                @endif
                                @if ($rostisado->Status == 0 && $rostisado->Finalizado == 1)
                                    <span class="tags-blue ms-2" title="Finalizado"> @include('components.icons.check') </span>
                                @endif

                            </td>
                            <td>

                                <div class="d-flex gap-2">
                                    {{-- /*{{ count($rostisado->Detalle) > 0 ? '' : 'disabled' }}* --}}
                                    <button
                                        class="{{ Session::get('id') == $rostisado->IdRosticero ? 'modalOpen' : '' }} btn-table"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalMostrarDetalle{{ $rostisado->IdDatRosticero }}">
                                        @include('components.icons.list')
                                    </button>

                                    {{-- @if ($rostisado->Status != 1 || $rostisado->Finalizado == 1)
                                    @endif --}}

                                    @if ($rostisado->Finalizado != 1)
                                        <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarConfirm{{ $rostisado->IdDatRosticero }}"
                                            title="Eliminar rostizado">
                                            {{-- {{ count($rostisado->Detalle) == 0 ? '' : 'disabled' }}> --}}
                                            @include('components.icons.delete')
                                        </button>

                                        <button class="btn-table btn-table-success" data-bs-toggle="modal"
                                            data-bs-target="#ModalFinalizar{{ $rostisado->IdDatRosticero }}"
                                            title="Finalizar">
                                            @include('components.icons.check')
                                        </button>
                                    @endif

                                    @include('Rosticero.ModalMostrarDetalle')
                                    @include('Rosticero.ModalEliminarConfirm')
                                    @include('Rosticero.ModalFinalizar')
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $rostisados])
        </div>

        @include('Rosticero.ModalMermar')
        @include('Rosticero.ModalRecalentado')
        @include('Rosticero.ModalAgregar')
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rostisados.js') }}"></script>
@endsection
