<x-page-container title="Módulo de Precios">
    <x-card-gradient-header
        icon="currency-dollar"
        title="Módulo de Precios"
        subtitle="Consulta de precios por artículo"
    >
        <x-slot:buttons>
            <a
                href="/ExportExcelDetallePrecios"
                class="btn-header-ghost"
                title="Exportar precios"
                style="background: var(--btn-green-bg); color: var(--btn-green-text);"
                onmouseover="this.style.background='var(--btn-green-hover)'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='var(--btn-green-bg)'; this.style.transform='translateY(0)'"
            >
                <i class="bi bi-file-earmark-excel"></i> Exportar
            </a>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros de búsqueda -->
        <x-form.form action="/DetallePrecios">
            <x-form.group>
                <x-form.text
                    name="txtFiltro"
                    label="Buscar artículo"
                    icon="search"
                    placeholder="Código, nombre, PLU..."
                    col="col-md-4"
                    :autofocus="true"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                />
                <x-form.clear url="/DetallePrecios" />
            </div>
        </x-form.form>

        <!-- Tabla -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: var(--text-secondary);"
                        ></i>Listado de Precios
                    </h5>
                    <p class="section-content-subtitle">Consulta de precios actualizados por artículo</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-upc me-1"></i>PLU</th>
                            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                            <th><i class="bi bi-box me-1"></i>Nombre Artículo</th>
                            <th><i class="bi bi-cash me-1"></i>Menudeo</th>
                            <th><i class="bi bi-cash-stack me-1"></i>Minorista</th>
                            <th><i class="bi bi-cash-coin me-1"></i>Detalle</th>
                            <th><i class="bi bi-people me-1"></i>Empleados y Socios</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($precios as $precio)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">{{ $precio->CodEtiqueta }}
                                </td>
                                <td style="font-weight: 500;">{{ $precio->CodArticulo }}</td>
                                <td>{{ $precio->NomArticulo }}</td>
                                <td style="font-weight: 500;">${{ number_format($precio->Menudeo, 2) }}</td>
                                <td style="font-weight: 500;">${{ number_format($precio->Minorista, 2) }}</td>
                                <td style="font-weight: 500;">${{ number_format($precio->Detalle, 2) }}</td>
                                <td style="font-weight: 500;">${{ number_format($precio->EmpySoc, 2) }}</td>
                            </tr>
                        @empty
                            <x-table-empty-data
                                colspan="7"
                                title="Sin datos disponibles"
                                message="No se encontraron precios con los filtros seleccionados"
                                icon="currency-dollar"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/DetallePrecios"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $precios])
        </div>
    </x-card-gradient-header>
</x-page-container>
