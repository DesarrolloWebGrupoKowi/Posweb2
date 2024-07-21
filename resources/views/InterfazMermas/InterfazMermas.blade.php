@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Interfaz de Mermas')
@section('dashboardWidth', 'width-general')
<style>
    #modalLote {
        font-size: 20px;
        cursor: pointer;
    }

    i#modalLote:hover {
        font-size: 22px;
        color: rgb(255, 145, 0);
    }
</style>
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Interfaz de Mermas'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/InterfazMermas"
                method="GET">
                <div class="col-auto">
                    <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
                </div>
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            <div class="content-table content-table-full" style="height: 58vh">
                <table style="width: 100%;">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Id</th>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Merma</th>
                            <th>Cantidad</th>
                            <th>Almacen</th>
                            <th>Cuenta</th>
                            <th class="rounded-end">Lotes Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', ['items' => $mermas, 'colspan' => 8])
                        @foreach ($mermas as $merma)
                            <tr>
                                <td>{{ $merma->FolioMerma }}</td>
                                <td>{{ $merma->CodArticulo }}</td>
                                <td>{{ $merma->NomArticulo }}</td>
                                <td>{{ $merma->NomTipoMerma }}</td>
                                <th>{{ number_format($merma->CantArticulo, 2) }}</th>
                                <td>{{ $merma->Almacen }}</td>
                                <td>
                                    {{ empty($merma->Libro) ? '?' : $merma->Libro }}.{{ empty($merma->CentroCosto) ? '?' : $merma->CentroCosto }}.{{ empty($merma->Cuenta) ? '?' : $merma->Cuenta }}.{{ empty($merma->SubCuenta) ? '?' : $merma->SubCuenta }}.{{ empty($merma->InterCosto) ? '?' : $merma->InterCosto }}.{{ empty($merma->IdTipoArticulo) ? '?' : $merma->IdTipoArticulo }}.{{ $merma->Futuro != '0' ? '?' : $merma->Futuro }}
                                </td>
                                <td>
                                    @if ($merma->Lotes->count() == 0)
                                        <span class="tags-red">Sin lotes</span>
                                    @else
                                        <button id="modalLote" class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalLotes{{ $merma->CodArticulo }}">
                                            @include('components.icons.database')
                                        </button>
                                        @include('InterfazMermas.ModalLotes')
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (!empty($lotesDisponibles))
                <div class="d-flex justify-content-center mb-1">
                    <div class="col-auto">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalConfirmarInterfaz">
                            Interfazar Mermas
                        </button>
                    </div>
                </div>
            @elseif(empty($lotesDisponibles) && $mermas->count() > 0)
                <div class="d-flex justify-content-center mb-1">
                    <div class="col-auto">
                        <h5 class="bg-danger text-white p-1 shadow rounded-3">
                            <i class="fa fa-exclamation-circle"></i> Ninguna Merma Apta para ser Interfazada <i
                                class="fa fa-exclamation-circle"></i>
                        </h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('InterfazMermas.ModalConfirmarInterfaz')
@endsection
