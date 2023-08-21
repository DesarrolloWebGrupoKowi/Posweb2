@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Bloqueo de Empleados')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Bloqueo de Empleados'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar bloqueo"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#AgregarBloqueo">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar menú
                </button>
                <a href="/BloqueoEmpleados" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/BloqueoEmpleados" method="GET">
            <div class="input-group d-flex justify-content-end" style="max-width: 300px">
                <span class="input-group-text">
                    <input {!! $radioFiltro == 'numNomina' ? 'checked' : '' !!} checked class="form-check-input mt-0" type="radio" name="radioFiltro"
                        id="numNomina" value="numNomina">
                </span>
                <span class="input-group-text card">Nómina</span>

                <span class="input-group-text">
                    <input {!! $radioFiltro == 'nomEmpleado' ? 'checked' : '' !!} class="form-check-input mt-0" type="radio" name="radioFiltro"
                        id="nomEmpleado" value="nomEmpleado">
                </span>
                <span class="input-group-text card ">Nombre</span>
            </div>
            <div class="input-group" style="max-width: 300px">
                <input type="text" class="form-control" name="filtroBusqueda" id="filtroBusqueda"
                    placeholder="Buscar empleado..." value="{{ $filtroBusqueda }}" required>
                <div class="input-group-append">
                    <button class="input-group-text"><span class="material-icons">search</span></button>
                </div>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Nómina</th>
                        <th>Empleado</th>
                        <th>Motivo de Bloqueo</th>
                        <th>Fecha de Bloqueo</th>
                        <th>Bloqueado Por</th>
                        <th class="rounded-end">Desbloquear</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($bloqueos->count() == 0)
                        <tr>
                            <th colspan="6">No hay bloqueos</th>
                        </tr>
                    @else
                        @foreach ($bloqueos as $bloqueo)
                            <tr>
                                <td>{{ $bloqueo->NumNomina }}</td>
                                <td>{{ $bloqueo->Empleado->Nombre }} {{ $bloqueo->Empleado->Apellidos }}</td>
                                <td>{{ $bloqueo->MotivoBloqueo }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($bloqueo->FechaBloqueo)) }}</td>
                                <td>{{ $bloqueo->Usuario->NomUsuario }}</td>
                                <td>
                                    <i style="font-size: 20px; cursor: pointer;" class="fa fa-user-plus"
                                        data-bs-toggle="modal"
                                        data-bs-target="#DesbloquearEmpleado{{ $bloqueo->NumNomina }}"></i>
                                </td>
                                @include('BloqueoEmpleados.ModalDesbloquearEmpleado')
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $bloqueos->links() }}
        </div>
    </div>


    @include('BloqueoEmpleados.ModalAgregarBloqueo')

    <script>
        const btnBuscarEmpleado = document.getElementById('btnBuscarEmpleado');
        btnBuscarEmpleado.addEventListener('click', (e) => {
            if (document.getElementById('numNominaEmpleado').value) {
                fetch('/BuscarEmpleadoParaBloqueo/' + document.getElementById('numNominaEmpleado').value)
                    .then(res => res.text())
                    .then(respuesta => {
                        if (respuesta == 'bajaOrNotExists') {
                            document.getElementById('nomEmpleadoFetch').textContent =
                                ''; // limpiar el span para insertar la nueva respuesta
                            document.getElementById('divMotivoBloqueo').hidden = true;
                            document.getElementById('btnBloquear').hidden = true;
                            document.getElementById('divNomEmpleado').hidden = false;
                            const spanNomEmpleado = document.getElementById('nomEmpleadoFetch');
                            spanNomEmpleado.classList.add("badge");
                            spanNomEmpleado.classList.add("bg-danger");
                            const nomEmpleado = document.createTextNode(
                                'El empleado no existe o esta dado de baja');
                            spanNomEmpleado.appendChild(nomEmpleado);
                        }

                        if (respuesta == 'bloqueado') {
                            document.getElementById('nomEmpleadoFetch').textContent =
                                ''; // limpiar el span para insertar la nueva respuesta
                            document.getElementById('divMotivoBloqueo').hidden = true;
                            document.getElementById('btnBloquear').hidden = true;
                            document.getElementById('divNomEmpleado').hidden = false;
                            const spanNomEmpleado = document.getElementById('nomEmpleadoFetch');
                            spanNomEmpleado.classList.add("badge");
                            spanNomEmpleado.classList.add("bg-danger");
                            const nomEmpleado = document.createTextNode(
                                'El empleado ya se encuentra bloqueado actualmente');
                            spanNomEmpleado.appendChild(nomEmpleado);

                        }
                        if (respuesta != 'bloqueado' && respuesta != 'bajaOrNotExists') {
                            document.getElementById('nomEmpleadoFetch').textContent =
                                ''; // limpiar el span para insertar la nueva respuesta
                            document.getElementById('divNomEmpleado').hidden = false;
                            const spanNomEmpleado = document.getElementById('nomEmpleadoFetch');
                            spanNomEmpleado.classList.add("badge");
                            spanNomEmpleado.classList.remove("bg-danger");
                            spanNomEmpleado.classList.add("bg-primary");
                            const nomEmpleado = document.createTextNode(respuesta);
                            spanNomEmpleado.appendChild(nomEmpleado);
                            document.getElementById('divMotivoBloqueo').hidden = false;
                            document.getElementById('btnBloquear').hidden = false;
                        }
                    });
            }
        });
    </script>
@endsection
