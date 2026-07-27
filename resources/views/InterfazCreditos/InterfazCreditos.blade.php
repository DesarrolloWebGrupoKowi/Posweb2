<x-page-container title="Interfaz de Créditos">
    <x-card-gradient-header
        icon="cash-stack"
        title="Interfaz de Créditos"
        subtitle="Gestión de interfaz de créditos al ERP"
        class="d-flex flex-column"
        style="height: calc(100vh - 100px); overflow: hidden;"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="{{ url('/InterfazCreditos') }}"
            id="formBuscarCreditos"
            method="GET"
        >
            <x-form.group>
                <x-form.date
                    name="fecha1"
                    label="Fecha Inicio"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha1', date('Y-m-d'))"
                />
                <x-form.date
                    name="fecha2"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha2', date('Y-m-d'))"
                />
                <x-form.select
                    name="tipoNomina"
                    label="Tipo Nómina"
                    icon="people"
                    col="col-md-3"
                    :options="$tiposNomina->pluck('NomTipoNomina', 'TipoNomina')->toArray()"
                    :value="request('tipoNomina')"
                    :disabled="!empty($chkNomina)"
                />
                <div class="col-md-3">
                    <label
                        class="form-label fw-medium"
                        style="font-size: 0.8rem; color: var(--text-secondary);"
                    >
                        <i class="bi bi-check-square me-1"></i>Buscar por Nómina
                    </label>
                    <div class="input-group">
                        <span
                            class="input-group-text"
                            style="background: var(--bg-light); border-right: none;"
                        >
                            <input
                                class="form-check-input mt-0"
                                type="checkbox"
                                name="chkNomina"
                                id="chkNomina"
                                {{ !empty($chkNomina) ? 'checked' : '' }}
                            >
                        </span>
                        <input
                            class="form-control"
                            type="number"
                            name="numNomina"
                            id="numNomina"
                            value="{{ old('numNomina', $numNomina) }}"
                            placeholder="# Nómina"
                            {{ empty($chkNomina) ? 'disabled' : '' }}
                            style="border-left: none;"
                        >
                    </div>
                </div>
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                    id="btnBuscar"
                />
                <button
                    id="btnBuscandoCreditos"
                    hidden
                    class="btn btn-warning flex-grow-1"
                    style="border-radius: 8px"
                    type="button"
                >
                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                    ></span> Buscando...
                </button>
                <x-form.clear />
            </div>
        </x-form.form>

        <!-- Resultados -->
        <div
            class="d-flex flex-column flex-grow-1 rounded p-4 shadow-sm"
            style="min-height: 0; overflow: hidden;"
        >
            <div class="d-flex justify-content-between align-items-center mb-3 flex-shrink-0 flex-wrap">
                <p
                    class="text-uppercase mb-0"
                    style="font-weight: 500; font-size: 0.85rem; color: var(--text-primary);"
                >
                    <i class="bi bi-person-badge me-1"></i>
                    Créditos Empleado — {{ empty($chkNomina) ? $nomTipoNomina : $empleado }}
                </p>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">
                    <i class="bi bi-list-ul me-1"></i> Se encontraron ({{ count($creditos) }}) registros
                </span>
            </div>

            <div class="table-responsive flex-grow-1">
                <table
                    class="table-hover table-custom table"
                    style="height: {{ count($creditos) > 0 ? 'auto' : '90%' }}"
                >
                    <thead style="position: sticky; top: 0; z-index: 2; background: var(--card-bg);">
                        <tr>
                            <th><i class="bi bi-geo-alt me-1"></i>Ciudad</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-123 me-1"></i>Nómina</th>
                            <th><i class="bi bi-person me-1"></i>Empleado</th>
                            <th class="text-end"><i class="bi bi-cash me-1"></i>Importe</th>
                            <th class="text-center"><i class="bi bi-display me-1"></i>Sistema</th>
                        </tr>
                    </thead>
                    <tbody class="position-relative">
                        @forelse ($creditos as $credito)
                            <tr>
                                <td><span style="color: var(--text-subtle);">{{ $credito->NomCiudad }}</span></td>
                                <td>{{ $credito->NomTienda }}</td>
                                <td><span
                                        class="fw-semibold"
                                        style="color: var(--text-primary);"
                                    >{{ $credito->NumNomina }}</span></td>
                                <td>{{ $credito->Nombre }} {{ $credito->Apellidos }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 700;"
                                >$ {{ number_format($credito->ImporteCredito, 2) }}</td>
                                <td class="text-center">
                                    @if ($credito->isSistemaNuevo == 1)
                                        <span class="tags-green"><i class="bi bi-browser-chrome me-1"></i> Sistema
                                            nuevo</span>
                                    @else
                                        <span class="tags-red"><i class="bi bi-browser-edge me-1"></i> Sistema
                                            viejo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr style="height: 100%;">
                                <td
                                    colspan="6"
                                    style="vertical-align: middle; height: 100%;"
                                >
                                    <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                        <i
                                            class="bi bi-search"
                                            style="font-size: 3rem; color: var(--border-medium);"
                                        ></i>
                                        <h5
                                            class="mt-3"
                                            style="color: var(--text-secondary);"
                                        >Sin datos disponibles</h5>
                                        <p style="color: var(--text-muted);">No se encontraron registros con los filtros
                                            seleccionados</p>
                                        <a
                                            href="/InterfazCreditos"
                                            class="btn btn-sm"
                                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border-radius: 8px;"
                                        >
                                            <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($creditos) > 0)
                        <tfoot>
                            <tr
                                class="bg-table-totals"
                                style="border-top: 2px solid var(--border-light);"
                            >
                                <td></td>
                                <td></td>
                                <td></td>
                                <td
                                    class="fw-bold py-2 text-end"
                                    style="color: var(--text-primary); font-size: 0.9rem;"
                                >
                                    <i class="bi bi-calculator me-1"></i>Total:
                                </td>
                                <td
                                    class="fw-bold py-2 text-end"
                                    style="color: var(--text-primary); font-size: 0.9rem;"
                                >
                                    ${{ number_format($totalAdeudo, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <div style="flex-shrink: 0; border-top: 1px solid var(--border-light); padding-top: 12px;">
                @if (!empty($creditos) && count($creditos) > 0)
                    <div class="d-flex justify-content-center gap-2">
                        <button
                            class="btn btn-sm d-flex align-items-center btn-animated gap-2"
                            style="background: var(--btn-amber-bg); color: var(--btn-amber-text); border: none; border-radius: 8px; padding: 10px 20px; font-size: 0.85rem; font-weight: 500;"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalConfirmarInterfazCreditos"
                        >
                            <i class="bi bi-check-circle"></i> Interfazar Créditos
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal de Éxito -->
        <div
            class="modal fade"
            id="modalExitoCreditos"
            tabindex="-1"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div
                    class="modal-content"
                    style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
                >
                    <div class="p-4 text-center">
                        <div
                            class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 64px; height: 64px; background: var(--badge-active-bg);"
                        >
                            <i
                                class="bi bi-check-circle"
                                style="font-size: 2rem; color: var(--badge-active-text);"
                            ></i>
                        </div>
                        <h5
                            class="fw-bold mb-2"
                            style="color: var(--text-primary);"
                        >¡Créditos Interfazados!</h5>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 16px;">Los créditos
                            se han interfazado correctamente</p>
                        <div class="d-flex align-items-center justify-content-center mb-4 gap-2">
                            <span
                                style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;"
                            >Identificador SPARH</span>
                            <input
                                type="text"
                                id="identificador-sparh"
                                value=""
                                readonly
                                style="background: var(--bg-light); border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-weight: 700; color: var(--text-primary); font-size: 1rem; text-align: center; width: 180px;"
                                onclick="this.select()"
                            >
                            <button
                                type="button"
                                onclick="copiarIdentificador()"
                                class="btn btn-sm d-flex align-items-center gap-1"
                                style="background: var(--btn-blue-bg); color: var(--btn-blue-text); border: 1px solid var(--btn-blue-hover); border-radius: 8px; padding: 6px 12px; font-weight: 500; font-size: 0.8rem;"
                            >
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                        <button
                            type="button"
                            class="btn w-100"
                            data-bs-dismiss="modal"
                            style="background: var(--gradient-start); color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem; font-weight: 500;"
                        >
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-card-gradient-header>

    @include('InterfazCreditos.ModalConfirmacion')
    <script>
        const chkNomina = document.getElementById('chkNomina');
        const numNomina = document.getElementById('numNomina');
        const tipoNomina = document.getElementById('tipoNomina');

        chkNomina.addEventListener('click', function() {
            if (numNomina.disabled == true) {
                numNomina.disabled = false;
                tipoNomina.disabled = true;
            } else {
                numNomina.disabled = true;
                numNomina.value = '';
                tipoNomina.disabled = false;
            }
        });

        document.getElementById('formBuscarCreditos').addEventListener('submit', function() {
            document.getElementById('btnBuscar').hidden = true;
            document.getElementById('btnBuscandoCreditos').hidden = false;
        });

        function copiarIdentificador() {
            const input = document.getElementById('identificador-sparh');
            input.select();
            document.execCommand('copy');
            const btn = event.target.closest('button');
            const icon = btn.querySelector('i');
            icon.className = 'bi bi-check-lg';
            setTimeout(() => {
                icon.className = 'bi bi-clipboard';
            }, 2000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if (session()->has('IdentificadorSparh'))
                const identificador = "{{ session('IdentificadorSparh') }}";
                const match = identificador.match(/[\d]+$/);
                const valor = match ? match[0] : identificador;
                document.getElementById('identificador-sparh').value = valor;
                const modal = new bootstrap.Modal(document.getElementById('modalExitoCreditos'));
                modal.show();
            @endif
        });
    </script>
</x-page-container>
