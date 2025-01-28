@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tablas Actualizables Por Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Tablas Actualizables'])
                @if ($idTienda != 0)
                    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#AgregarTablaUpdate">
                        Agregar Tablas @include('components.icons.plus-circle')
                    </button>
                @endif
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>


        {{-- @if ($idTienda != 0) --}}
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-2" id="formTabla"
                action="/TablasUpdate" method="GET">
                <div>
                    @if (!empty($idTienda))
                        <h6>Tablas pendientes por descargar: ({{ $tablasPorDescargar }})</h6>
                    @endif
                </div>
                <div class="input-group" style="max-width: 350px">
                    <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda">
                        <option value="0">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $tienda->IdTienda == $idTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Nombre Tabla</th>
                        <th>Caja</th>
                        <th>Descargada</th>
                        <th class="rounded-end">Descargar Todas
                            <div class="ps-5 form-switch d-inline-block" style="line-height: 18px">
                                <input {!! $checkedTodas == 0 ? 'checked' : '' !!} class="form-check-input" type="checkbox" role="switch"
                                    name="descargarTodas" id="descargarTodas" />
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tablasActualizables->count() == 0)
                        @include('components.table-empty', [
                            'items' => $tablasActualizables,
                            'colspan' => 4,
                        ])
                    @else
                        <form id="formActualizarTablas" action="/ActualizarTablas/{{ $idTienda }}">
                            @foreach ($tablasActualizables as $tActualizable)
                                <tr>
                                    <td>{{ $tActualizable->NombreTabla }}</td>
                                    <td>{{ $tActualizable->IdCaja }}</td>
                                    <td>
                                        @if ($tActualizable->Descargar == 1)
                                            <i style="font-size: 20px;" class="fa fa-check"></i>
                                        @else
                                            <i style="color: red; font-size: 20px;" class="fa fa-clock-o"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input {!! $tActualizable->Descargar == 0 ? 'checked' : '' !!} class="form-check-input" type="checkbox"
                                                role="switch" name="descargado[]" id="descargado"
                                                value="{{ $tActualizable->NombreTabla }}" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </form>
                    @endif
                </tbody>
            </table>
            @if ($tablasActualizables->count() > 0)
                <div class="mb-4">
                    <div class="d-flex justify-content-end">
                        <div class="col-auto">
                            <button id="btnActualizarTablas" class="btn btn-warning">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @include('TablasUpdate.AgregarTablaUpdate')
        {{-- @endif --}}
    </div>


    <script>
        const idTienda = document.getElementById('idTienda');
        const formTabla = document.getElementById('formTabla');
        idTienda.addEventListener('change', (e) => {
            formTabla.submit();
        });

        const btnActualizarTablas = document.getElementById('btnActualizarTablas');
        const formActualizarTablas = document.getElementById('formActualizarTablas');
        btnActualizarTablas.addEventListener('click', (e) => {
            formActualizarTablas.submit();
        });

        const tablas = document.querySelectorAll('#descargado');
        let totalTablas = tablas.length;
        let aux = <?php echo $tablasPorDescargar; ?>;

        document.getElementById('descargarTodas').addEventListener('click', (e) => {
            chkTablas = document.querySelectorAll('#descargado');
            chkTablas.forEach(element => {
                if (document.getElementById('descargarTodas').checked) {
                    element.checked = true;
                    aux = totalTablas;
                } else {
                    element.checked = false;
                    aux = 0;
                }
            });
        });

        tablas.forEach(element => {
            element.addEventListener('click', (e) => {
                element.checked ? aux = aux + 1 : aux = aux - 1;
                //alert(aux + " " + totalTablas);
                if (totalTablas == aux) {
                    document.getElementById('descargarTodas').checked = true;
                } else {
                    document.getElementById('descargarTodas').checked = false;
                }
            });
        });
    </script>
@endsection
