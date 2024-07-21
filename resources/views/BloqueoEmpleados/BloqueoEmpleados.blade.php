@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Bloqueo de Empleados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Bloqueo de Empleados'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar bloqueo"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#AgregarBloqueo">
                        Bloquear usuario @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/BloqueoEmpleados"
                method="GET">
                <div class="input-group d-flex justify-content-end" style="max-width: 300px">
                    <span class="input-group-text">
                        <input {!! $radioFiltro == 'numNomina' ? 'checked' : '' !!} checked class="form-check-input mt-0" type="radio"
                            name="radioFiltro" id="numNomina" value="numNomina">
                    </span>
                    <span class="input-group-text card" style="line-height: 18px">Nómina</span>

                    <span class="input-group-text">
                        <input {!! $radioFiltro == 'nomEmpleado' ? 'checked' : '' !!} class="form-check-input mt-0" type="radio" name="radioFiltro"
                            id="nomEmpleado" value="nomEmpleado">
                    </span>
                    <span class="input-group-text card " style="line-height: 18px">Nombre</span>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="filtroBusqueda"
                        id="filtroBusqueda" placeholder="Buscar empleado..." value="{{ $filtroBusqueda }}" required>
                </div>
                <button class="btn btn-dark-outline" title="Buscar">
                    @include('components.icons.search')
                </button>
                <a href="/BloqueoEmpleados" class="btn btn-dark-outline" title="Limpiar busqueda">
                    @include('components.icons.switch')
                </a>
            </form>
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
                    @include('components.table-empty', ['items' => $bloqueos, 'colspan' => 6])
                    @foreach ($bloqueos as $bloqueo)
                        <tr>
                            <td>{{ $bloqueo->NumNomina }}</td>
                            <td>{{ $bloqueo->Empleado->Nombre }} {{ $bloqueo->Empleado->Apellidos }}</td>
                            <td>{{ $bloqueo->MotivoBloqueo }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($bloqueo->FechaBloqueo)) }}</td>
                            <td>{{ $bloqueo->Usuario->NomUsuario }}</td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#DesbloquearEmpleado{{ $bloqueo->NumNomina }}"
                                    title="Desbloquear empleado">
                                    @include('components.icons.user-less')
                                </button>
                            </td>
                            @include('BloqueoEmpleados.ModalDesbloquearEmpleado')
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $bloqueos])
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
