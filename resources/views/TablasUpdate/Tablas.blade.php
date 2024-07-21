@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tablas Para Actualizar')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Tablas'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar tabla"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarTabla">
                        Agregar tabla @include('components.icons.plus-circle')
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
                        <th class="rounded-start">Tabla</th>
                        <th class="rounded-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tablas) == 0)
                        <tr>
                            <td colspan="2">No ahi tablas!</td>
                        </tr>
                    @else
                        @foreach ($tablas as $tabla)
                            <tr>
                                <td>{{ $tabla->NomTabla }}</td>
                                <td>
                                    @if ($tabla->Status == 1)
                                        <span class="tags-red">
                                            @include('components.icons.x')
                                        </span>
                                    @else
                                        <span class="tags-green">
                                            @include('components.icons.check-all')
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('TablasUpdate.ModalAgregarTabla')
@endsection
