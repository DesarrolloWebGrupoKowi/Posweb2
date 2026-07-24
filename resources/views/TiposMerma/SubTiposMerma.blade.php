<x-page-container title="Catálogo de Sub Tipos de Merma">
    <x-card-gradient-header
        icon="exclamation-circle"
        title="Catálogo de Sub Tipos de Merma"
        subtitle="Gestión de sub tipos de merma del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtro: Tipo de Merma -->
        <x-form.form
            action="/SubTiposMerma"
            id="formTipoMerma"
        >
            <x-form.group>
                <x-form.select
                    name="idTipoMerma"
                    label="Tipo de Merma"
                    icon="filter"
                    col="col-md-4"
                    :options="$tiposMerma->pluck('NomTipoMerma', 'IdTipoMerma')->toArray()"
                    {{-- onchange="this.form.submit()" --}}
                    autofocus
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <!-- Contenido: Estado vacío o Tabla -->
        @if (empty($idTipoMerma))
            <div class="p-4 text-center">
                <div class="d-flex align-items-center justify-content-center empty-state-icon mx-auto mb-3">
                    <i
                        class="fa fa-hand-pointer-o"
                        style="font-size: 28px; color: #94a3b8;"
                    ></i>
                </div>
                <h6
                    class="fw-semibold mb-1"
                    style="color: #475569;"
                >Selecciona un tipo de merma</h6>
                <p
                    class="text-muted mb-0 pb-4"
                    style="font-size: 0.85rem;"
                >Elige un tipo de merma para ver sus sub tipos</p>
            </div>
        @else
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="fa fa-table me-2"
                                style="color: #64748b;"
                            ></i>Sub Tipos de Merma
                        </h5>
                        <p class="section-content-subtitle">Listado de sub tipos de merma registrados</p>
                    </div>
                    <button
                        type="button"
                        class="btn-create"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarSubTipoMerma"
                    >
                        <i class="fa fa-plus-circle"></i> Agregar subtipo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="fa fa-tag me-1"></i>Tipo de Merma</th>
                                <th><i class="fa fa-tags me-1"></i>Sub Tipo de Merma</th>
                                <th><i class="fa fa-cog me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subTiposMerma as $subTipoMerma)
                                <tr>
                                    <td>
                                        <span
                                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                        >
                                            {{ $subTipoMerma->NomTipoMerma }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 500;">{{ $subTipoMerma->NomSubTipoMerma }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.delete-button
                                                :id="$subTipoMerma->IdSubTipoMerma"
                                                modal="ModalEliminarSubTipoMerma"
                                                title="Eliminar sub tipo de merma"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('TiposMerma.ModalEliminarSubTipoMerma')
                            @empty
                                <x-table-empty-data
                                    colspan="3"
                                    title="Sin datos disponibles"
                                    message="No hay sub tipos de merma para este tipo de merma"
                                    icon="receipt"
                                    :action="false"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-card-gradient-header>
</x-page-container>

@include('TiposMerma.ModalAgregarSubTipoMerma')
