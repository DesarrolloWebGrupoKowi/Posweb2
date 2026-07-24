<x-page-container title="Catálogo de Clientes">
    <x-card-gradient-header
        icon="people"
        title="Catálogo de Clientes"
        subtitle="Gestione el catálogo de clientes del sistema"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            {{-- <form
                action="/CatClientes/Actualizar"
                method="POST"
                class="d-inline"
            >
                @csrf
                <button
                    type="submit"
                    class="btn-modern btn-agregar"
                >
                    <i class="bi bi-cloud-upload me-2"></i>Actualizar clientes
                </button>
            </form> --}}
        </x-slot:buttons>

        <x-form.form
            action="/CatClientes"
            method="GET"
        >
            <x-form.group>
                <x-form.text
                    name="txtFiltro"
                    label="Buscar"
                    icon="search"
                    placeholder="RFC, Nombre o Locación..."
                    col="col-md-4"
                    :autofocus="true"
                    :value="$txtFiltro ?? ''"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
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
                        ></i>Clientes Registrados
                    </h5>
                    <p class="section-content-subtitle">{{ $clientes->total() }} clientes</p>
                </div>
            </div>

            <table class="table-hover table-custom table">
                <thead>
                    <tr>
                        <th><i class="bi bi-cloud me-1"></i>Id Cliente</th>
                        <th><i class="bi bi-rss me-1"></i>RFC</th>
                        <th><i class="bi bi-person me-1"></i>Nombre</th>
                        <th><i class="bi bi-person-badge me-1"></i>Tipo de Cliente</th>
                        <th><i class="bi bi-geo-alt me-1"></i>Locación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td style="font-weight: 600; color: #0f172a;">{{ $cliente->IdClienteCloud }}</td>
                            <td style="font-weight: 500;">{{ $cliente->RFC }}</td>
                            <td>{{ $cliente->NomCliente }}</td>
                            <td>{{ $cliente->TipoPersona }}</td>
                            <td style="color: #64748b;">{{ $cliente->Locacion }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="py-5 text-center">
                                    <div class="empty-state-icon mx-auto mb-3">
                                        <i
                                            class="bi bi-people fs-3"
                                            style="color: #94a3b8;"
                                        ></i>
                                    </div>
                                    <h6 class="text-muted">Sin clientes registrados</h6>
                                    <small class="text-muted">No se encontraron clientes con los filtros
                                        seleccionados</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('components.paginate', ['items' => $clientes])
        </div>
    </x-card-gradient-header>
</x-page-container>
