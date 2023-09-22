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
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Interfaz de Mermas'])
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4" action="/InterfazMermas" method="GET">
            <div class="row d-flex justify-content-center">
                <div class="input-group" style="max-width: 300px">
                    <select class="form-select" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="form-control" type="date" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
                </div>
                <div class="col-auto">
                    <input class="form-control" type="date" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>

        @if (!empty($idTienda))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Articulo</th>
                            <th>Merma</th>
                            <th>Cantidad</th>
                            <th>Almacen</th>
                            <th>Cuenta</th>
                            <th class="rounded-end">Lotes Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($mermas->count() == 0)
                            <tr>
                                <th colspan="7">No hay mermas en el rango de fechas seleccionadas</th>
                            </tr>
                        @else
                            @foreach ($mermas as $merma)
                                <tr>
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
                                            <i style="color: red; font-size:22px" class="fa fa-close"></i>
                                        @else
                                            <i id="modalLote" class="fa fa-database" data-bs-toggle="modal"
                                                data-bs-target="#ModalLotes{{ $merma->CodArticulo }}"></i>
                                            @include('InterfazMermas.ModalLotes')
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if (!empty($lotesDisponibles))
                    <div class="d-flex justify-content-center mb-1">
                        <div class="col-auto">
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalConfirmarInterfaz">
                                <i class="fa fa-refresh"></i> Interfazar Mermas
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
        @endif
    </div>
    @include('InterfazMermas.ModalConfirmarInterfaz')
@endsection
