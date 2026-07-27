<x-page-container title="Interfazar Rosticero">
    <x-card-gradient-header
        icon="fire"
        title="Interfazar Rosticero"
        subtitle="Gestión de interfaz de altas y bajas de rostizado"
        class="d-flex flex-column"
        style="height: calc(100vh - 100px); overflow: hidden;"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="{{ url('/InterfazarRosticero') }}"
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
        @php
            $baja = false;
            $alta = false;
        @endphp

        <div
            class="d-flex flex-column flex-grow-1 card-chart rounded p-4 shadow-sm"
            style="min-height: 0; overflow: hidden;"
        >
            <div class="table-responsive flex-grow-1">
                <table
                    class="table-hover table-custom table"
                    style="height: {{ $rostisados->count() > 0 ? 'auto' : '90%' }}"
                >
                    <thead style="position: sticky; top: 0; z-index: 2; background: var(--card-bg);">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                            <th><i class="bi bi-upc me-1"></i>Código Baja</th>
                            <th><i class="bi bi-upc me-1"></i>Código Alta</th>
                            <th><i class="bi bi-box me-1"></i>Rostizado</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th class="text-end"><i class="bi bi-box-arrow-down me-1"></i>Cant. Baja</th>
                            <th class="text-end"><i class="bi bi-box-arrow-up me-1"></i>Cant. Alta</th>
                            <th><i class="bi bi-tag me-1"></i>Tipo</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="position-relative">
                        @forelse ($rostisados as $rostisado)
                            <tr>
                                <td><span
                                        class="fw-semibold"
                                        style="color: var(--text-primary);"
                                    >{{ $rostisado->IdRosticero }}</span></td>
                                <td><span
                                        style="color: var(--text-subtle); font-size: 0.85rem;">{{ strftime('%d %B %Y, %H:%M', strtotime($rostisado->Fecha)) }}</span>
                                </td>
                                <td><span class="tags-blue">{{ $rostisado->CodigoMatPrima }}</span></td>
                                <td><span class="tags-purple">{{ $rostisado->CodigoVenta }}</span></td>
                                <td><span
                                        class="text-truncate"
                                        style="max-width: 200px; display: inline-block;"
                                        title="{{ $rostisado->NomArticulo }}"
                                    >{{ $rostisado->NomArticulo }}</span></td>
                                <td>{{ $rostisado->NomTienda }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 700;"
                                >{{ $rostisado->CantidadMatPrima }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 700;"
                                >{{ $rostisado->Detalle->where('Status', 0)->whereNull('CantMermaRecalentado')->where('Vendida', 1)->sum('Cantidad') }}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if (!$rostisado->FechaInterfazBaja)
                                            <span class="tags-green"><i
                                                    class="bi bi-arrow-down-circle me-1"></i>Baja</span>
                                        @endif
                                        @if (!$rostisado->FechaInterfazAlta)
                                            <span class="tags-blue"><i
                                                    class="bi bi-arrow-up-circle me-1"></i>Alta</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($rostisado->Lotes->count() == 0)
                                            <span class="tags-red"><i class="bi bi-exclamation-triangle me-1"></i>Sin
                                                lotes</span>
                                        @else
                                            @php
                                                $baja = !$rostisado->FechaInterfazBaja;
                                                $alta = !$rostisado->FechaInterfazAlta;
                                            @endphp
                                            <x-table.buttons.edit-button
                                                :id="$rostisado->IdDatRosticero"
                                                modal="ModalLotes"
                                                title="Ver lotes"
                                                label="Lotes"
                                            />
                                            @include('InterfazRosticero.ModalLotes')
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr style="height: 100%;">
                                <td
                                    colspan="11"
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
                                            href="/InterfazarRosticero"
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
                @include('components.paginate', ['items' => $rostisados])

                @if ($baja || $alta)
                    <div class="d-flex justify-content-center gap-2">
                        @if ($baja)
                            <button
                                class="btn btn-sm d-flex align-items-center btn-animated gap-2"
                                style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 10px 20px; font-size: 0.85rem; font-weight: 500"
                                data-bs-toggle="modal"
                                data-bs-target="#ModalConfirmarInterfaz"
                            >
                                <i class="bi bi-box-arrow-down"></i> Interfazar Bajas
                            </button>
                        @endif
                        @if ($alta)
                            <button
                                class="btn btn-sm d-flex align-items-center btn-animated gap-2"
                                style="background: var(--btn-amber-bg); color: var(--btn-amber-text); border: none; border-radius: 8px; padding: 10px 20px; font-size: 0.85rem; font-weight: 500"
                                data-bs-toggle="modal"
                                data-bs-target="#ModalConfirmarInterfazAlta"
                            >
                                <i class="bi bi-box-arrow-up"></i> Interfazar Altas
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </x-card-gradient-header>

    @include('InterfazRosticero.ModalConfirmarInterfaz')
    @include('InterfazRosticero.ModalConfirmarInterfazAlta')
    <script src="{{ asset('js/rostisados.js') }}"></script>
</x-page-container>
