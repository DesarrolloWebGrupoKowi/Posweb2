@extends('PlantillaBase.masterblade')
@section('title', 'Bloqueo de Empleados')
<style>
    i:active {
        transform: scale(1.5);
    }
</style>
@section('contenido')
    <div class="container mb-3">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                <h3 class="card shadow p-1">Bloqueo de Empleados</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center">
            @include('Alertas.Alertas')
        </div>
    </div>
    <div class="container mb-2">
        <form action="/BloqueoEmpleados" method="GET">
            <div class="row">
                <div class="col-5 d-flex justify-content-start ms-3">
                    <div class="input-group">
                        <span class="input-group-text shadow">
                            <input {!! $radioFiltro == 'numNomina' ? 'checked' : '' !!} checked class="form-check-input mt-0" type="radio"
                                name="radioFiltro" id="numNomina" value="numNomina">
                        </span>
                        <span class="input-group-text card shadow">Nómina</span>
                        <span class="input-group-text shadow">
                            <input {!! $radioFiltro == 'nomEmpleado' ? 'checked' : '' !!} class="form-check-input mt-0" type="radio" name="radioFiltro"
                                id="nomEmpleado" value="nomEmpleado">
                        </span>
                        <span class="input-group-text card shadow">Nombre</span>
                        <input type="text" class="form-control shadow" name="filtroBusqueda" id="filtroBusqueda"
                            placeholder="Buscar empleado..." value="{{ $filtroBusqueda }}" required>
                    </div>
                </div>
                <div class="col d-flex justify-content-start">
                    <button class="btn card">
                        <i style="color: orange" class="fa fa-search mt-1"></i>
                    </button>
                </div>
                <div class="col d-flex justify-content-start">
                    <a class="btn card" href="/BloqueoEmpleados">
                        <i class="fa fa-refresh mt-1"></i>
                    </a>
                </div>
                <div class="col d-flex justify-content-end me-3">
                    <button type="button" class="btn card d-inline shadow" data-bs-toggle="modal"
                        data-bs-target="#AgregarBloqueo">
                        <i class="fa fa-user-times"></i> Nuevo Bloqueo
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <table class="table table-striped table-responsive shadow">
            <thead class="table-dark">
                <tr>
                    <th>Nómina</th>
                    <th>Empleado</th>
                    <th>Motivo de Bloqueo</th>
                    <th>Fecha de Bloqueo</th>
                    <th>Bloqueado Por</th>
                    <th>Desbloquear</th>
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
                                <i style="font-size: 20px; cursor: pointer;" class="fa fa-user-plus" data-bs-toggle="modal"
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
