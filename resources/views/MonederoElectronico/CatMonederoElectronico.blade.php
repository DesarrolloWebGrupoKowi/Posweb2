<x-page-container title="Monedero Electrónico">
    <x-card-gradient-header
        icon="wallet2"
        title="Monedero Electrónico"
        subtitle="Gestione la configuración del monedero electrónico"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Configuración de Monedero
                    </h5>
                    <p class="section-content-subtitle">{{ $monederoElectronico->count() }} configuraciones</p>
                </div>
            </div>

            <table class="table-hover table-custom table">
                <thead>
                    <tr>
                        <th><i class="bi bi-collection me-1"></i>Grupo</th>
                        <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Máximo Acumulado</th>
                        <th class="text-end"><i class="bi bi-stack me-1"></i>Múltiplo</th>
                        <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Pesos por Múltiplo</th>
                        <th><i class="bi bi-calendar-check me-1"></i>Vigencia</th>
                        <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($monederoElectronico as $monedero)
                        <tr>
                            <td style="color: #64748b;">{{ $monedero->NomGrupo }}</td>
                            <td
                                class="text-end"
                                style="font-weight: 600; color: #0f172a;"
                            >
                                ${{ number_format($monedero->MaximoAcumulado, 2) }}
                            </td>
                            <td class="text-end">${{ number_format($monedero->MonederoMultiplo, 2) }}</td>
                            <td class="text-end">${{ number_format($monedero->PesosPorMultiplo, 2) }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $monedero->VigenciaMonedero }} días
                                </span>
                            </td>
                            <td class="text-center">
                                <button
                                    class="btn-table-action btn-table-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $monedero->IdCatMonederoElectronico }}"
                                    title="Editar configuración"
                                >
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                        @include('MonederoElectronico.ModalEditarMonedero')
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="py-5 text-center">
                                    <div class="empty-state-icon mx-auto mb-3">
                                        <i
                                            class="bi bi-wallet2 fs-3"
                                            style="color: #94a3b8;"
                                        ></i>
                                    </div>
                                    <h6 class="text-muted">Sin configuraciones</h6>
                                    <small class="text-muted">No hay configuraciones de monedero electrónico
                                        disponibles</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card-gradient-header>
</x-page-container>
