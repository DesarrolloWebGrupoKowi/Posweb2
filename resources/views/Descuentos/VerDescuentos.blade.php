<x-page-container title="Descuentos y Promociones">
    <x-card-gradient-header
        icon="tags"
        title="Descuentos y Promociones"
        subtitle="Gestione los descuentos y promociones del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form action="/VerDescuentos">
            <!-- Fila 1: Filtros principales -->
            <x-form.group>
                <x-form.select
                    name="idPlaza"
                    label="Plaza"
                    icon="building"
                    col="col-md-3"
                    placeholder="Todas las plazas"
                    :options="$plazas->pluck('NomPlaza', 'IdPlaza')->toArray()"
                    :selected="request('idPlaza')"
                />
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    placeholder="Todas las tiendas"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="request('idTienda')"
                />
                <x-form.checkbox-input
                    name="activos"
                    label="Solo activos"
                    icon="check-circle"
                    :checked="request('activos') == 'on'"
                    col="col-md-2"
                />
                <x-form.checkbox-input
                    name="detallado"
                    label="Detallado"
                    icon="list-ul"
                    :checked="request('detallado') == 'on'"
                    col="col-md-2"
                />
                <div class="col-md-2">
                    <x-form.advanced-toggle :active="$filtrosAvanzadosActivos" />
                </div>
            </x-form.group>

            <!-- Fila 2: Filtros avanzados (ocultos) -->
            <x-form.advanced-panel :active="$filtrosAvanzadosActivos">
                <x-form.group>
                    <x-form.text
                        name="codigo"
                        label="Código"
                        icon="upc-scan"
                        placeholder="Código artículo"
                        col="col-md-3"
                        :value="request('codigo')"
                    />
                </x-form.group>
            </x-form.advanced-panel>
        </x-form.form>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Concentrado de Descuentos
                    </h5>
                    <p class="section-content-subtitle">{{ $descuentos->total() }} descuentos registrados</p>
                </div>
                <a
                    href="/CatDescuentos"
                    class="btn-modern btn-agregar"
                >
                    <i class="bi bi-plus-circle"></i> Crear descuento
                </a>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Id</th>
                            <th><i class="bi bi-tag me-1"></i>Descuento</th>
                            <th><i class="bi bi-gear me-1"></i>Tipo</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-building me-1"></i>Plaza</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Fecha Inicio</th>
                            <th><i class="bi bi-calendar-x me-1"></i>Fecha Fin</th>
                            <th><i class="bi bi-calendar-plus me-1"></i>Creado</th>
                            <th><i class="bi bi-calendar-minus me-1"></i>Deshabilitado</th>
                            <th><i
                                    class="bi bi-circle-fill me-1"
                                    style="font-size: 0.5rem;"
                                ></i>Estatus</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($descuentos as $descuento)
                            @php
                                $hoy = \Carbon\Carbon::now()->startOfDay();
                                $fechaFin = \Carbon\Carbon::parse($descuento->FechaFin)->startOfDay();
                            @endphp
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $descuento->IdEncDescuento }}</td>
                                <td>{{ $descuento->NomDescuento }}</td>
                                <td>{{ $descuento->NomTipoDescuento }}</td>
                                <td style="color: #64748b;">{{ $descuento->NomTienda ?? '-' }}</td>
                                <td style="color: #64748b;">{{ $descuento->NomPlaza ?? '-' }}</td>
                                <td style="color: #64748b;">
                                    {{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaInicio)) }}</td>
                                <td style="color: #64748b;">
                                    {{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaFin)) }}</td>
                                <td style="color: #64748b;">
                                    {{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaCreacion)) }}</td>
                                <td style="color: #64748b;">
                                    {{ $descuento->FechaDesactivar ? strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaDesactivar)) : '-' }}
                                </td>
                                <td>
                                    @if ($descuento->Status == 1)
                                        <span class="badge-status badge-inactive">Deshabilitado</span>
                                    @elseif ($fechaFin->lt($hoy))
                                        <span class="badge-status badge-pending">Expirado</span>
                                    @elseif ($descuento->ArticulosDescuento->count() == 0)
                                        <span class="badge-status badge-pending">Sin lineas</span>
                                    @else
                                        <span class="badge-status badge-active">Activo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button
                                            class="btn-table-action btn-table-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalArticulos{{ $descuento->IdEncDescuento }}"
                                            title="Detalle de descuento"
                                        >
                                            <i class="bi bi-list"></i>
                                        </button>
                                        <a
                                            href="/EditarDescuento/{{ $descuento->IdEncDescuento }}"
                                            class="btn-table-action btn-table-activate"
                                            title="Editar descuento"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if ($descuento->Status == 0 && !$fechaFin->lt($hoy))
                                            <button
                                                class="btn-table-action btn-table-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalEliminarConfirm{{ $descuento->IdEncDescuento }}"
                                                title="Deshabilitar descuento"
                                            >
                                                <i class="bi bi-arrow-down-circle"></i>
                                            </button>
                                        @endif
                                        <a
                                            href="/ReporteDescuentos?fecha_fin={{ \Carbon\Carbon::now()->format('Y-m-d') }}&id_enc_descuento={{ $descuento->IdEncDescuento }}"
                                            class="btn-table-action btn-table-password"
                                            title="Ver ventas"
                                            target="_blank"
                                        >
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-tags fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin descuentos ni promociones</h6>
                                        <small class="text-muted">No se encontraron resultados con los filtros
                                            seleccionados</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @foreach ($descuentos as $descuento)
                @include('Descuentos.ModalArticulos')
                @include('Descuentos.ModalEliminarConfirm')
            @endforeach
            @include('components.paginate', ['items' => $descuentos])
        </div>
    </x-card-gradient-header>
</x-page-container>
