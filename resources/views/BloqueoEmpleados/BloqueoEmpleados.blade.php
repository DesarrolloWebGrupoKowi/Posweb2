<x-page-container title="Bloqueo de Empleados">
    <x-card-gradient-header
        icon="lock"
        title="Bloqueo de Empleados"
        subtitle="Gestione los bloqueos de empleados en el sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form action="/BloqueoEmpleados">
            <x-form.group>
                <x-form.text
                    name="numNomina"
                    label="Nómina"
                    icon="hash"
                    placeholder="Buscar por nómina..."
                    col="col-md-4"
                    :autofocus="true"
                    :value="$numNomina ?? ''"
                />
                <x-form.text
                    name="nomEmpleado"
                    label="Nombre"
                    icon="person"
                    placeholder="Buscar por nombre..."
                    col="col-md-4"
                    :value="$nomEmpleado ?? ''"
                />
            </x-form.group>
            <div class="col-md-4 d-flex gap-2">
                <x-form.submit
                    text="Filtrar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Empleados Bloqueados
                    </h5>
                    <p class="section-content-subtitle">{{ $bloqueos->total() }} empleados bloqueados</p>
                </div>
                <button
                    class="btn-modern btn-agregar"
                    data-bs-toggle="modal"
                    data-bs-target="#AgregarBloqueo"
                >
                    <i class="bi bi-lock me-2"></i>Bloquear usuario
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Nómina</th>
                            <th><i class="bi bi-person me-1"></i>Empleado</th>
                            <th><i class="bi bi-exclamation-circle me-1"></i>Motivo</th>
                            <th><i class="bi bi-calendar me-1"></i>Fecha Bloqueo</th>
                            <th><i class="bi bi-person-check me-1"></i>Bloqueado Por</th>
                            <th><i class="bi bi-unlock me-1"></i>Desbloquear</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bloqueos as $bloqueo)
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $bloqueo->NumNomina }}</td>
                                <td>{{ $bloqueo->Empleado->Nombre }} {{ $bloqueo->Empleado->Apellidos }}</td>
                                <td>{{ $bloqueo->MotivoBloqueo }}</td>
                                <td style="color: #64748b;">
                                    {{ strftime('%d %B %Y, %H:%M', strtotime($bloqueo->FechaBloqueo)) }}</td>
                                <td>{{ $bloqueo->Usuario->NomUsuario }}</td>
                                <td>
                                    <button
                                        class="btn-table-action btn-table-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#DesbloquearEmpleado{{ $bloqueo->NumNomina }}"
                                        title="Desbloquear empleado"
                                    >
                                        <i class="bi bi-unlock"></i>
                                    </button>
                                </td>
                            </tr>
                            {{-- @include('BloqueoEmpleados.ModalDesbloquearEmpleado') --}}
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-search fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin datos disponibles</h6>
                                        <small class="text-muted">No se encontraron registros con los filtros
                                            seleccionados</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @foreach ($bloqueos as $bloqueo)
                @include('BloqueoEmpleados.ModalDesbloquearEmpleado')
            @endforeach
            @include('components.paginate', ['items' => $bloqueos])
        </div>
    </x-card-gradient-header>

    @include('BloqueoEmpleados.ModalAgregarBloqueo')
    <script>
        const btnBuscarEmpleado = document.getElementById('btnBuscarEmpleado');
        if (btnBuscarEmpleado) {
            btnBuscarEmpleado.addEventListener('click', (e) => {
                if (document.getElementById('numNominaEmpleado').value) {
                    fetch('/BuscarEmpleadoParaBloqueo/' + document.getElementById('numNominaEmpleado').value)
                        .then(res => res.text())
                        .then(respuesta => {
                            const spanNomEmpleado = document.getElementById('nomEmpleadoFetch');
                            const divNomEmpleado = document.getElementById('divNomEmpleado');
                            const divMotivoBloqueo = document.getElementById('divMotivoBloqueo');
                            const btnBloquear = document.getElementById('btnBloquear');

                            spanNomEmpleado.textContent = '';
                            spanNomEmpleado.className = 'badge';

                            if (respuesta == 'bajaOrNotExists') {
                                divMotivoBloqueo.hidden = true;
                                btnBloquear.hidden = true;
                                divNomEmpleado.hidden = false;
                                spanNomEmpleado.classList.add('bg-danger');
                                spanNomEmpleado.textContent = 'El empleado no existe o está dado de baja';
                            } else if (respuesta == 'bloqueado') {
                                divMotivoBloqueo.hidden = true;
                                btnBloquear.hidden = true;
                                divNomEmpleado.hidden = false;
                                spanNomEmpleado.classList.add('bg-danger');
                                spanNomEmpleado.textContent =
                                    'El empleado ya se encuentra bloqueado actualmente';
                            } else {
                                divNomEmpleado.hidden = false;
                                spanNomEmpleado.classList.add('bg-primary');
                                spanNomEmpleado.textContent = respuesta;
                                divMotivoBloqueo.hidden = false;
                                btnBloquear.hidden = false;
                            }
                        });
                }
            });
        }
    </script>
</x-page-container>
