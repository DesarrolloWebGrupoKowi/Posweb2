@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial preparados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Historial Preparados',
                    'options' => [['name' => 'Preparados', 'value' => '/AsignarPreparados']],
                ])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/DetalleAsignados">
                <div class="d-flex align-items-center gap-2">
                    <label for="fecha" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha"
                        id="fecha" value="{{ $fecha }}" autofocus>
                </div>
                <button type="submit" class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Folio</th>
                        <th>Nombre</th>
                        <th>Tienda</th>
                        <th>Fecha</th>
                        <th>Cantidad Enviada</th>
                        <th>Estatus</th>
                        <th class="rounded-end">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $asignados, 'colspan' => 5])
                    @foreach ($asignados as $asignado)
                        <tr>
                            <td>{{ $asignado->IdPreparado }}</td>
                            <td>{{ $asignado->Nombre }}</td>
                            <td>{{ $asignado->NomTienda }}</td>
                            <td>{{ ucfirst(\Carbon\Carbon::parse($asignado->Fecha)->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                            </td>
                            <td>{{ $asignado->CantidadEnvio }} piezas</td>
                            <td>
                                @if ($asignado->Subir == 1)
                                    <span class="tags-green" title="En linea"> @include('components.icons.cloud-check') </span>
                                @else
                                    <span class="tags-red" title="Fuera de linea"> @include('components.icons.cloud-slash') </span>
                                @endif
                            </td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalShowDetails{{ $asignado->IdDatAsignacionPreparado }}">
                                    @include('components.icons.list')
                                </button>
                                @include('AsignacionPreparados.ModalShowDetails')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $asignados])
        </div>
    </div>

@endsection
