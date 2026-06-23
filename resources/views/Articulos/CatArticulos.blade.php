@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Catálogo de Articulos')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="box-seam"
            title="Catálogo de Artículos"
            subtitle="Gestión de artículos del sistema"
        >
            <x-slot:buttons>
                <x-header.buttons.home-button />
                <x-header.buttons.refresh-button />
            </x-slot:buttons>

            <!-- Filtros de búsqueda -->
            <x-form.form action="/CatArticulos">
                <x-form.group>
                    <x-form.text
                        name="txtFiltro"
                        label="Buscar artículo"
                        icon="search"
                        placeholder="Código, nombre, amece..."
                        col="col-md-4"
                        :autofocus="true"
                    />
                    <x-form.select
                        name="IdTipoArticulo"
                        label="Tipo de artículo"
                        icon="tag"
                        col="col-md-3"
                        :options="$tiposArticulo->pluck('NomTipoArticulo', 'IdTipoArticulo')->toArray()"
                    />
                    <x-form.select
                        name="IdFamilia"
                        label="Familia"
                        icon="folder"
                        col="col-md-3"
                        :options="$familias->pluck('NomFamilia', 'IdFamilia')->toArray()"
                    />
                </x-form.group>
                <div class="col-md-2 d-flex gap-2">
                    <x-form.submit
                        text="Filtrar"
                        icon="funnel"
                    />
                    <x-form.clear />
                </div>
            </x-form.form>

            <!-- Tabla -->
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: #64748b;"
                            ></i>Concentrado de Artículos
                        </h5>
                        <p class="section-content-subtitle">Listado de artículos registrados en el sistema</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a
                            href="/BuscarArticulo"
                            class="btn-header-ghost"
                            title="Agregar artículo"
                            style="background: #10b981; color: white;"
                            onmouseover="this.style.background='#059669'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#10b981'; this.style.transform='translateY(0)'"
                        >
                            <i class="bi bi-plus-circle"></i> Descargar artículo
                        </a>
                        <a
                            href="/ExportExcelCatArticulos"
                            class="btn-header-ghost"
                            title="Exportar precios"
                            style="background: #f1f5f9; color: #475569;"
                            onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                        >
                            <i class="bi bi-file-earmark-excel"></i> Exportar precios
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>Id</th>
                                <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                <th><i class="bi bi-box me-1"></i>Nombre</th>
                                <th><i class="bi bi-qr-code me-1"></i>Amece</th>
                                <th><i class="bi bi-rulers me-1"></i>UOM</th>
                                <th><i class="bi bi-rulers me-1"></i>UOM2</th>
                                <th><i class="bi bi-speedometer2 me-1"></i>Peso</th>
                                <th><i class="bi bi-upc me-1"></i>PLU</th>
                                <th><i class="bi bi-currency-dollar me-1"></i>Precio Recorte</th>
                                <th><i class="bi bi-percent me-1"></i>Factor</th>
                                <th><i class="bi bi-tag me-1"></i>Tipo</th>
                                <th><i class="bi bi-folder me-1"></i>Familia</th>
                                <th><i class="bi bi-collection me-1"></i>Grupo</th>
                                <th><i class="bi bi-receipt me-1"></i>IVA</th>
                                <th><i class="bi bi-gear me-1"></i>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($articulos as $articulo)
                                <tr>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $articulo->IdArticulo }}</td>
                                    <td style="font-weight: 500;">{{ $articulo->CodArticulo }}</td>
                                    <td>{{ $articulo->NomArticulo }}</td>
                                    <td style="color: #64748b;">{{ $articulo->Amece }}</td>
                                    <td>{{ $articulo->UOM }}</td>
                                    <td>{{ $articulo->UOM2 }}</td>
                                    <td>{{ $articulo->Peso }}</td>
                                    <td>{{ $articulo->CodEtiqueta }}</td>
                                    <td style="font-weight: 500;">${{ number_format($articulo->PrecioRecorte, 2) }}</td>
                                    <td>{{ $articulo->Factor }}</td>
                                    <td>{{ $articulo->NomTipoArticulo }}</td>
                                    <td>{{ $articulo->NomFamilia }}</td>
                                    <td>{{ $articulo->NomGrupo }}</td>
                                    <td>
                                        @if ($articulo->Iva == 0)
                                            <span
                                                style="background: #f0fdf4; color: #10b981; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                            >
                                                <i class="bi bi-check-circle me-1"></i>Si
                                            </span>
                                        @else
                                            <span
                                                style="background: #fef2f2; color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 500;"
                                            >
                                                <i class="bi bi-x-circle me-1"></i>No
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <x-table.buttons.edit-button
                                                :id="$articulo->CodArticulo"
                                                modal="ModalEditar-"
                                                title="Editar artículo"
                                                label="Editar"
                                            />
                                        </div>
                                    </td>
                                </tr>
                                @include('Articulos.ModalEditar')
                            @empty
                                <x-table-empty-data
                                    colspan="15"
                                    title="Sin datos disponibles"
                                    message="No se encontraron artículos con los filtros seleccionados"
                                    icon="box"
                                    :action="true"
                                    actionText="Limpiar filtros"
                                    actionUrl="/CatArticulos"
                                />
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('components.paginate', ['items' => $articulos])
            </div>
        </x-card-gradient-header>
    </x-page-container>
@endsection
