@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Tablas Actualizables Por Tienda')
@section('contenido')
    <div class="d-flex justify-content-center">
        <div class="col-auto">
            <h2 class="card shadow p-1">
                Catálogo de Tablas Actualizables
            </h2>
        </div>
    </div>
    <div class="container">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form id="formTabla" action="/TablasUpdate" method="GET">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTienda" id="idTienda">
                        <option value="0">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $tienda->IdTienda == $idTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if (!empty($idTienda))
                    <div class="col d-flex justify-content-end">
                        <h4 class="mt-1">Tablas pendientes por descargar: ({{ $tablasPorDescargar }})</h4>
                    </div>
                @endif
            </div>
        </form>
    </div>
    @if ($idTienda != 0)
        <div class="container mb-3">
            <table class="table table-striped table-reponsive shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre Tabla</th>
                        <th>Descargada</th>
                        <th>Descargar Todas
                            <div class="form-switch d-inline">
                                <input {!! $checkedTodas == 0 ? 'checked' : '' !!} class="form-check-input" type="checkbox" role="switch"
                                    name="descargarTodas" id="descargarTodas" />
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tablasActualizables->count() == 0)
                        <tr>
                            <td style="text-align: center" colspan="3">
                                <a href="/AgregarTablasActualizablesTienda/{{ $idTienda }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="fa fa-plus-circle"></i>
                                    Agregar Tablas
                                </a>
                            </td>
                        </tr>
                    @else
                        <form id="formActualizarTablas" action="/ActualizarTablas/{{ $idTienda }}">
                            @foreach ($tablasActualizables as $tActualizable)
                                <tr>
                                    <td>{{ $tActualizable->NombreTabla }}</td>
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
        </div>
        @if ($tablasActualizables->count() > 0)
            <div class="container mb-3">
                <div class="d-flex justify-content-end">
                    <div class="col-auto">
                        <button id="btnActualizarTablas" class="btn btn-warning">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif

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
