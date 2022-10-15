@extends('plantillaBase.masterblade')
@section('title', 'Interfaz de Mermas')
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
    <div class="d-flex justify-content-center mb-2">
        <div class="col-auto">
            <h2 class="card shadow p-1">Interfaz de Mermas</h2>
        </div>
    </div>
    <div class="container card shadow-lg p-2 rounded-3">
        <form action="/InterfazMermas" method="GET">
            <div class="row d-flex justify-content-center mb-3">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="form-control shadow" type="date" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
                </div>
                <div class="col-auto">
                    <input class="form-control shadow" type="date" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>
        @if (!empty($idTienda))
            <table class="table table-striped table-responsive shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Merma</th>
                        <th>Cantidad</th>
                        <th>Almacen</th>
                        <th>Cuenta</th>
                        <th>Lotes Disponibles</th>
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
            @endif
        @endif
    </div>
    @include('InterfazMermas.ModalConfirmarInterfaz')
@endsection
