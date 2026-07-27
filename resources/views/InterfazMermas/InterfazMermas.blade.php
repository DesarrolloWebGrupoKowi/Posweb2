<x-page-container title="Interfaz de Mermas">
    <x-card-gradient-header
        icon="fire"
        title="Interfaz de Mermas"
        subtitle="Gestión de interfaz de mermas al ERP"
        class="d-flex flex-column"
        style="height: calc(100vh - 100px); overflow: hidden;"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="{{ url('/InterfazMermas') }}"
            id="formFiltros"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    placeholder="Seleccione Tienda"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :value="request('idTienda')"
                    autofocus
                />
                <x-form.date
                    name="fecha1"
                    label="Fecha Inicio"
                    icon="calendar3"
                    col="col-md-3"
                    :value="request('fecha1', date('Y-m-d'))"
                />
                <x-form.date
                    name="fecha2"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha2', date('Y-m-d'))"
                />
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <!-- Alertas -->
        @include('Alertas.Alertas')

        <!-- Resultados -->
        <div
            class="d-flex flex-column flex-grow-1 card-chart rounded p-4 shadow-sm"
            style="min-height: 0; overflow: hidden;"
        >
            <div class="table-responsive flex-grow-1">
                <table
                    class="table-hover table-custom table"
                    style="height: {{ $mermas->count() > 0 ? 'auto' : '90%' }}"
                >
                    <thead style="position: sticky; top: 0; z-index: 2; background: var(--card-bg);">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-upc me-1"></i>Código</th>
                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                            <th><i class="bi bi-exclamation-triangle me-1"></i>Merma</th>
                            <th class="text-end"><i class="bi bi-123 me-1"></i>Cantidad</th>
                            <th><i class="bi bi-building me-1"></i>Almacén</th>
                            <th><i class="bi bi-journal-text me-1"></i>Cuenta</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Lotes</th>
                        </tr>
                    </thead>
                    <tbody class="position-relative">
                        @forelse ($mermas as $merma)
                            <tr>
                                <td><span
                                        class="fw-semibold"
                                        style="color: var(--text-primary);"
                                    >{{ $merma->FolioMerma }}</span></td>
                                <td>{{ $merma->CodArticulo }}</td>
                                <td><span
                                        class="text-truncate"
                                        style="max-width: 200px; display: inline-block;"
                                        title="{{ $merma->NomArticulo }}"
                                    >{{ $merma->NomArticulo }}</span></td>
                                <td>{{ $merma->NomTipoMerma }}</td>
                                <td class="fw-bold text-end">{{ number_format($merma->CantArticulo, 2) }}</td>
                                <td>{{ $merma->Almacen }}</td>
                                <td>
                                    <span style="font-size: 0.8rem; color: var(--text-subtle);">
                                        {{ empty($merma->Libro) ? '?' : $merma->Libro }}.
                                        {{ empty($merma->CentroCosto) ? '?' : $merma->CentroCosto }}.
                                        {{ empty($merma->Cuenta) ? '?' : $merma->Cuenta }}.
                                        {{ empty($merma->SubCuenta) ? '?' : $merma->SubCuenta }}.
                                        {{ empty($merma->InterCosto) ? '?' : $merma->InterCosto }}.
                                        {{ empty($merma->IdTipoArticulo) ? '?' : $merma->IdTipoArticulo }}.
                                        {{ $merma->Futuro != '0' ? '?' : $merma->Futuro }}
                                    </span>
                                </td>
                                <td>
                                    @if ($merma->Lotes->count() == 0)
                                        <span class="tags-red"><i class="bi bi-exclamation-triangle me-1"></i>Sin
                                            lotes</span>
                                    @else
                                        <x-table.buttons.edit-button
                                            :id="$merma->CodArticulo"
                                            modal="ModalLotes"
                                            title="Ver lotes"
                                            label="Lotes"
                                        />
                                        @include('InterfazMermas.ModalLotes')
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr style="height: 100%;">
                                <td
                                    colspan="8"
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
                                            href="/InterfazMermas"
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
                </table>
            </div>

            <!-- Footer fijo -->
            <div style="flex-shrink: 0; border-top: 1px solid var(--border-light); padding-top: 12px;">
                <div class="d-flex justify-content-center gap-2">
                    @if (!empty($lotesDisponibles))
                        <button
                            class="btn btn-sm d-flex align-items-center btn-animated gap-2"
                            style="background: var(--btn-amber-bg); color: var(--btn-amber-text); border: none; border-radius: 8px; padding: 10px 20px; font-size: 0.85rem;"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalConfirmarInterfaz"
                        >
                            <i class="bi bi-box-arrow-up"></i> Interfazar Mermas
                        </button>
                    @elseif(empty($lotesDisponibles) && $mermas->count() > 0)
                        <h5
                            class="rounded-3 p-1 px-3 py-2 text-white shadow"
                            style="background: var(--danger-color); font-size: 0.85rem;"
                        >
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Ninguna Merma Apta para ser Interfazada
                            <i class="bi bi-exclamation-circle ms-1"></i>
                        </h5>
                    @endif
                </div>
            </div>
        </div>
    </x-card-gradient-header>

    @include('InterfazMermas.ModalConfirmarInterfaz')
</x-page-container>
