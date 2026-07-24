<x-page-container title="Catálogo de Paquetes">
    <x-card-gradient-header
        icon="box"
        title="Catálogo de Paquetes"
        subtitle="Gestione los paquetes del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form action="/VerPaquetes">
            <x-form.group>
                <x-form.text
                    name="txtFiltro"
                    label="Buscar paquete"
                    icon="search"
                    placeholder="Buscar por nombre..."
                    col="col-md-4"
                    :autofocus="true"
                    :value="$txtFiltro ?? ''"
                />

                <x-form.checkbox-input
                    name="soloActivos"
                    label="Solo activos"
                    :checked="request('soloActivos') == 'on'"
                />
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
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
                        ></i>Paquetes Registrados
                    </h5>
                    <p class="section-content-subtitle">{{ $paquetes->total() }} paquetes</p>
                </div>
                <a
                    href="/CatPaquetes"
                    class="btn-modern btn-agregar"
                >
                    <i class="bi bi-plus-circle me-2"></i>Agregar paquete
                </a>
            </div>

            <table class="table-hover table-custom table">
                <thead>
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>Id</th>
                        <th><i class="bi bi-box me-1"></i>Paquete</th>
                        <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Costo</th>
                        <th><i class="bi bi-calendar me-1"></i>Fecha Creación</th>
                        <th><i class="bi bi-person me-1"></i>Creado Por</th>
                        <th><i class="bi bi-toggle-on me-1"></i>Status</th>
                        <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paquetes as $paquete)
                        <tr>
                            <td style="font-weight: 600; color: #0f172a;">{{ $paquete->IdPaquete }}</td>
                            <td>{{ $paquete->NomPaquete }}</td>
                            <td
                                class="text-end"
                                style="font-weight: 500;"
                            >${{ number_format($paquete->ImportePaquete, 2) }}</td>
                            <td style="color: #64748b;">
                                {{ strftime('%d %B %Y, %H:%M', strtotime($paquete->FechaCreacion)) }}</td>
                            <td>{{ strtoupper($paquete->Usuario->NomUsuario) }}</td>
                            <td>
                                @if ($paquete->Status == 0)
                                    <span class="badge-status badge-active">
                                        <i class="bi bi-check-circle me-1"></i>Activo
                                    </span>
                                @else
                                    <span class="badge-status badge-inactive">
                                        <i class="bi bi-x-circle me-1"></i>Inactivo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button
                                        class="btn-table-action btn-table-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalArticulos{{ $paquete->IdPaquete }}"
                                        title="Ver artículos"
                                    >
                                        <i class="bi bi-list"></i> Ver
                                    </button>
                                    <a
                                        href="/EditarPaquete/{{ $paquete->IdPaquete }}"
                                        class="btn-table-action btn-table-activate"
                                    >
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    @if ($paquete->Status == 0)
                                        <button
                                            class="btn-table-action btn-table-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarConfirm{{ $paquete->IdPaquete }}"
                                            title="Eliminar paquete"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="py-5 text-center">
                                    <div class="empty-state-icon mx-auto mb-3">
                                        <i
                                            class="bi bi-box fs-3"
                                            style="color: #94a3b8;"
                                        ></i>
                                    </div>
                                    <h6 class="text-muted">Sin paquetes registrados</h6>
                                    <small class="text-muted">No se encontraron paquetes disponibles</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @foreach ($paquetes as $paquete)
                @include('Paquetes.ModalArticulos')
                @include('Paquetes.ModalEliminarConfirm')
            @endforeach
            @include('components.paginate', ['items' => $paquetes])
        </div>
    </x-card-gradient-header>
</x-page-container>
