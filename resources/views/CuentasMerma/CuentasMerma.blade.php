<x-page-container title="Cuentas Mermas Por Tienda">
        <x-card-gradient-header
            icon="receipt"
            title="Catálogo de Cuentas Merma"
            subtitle="Gestión de cuentas de merma por tienda"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Tabla -->
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                                <i
                                    class="fa fa-table me-2"
                                    style="color: var(--text-secondary);"
                                ></i>Concentrado de Cuentas Merma
                        </h5>
                        <p class="section-content-subtitle">Listado de cuentas de merma registradas en el sistema</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarCuentaMerma"
                        {{ count($tiposMerma) == 0 ? 'disabled' : '' }}
                    >
                        <i class="fa fa-plus-circle"></i> Agregar cuenta
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-tag me-1"></i>Tipo Merma</th>
                                <th><i class="fa fa-book me-1"></i>Libro</th>
                                <th><i class="fa fa-credit-card me-1"></i>Cuenta</th>
                                <th><i class="fa fa-list-alt me-1"></i>Subcuenta</th>
                                <th><i class="fa fa-exchange me-1"></i>Intercosto</th>
                                <th><i class="fa fa-clock-o me-1"></i>Futuro</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cuentasMerma as $cuentaMerma)
                                <tr>
                                    <td style="font-weight: 500;">
                                        <span class="tags-blue">{{ $cuentaMerma->NomTipoMerma }}</span>
                                    </td>
                                    <td style="font-weight: 600; color: var(--text-primary);">{{ $cuentaMerma->Libro }}</td>
                                    <td>{{ $cuentaMerma->Cuenta }}</td>
                                    <td>{{ $cuentaMerma->SubCuenta }}</td>
                                    <td>{{ $cuentaMerma->InterCosto }}</td>
                                    <td>{{ $cuentaMerma->Futuro }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$cuentaMerma->IdCatCuentaMerma"
                                                modal="ModalEditarCuentaMerma"
                                                title="Ver cuenta merma"
                                                label="Ver"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <x-table-empty-data
                                    colspan="7"
                                    title="Sin datos disponibles"
                                    message="No se encontraron cuentas de merma registradas"
                                    icon="receipt"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CuentasMerma"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

    @include('CuentasMerma.ModalAgregarCuentaMerma')

    @foreach ($cuentasMerma as $cuentaMerma)
        @include('CuentasMerma.ModalEditarCuentaMerma')
    @endforeach

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formCuentasMerma').submit();
        });
    </script>
