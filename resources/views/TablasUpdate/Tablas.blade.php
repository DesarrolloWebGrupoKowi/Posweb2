@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tablas Para Actualizar')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Tablas'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar tabla"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarTabla">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar tabla
                </button>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                                    @if ($tabla->Status == 0)
                                        <i class="fa fa-check"></i>
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
